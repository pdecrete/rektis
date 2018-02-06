<?php

use yii\helpers\Html;
use dosamigos\fileupload\FileUploadUI;
use app\modules\SubstituteTeacher\assets\ModuleAsset;

$bundle = ModuleAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel app\models\LeaveSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('substituteteacher', 'Substitute Teacher Files Upload');
$this->params['breadcrumbs'][] = ['label' => Yii::t('substituteteacher', 'Substitute Teacher Files'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="leave-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    FileUploadUI::widget([
        'model' => $model,
        'attribute' => 'uploadfile',
        'url' => [
            'substitute-teacher-file/file-upload',
        ],
        'gallery' => false,
//        'fieldOptions' => [
//            'accept' => 'image/*',
//        ],
        'clientOptions' => [
            'type' => 'POST',
            'maxFileSize' => 2000000
        ],
        'clientEvents' => [
            'fileuploaddone' => 'function(e, data) {
                substitute_teacher_module.alert_fileuploaddone();
            }',
            'fileuploadfail' => 'function(e, data) {
                substitute_teacher_module.alert_fileuploadfail();
            }',
            'fileuploadprocessfail' => 'function(e, data) {
                substitute_teacher_module.alert_fileuploadfail();
            }',
        ],
    ]);

    ?>


</div>
