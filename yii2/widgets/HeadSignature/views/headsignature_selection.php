<?php

use kartik\select2\Select2;

echo Select2::widget([
    'name' => 'state_10',
    'data' => $head_signs,
    'options' => [
        'placeholder' => 'Select Head to sign ...',
        'multiple' => false
    ],
    'pluginOptions' => [
        'allowClear' => true
    ],
]);