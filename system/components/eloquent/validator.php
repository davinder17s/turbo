<?php

use Acme\Validation\Capsule\Manager as Validation;

class Validator{
    public static function __callStatic($name, $args)
    {
        // Define the locale and the path to the language directory.
        $validation = new Validation('en', APPDIR .'/lang');
        // Adding a database connection is optional. Only used for
        // the Exists and Unique rules.
        $db_config = require APPDIR . 'config/database.php';
        $validation->setConnection($db_config);
        $validator = $validation->getValidator();

        return call_user_func_array(array($validator, $name), $args);
    }
}