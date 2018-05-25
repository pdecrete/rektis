<?php
return [
    'params' => [
        'archive-messages' => false, // set to true or false 
        'archive-repository' => __DIR__ . "/../repository/", // where to save emails sent for archiving purposes; must exist even if archive-messages is false
        'from' => '', // default email from: address, string (email address) or assoc array (display name => email address)
        'reply-to' => '', // use as reply to header: string (email address)
        // shadow-recipients are email addresses that receive a copy of sent emails; can be an empty array
        'shadow-recipients' => [
        ]
    ]
];
