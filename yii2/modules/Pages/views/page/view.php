<?php

use yii\bootstrap\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Page */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Σελίδες', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="page-view">

    <h1>
        <?= Html::encode($this->title) ?>
    </h1>

    <p>
        <?= Html::a('Ενημέρωση', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a('Διαγραφή', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Η ενέργεια αυτή είναι μη αναστρέψιμη. Είστε βέβαιοι;',
                'method' => 'post',
            ],
        ])

        ?>
    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'identity',
            'title',
            'content:html',
            'created_at_str',
            'updated_at_str',
        ],
    ])

    ?>

</div>