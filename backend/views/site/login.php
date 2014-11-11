<?php
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\ActiveForm; // Pake activeform kartik, yg yii tak hapus, biar ada addon nya

$this->title = 'Login';
/*
$this->params['breadcrumbs'][] = $this->title;
 */
?>
<div class="site-login">

	<div class="logo">
		<div class="ikon"><img src="<?php echo Yii::$app->homeUrl.'/logo_simbppk_pelangi.png'; ?>"></div>
		<div class="judul apl">SIM BPPK</div>
		<div class="judul">Selamat datang. Silahkan login</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="row">
				<div class="col-lg-12">
					<?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
						
						<?= $form->field($model, 'username', [
						  'template' => "{input}\n{label}\n{hint}\n{error}"
						])->textInput(array('placeholder' => 'Masukkan nama Anda', 'class' => 'form-control input-lg'))->label('<i class="fa fa-fw fa-user"></i>User'); // fajar ?>
						
						<?= $form->field($model, 'password', [
						  'template' => "{input}\n{label}\n{hint}\n{error}"
						])->passwordInput(array('placeholder' => 'Masukkan kata sandi', 'class' => 'form-control input-lg'))->label('<i class="fa fa-fw fa-key"></i>Sandi'); // fajar ?>
						
						<?= $form->field($model, 'rememberMe')->checkbox(); ?>
						<div class="form-group">
							<?= Html::submitButton('Masuk <i class="fa fa-fw fa-sign-in"></i>', [
								'class' => 'btn btn-block btn-lg bg-cyan-pucat', 
								'name' => 'login-button'
							]) // fajar?>
						</div>
						<div class="lupasandi">
							<?= Html::a('Saya lupa kata sandi', ['site/request-password-reset']) ?>
							<br>
							<?= Html::a('Download Panduan Admin', ['file/free-download','file'=>'guide/manual-admin-pengelolaan-pegawai.pdf']) ?>
						</div>
					<?php ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</div>
<div class="hc">
	<p>Hakcipta &copy; <?php echo (date('Y') == '2014' ? '2014': '2014-'.date('Y')); ?> Badan Pendidikan dan Pelatihan Keuangan</p>
