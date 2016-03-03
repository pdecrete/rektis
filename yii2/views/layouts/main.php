<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
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
    </head>
    <body>
        <?php $this->beginBody() ?>

        <div class="wrap">
            <?php
            NavBar::begin([
                'brandLabel' => Yii::$app->params['companyName'],
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => [
                    [ 'label' => 'Αρχική', 'url' => ['/site/index']],
                    [ 'label' => 'Παράμετροι',
                        'items' => [
                            '<li class="dropdown-header">Εφαρμογής</li>',
                            ['label' => 'Ειδικότητες', 'url' => ['/specialisation']],
                            ['label' => 'Υπηρεσίες', 'url' => ['/service']],
                            ['label' => 'Θέσεις', 'url' => ['/position']],
                            ['label' => 'Καταστάσεις υπαλλήλων', 'url' => ['/employee-status']],
                            '<li class="divider"></li>',
                            '<li class="dropdown-header">Διαχειριστικές</li>',
                            ['label' => 'Auth items', 'url' => ['/auth-item']],
                            ['label' => 'Auth item connections', 'url' => ['/auth-item-child']],
                            ['label' => 'Auth assignments', 'url' => ['/auth-assignment']],
                            ['label' => 'Auth rules', 'url' => ['/auth-rule']],
                        ],
                    ],
                    [ 'label' => 'Χρήστες',
                        'items' => [
                            '<li class="dropdown-header">Λογαριασμός</li>',
                            ['label' => 'Ο λογαριασμός μου', 'url' => ['/employee']],
                            '<li class="divider"></li>',
                            '<li class="dropdown-header">Διαχειριστικά</li>',
                            ['label' => 'Όλοι οι χρήστες', 'url' => ['/employee']],
                        ],
                    ],
                    [ 'label' => 'Σχετικά', 'url' => ['/site/about']],
                    [ 'label' => 'Επικοινωνία', 'url' => ['/site/contact']],
                    Yii::$app->user->isGuest ?
                            [
                        'label' => 'Είσοδος',
                        'url' => ['/site/login']
                            ] :
                            [
                        'label' => 'Έξοδος (' . Yii::$app->user->identity->username . ')',
                        'url' => ['/site/logout'],
                        'linkOptions' => ['data-method' => 'post']
                            ],
                ],
            ]);
            NavBar::end();
            ?>

            <div class="container">
                <?=
                Breadcrumbs::widget([
                    'homeLink' => [ 'label' => 'Αρχική', 'url' => ['/site/index']],
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ])
                ?>
                <?= $content ?>
            </div>
        </div>

        <footer class="footer">
            <div class="container">
                <p class="pull-left">&copy; <?= Yii::$app->params['companyName'] ?> <?= date('Y') ?></p>

                <p class="pull-right"><?= Yii::powered() ?></p>
            </div>
        </footer>

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
