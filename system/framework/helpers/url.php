<?php

function baseUrl($path = ''){
    $baseUrl = App::instance()->request()->getBaseUrl();
    $strippedBaseUrl = str_replace('index.php/', '', $baseUrl);
    $generated = $strippedBaseUrl .'/'. $path;
    $sanitized = str_replace('index.php/', '', $generated);
    return $sanitized;
}

function siteUrl($path = '')
{
    $environment_config = require APPDIR . 'config/environment.php';
    $index_page = $environment_config['index_page'];
    if ($index_page != '') {
        $index_page = '/' . $index_page . '/';
    }
    $generated = baseUrl() . $index_page . $path;
    return str_replace('//', '/', $generated);
}

function upload($file, $path = 'uploads/', $all = false)
{
    $uniq_id = substr(strrev(uniqid()), 0, 4);
    $parts = explode('.', $file->getClientOriginalName());
    $ext = array_pop($parts);
    $file_name = $uniq_id . '_' .sanitize_url(implode('.', $parts)) . '.' .$ext;

    if (file_exists($path . $file_name)) {
        $parts = explode('.', $file_name);
        $ext = array_pop($parts);
        $parts[count($parts) - 1] .= rand(0, 99);
        $parts[] = $ext;
        $file_name = implode('.', $parts);
    }
    if ($all == false) {
        return $file->move($path, $file_name )->getFileName();
    } else {
        return $file->move($path, $file_name );
    }
}


function sanitize_url($str, $separator = '-', $lowercase = true)
{
    $q_separator = preg_quote($separator);

    $trans = array(
        '&.+?;'                 => '',
        '[^a-z0-9 _-]'          => '',
        '\s+'                   => $separator,
        '('.$q_separator.')+'   => $separator
    );

    $str = strip_tags($str);

    foreach ($trans as $key => $val)
    {
        $str = preg_replace("#".$key."#i", $val, $str);
    }

    if ($lowercase === TRUE)
    {
        $str = strtolower($str);
    }

    return trim($str, $separator);
}