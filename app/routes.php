<?php

return array(
	'mode' => 'manual', // mode: auto|manual|both
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