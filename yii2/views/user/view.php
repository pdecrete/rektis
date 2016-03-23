<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->name . ' ' . $model->surname . ' ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Χρήστες', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Ενημέρωση', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php if ($model->status === User::STATUS_ACTIVE) : ?>
            <?=
            Html::a('Απενεργοποίηση', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Είστε σίγουροι για την απενεργοποίηση αυτού του αντικειμένου;',
                    'method' => 'post',
                ],
            ])
            ?>
        <?php else: ?>
            <?=
            Html::a('Ενεργοποίηση', ['undelete', 'id' => $model->id], [
                'class' => 'btn btn-success',
                'data' => [
                    'confirm' => 'Είστε σίγουροι για την ενεργοποίηση αυτού του αντικειμένου;',
                    'method' => 'post',
                ],
            ])
            ?>
        <?php endif; ?>
    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'roles',
            [
                'attribute' => 'status',
                'value' => $model->statuslabel,
            ],
            'last_login:datetime',
            'name',
            'surname',
            'email:email',
            'auth_key',
            'password_hash',
            'password_reset_token',
            'create_ts:datetime',
            'update_ts:datetime',
        ],
    ])
    ?>
</div>
