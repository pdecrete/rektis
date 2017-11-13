<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = Yii::$app->name;

?>
<div class="site-index">
    <div class="page-header">
        <h1>Αρχική σελίδα <small><?= Yii::$app->name ?></small></h1>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Ο λογαριασμός μου</h2>
                <?php if (!Yii::$app->user->isGuest) : ?>
                    <p><i class="glyphicon glyphicon-credit-card"></i> <?= Yii::$app->user->identity->fullname ?></p>
                    <p><i class="glyphicon glyphicon-user"></i> <?= Yii::$app->user->identity->username ?></p>
                    <p><i class="glyphicon glyphicon-envelope"></i> <?= Yii::$app->user->identity->email ?></p>
                <?php endif; ?>
                <p><?= Html::a('<i class="glyphicon glyphicon-user"></i> Τα στοιχεία μου', ['/user/account'], ['class' => 'btn btn-primary btn-block']) ?></p>
                <p><?= Html::a('<i class="glyphicon glyphicon-log-out"></i> Αποσύνδεση', ['/site/logout'], ['data-method' => 'post', 'class' => 'btn btn-danger btn-block sweetalert', 'data-confirm' => 'Είστε βέβαιοι για την αποσύνδεση;']) ?></p>
            </div>
            <div class="col-lg-8">
                <?php if (!Yii::$app->user->isGuest && defined(YII_ENV) && YII_ENV === 'dev') : ?>
                    <h2>DEBUG</h2>
                    <div class="well" style="white-space: pre-line;">
                        <p>DEMO and SAMPLE calls; to be removed on production</p>
                        <p>YII_ENV = [<?= YII_ENV ?>]</p>
                        <?= "User ID: " . Yii::$app->user->getId() . " username: " . Yii::$app->user->identity->username . " roles: " . implode(', ', array_keys(Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId()))); ?> <br/>
                        <?= "User has role 'admin': " . yii\helpers\VarDumper::dumpAsString(Yii::$app->user->can('admin')); ?> <br/>
                        Last login: <?= Yii::$app->user->identity->last_login; ?>, Create at: <?= Yii::$app->user->identity->create_ts; ?>, Update at: <?= Yii::$app->user->identity->update_ts; ?> <br/>
                        <?= "Params: " . yii\helpers\VarDumper::dumpAsString(Yii::$app->params); ?> <br/>
                        <?php
                        $pass = admapp\Util\Core::generateToken(10);
                        echo $pass, ' = ', Yii::$app->security->generatePasswordHash($pass);

                        ?> <br/>
                        <?= admapp\Util\Core::generateToken(20); ?> <br/>
                    </div>
                <?php else: ?>
                    <div class="well" style="white-space: pre-line;">
                        <h2> <?= Yii::t('app', 'Welcome!') ?> </h2>
                    </div>
                <?php endif; ?>

            </div>
        </div>

    </div>
</div>
