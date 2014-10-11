<?php

$loader = new Twig_Loader_Filesystem(VIEWSDIR);
$twig = new Twig_Environment($loader, array(
    'cache' => APPDIR . 'cache/',
    'charset' => 'utf-8',
    'auto_reload' => true,
    'strict_variables' => false,
    'optimizations' => -1,
    'autoescape' => false
));

require __DIR__ . '/configure.php';
require __DIR__ . '/view.php';

App::register('twig', $twig);

TwigConfiguration::setup();