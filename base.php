<?php

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Event\Listeners\MysqlSessionInit;
use Doctrine\ORM\Tools\Setup;
use hu\szjani\infrastructure\finders\DbalIssueFinder;
use hu\szjani\infrastructure\finders\IssueReadModelSynchronizer;
use hu\szjani\infrastructure\eventstore\ModuloSnapshotter;
use precore\util\error\ErrorHandler;
use predaddy\domain\AbstractEventSourcedAggregateRoot;
use predaddy\domain\EventSourcingEventHandlerDescriptorFactory;
use predaddy\domain\EventSourcingRepository;
use predaddy\domain\impl\doctrine\DoctrineOrmEventStore;
use predaddy\eventhandling\EventBus;
use predaddy\eventhandling\EventFunctionDescriptorFactory;
use predaddy\messagehandling\interceptors\EventPersister;
use predaddy\messagehandling\SimpleMessageBusFactory;
use predaddy\util\TransactionalBuses;
use trf4php\doctrine\DoctrineTransactionManager;
use trf4php\doctrine\impl\DefaultEntityManagerFactory;
use trf4php\doctrine\impl\DefaultEntityManagerProxy;
use trf4php\doctrine\impl\TransactionalEntityManagerReloader;

define('ROOT', __DIR__);
define('VENDOR', ROOT . '/vendor');

error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', 1);

require_once VENDOR . '/autoload.php';

Logger::configure(ROOT . '/src/resources/log4php.xml');
ErrorHandler::register();

$app = new Silex\Application();

$app['serializer'] = $app->share(
    function () use ($app) {
        AnnotationRegistry::registerAutoloadNamespace(
            'JMS\Serializer\Annotation',
            ROOT . "/vendor/jms/serializer/src"
        );
        $builder = JMS\Serializer\SerializerBuilder::create();
        $builder
            ->setAnnotationReader($app['annotation.reader'])
            ->setCacheDir(ROOT . '/cache')
            ->addMetadataDir(VENDOR . '/predaddy/predaddy/src/resources/jms');
        return new \predaddy\serializer\JmsSerializer($builder->build(), 'xml');
    }
);
$app['annotation.reader'] = $app->share(
    function () {
        return new CachedReader(new AnnotationReader(), new ArrayCache());
    }
);
$app['doctrine.config'] = $app->share(
    function () {
        $config = Setup::createAnnotationMetadataConfiguration(
            [VENDOR . '/predaddy/predaddy/src/predaddy/domain/impl/doctrine'],
            false,
            ROOT . '/cache',
            null,
            false
        );
//        $config->setSQLLogger(new \Doctrine\DBAL\Logging\EchoSQLLogger());
        return $config;
    }
);
$app['doctrine.connection'] = $app->share(
    function () {
        return [
            'driver' => 'pdo_mysql',
            'user' => 'predaddy',
            'password' => 'test001',
            'dbname' => 'predaddy',
            'charset' => 'UTF8'
        ];
    }
);
$app['doctrine.eventmanager'] = $app->share(
    function () {
        $manager = new Doctrine\Common\EventManager();
        $manager->addEventSubscriber(new MysqlSessionInit('utf8', 'utf8_unicode_ci'));
    }
);
$app['entity.manager'] = $app->share(
    function () {
        return new DefaultEntityManagerProxy();
    }
);
$app['transaction.manager'] = $app->share(
    function () use ($app) {
        $manager = new DoctrineTransactionManager($app['entity.manager']);
        $manager->attach(
            new TransactionalEntityManagerReloader(
                new DefaultEntityManagerFactory(
                    $app['doctrine.connection'],
                    $app['doctrine.config'],
                    $app['doctrine.eventmanager']
                )
            )
        );
        return $manager;
    }
);
$app['event.store'] = $app->share(
    function () use ($app) {
        return new DoctrineOrmEventStore($app['entity.manager'], new ModuloSnapshotter(1), $app['serializer']);
    }
);
$app['transactional.buses'] = $app->share(
    function () use ($app) {
        return TransactionalBuses::create(
            $app['transaction.manager'],
            new EventSourcingRepository($app['event.store']),
            [],
            [new EventPersister($app['event.store'])]
        );
    }
);
$app['event.bus'] = $app->share(
    function () use ($app) {
        return $app['transactional.buses']->eventBus();
    }
);
$app['command.bus'] = $app->share(
    function () use ($app) {
        return $app['transactional.buses']->commandBus();
    }
);
$app['dbal.connection'] = $app->share(
    function () use ($app) {
        return DriverManager::getConnection(
            $app['doctrine.connection'],
            $app['doctrine.config'],
            $app['doctrine.eventmanager']
        );
    }
);
$app['issue.finder'] = $app->share(
    function () use ($app) {
        return new DbalIssueFinder($app['dbal.connection']);
    }
);

AbstractEventSourcedAggregateRoot::setInnerMessageBusFactory(
    new SimpleMessageBusFactory(
        new EventSourcingEventHandlerDescriptorFactory(
            new EventFunctionDescriptorFactory(),
            $app['annotation.reader']
        )
    )
);

/* @var $eventBus EventBus */
$eventBus = $app['event.bus'];
$eventBus->register(new IssueReadModelSynchronizer($app['dbal.connection']));

return $app;
