<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$db_config = require APPDIR . 'config/database.php';

$capsule = new Capsule;

$capsule->addConnection($db_config);

// Set the event dispatcher used by Eloquent models... (optional)
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
$capsule->setEventDispatcher(new Dispatcher(new Container));

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();

// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();

require __DIR__ . '/db.php';
require __DIR__ . '/paginator.php';
require __DIR__ . '/manager.php';
require __DIR__ . '/validator.php';
require __DIR__ . '/filter.php';
require __DIR__ . '/models_autoload.php';

App::register('db', new DB);

