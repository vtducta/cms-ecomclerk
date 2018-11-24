<?php

/**
 * List of plain SQS queues and their corresponding handling classes
 */
return [
    'handlers' => [
        'base-integrations-updates' => App\Jobs\ProductSqs::class,
    ],

    'default-handler' => App\Jobs\HandlerJob::class
];