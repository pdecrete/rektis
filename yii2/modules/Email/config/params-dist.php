<?php
return [
    'params' => [
        'from' => '', // default email from: address, string (email address) or assoc array (display name => email address)
        'replyTo' => '', // use as reply to header: string (email address)
        // shadow-recipients are email addresses that receive a copy of sent emails
        'shadow-recipients' => [
        ]
    ]
];
