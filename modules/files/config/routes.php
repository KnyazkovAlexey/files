<?php

return [
    'files' => 'files/default/index',
    'files/add' => 'files/default/add',
    'files/upload' => 'files/default/upload',
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => [
            'api/v1/files',
        ],
        'patterns' => [
            'GET' => 'index',
            'POST' => 'upload',
        ],
    ],
];

