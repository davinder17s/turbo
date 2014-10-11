<?php

return array(
    'date' => function ($string, $format) {
        if (!is_numeric($string)) {
            $timestamp = strtotime($string);
            if ($timestamp == 0) {
                return false;
            }
        } else {
            $timestamp = $string;
        }
        return date($format, $timestamp);
    }
);