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
    <?php
	if (!Yii::$app->user->isGuest) {
		$this->registerJs("
			var goLockScreen = false;
			var stop = false;
			var autoLockTimer;
			window.onload = resetTimer;
			window.onmousemove = resetTimer;
			window.onmousedown = resetTimer; // catches touchscreen presses
			window.onclick = resetTimer;     // catches touchpad clicks
			window.onscroll = resetTimer;    // catches scrolling with arrow keys
			window.onkeypress = resetTimer;

			function lockScreen() {
				stop = true;
				window.location.href = '".\yii\helpers\Url::toRoute(['/site/lock-screen'])."?previous='+encodeURIComponent(window.location.href);
			}
			
			function lockIdentity(){
				goLockScreen = true;
			}
			
			function resetTimer() {
				if(stop==true){
				
				}
				else if (goLockScreen) {
					lockScreen();				
				}
				else{
					clearTimeout(autoLockTimer);
					autoLockTimer = setTimeout(lockIdentity, 1000*5*60);  // time is in milliseconds						
				}
					
			}
		");

		// Ngatur css buat semua file yang manggil main.php
		$this->registerCss('
			.kartu-profil {
				margin-top:-5px;
				text-align: center;
				padding: 10px;
				width: 300px;
				background-color: rgb(38, 152, 222);
			}
			.kartu-profil p {
				text-align: center;
				color: rgba(255, 255, 255, 0.8);
				text-shadow: 2px 2px 3px #333333;
			}
			.kartu-profil p.nama-nid {
				font-size: 110%;
			}
			.kartu-profil p.tanggal-terdaftar {
				font-size: 90%;
			}
			.kartu-profil img {
				width: 80px;
				height: 80px;
				margin: 0px 0px 10px 0px;
				border-radius: 50%;
				border-style: solid;
				border-color: #5eb4e7;
				border-width: 9px;
				background-color: #daebf5;
			}
			.kartu-profil .btn {
				width: 40%;
			}
			.kartu-profil .pull-left {
				margin-top: 22px;
			}
			.kartu-profil .pull-right {
				margin-top: -30px;
				margin-bottom: 7px;
			}
		');
	}
	?>
</body>
</html>
<?php $this->endPage() ?>
