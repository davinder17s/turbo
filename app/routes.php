<?php

return array(
	'mode' => 'both', // mode: auto|manual|both
    'default_controller' => 'home',
	'routes' => array(
			'name' => array(
					'/name/{name}',
					'names/namescontroller@view',
					'requirements' => array(
							'name' => '[0-9]+'
						)
				),
			'hello' => array(
					'/hello',
					'hellocontroller@index'
				)
		)
);