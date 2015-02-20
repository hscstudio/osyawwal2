<?php
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\ActiveForm; // Pake activeform kartik, yg yii tak hapus, biar ada addon nya

$this->title = 'Request password reset';
?>
<div class="site-request-password-reset">
	<div class="beta" data-text="Beta Version 0.7.33">Beta Version 0.7.33</div> 
	<div class="logo">
		<div class="ikon"><img src="<?php echo Yii::$app->homeUrl.'/logo_simbppk_pelangi.png'; ?>"></div>
		<div class="judul apl">SIM BPPK</div>
	</div>
    <div class="panel  panel-default">
		<div class="panel-body">			
			<p>Ketikkan alamat email Anda. Segera kami akan memberikan tautan untuk mereset kata sandi Anda</p>

			<div class="row">
				<div class="col-lg-12">
					<?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>
						<?= $form->field($model, 'email', [
						  'template' => "{input}\n{label}\n{hint}\n{error}"
						])->textInput(array('placeholder' => 'Masukkan email Anda', 'class' => 'form-control input-lg'))->label('<i class="fa fa-fw fa-envelope"></i>Email'); // modipikasi ?>
						
						<div class="form-group">
							<?= Html::submitButton('<i class="fa fa-fw fa-send-o"></i>Kirim', ['class' => 'btn btn-block btn-lg bg-cyan-pucat']) ?>
						</div>
					<?php ActiveForm::end(); ?>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12">
					<?php echo Html::a('<i class="fa fa-fw fa-sign-in"></i>Kembali ke halaman login', ['site/masuk'], [
						'class' => 'pull-right'
					]);
					?>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="hc">
	<p>Hakcipta &copy; Badan Pendidikan dan Pelatihan Keuangan</p>
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
		.site-request-password-reset {
			margin: 0px auto;
			width: 400px;
			margin-top: -51px;
		}
		.site-request-password-reset .panel {
			box-shadow:none;
			border-width:0px;
			padding: 10px;
			background-color: rgb(238, 239, 241);
		}
		.site-request-password-reset input, .site-request-password-reset input:focus, .site-request-password-reset input:active {
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
			border-radius: 120px;
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
			.logo, .beta {
				display: none;
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