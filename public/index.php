<?php

use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\Url as UrlProvider;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Http\Response\Cookies;
use Phalcon\Session\Adapter\Files;

require "../app/config/Config.php";

try {

    // Register an autoloader
    $loader = new Loader();
    $loader->registerDirs(array(
        '../app/controllers/',
        '../app/models/',
        '../app/config/',
        '../app/libraries/'
    ))->register();

    // Create a DI
    $di = new FactoryDefault();

    // return DB config
    $di->setShared('config', function() use ($config) {
        return $config;
    });

    // Database
    $di->set('db', function() use ($di) {
        $dbconfig = $di->get('config')->get('db')->toArray();
        $db = new DbAdapter($dbconfig);
        return $db;
    });

    // Setup the view component
    $di->set('view', function(){
        $view = new View();
        $view->setViewsDir('../app/views/');
        $view->registerEngines([
            ".volt" => 'Phalcon\Mvc\View\Engine\Volt'
        ]);
        return $view;
    });

    // Setup a base URI so that all generated URIs include the "tutorial" folder
    $di->set('url', function(){
        $url = new UrlProvider();
        $url->setBaseUri('/bot/');
        return $url;
    });

    // Cookies
    $di->set('cookies', function() {
        $cookies = new Cookies();
        $cookies->useEncryption(false);
        return $cookies;
    });

    // Session
    $di->setShared('session', function() {
        $session = new Files();
        $session->start();
        return $session;
    });

    // Handle the request
    $application = new Application($di);

    echo $application->handle()->getContent();

} catch (\Exception $e) {
     echo "PhalconException: ", $e->getMessage();
}