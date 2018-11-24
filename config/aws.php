<?php
/* AWS Configurations file */
return [
    'credentials' => [
        'key'    => 'AKIAIZTRKPR2ZIGHQE4A',
        'secret' => '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',
    ],
    'region' => 'us-east-1',
    'version' => 'latest',
    
    // You can override settings for specific services
    'Ses' => [
        'region' => 'us-east-1',
    ],
];