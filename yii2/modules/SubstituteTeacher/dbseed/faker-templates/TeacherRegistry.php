<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
$gender = $faker->randomElement(['M','F','O']);
$gender_faker = ($gender === 'M' ? 'male' : ($gender === 'F' ? 'female' : null));
$max_bdate_year = (int)date('Y') - 21;
$edu_selection = $faker->randomElement(['aei', 'tei', 'epal', 'iek']);

return [
    'gender' => $gender,
    'firstname' => $faker->firstName($gender_faker),
    'surname' => $faker->lastName, 
    'fathername' => $faker->firstNameMale,
    'mothername' => $faker->firstNameFemale,
    'marital_status' => $faker->randomElement(['M','S','D']),
    'protected_children' => $faker->numberBetween(0, 5),
    'mobile_phone' => $faker->regexify('69[0-9]{8}'),
    'home_phone' => $faker->regexify('2[0-9]{9}'),
    'work_phone' => $faker->regexify('2[0-9]{9}'),
    'home_address' => $faker->streetAddress,
    'city' => $faker->city,
    'postal_code' => $faker->regexify('[1-9][0-9]{4}'),
    'social_security_number' => $faker->regexify('[0-9]{11}'),
    'tax_identification_number' => $faker->regexify('[0-9]{9}'),
    'tax_service'=> $faker->city,
    'identity_number' => $faker->regexify('[A-Z]{2}[0-9]{6}'),
    'bank' => $faker->randomElement(['ΕΘΝΙΚΗ', 'ΠΕΙΡΑΙΩΣ', 'ALPHA', 'EUROBANK']),
    'iban' => 'GR' . $faker->regexify('[0-9]{25}'),
    'email' => $faker->email,
    'birthdate' => $faker->date('Y-m-d', mktime(0, 0, 0, 12, 31, $max_bdate_year)),
    'birthplace' => $faker->city,
    'aei' => ($edu_selection === 'aei' ? 1 : 0),
    'tei' => ($edu_selection === 'tei' ? 1 : 0),
    'epal' => ($edu_selection === 'epal' ? 1 : 0),
    'iek' => ($edu_selection === 'iek' ? 1 : 0),
    'military_service_certificate' => ($faker->boolean(80) ? 1 : 0),
    'sign_language' => ($faker->boolean(10) ? 1 : 0),
    'braille' => ($faker->boolean(5) ? 1 : 0),
    'comments' => $faker->text(200),
    'created_at' => date('Y-m-d H:i:s'),
    'updated_at' => date('Y-m-d H:i:s')
];
