<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Χρήστες';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Δημιουργία νέου χρήστη', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            // 'id',
            'username',
            // 'auth_key',
            // 'password_hash',
            // 'password_reset_token',
            'email:email',
            'name',
            'surname',
            [
                'attribute' => 'status',
                'value' => 'statuslabel',
                'filter' => User::getStatusLabelsArray()
            ],
            [
                'attribute' => 'searchrole',
                'value' => 'roles',
                'filter' => array_combine(array_keys(Yii::$app->authManager->getRoles()), array_keys(Yii::$app->authManager->getRoles()))
            ],
            [
                'attribute' => 'last_login',
                'format' => 'datetime',
                'filter' => false
            ],
//            [
//                'attribute' => 'create_ts',
//                'format' => ['datetime', 'd/M/yyyy'],
//                'filter' => false
//            ],
            [
                'label' => 'Ενημέρωση',
                'attribute' => 'update_ts',
                'format' => 'datetime',
                'filter' => false
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>

</div>
