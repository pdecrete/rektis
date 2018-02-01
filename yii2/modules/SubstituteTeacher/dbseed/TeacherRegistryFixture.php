<?php

namespace app\modules\SubstituteTeacher\dbseed;

use Yii;
use yii\test\ActiveFixture;

class TeacherRegistryFixture extends ActiveFixture
{
    public $tableName = '{{%stteacher_registry}}';
    public $dataFile = __DIR__ . '/data/TeacherRegistry.php';

    public function init()
    {
        parent::init();
        Yii::$app->db->createCommand("SET SQL_MODE='ALLOW_INVALID_DATES'")->execute();
    }

    // protected function getData()
    // {
    //     $data = [
    //         [
    //             'gender' => 'F',
    //             'firstname' => 'ΙΟΥΛΙΑ',
    //             'surname' => 'ΚΑΤΕΧΑΚΗ',
    //             'fathername' => 'ΑΡΙΣΤΟΜΕΝΗΣ',
    //             'mothername' => 'ΕΥΣΤΑΘΙΑ',
    //             'marital_status' => 'S',
    //             'protected_children' => 0,
    //             'mobile_phone' => '6936880531',
    //             'home_phone' => '2810347298',
    //             'work_phone' => '',
    //             'home_address' => 'ΔΙΕΥΘΥΝΣΗ Α0',
    //             'city' => 'ΗΡΑΚΛΕΙΟ',
    //             'postal_code' => '71111',
    //             'social_security_number' => '22129312345',
    //             'tax_identification_number' => '012345678',
    //             'tax_service'=> 'ΧΑΝΙΩΝ',
    //             'identity_number' => 'AA000000',
    //             'bank' => 'ΕΘΝΙΚΗ',
    //             'iban' => 'GR0000000000000000000000000',
    //             'email' => 'spapad+10@gmail.com',
    //             'birthdate' => '1993-12-12',
    //             'birthplace' => 'ΗΡΑΚΛΕΙΟ',
    //             'aei' => 1,
    //             'tei' => 0,
    //             'epal' => 0,
    //             'iek' => 0,
    //             'military_service_certificate' => 0,
    //             'sign_language' => 0,
    //             'braille' => 0,
    //             'comments' => '',
    //             'created_at' => date('Y-m-d H:i:s'),
    //             'updated_at' => date('Y-m-d H:i:s')
    //         ],
    //         [
    //             'gender' => 'M',
    //             'firstname' => 'ΑΛΕΚΟΣ',
    //             'surname' => 'ΑΛΕΞΑΚΗΣ',
    //             'fathername' => 'ΝΙΚΟΣ',
    //             'mothername' => 'ΕΛΕΝΗ',
    //             'marital_status' => 'Μ',
    //             'protected_children' => 3,
    //             'mobile_phone' => '6936880531',
    //             'home_phone' => '2810347298',
    //             'work_phone' => '',
    //             'home_address' => 'ΔΙΕΥΘΥΝΣΗ Α1',
    //             'city' => 'ΗΡΑΚΛΕΙΟ',
    //             'postal_code' => '71110',
    //             'social_security_number' => '12128912345',
    //             'tax_identification_number' => '112345678',
    //             'tax_service'=> 'ΗΡΑΚΛΕΙΟΥ',
    //             'identity_number' => 'AA123456',
    //             'bank' => 'ΕΘΝΙΚΗ',
    //             'iban' => 'GR0000000000000000000000000',
    //             'email' => 'spapad+11@gmail.com',
    //             'birthdate' => '1989-12-12',
    //             'birthplace' => 'ΗΡΑΚΛΕΙΟ',
    //             'aei' => 1,
    //             'tei' => 0,
    //             'epal' => 0,
    //             'iek' => 0,
    //             'military_service_certificate' => 1,
    //             'sign_language' => 0,
    //             'braille' => 0,
    //             'comments' => '',
    //             'created_at' => date('Y-m-d H:i:s'),
    //             'updated_at' => date('Y-m-d H:i:s')
    //         ],
    //         [
    //             'gender' => 'F',
    //             'firstname' => 'ΚΑΛΛΙΟΠΗ',
    //             'surname' => 'ΠΑΠΑΝΙΚΟΛΑΟΥ',
    //             'fathername' => 'ΝΙΚΟΣ',
    //             'mothername' => 'ΕΥΤΕΡΠΗ',
    //             'marital_status' => 'D',
    //             'protected_children' => 1,
    //             'mobile_phone' => '6936880531',
    //             'home_phone' => '2810347298',
    //             'work_phone' => '',
    //             'home_address' => 'ΔΙΕΥΘΥΝΣΗ Β1',
    //             'city' => 'ΙΕΡΑΠΕΤΡΑ',
    //             'postal_code' => '71110',
    //             'social_security_number' => '12058912345',
    //             'tax_identification_number' => '212345678',
    //             'tax_service'=> 'ΗΡΑΚΛΕΙΟΥ',
    //             'identity_number' => 'AΒ123456',
    //             'bank' => 'ΕΘΝΙΚΗ',
    //             'iban' => 'GR0000000000000000000000000',
    //             'email' => 'spapad+12@gmail.com',
    //             'birthdate' => '1989-05-12',
    //             'birthplace' => 'ΑΓΙΟΣ ΝΙΚΟΛΑΟΣ',
    //             'aei' => 0,
    //             'tei' => 1,
    //             'epal' => 0,
    //             'iek' => 0,
    //             'military_service_certificate' => 0,
    //             'sign_language' => 1,
    //             'braille' => 0,
    //             'comments' => '',
    //             'created_at' => date('Y-m-d H:i:s'),
    //             'updated_at' => date('Y-m-d H:i:s')
    //         ],
    //         [
    //             'gender' => 'M',
    //             'firstname' => 'ΚΩΝΣΤΑΝΤΙΝΟΣ',
    //             'surname' => 'ΓΕΩΡΓΙΟΥ',
    //             'fathername' => 'ΑΘΑΝΑΣΙΟΣ',
    //             'mothername' => 'ΕΛΕΝΗ',
    //             'marital_status' => 'S',
    //             'protected_children' => 0,
    //             'mobile_phone' => '6936880531',
    //             'home_phone' => '2810347298',
    //             'work_phone' => '',
    //             'home_address' => 'ΔΙΕΥΘΥΝΣΗ Β2',
    //             'city' => 'ΑΘΗΝΑ',
    //             'postal_code' => '70001',
    //             'social_security_number' => '02128912345',
    //             'tax_identification_number' => '312345678',
    //             'tax_service'=> 'Α ΑΘΗΝΩΝ',
    //             'identity_number' => 'AΕ123456',
    //             'bank' => 'ΕΘΝΙΚΗ',
    //             'iban' => 'GR0000000000000000000000000',
    //             'email' => 'spapad+13@gmail.com',
    //             'birthdate' => '1989-12-02',
    //             'birthplace' => 'ΗΡΑΚΛΕΙΟ',
    //             'aei' => 0,
    //             'tei' => 1,
    //             'epal' => 0,
    //             'iek' => 0,
    //             'military_service_certificate' => 1,
    //             'sign_language' => 1,
    //             'braille' => 0,
    //             'comments' => '',
    //             'created_at' => date('Y-m-d H:i:s'),
    //             'updated_at' => date('Y-m-d H:i:s')
    //         ],
    //     ];

    //     return $data;
    // }
}
