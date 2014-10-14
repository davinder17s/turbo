<?php
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
