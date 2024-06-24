<?php

return [
    'default' => env('SERIALIZER_DEFAULT', 'json'),

    'serializers' => [
        'json' => [
            'class' => env('SERIALIZER_JSON_CLASS', \Zus1\Serializer\Serializer\JsonSerializer::class),
        ],
        'csv' => [
            'class' => env('SERIALIZER_CSV_CLASS', \Zus1\Serializer\Serializer\CsvSerializer::class),
        ]
    ]
];
