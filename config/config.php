<?php

$config = [
    'homepage_route' => 'home',
    'db' => [
        'name'     => 'filer',
        'user'     => '',
        'password' => '',
        'host'     => '127.0.0.1',
        'port'     => null,
    ],
    'routes' => [
        'home'        => 'Main:home',
        'login'       => 'Main:login',
        'logout'      => 'Main:logout',
        'register'    => 'Main:register',
        'files'       => 'Files:files',
        'upload'      => 'Files:upload',
        'download'    => 'Files:download',
        'delete'      => 'Files:delete',
        'deleteDir'   => 'Files:deleteDir',
        'moveItem'    => 'Files:moveItem',
        'renameItem'  => 'Files:renameItem',
        'makeDir'     => 'Files:makeDir',
        'view'        => 'Files:view',
        'editFile'    => 'Files:editFile',
    ]
];