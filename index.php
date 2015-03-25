<?php
require 'vendor/autoload.php';
use Symfony\Component\HttpFoundation\Request;

define('BASEDIR', __DIR__ . '/');
define('APPDIR' , BASEDIR . 'app/');
define('SYSDIR' , BASEDIR . 'system/');
define('VENDORDIR',BASEDIR . 'vendor/');
define('VIEWSDIR', APPDIR . 'views/');

// define set of local environments
$environments = array(
    'local' => array('localhost')
);

// Enable powerful debugger for local only.
if (in_array($_SERVER['HTTP_HOST'], $environments['local'])) {
    require SYSDIR . 'php_error.php';
    $options = array(
        'enable_saving' => false,
        'snippet_num_lines' => 10
    );
    \php_error\reportErrors($options);
}

// start running the application
require SYSDIR . 'app.php';
$app = new App(Request::createFromGlobals());
$app->start();
$app->router->run();
$app->shutdown();