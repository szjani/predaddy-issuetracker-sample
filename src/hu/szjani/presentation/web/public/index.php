<?php
use hu\szjani\presentation\web\controllers\IssueController;

define('WEB', __DIR__ . '/..');

$app = require_once(__DIR__ . '/../../../../../../base.php');
$app->register(new Silex\Provider\ServiceControllerServiceProvider());

$app['issue.controller'] = $app->share(
    function () use ($app) {
        return new IssueController($app['command.bus'], $app['issue.finder'], $app['twig']);
    }
);

$app->get('/create', 'issue.controller:create');
$app->post('/create', 'issue.controller:createIssue');
$app->get('/reassign/{aggregateId}', 'issue.controller:reassign');
$app->post('/reassign/{aggregateId}', 'issue.controller:reassignIssue');
$app->get('/', 'issue.controller:index');
$app->error(
    function (Exception $e) {
        var_dump($e);
    }
);
$app->register(
    new Silex\Provider\TwigServiceProvider(),
    array(
        'twig.path' => WEB . '/views',
    )
);

$app->run();
