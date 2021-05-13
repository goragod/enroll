<?php

/* settings/database.php */

return array(
    'mysql' => array(
        'dbdriver' => 'mysql',
        'username' => 'root',
        'password' => '',
        'dbname' => 'enrollment',
        'prefix' => 'reg',
    ),
    'tables' => array(
        'amphur' => 'amphur',
        'category' => 'category',
        'district' => 'district',
        'enroll' => 'enroll',
        'enroll_plan' => 'enroll_plan',
        'language' => 'language',
        'province' => 'province',
        'user' => 'user',
    ),
);
