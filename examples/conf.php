<?php
return [
    'classesMap' => [
        'model' => \Model::class,
    ],
    'defaultCallback' => function ($message) {},
    'encodeCallback' => function ($email, $host) { return bin2hex($email); },
    'decodeCallback' => function ($email, $host) { return hex2bin($email); },
];