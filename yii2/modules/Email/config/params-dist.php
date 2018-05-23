<?php
return [
    'params' => [
        'archive-repository' => __DIR__ . "/../repository/", // where to save emails sent for archiving purposes
        'from' => '', // default email from: address, string (email address) or assoc array (display name => email address)
        'replyTo' => '', // use as reply to header: string (email address)
        // shadow-recipients are email addresses that receive a copy of sent emails
        'shadow-recipients' => [
        ]
    ]
];
