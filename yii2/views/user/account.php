<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Ο λογαριασμός μου: ' . $model->name;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Ενημέρωση στοιχείων', ['updateaccount'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'auth_key',
            'password_hash',
            'password_reset_token',
            'email:email',
            'name',
            'surname',
            [
                'attribute' => 'status',
                'value' => $model->statuslabel,
            ],
            'last_login:datetime',
            'create_ts:datetime',
            'update_ts:datetime',
        ],
    ])
    ?>

</div>
