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
                'activateParents' => true,
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => [
						[ 	
						'label' => '<i class="glyphicon glyphicon-home"></i> Αρχική', 
						'encode' => false, 
						'url' => ['/site/index']
						],
                    [ 'label' => 'Παράμετροι',
						'visible' => !Yii::$app->user->isGuest,
                        'items' => [
                            '<li class="dropdown-header"><i class="glyphicon glyphicon-cog"></i> Εφαρμογής</li>',
                            [	'label' => 'Ειδικότητες', 
								'url' => ['/specialisation']
							],
                            [	'label' => 'Υπηρεσίες', 
								'url' => ['/service']
							],
                            [	'label' => 'Θέσεις', 
								'url' => ['/position']
							],
                            [	'label' => 'Καταστάσεις υπαλλήλων', 
								'url' => ['/employee-status']
							],
                            
                            '<li class="divider"></li>',
                            
                            '<li class="dropdown-header"><i class="glyphicon glyphicon-sunglasses"></i> Αδειών</li>',
                            [	'label' => 'Είδη αδειών', 
								'url' => ['/leave-type']
							],
                           
                            '<li class="divider"></li>',
                           
                            '<li class="dropdown-header"><i class="glyphicon glyphicon-plane"></i> Μετακινήσεων</li>',
                            [	'label' => 'Αποστάσεις', 
								'url' => ['/transport-distance']
							],
                            [	'label' => 'Είδη μετακινήσεων', 
								'url' => ['/transport-type']
							],
                            [	'label' => 'Μέσα μετακίνησης', 
								'url' => ['/transport-mode']
							],
                            [	'label' => 'Αποφάσεις ανάληψης υποχρέωσης', 
								'url' => ['/transport-funds']
							],
                            [	'label' => 'Καταστάσεις μετακινήσεων', 
								'url' => ['/transport-status']
							],
                            
                            Yii::$app->user->can('admin') ? 
                            '<li class="divider"></li>' : 
                            '<li></li>',
                            
                            Yii::$app->user->can('admin') ? 
                            '<li class="dropdown-header"><i class="glyphicon glyphicon-dashboard"></i> Διαχειριστικές</li>' : 
                            '<li></li>',
                            
                            [	'label' => 'Auth items', 
								'url' => ['/auth-item'],
								'visible' => Yii::$app->user->can('admin'),
							],
                            [	'label' => 'Auth item connections', 
								'url' => ['/auth-item-child'],
								'visible' => Yii::$app->user->can('admin'),
							],
                            [	'label' => 'Auth assignments', 
								'url' => ['/auth-assignment'],
								'visible' => Yii::$app->user->can('admin'),
							],
                            [	'label' => 'Auth rules', 
								'url' => ['/auth-rule'],
								'visible' => Yii::$app->user->can('admin'),
							],
                        ],
                    ],
                    
                    [ 	'label' => 'Χρήστες',
                    	'visible' => Yii::$app->user->can('admin'),
                        'items' => [
                            [	'label' => 'Όλοι οι χρήστες', 
								'url' => ['/user/index']
							],
                            [	'label' => 'Νέος χρήστης', 
								'url' => ['/user/create']
							],
                        ],
                    ],
                    
                    [ 	'label' => 'Εργαζόμενοι',
						'visible' => !Yii::$app->user->isGuest,
                        'items' => [
                            [	'label' => 'Όλοι οι εργαζόμενοι', 
								'url' => ['/employee/index']
							],
                            
                            '<li class="divider"></li>',
							
							'<li class="dropdown-header"><i class="glyphicon glyphicon-sunglasses"></i></li>',
                            [	'label' => 'Άδειες', 
								'url' => ['/leave']
							],
                            [
                                'label' => 'Αρχεία εκτύπωσης αδειών',
                                'url' => ['/leave-print'],
                            ],
                            
                            '<li class="divider"></li>',
                            '<li class="dropdown-header"><i class="glyphicon glyphicon-plane"></i></li>',
                            [	'label' => 'Μετακινήσεις', 
								'url' => ['/transport']
							],
                            [
                                'label' => 'Αρχεία εκτύπωσης μετακινήσεων',
                                'url' => ['/transport-print'],
                            ],
                        ],
                    ],
                    [ 	'label' => 'Σχετικά', 
						'url' => ['/site/about']
					],
                    [ 	'label' => 'Επικοινωνία', 
						'url' => ['/site/contact']
					],
                    Yii::$app->user->isGuest ?
                            [ 	'label' => '<i class="glyphicon glyphicon-log-in"></i> Είσοδος', 
								'encode' => false,
								'visible' => Yii::$app->user->isGuest,
								'url' => ['/site/login']
                            ] :
                            [ 	'label' => '<i class="glyphicon glyphicon-user"></i>', 
								'encode' => false,
								'visible' => !Yii::$app->user->isGuest,
								'items' => [
									'<li class="dropdown-header">' . Yii::$app->user->identity->username . '</li>',
									[	'label' => 'Ο λογαριασμός μου', 
										'url' => ['/user/account']
									],
									'<li class="divider"></li>',
									[	'label' => '<i class="glyphicon glyphicon-log-out"></i> Έξοδος', 
										'encode' => false, 
										'url' => ['/site/logout'], 
										'linkOptions' => ['data-method' => 'post']
									],
								],
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
                <?php
                foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
                    if (!is_array($message)) {
                        $messages = array($message);
                    }
                    echo '<div class="alert alert-' . $key . '">' . implode('<br/>', $messages) . '</div>';
                }
                ?>
                <?= $content ?>
            </div>
        </div>

        <footer class="footer">
            <div class="container">
                <div class="container">
                    <p class="pull-left">&copy; <?= Yii::$app->params['companyName'] ?> <?= date('Y') ?> |
                        <?= Html::a('Αρχική', ['site/index']) ?> | 
                        <?= Html::a('Σχετικά', ['site/about']) ?> | 
                        <?= Html::a('Επικοινωνία', ['site/contact']) ?> | 
                        <?=
                        Yii::$app->user->isGuest ?
                                Html::a('Είσοδος', ['site/login']) :
                                Html::a('Έξοδος ' . Yii::$app->user->identity->username, ['site/logout'], ['data-method' => 'post'])
                        ?>
                    </p>
                    <p class="pull-right"><?= Yii::powered() ?></p>
                </div>
            </div>
        </footer>

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
