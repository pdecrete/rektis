<?php

use yii\helpers\Html;
use dosamigos\fileupload\FileUploadUI;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LeaveSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Files');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="leave-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    FileUploadUI::widget([
        'model' => $model,
        'attribute' => 'image',
        'url' => [
            'SubstrituteTeacher/file/image-upload',
            'id' => 1, // $id
        ],
        'gallery' => false,
        'fieldOptions' => [
            'accept' => 'image/*'
        ],
        'clientOptions' => [
            'maxFileSize' => 2000000
        ],
        'clientEvents' => [
            'fileuploaddone' => 'function(e, data) {
                console.log(e);
                console.log(data);
            }',
            'fileuploadfail' => 'function(e, data) {
                console.log(e);
                console.log(data);
            }',
        ],
    ]);

    ?>


</div>
