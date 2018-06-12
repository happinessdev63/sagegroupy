<?php

return [

        'apiKey' => env('MAILCHIMP_APIKEY', 'ac876088868e89cab2e28aa46cf9aa94-us15'),
        'defaultListName' => 'prelaunch',
        'lists' => [
            'prelaunch' => [
                'id' => env('MAILCHIMP_LIST_ID', "b5a99e2573"),
            ],
        ],
        'ssl' => false,
];
