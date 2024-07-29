<?php

return [
    'pdf' => [
        'enabled' => true,
        'binary'  => env("SNAPPY_BINARY_PDF", '/usr/bin/wkhtmltopdf'),
        'timeout' => false,
        'options' => [
            'enable-local-file-access' => true
        ],
        'env'     => [],
    ],
    'image' => [
        'enabled' => true,
        'binary'  => env("SNAPPY_BINARY_IMAGE", '/usr/bin/wkhtmltoimage'),
        'timeout' => false,
        'options' => [
            'enable-local-file-access' => true
        ],
        'env'     => [],
    ],
];
