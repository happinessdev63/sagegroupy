<?php

return [

        'apiKey' => env('MAILCHIMP_APIKEY', 'e84fc612c377227754b66796454c18f0-us18'),
        'defaultListName' => 'prelaunch',
        'lists' => [
            'prelaunch' => [
                'id' => env('MAILCHIMP_LIST_ID', "b5a99e2573"),
            ],
        ],
        'ssl' => false,
];
