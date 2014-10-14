<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 10/14/2014
 * Time: 10:38 PM
 */

return array(
    'get_option' => function ($option_name) {
        $option = Option::where('option', '=', $option_name)->first();
        if ($option) {
            return $option->value;
        } else {
            return false;
        }
    }
);