<?php

return array(
    'siteUrl' => function($path=''){
        return App::instance()->request()->getBaseUrl() . '/' . $path;
    },
    'baseUrl' => function ($path = '') {
        $baseUrl = App::instance()->request()->getBaseUrl();
        $strippedBaseUrl = str_replace('index.php', '', $baseUrl);
        return $strippedBaseUrl . $path;
    },
    'date' => 'date',
);