<?php

use Illuminate\Database\Eloquent\Model as Model;

class EloquentModel extends Model{
    // dummy class for shorthand productivity
}

$models_dir = APPDIR . 'eloquent/';
$models = scandir($models_dir);
unset($models[0], $models[1]);
foreach ($models as $model) {
    require $models_dir . $model;
}