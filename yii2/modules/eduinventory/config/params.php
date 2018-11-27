<?php

return [
    'params' => [               
        'eduinventory_uploadfolder' => '@vendor/admapp/uploads/eduinventory/',
        'eduinventory_importfolder' => '@vendor/admapp/uploads/eduinventory/imports/',
        
        'teachersimport_excelfile_columns' => [  'AM' => 1, 'AFM' => 2, 'GENDER' => '3', 'SURNAME' => 4, 'NAME' => 5, 'FATHERNAME' => 6, 'MOTHERNAME' => 7, 'SPECIALISATION' => 14, 'ORGANIC_SCHOOL' => 35],
        
        'prefectures' => [  'Ηρακλείου' => 'Ηρακλείου',
                            'Λασιθίου' => 'Λασιθίου',
                            'Ρεθύμνου' => 'Ρεθύμνου',
                            'Χανίων' => 'Χανίων',        
                         ],
        
        'education_levels' => [  'PRIMARY' => 'Πρωτοβάθμιας', 'SECONDARY' => 'Δευτεροβάθμιας' ],
    ]
];