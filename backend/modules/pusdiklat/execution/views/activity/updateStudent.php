<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\SwitchInput;
use kartik\widgets\Select2;
/* @var $this yii\web\View */
/* @var $model backend\models\TrainingStudent */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = Yii::t('app', 'Perbarui {modelClass}: ', [
    'modelClass' => 'Training Student',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'BPPK_TEXT_STUDENT'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Perbarui');
?>
<div class="training-student-update panel panel-default">
	
    <div class="panel-heading">		
		<div class="pull-right">
        <?=
 Html::a('<i class="fa fa-fw fa-arrow-left"></i> Back', ['index'], ['class' => 'btn btn-xs btn-primary']) ?>
		</div>
		<h1 class="panel-title"><?= Html::encode($this->title) ?></h1>
	</div>
	<div class="panel-body">
		<?php $form = ActiveForm::begin(); ?>
		<?= $form->errorSummary($training_student) ?> <!-- ADDED HERE -->
		<div class="row clearfix">
			<div class="col-md-4">
			<?= $form->field($training_student, 'status')->widget(Select2::classname(), [
					'data' => [0=>'Batal',1=>'Baru (Aktif)',2=>'Mengulang (Aktif)',3=>'Mengundurkan Diri'],
					'options' => ['placeholder' => 'Pilih Status ...'],
					'pluginOptions' => [
					'allowClear' => true
					],
				])->label('Status') ?>
			</div>
			<div class="col-md-8">
			<?= $form->field($training_student, 'note')->textInput(['maxlength' => 255])->label('Catatan'); ?>
			</div>
		</div>
		<div class="form-group">
			<?= Html::submitButton($training_student->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'SYSTEM_BUTTON_UPDATE'), ['class' => $training_student->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		</div>

		<?php ActiveForm::end(); ?>
		<?php $this->registerCss('label{display:block !important;}'); ?>
	</div>
</div>
