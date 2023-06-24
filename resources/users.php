<?php
const MAIN = true;

$GLOBALS['config'] = [
    'key' => 'users',
    'table' => 'users',
    'name' => 'User',
    'plural_name' => 'Users',
    'fields' => [
        'name' => [
            'type' => 'string',
            'label' => 'Name',
            'required' => true,
            'readonly' => false,
            'default' => null,
            'validation' => [
                'string' => true,
                'min' => 3,
                'max' => 255
            ],
            'table' => true,
        ],
        'email' => [
            'type' => 'email',
            'label' => 'Email',
            'required' => true,
            'readonly' => false,
            'default' => null,
            'validation' => [
                'string' => true,
                'email' => true,
                'min' => 3,
                'max' => 255
            ],
            'table' => true,
        ],
        'username' => [
            'type' => 'string',
            'label' => 'Username',
            'required' => true,
            'readonly' => false,
            'default' => null,
            'validation' => [
                'string' => true,
                'min' => 3,
                'max' => 255
            ],
            'table' => true,
        ],
        'password' => [
            'type' => 'password',
            'label' => 'Password',
            'required' => true,
            'readonly' => false,
            'default' => null,
            'validation' => [
                'string' => true,
                'min' => 3,
                'max' => 255
            ],
            'hidden' => true,
        ],
    ],
    'permissions' => [
        'index' => true,
        'create' => true,
        'read' => true,
        'update' => true,
        'delete' => function($item){
            return auth()->user()->id != $item->id;
        },
    ]
];

require __DIR__.'/_provider.php';