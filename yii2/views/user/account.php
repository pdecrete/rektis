<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\User;

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
            'name',
            'surname',
            'email:email',
            [
                'attribute' => 'status',
                'value' => $model->statuslabel,
            ],
            'roles',
            'auth_key',
            'password_hash',
            'password_reset_token',
            'last_login:datetime',
            'create_ts:datetime',
            'update_ts:datetime',
        ],
    ])
    ?>

</div>
