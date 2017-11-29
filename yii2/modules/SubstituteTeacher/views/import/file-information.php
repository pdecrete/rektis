<?php

use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $model ... */

$this->title = Yii::t('substituteteacher', 'File information');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="file-information-view">

    <h1><?= Html::encode(pathinfo($model->filename, PATHINFO_BASENAME)) ?></h1>

    <table class="table table-striped">
        <tbody>
            <?php foreach ($model->fileinfo as $label => $val) : ?>
                <tr>
                    <td><?= $label ?></td>
                    <td><?= $val ?></td>
                </tr>
            <?php endforeach; ?>
            <?php foreach ($model->worksheets as $idx => $name) : ?>
                <tr>
                    <td><?php echo Yii::t('substituteteacher', "Worksheet"), " {$idx}" ?></td>
                    <td>
                        <?= $name ?>
                        <?= Html::a('<span class="glyphicon glyphicon-check"></span> ' . Yii::t('substituteteacher', 'Select this worksheet'), [$route, 'file_id' => $file_id, 'sheet' => $idx], ['class' => 'btn btn-primary btn-sm']) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
