<?php
$app_start_time = microtime(true);
require 'vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;

define('APPDIR' , __DIR__ . '/app/');
define('SYSDIR' , __DIR__ . '/system/');

require SYSDIR . 'app.php';

$app = new App(Request::createFromGlobals());
$app->start();

$app->router->run();

$app_end_time = microtime(true);
echo '<br>';
echo ($app_end_time) - ($app_start_time);