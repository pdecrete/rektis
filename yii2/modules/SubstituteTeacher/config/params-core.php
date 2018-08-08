<?php

// you should not need to change this file contents
return [
    'params' => [
        'identity-number-pattern' => '/^[A-ZΑ-Ω]+[0-9]+$/', // preg pattern; only letters and numbers; allow greek for older id cards
        'ada-validate-pattern' => '/^[Α-Ω0-9]+-[Α-Ω0-9]+$/', // preg pattern 
        'ada-view-baseurl' => 'https://diavgeia.gov.gr/decision/view/', // used to link to ADA decisions; ada number is appended to this
    ]
];
