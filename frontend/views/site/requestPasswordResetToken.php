<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

$this->title = 'Request password reset';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-request-password-reset" style="width:350px; margin:auto;">
    <div class="panel  panel-default">
		<div class="panel-heading"><h1 class="panel-title"><?= Html::encode($this->title) ?></h1></div>
		<div class="panel-body">			
			<p>Silahkan Menghubungi Call Center BPPK di 021-29054300 atau menghubungi bidang penyelenggara pusdiklat/balai diklat terkait .</p>

			<div class="row">
				<div class="col-lg-12">
						<div class="form-group">
							<?php //Html::submitButton('Send', ['class' => 'btn btn-primary']) ;?>
						</div>
				</div>
			</div>
		</div>
	</div>
</div>
