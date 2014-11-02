<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav as Nav2;
use yii\bootstrap\NavBar as NavBar2;
use yii\widgets\Breadcrumbs;
use hscstudio\heart\widgets\Nav;
use hscstudio\heart\widgets\NavBar;

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
    <link href="<?php echo Url::to('favicon.ico');?>" rel="shortcut icon"> <!-- fajar -->
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title); ?> - SIMBPPK</title> <!-- fajar -->
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <div class="wrap">
        <?php
        	if (Yii::$app->user->isGuest === false) { // kalau guest, artinya belum login, so ga usah munculin menu
	            NavBar::begin([
	                'brandLabel' => '<img src="'.Yii::$app->homeUrl.'/logo_simbppk_pelangi.png"> SIM BPPK',
	                'brandUrl' => Yii::$app->homeUrl,
	                'options' => [
	                    'class' => 'navbar-inverse navbar-fixed-top',
	                ],
					'innerContainerOptions'=>[
	                    'class' => 'container-fluid',
	                ]
	            ]);
				
				if (Yii::$app->user->isGuest) {
					//$menuItems[] = ['icon'=>'fa fa-user fa-fw','label' => 'Signup', 'url' => ['/site/signup']];
					$menuItems[] = ['icon'=>'fa fa-key fa-fw','label' => 'Login', 'url' => ['/site/login']];
	            } else {
					$callback = function($menuX){
						$data = eval($menuX['data']);
						return [
							'label' => $menuX['name'], 
							'url' => [$menuX['route']],
							'icon'=> isset($data['icon'])?$data['icon']:'fa fa-link fa-fw',
							'path'=> isset($data['path'])?$data['path']:'',
							'items' => $menuX['children'],
						];
					};
					
					if(\Yii::$app->user->can('superadmin')){
						$menuItemsLeft[] =
							['icon'=>'fa fa-android fa-fw','label' => 'Admin', 'url' => ['/administrator/default/index'], 'items' => [
								['icon'=>'fa fa-cog fa-fw fa-spin','label'=>'Generator', 'url'=> ['/gii']],
								['icon'=>'fa fa-warning fa-fw','label'=>'Privileges', 'url'=> ['/privilege/assignment'],'path'=>'privilege'],
								['icon'=>'fa fa-key fa-fw','label'=>'Users', 'url'=> ['/admin/default'],'path'=>'/admin/'],
							]];
					}
					
					function checkAccess($roles=[]){
						foreach ($roles as $role){
							if(\Yii::$app->user->can($role)){
								return true;
							}
						}					
					}
					
					if(checkAccess([
						'sekretariat-badan-finance',
						'sekretariat-badan-hrd',
						'sekretariat-badan-organisation',
						'sekretariat-badan-tik',
						'sekretariat-badan-general'
					])){
						/* $menuItemsLeft[] =
							['icon'=>'fa fa-home fa-fw','label' => 'SEKRETARIAT', 'url' => '#', 'items' => [
								['icon'=>'fa fa-sitemap fa-fw','label'=>'Organisation', 'url'=> ['/sekretariat-organisation/default/index'],'path'=>'sekretariat-organisation' ],
								['icon'=>'fa fa-users fa-fw','label'=>'HRD', 'url'=> ['/sekretariat-hrd/default/index'],'path'=>'sekretariat-hrd' ],
								['icon'=>'fa fa-money fa-fw','label'=>'Finance', 'url'=> ['/sekretariat-finance/default/index'],'path'=>'sekretariat-finance' ],
								['icon'=>'fa fa-desktop fa-fw','label'=>'Information Technology', 'url'=> ['/sekretariat-it/default/index'],'path'=>'sekretariat-it' ],
								['icon'=>'fa fa-joomla fa-fw','label'=>'General', 'url'=> ['/sekretariat-general/default/index'],'path'=>'sekretariat-general' ],
							]]; */
						$menus_sekretariat = \mdm\admin\components\MenuHelper::getAssignedMenu(\Yii::$app->user->id,1,$callback,true);
						$menuItemsLeft[] = ['icon'=>'fa fa-home fa-fw','label' => 'Sekretariat', 'url' => ['#'], 'items' => $menus_sekretariat ];
					}
					
					if(checkAccess([
						'pusdiklat-general-1','pusdiklat-general-2','pusdiklat-general-3',
						'pusdiklat-planning-1','pusdiklat-planning-2','pusdiklat-planning-3',
						'pusdiklat-execution-1','pusdiklat-execution-2',
						'pusdiklat-evaluation-1','pusdiklat-evaluation-2','pusdiklat-evaluation-3',
						
					])){
						/* $menuItemsLeft[] =
							['icon'=>'fa fa-building fa-fw','label' => 'PUSDIKLAT', 'url' => ['#'], 'items' => [
								['icon'=>'fa fa-joomla fa-fw','label'=>'Tata Usaha', 'url'=> ['/pusdiklat-general/default/index'],'path'=>'pusdiklat-general'],
								['icon'=>'fa fa-calendar fa-fw','label'=>'Perencanaan', 'url'=> ['/pusdiklat-planning/default/index'],'path'=>'pusdiklat-planning'],
								['icon'=>'fa fa-paper-plane fa-fw','label'=>'Penyelenggaraan', 'url'=> ['/pusdiklat-execution/default/index'],'path'=>'pusdiklat-execution'],
								['icon'=>'fa fa-check-square-o fa-fw','label'=>'Evaluasi', 'url'=> ['/pusdiklat-evaluation/default/index'],'path'=>'pusdiklat-evaluation'],
							]]; */
						$menus_pusdiklat = \mdm\admin\components\MenuHelper::getAssignedMenu(Yii::$app->user->id,7,$callback,true);
						$menuItemsLeft[] = ['icon'=>'fa fa-building fa-fw','label' => 'Pusdiklat', 'url' => ['#'], 'items' => $menus_pusdiklat ];
					}
					
					if(checkAccess([
						'pusdiklat2-general',
						'pusdiklat2-training',
						'pusdiklat2-test',
						'pusdiklat2-scholarship',
						
					])){
						$menuItemsLeft[] =
							['icon'=>'fa fa-building fa-fw','label' => 'PSDM', 'url' => ['#'], 'items' => [
								['icon'=>'fa fa-joomla fa-fw','label'=>'General', 'url'=> ['/pusdiklat2-general/default/index'],'path'=>'pusdiklat2-general' ],
								['icon'=>'fa fa-stack-overflow fa-fw','label'=>'Training', 'url'=> ['/pusdiklat2-training/default/index'],'path'=>'pusdiklat2-training' ],
								['icon'=>'fa fa-sort-numeric-asc fa-fw','label'=>'Test', 'url'=> ['/pusdiklat2-test/default/index'],'path'=>'pusdiklat2-test' ],
								['icon'=>'fa fa-graduation-cap fa-fw','label'=>'Scholarship', 'url'=> ['/pusdiklat2-scholarship/default/index'],'path'=>'pusdiklat2-scholarship' ],
							]];
					}
					
					if(checkAccess([
						'bdk-general',
						'bdk-execution',
						'bdk-evaluation',
						
					])){				
						$menuItemsLeft[] =
							['icon'=>'fa fa-university fa-fw','label' => 'BDK', 'url' => ['#'], 'items' => [
								['icon'=>'fa fa-joomla fa-fw','label'=>'General', 'url'=> ['/bdk-general/default/index'],'path'=>'bdk-general'],
								['icon'=>'fa fa-paper-plane fa-fw','label'=>'Execution', 'url'=> ['/bdk-execution/default/index'],'path'=>'bdk-execution'],
								['icon'=>'fa fa-check-square-o fa-fw','label'=>'Evaluation', 'url'=> ['/bdk-evaluation/default/index'],'path'=>'bdk-evaluation'],
							]];
					}
					
	                echo Nav::widget([
	                    'options' => ['class' => 'navbar-nav'],
	                    'position'=>'left',
	                    'items' => $menuItemsLeft,
	                ]);

	                $menuItems[] =  
	                    ['icon'=>'fa fa-user','label'=>'', 'url'=> '', 'items'=>[
							['icon'=>'fa fa-user','label' => 'Profile','url' => ['/user/user/profile'],],
	                        '<li class="divider"></li>',
	                        [
	                            'icon'=>'fa fa-power-off',
	                            'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
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
        	}
        ?>

        <div class="container-fluid">
        <?= $content ?>
        </div>
    </div>

    <?php if (Yii::$app->user->isGuest === false) { // Klo guest, berarti belum login, so ga usa munculin footer ?>
    <footer class="footer">
        <div class="container-fluid">
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
					autoLockTimer = setTimeout(lockIdentity, 1000*15*60);  // time is in milliseconds						
				}
					
			}
		");
	}
	?>

</body>
</html>
<?php $this->endPage() ?>
