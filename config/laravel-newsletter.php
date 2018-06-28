<?php

return [

        'apiKey' => env('MAILCHIMP_APIKEY', 'e84fc612c377227754b66796454c18f0-us18'),
        'defaultListName' => 'prelaunch',
        'lists' => [
            'prelaunch' => [
                'id' => env('MAILCHIMP_LIST_ID', "c3d34b3bc8"),
            ],
        ],
        'ssl' => false,
];
