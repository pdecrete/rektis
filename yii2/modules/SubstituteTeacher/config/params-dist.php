<?php
return [
    'params' => [
        'maxFileSize' => 200000,
        'applications-baseurl' => 'http://app.pdekritis.gr/aitisi',
        'applications-key' => 'your-secret-key',
        'crypt-key-file' => "/path/to/your/key.file",
        // "codes" of specialisations this module *handles*; used to filter specialisations
        'applicable-specialisation-codes' => [
            'ΠΕ 2300',
            'ΠΕ 2500',
            'ΠΕ 3000',
            'ΕΒΠ'
        ],
        'extra-call-teachers-percent' => 0.2, // call an extra 20% of teachers to cover loses; ONLY USED WHEN call does not explicitely define number of teachers to call 
    ]
];
