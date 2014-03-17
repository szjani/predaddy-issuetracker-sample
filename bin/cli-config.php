<?php
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Symfony\Component\Console\Helper\HelperSet;

$container = require_once __DIR__ . '/../base.php';

/* @var $em Doctrine\ORM\EntityManager */
$em = EntityManager::create($app['doctrine.connection'], $app['doctrine.config'], $app['doctrine.eventmanager']);

return new HelperSet(
    array(
        'db' => new ConnectionHelper($em->getConnection()),
        'em' => new EntityManagerHelper($em),
        'dialog' => new \Symfony\Component\Console\Helper\DialogHelper()
    )
);
