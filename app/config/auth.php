<?php
/**
return array(
    'types' => array(
        'user' => array(  // will be available as Auth::user()
            'model' => 'User',  // Eloquent model name for this user type
        ),
        'admin' => array( // will be available as Auth::admin()
            'model' => 'Admin', // Eloquent model
        )
    )
);
*/

return array(
    'types' => array(
        'user' => array(
            'model' => 'User',
        ),
        'admin' => array(
            'model' => 'Admin',
        )
    )
);