</div>
<?php
	// Bikin css khusus buat halaman login
	$noise = '@-webkit-keyframes noise-anim {
		';
	for ($i = 1; $i < 20; $i++) {
		$noise .= ($i*(1/20)*100).'% {
				    clip:rect('.rand(0, 100).'px,9999px,'.rand(0, 100).'px,0);
				  }
				  ';
	}
	$noise .= '}';
	$noise .= '@keyframes noise-anim {
		';
	for ($i = 1; $i < 20; $i++) {
		$noise .= ($i*(1/20)*100).'% {
				    clip:rect('.rand(0, 100).'px,9999px,'.rand(0, 100).'px,0);
				  }
				  ';
	}
	$noise .= '}';
	
	$noise2 = '@-webkit-keyframes noise-anim-2 {
		';
	for ($i = 1; $i < 20; $i++) {
		$noise2 .= ($i*(1/20)*100).'% {
				    clip:rect('.rand(0, 100).'px,9999px,'.rand(0, 100).'px,0);
				  }
				  ';
	}
	$noise2 .= '}';
	$noise2 = '@keyframes noise-anim {
		';
	for ($i = 1; $i < 20; $i++) {
		$noise2 .= ($i*(1/20)*100).'% {
				    clip:rect('.rand(0, 100).'px,9999px,'.rand(0, 100).'px,0);
				  }
				  ';
	}
	$noise2 .= '}';
	
	$this->registerCss('
		body {
			background-color: rgb(38, 152, 222);
			font-weight: 100;
		}
		.lupasandi {
			font-size: 100%;
			text-align: center;
		}
		.right-side {
			background-color: rgba(0,0,0,0);
		}
		.site-login {
			margin: 0px auto;
			width: 400px;
			margin-top: -51px;
		}
		.site-login .panel {
			box-shadow:none;
			border-width:0px;
			padding: 10px;
			background-color: rgb(238, 239, 241);
		}
		.site-login input, .site-login input:focus, .site-login input:active {
			border:none;
			background-color:white;
			color:grey;
			box-shadow: none;
		}

		.input-group {
			width: 100%;
		}
		.has-success .control-label {
			color: white;
		}
		.has-error .control-label {
			color: white;
		}
		.field-loginform-rememberme label {
			font-size: 130%;
			color: grey;
			font-weight: normal;
		}
		a {
			color: grey;
		}
		a:hover {
			text-decoration:none;
		}
		.control-label {
			position: absolute;
			background-color: #5bc0de;
			font-size: 130%;
			padding: 10px 10px;
			width: 100px;
			border-width: 1px;
			border-style:solid;
			border-color: rgba(0,0,0,0);
			color: white;
			border-radius: 4px 0px 0px 4px;
			z-index: 99;
			margin-top: -46px;
			font-weight: normal;
			transition: all .4s ease-in-out;
			transform-origin: left bottom;
		}
		.control-label::before,
		.control-label::after {
			content:"";
	    	position: absolute;
	    	top: 0;
		    right: 0;
		    bottom: 0;
		    left: 0;
			background-color: #5bc0de;
			transform-origin: left bottom;
	    	transition: all .4s ease-in-out;
	    	pointer-events: none;
	    	z-index: -1;
			font-weight: normal;
	    }
	    .control-label::before {
	    	background: rgba(3,36,41,.2);
	    	z-index: -2;
	    	right:10%;
	    }
	    .form-control {
	    	text-indent: 90px;
	    	font-weight: 300;
	    	transition: all .4s ease-in-out;
	    }
	    .form-control:focus, .form-control:active {
	    	text-indent: 0px;
	    }
		.form-control:focus + .control-label {
			transform: rotate(-66deg);
			border-radius: 4px;
		}
		.form-control:focus + .control-label::before {
			transform: rotate(10deg);
			border-radius: 4px;
		}

		.logo {
			color: white;
		}
		.logo .ikon {
			border-radius: 50%;
			border-width: 0px;
			border-color: rgb(88, 183, 240);
			border-style: solid;
			padding: 3px;
			width: 112px;
			height: 112px;
			margin: 0px auto;
		}
		.logo .ikon img {
			width: 80px;
			margin-top: 10px;
			margin-left: 10px;
		}
		.logo .judul.apl {
			font-size: 300%;
			font-weight: 100;
			text-align:center;
			padding: 10px 0px 0px;
		}
		.logo .judul {
			font-size: 120%;
			padding: 10px 0px;
		}
		.hc {
			text-align: center;
			color: white;
		}

		.beta {
		  color: white;
		  font-size: 160%;
		  position: absolute;
		  left: 200px;
		  top: 200px;
		  width: 400px;
		}

		'.$noise.'
		.beta:after {
		  content: attr(data-text);
		  position: absolute;
		  left: 2px;
		  text-shadow: -1px 0 black;
		  top: 0;
		  color: white;
		  overflow: hidden;
		  clip: rect(0, 900px, 0, 0);
		  -webkit-animation: noise-anim 2s infinite linear alternate-reverse;
		  animation: noise-anim 2s infinite linear alternate-reverse;
		}

		'.$noise2.'
		.beta:before {
		  content: attr(data-text);
		  position: absolute;
		  left: -2px;
		  text-shadow: 1px 0 black;
		  top: 0;
		  color: white;
		  overflow: hidden;
		  clip: rect(0, 900px, 0, 0);
		  -webkit-animation: noise-anim-2 3s infinite linear alternate-reverse;
		  animation: noise-anim-2 3s infinite linear alternate-reverse;
		}

		@media (max-width: 768px) {
			.wrap {
				padding-top: 0px!important;
			}
			.beta {
			  color: white;
			  font-size: 160%;
			  position: relative;
			  top: 700px;
			  left: 0;
			  width: 400px;
			}
		}

		.bg-cyan-pucat {
			background-color: #5bc0de;
			color: white;
			border-color: #438EA5;
			border-top-width: 0px;
			border-left-width: 0px;
			border-right-width: 0px;
		}
		.bg-cyan-pucat:hover {
			color:white;
			background-color: #64CFEF;
			border-top-width: 1px;
			border-top-color: #5bc0de;
			border-bottom-width: 2px;
		}
		.bg-cyan-pucat:focus {
			color: white;
		}
	');