<?php
/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>!</h1>

        <p class="lead">You have successfully created your Yii-powered application.</p>

        <p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com">Get started with Yii</a></p>
        <?php if (!Yii::$app->user->isGuest) : ?>
            <div class="well" style="white-space: pre-line;">
                <p>DEMO and SAMPLE calls; to be removed on production</p>
                <?= "User ID: " . Yii::$app->user->getId() . " username: " . Yii::$app->user->identity->username . " roles: " . implode(', ', array_keys(Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId()))); ?> <br/>
                Last login: <?= Yii::$app->user->identity->last_login; ?>, Create at: <?= Yii::$app->user->identity->create_ts; ?>, Update at: <?= Yii::$app->user->identity->update_ts; ?> <br/>
                <?= "Params: " . yii\helpers\VarDumper::dumpAsString(Yii::$app->params); ?> <br/>
                <?php
                $pass = admapp\Util\Core::generateToken(10);
                echo $pass, ' = ', Yii::$app->security->generatePasswordHash($pass);
                ?> <br/>
                <?= admapp\Util\Core::generateToken(20); ?> <br/>
            </div>
        <?php endif; ?>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/doc/">Yii Documentation &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/forum/">Yii Forum &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/extensions/">Yii Extensions &raquo;</a></p>
            </div>
        </div>

    </div>
</div>
