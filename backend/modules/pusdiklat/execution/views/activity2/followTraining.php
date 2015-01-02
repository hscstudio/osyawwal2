<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model backend\models\TrainingClass */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="training-class-form">
<div class="panel panel-default">
	<div style="margin:10px">
    <?php $form = ActiveForm::begin([
		'id'=>'form-follow-training',
		'action' => ['follow-training','id'=>$activity->id,'class_id'=>$class->id],
		'enableAjaxValidation' => false,
        'enableClientValidation' => true,
	]); ?>
	<?= $form->errorSummary($model) ?>

	<?php echo $form->field($model, 'place_training')->textInput(['value'=>'-'])->label('Tempat'); ?>
	<?php echo $form->field($model, 'day_training')->textInput(['value'=>'1'])->label('Jumlah hari'); ?>
	<?php echo $form->field($model, 'day_hours_training')->textInput(['value'=>'10'])->label('Jamlat per hari'); ?>
	<hr>
	<?php	
	$satker_id = Yii::$app->user->identity->employee->satker_id;
	$modelEmployeeSigners = \backend\models\Employee::find()
			->where(
				'
				satker_id=:satker_id
				AND
				organisation_id=:organisation_id
				AND
				chairman = 1
				',
				[
					':satker_id'=>$satker_id,
					':organisation_id'=>[
						387,396,397,398
					],
				]
			)
			->all();

	$employeeSigners = [];
	foreach($modelEmployeeSigners as $modelEmployeeSigner){
		$employeeSigners[$modelEmployeeSigner->person_id]=$modelEmployeeSigner->person->name.' - '.$modelEmployeeSigner->organisation->NM_UNIT_ORG;
	}
	echo $form->field($model, 'employee_id')->widget(Select2::classname(), [
		'data' => $employeeSigners,
		'options' => ['placeholder' => 'Choose employee ...'],
		'pluginOptions' => [
		'allowClear' => true
		],
	])->label('Employee'); ?>
	<hr>
    <?= Html::submitButton(
		'<span class="fa fa-fw fa-print"></span> Generate', 
		['class' => 'btn btn-success']) ?>
	
    <?php ActiveForm::end(); ?>
	
	<?php
	$this->registerJs("
		$('#form-follow-training').on('beforeSubmit', function(event, jqXHR, settings) {
                var form = $(this);
                if(form.find('.has-error').length) {
                     return false;
                }
				$('#modal-heart').modal('hide');
        })"); ?>
	</div>
</div>
</div>
