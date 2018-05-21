<?php 

use yii\bootstrap\Html;

echo Html::beginForm($email_postman_route, 'post', [
    'id' => 'email-postman',
    'method' => 'post',
]),
    Html::hiddenInput('envelope', $envelope, []),
    Html::submitButton("<span class=\"glyphicon glyphicon-send\"></span> {$label}", ['class' => 'btn btn-primary', 'encode' => false]),
    Html::endForm();
