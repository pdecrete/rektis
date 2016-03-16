<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
        <style>
            body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,code,form,fieldset,legend,input,textarea,p,blockquote,th,td{margin:0;padding:0;}table{border-collapse:collapse;border-spacing:0;}fieldset,img{border:0;}address,caption,dfn,th,var{font-style:normal;font-weight:normal;}li{list-style:none;}caption,th{text-align:left;}h1,h2,h3,h4,h5,h6{font-size:100%;font-weight:normal;}
            html,body { height: 100%; }
            body { margin:0; padding:0; background:#000; font-family:Helvetica, sans-serif; }
            div#shim { visibility: hidden; width: 100%; height: 50%; margin-top: -140px; float: left; }
            div#content { width: 90%; height: auto; margin: 0 auto; clear: both; position: relative; top: -140px; position: static; }
            /* Hide from IE5mac \*//*/
            div#shim {
            display: none;
            }
            html, body {
            height: auto;
            }
            /* end hack */
            /* ]]> */
            .logo_box { width: 35%; float: left; border-right: 1px solid #303030; height: 280px; position: relative; }
            h1 { padding: 12px 70px 12px 20px; position: absolute; right: 0; text-align:left; top: 25%; float: left; color: #fff; letter-spacing: -1px; font-size: 3em; }
            .main_box { float: left; width: 60%; padding: 25px; }
            h2 { font-family: serif; color: #ffe400; font-size: 2em; margin-bottom: 1em; }
            .main_box p, .main_box div { text-align: left; color: #fff; line-height: 1.2em; }
            ul.info { padding: 0; margin: 2em 0 0 0; float: left; }
            ul.info li { margin-bottom: 20px; clear: both; float: left; }
            ul.info li p { font-size: 0.9em; line-height: 2em; color: #fff; float: left; margin: 0; }
            ul.info h3 { font-size: 1.5em; color: #333; float: left; margin-right: 15px; padding-top: 5px; }
            pre { white-space: pre-line; }
        </style>
    </head>
    <body>
        <?php $this->beginBody() ?>
        <div id="shim"></div>
        <div id="content">
            <div class="logo_box"><h1><?= preg_replace('/[[:space:]]+/', '<br/>', Yii::$app->name); ?></h1></div>          
            <div class="main_box">
                <h2><?= $this->title; ?></h2>
                <div><?= $content ?></div>
                <ul class="info">
                    <li>
                        <h3><span class="glyphicon glyphicon-home" aria-hidden="true"></span></h3>
                        <p><?= Html::a('Πίσω στην αρχική σελίδα', Url::home()); ?></p>
                    </li>
                    <li>
                        <h3><span class="glyphicon glyphicon-circle-arrow-left" aria-hidden="true"></span></h3>
                        <p><?= Html::a('Πίσω ', Url::previous()); ?> (εφοσον εχει τεθει)</p>
                    </li>
                    <li>
                        <h3><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></h3>
                        <p><?= date('d/m/Y H:i:s') ?></p>
                    </li>
                    <li>
                        <h3><span class="glyphicon glyphicon-asterisk" aria-hidden="true"></span></h3>
                        <p><?= Yii::powered() ?></p>
                    </li>
                </ul>
            </div>
        </div>

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
