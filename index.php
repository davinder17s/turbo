<?php
$app_start_time = microtime(true);
require 'vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;

define('BASEDIR', __DIR__ . '/');
define('APPDIR' , BASEDIR . 'app/');
define('SYSDIR' , BASEDIR . 'system/');
define('VENDORDIR',BASEDIR . 'vendor/');

define('VIEWSDIR', APPDIR . 'views/');

require SYSDIR . 'app.php';

$app = new App(Request::createFromGlobals());
$app->start();

$app->router->run();

$app_end_time = microtime(true);
echo '<br>';
echo ($app_end_time) - ($app_start_time);