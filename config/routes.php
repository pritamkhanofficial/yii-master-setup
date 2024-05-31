<?php

return [
    'authentication' => 'site/index',
    'logout' => 'site/logout',
    'reset-password' => 'site/forgotpassword',
    'mail-send' => 'site/mailsentsuccess',
    // 'change-password/<generate_token:\w+>' => 'site/changepassword',
    'dashboard' => 'dashboard/index',
    /* roles */
    'roles' => 'roles/index',
    'role/add' => 'roles/create',
    'role/edit/<id:\w+>' => 'roles/update',
    'role/delete/<id:\w+>' => 'roles/delete',
    /* users */
    'users' => 'users/index',
    'user/add' => 'users/create',
    'user/datatable' => 'users/datatable',
    'profile' => 'users/profile',
    'change-password' => 'users/changepassword',
];
