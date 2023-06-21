<?php

use app\library\Router\Router;
use app\library\Router\Dispatcher;
use app\library\Cache\Cache;
use app\library\Load;

require './vendor/autoload.php';

setlocale(LC_ALL, "pt_BR", "pt_BR.iso-8859-1", "pt_BR.utf-8", "portuguese");
date_default_timezone_set('America/Sao_Paulo');
error_reporting(0);
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__FILE__, 1));
$dotenv->load();

Cache::init(__DIR__ . '/../storage/__cache__');

$router = new Router;
require('./app/config/routes.php');

$dispatcher = new Dispatcher($router);
$dispatcher->dispatch(function (array $params, array|Closure $action) use ($dispatcher) {

    $controllerFileName = ucfirst($action[0]) . 'Controller';

    if (file_exists(__DIR__ . '/app/controllers/site/' . $controllerFileName . '.php')) {
        $controlerClass = '\\app\\controllers\\site\\' . $controllerFileName;
    } else if (file_exists(__DIR__ . '/app/controllers/admin/' . $controllerFileName  . '.php')) {
        $controlerClass = '\\app\\controllers\\admin\\' . $controllerFileName;
    }
    try {
        $controller = new $controlerClass($action[1], $params);
        $controller->init($action[1], $params);
    } catch (\Throwable $e) {
        $environment = Load::load('environment');

        if ($environment->type == 'dev') {
            var_dump($e->getMessage() . ' no arquivo ' . $e->getFile() . ' na linha ' . $e->getLine());
        }
    }
});
