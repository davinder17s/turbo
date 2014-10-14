<?php
/**
return array(
	'mode' => 'both', // mode: auto|manual|both
    'default_controller' => 'home', // default controller for each directory
	'routes' => array( // routes for manual mode
			'name' => array(
					'/name/{name}',  // url format
					'names/namescontroller@getView',  // path/to/controller@getAction
					'requirements' => array(
							'name' => '[0-9]+'  // {name} variable requirements
						)
				),
			'hello' => array(
					'/hello',
					'hellocontroller@getIndex'
				)
		)
);
 *
 */

return array(
    'mode' => 'both', // mode: auto|manual|both
    'default_controller' => 'home',
    'routes' => array(

    )
);