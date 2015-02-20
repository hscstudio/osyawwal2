<?php
use yii\helpers\Html;
use yii\bootstrap\Nav as Nav2;
use yii\bootstrap\NavBar as NavBar2;
use yii\widgets\Breadcrumbs;
use hscstudio\heart\widgets\Nav;
use hscstudio\heart\widgets\NavBar;
use frontend\assets\AppAsset;
use frontend\widgets\Alert;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="<?php echo Yii::$app->homeUrl;?>/favicon.ico" rel="shortcut icon"> <!-- modipikasi -->
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?>- SIMBPPK</title>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <div class="wrap">
        <?php
			NavBar::begin([
                'brandLabel' => 'SIMBPPK',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
				'innerContainerOptions'=>[
                    'class' => 'container-fluid',
                ]
            ]);
            
            if (Yii::$app->user->isGuest) {
               // $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
			   $menuItems = [['label' => 'Home', 'url' => ['/site/index']],];
                $menuItems[] = ['label' => 'Masuk', 'url' => ['/site/masuk']];
            } else {
                /* $menuItems[] = [
                    'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post']
                ]; */
				$menuItems[] =  
                    ['icon'=>'fa fa-user','label'=>'', 'url'=> '', 'items'=>[
						['icon'=>'fa fa-user','label' => 'Profile','url' => ['/student/student/profile'],],
                        '<li class="divider"></li>',
                        [
                            'icon'=>'fa fa-power-off',
                            'label' => 'Logout (' . Yii::$app->user->identity->person->name . ')',
                            'url' => ['/site/logout'],
                            'linkOptions' => ['data-method' => 'post']
                        ]
                    ]                        
                ];
            }
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $menuItems,
            ]);
            NavBar::end();
		  
        ?>

        <div class="container-fluid">
        <?= $content ?>
        </div>
    </div>

    <?php if (Yii::$app->user->isGuest === false) { // Klo guest, berarti belum login, so ga usa munculin footer ?>
    <footer class="footer">
		<div class="col-md-12">
			<p>Hak cipta &copy; Badan Pendidikan dan Pelatihan Keuangan <?= date('Y') ?></p>
		</div>
    </footer>
    <?php } ?>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
