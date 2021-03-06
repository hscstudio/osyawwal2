<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav as Nav2;
use yii\bootstrap\NavBar as NavBar2;
use yii\widgets\Breadcrumbs;
use hscstudio\heart\widgets\Nav;
use hscstudio\heart\widgets\NavBar;

use backend\models\Person;
use backend\models\ObjectFile;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="<?php echo Yii::$app->homeUrl;?>/favicon.ico" rel="shortcut icon">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title); ?> - SIMBPPK</title>
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
					$menuItems[] = ['icon'=>'fa fa-key fa-fw','label' => 'Masuk', 'url' => ['/site/masuk']];
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
						'sekretariat-finance-anggaran','sekretariat-finance-perbendaharaan','sekretariat-finance-akuntansi',
						'sekretariat-hrd-general','sekretariat-hrd-development','sekretariat-hrd-jafung','sekretariat-hrd-ki',
						'sekretariat-organisation-organisasi','sekretariat-organisation-tatalaksana','sekretariat-organisation-hukker',
						'sekretariat-it-si','sekretariat-it-duktek','sekretariat-it-komlik',
						'sekretariat-general-tu','sekretariat-general-asset','sekretariat-general-rumah-tangga',
					])){
						$menus_sekretariat = \mdm\admin\components\MenuHelper::getAssignedMenu(\Yii::$app->user->id,1,$callback,true);
						$menuItemsLeft[] = ['icon'=>'fa fa-jsfiddle fa-fw','label' => 'Sekretariat', 'url' => ['#'], 'items' => $menus_sekretariat ];
					}
					
					if(checkAccess([
						'pusdiklat-general-1','pusdiklat-general-2','pusdiklat-general-3',
						'pusdiklat-planning-1','pusdiklat-planning-2','pusdiklat-planning-3',
						'pusdiklat-execution-1','pusdiklat-execution-2',
						'pusdiklat-evaluation-1','pusdiklat-evaluation-2','pusdiklat-evaluation-3',
						
					])){
						$menus_pusdiklat = \mdm\admin\components\MenuHelper::getAssignedMenu(Yii::$app->user->id,7,$callback,true);
						$menuItemsLeft[] = ['icon'=>'fa fa-building fa-fw','label' => 'Pusdiklat', 'url' => ['#'], 'items' => $menus_pusdiklat ];
					}
					
					if(checkAccess([
						'pusdiklat2-general-1','pusdiklat2-general-2','pusdiklat2-general-3',
						'pusdiklat2-competency-planning-1','pusdiklat2-competency-planning-2','pusdiklat2-competency-planning-3','pusdiklat2-competency-execution-1','pusdiklat2-competency-execution-2','pusdiklat2-competency-evaluation-1','pusdiklat2-competency-evaluation-2','pusdiklat2-competency-evaluation-3',
						'pusdiklat2-test-planning','pusdiklat2-test-execution','pusdiklat2-test-evaluation',
						'pusdiklat2-scholarship-planning','pusdiklat2-scholarship-selection','pusdiklat2-scholarship-evacuation',
						
					])){
						$menus_pusdiklat2 = \mdm\admin\components\MenuHelper::getAssignedMenu(Yii::$app->user->id,59,$callback,true);
						$menuItemsLeft[] = ['icon'=>'fa fa-building fa-fw','label' => 'Pusdiklat PSDM', 'url' => ['#'], 'items' => $menus_pusdiklat2];
					}
					
					if(checkAccess([
						'bdk-general',
						'bdk-execution',
						'bdk-evaluation',
						
					])){
						$menus_bdk = \mdm\admin\components\MenuHelper::getAssignedMenu(Yii::$app->user->id,101,$callback,true);
						$menuItemsLeft[] =
							['icon'=>'fa fa-university fa-fw','label' => 'BDK', 'url' => ['#'], 'items' => $menus_bdk];
					}
					
					$menuItemsLeft[] =
							['icon'=>'fa fa-crosshairs fa-fw','label' => 'Issue', 'url' => ['/site/issue']];
					
					$menuItemsLeft[] =
							['icon'=>'fa fa-file fa-fw','label' => 'Dokumentasi', 'url' => '#', 'items' =>[
								['icon'=>'fa fa-link fa-faw','label'=>'Main Flow SIM BPPK', 'url' => ['file/free-download','file'=>'guide/main-flow-sim-bppk.pdf']],
								['icon'=>'fa fa-link fa-faw','label'=>'Manual Admin Pengelolaan Pusdiklat', 'url' => ['file/free-download','file'=>'guide/manual-admin-pengelolaan-pegawai.pdf']],
							]];
							
	                echo Nav::widget([
	                    'options' => ['class' => 'navbar-nav'],
	                    'position'=>'left',
	                    'items' => $menuItemsLeft,
	                ]);
					
					// Ngambil foto
					$objectFile = ObjectFile::find()
                        ->where([
                            'object' => 'person',
                            'object_id' => Yii::$app->user->identity->employee->person_id,
                            'type' => 'photo'
                        ])
                        ->joinWith('file')
                        ->one();
                    // dah

                    if (empty($objectFile)) {
                        // foto ga ada, so pake gambar default
                        $pathFoto = Yii::$app->homeUrl.'/logo_simbppk_pelangi.png';
                    }
                    else {
                        $pathFoto = Url::to(['/file/download','file'=>$objectFile->object.'/'.$objectFile->object_id.'/thumb_'.$objectFile->file->file_name]);
                    }

	                $menuItems[] =  
	                    [
	                    	'icon'=>'fa fa-fw fa-user',
	                    	'label'=> Yii::$app->user->identity->employee->person->name, 
	                    	'url'=> '', 
	                    	'items'=>[
	                    		'<li class="kartu-profil">
	                    			<img class="image-corner" src="'.$pathFoto.'">
	                    			<p class="nama-nid">'.Yii::$app->user->identity->employee->person->name.'</p>
	                    			<p class="tanggal-terdaftar">'.Yii::$app->user->identity->employee->person->nid.'<br>Terdaftar sejak '.date('D, d F Y', Yii::$app->user->identity->created_at).'</p>
	                    			'.Html::a('<i class="fa fa-fw fa-user"></i> Profil', ['/user/user/profile'], ['class' => 'btn btn-default pull-left']).'
	                    			'.Html::a('<i class="fa fa-fw fa-sign-out"></i> Keluar', ['/site/logout'], ['class' => 'btn btn-default pull-right', 'data-method'=>'post']).'
	                    		</li>',
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
					autoLockTimer = setTimeout(lockIdentity, 1000*15*60);  // time is in milliseconds						
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
