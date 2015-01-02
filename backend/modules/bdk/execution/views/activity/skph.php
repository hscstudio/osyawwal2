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
		'id'=>'form-skph',
		'action' => ['skph','id'=>$activity->id,'class_id'=>$class->id],
		'enableAjaxValidation' => false,
        'enableClientValidation' => true,
	]); ?>
	<?= $form->errorSummary($model) ?>
	
    <?php	
	$data = \yii\helpers\ArrayHelper::map(
				\backend\models\TrainingSubjectTrainerRecommendation::find()
					->select(['training_subject_trainer_recommendation.id', 'trainer_id', 'trainer_name'=>'person.name'])
					->joinWith('trainer')
					->joinWith('trainer.person')
					->where(['training_id'=>$activity->id])
					->asArray()
					->all(), 
					'trainer_id', 'trainer_name'
			);
	echo $form->field($model, 'trainer_id')->widget(Select2::classname(), [
		'data' => $data,
		'options' => ['placeholder' => 'Choose trainer ...'],
		'pluginOptions' => [
		'allowClear' => true
		],
	])->label('Trainer'); ?>
	
	<?php echo $form->field($model, 'trainer_job')->textInput(['value'=>'-'])->label('Pekerjaan'); ?>
	<?php echo $form->field($model, 'trainer_address')->textInput(['value'=>'-'])->label('Alamat'); ?>
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
						343,345,346,347
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
	<?php echo $form->field($model, 'employee_job')->textInput(['value'=>'-'])->label('Pekerjaan'); ?>
	<?php echo $form->field($model, 'employee_address')->textInput(['value'=>'-'])->label('Alamat'); ?>
	<hr>
    <?= Html::submitButton(
		'<span class="fa fa-fw fa-print"></span> Generate', 
		['class' => 'btn btn-success']) ?>
	
    <?php ActiveForm::end(); ?>
	
	<?php
	$this->registerJs("
		$('#form-skph').on('beforeSubmit', function(event, jqXHR, settings) {
                var form = $(this);
                if(form.find('.has-error').length) {
                     return false;
                }
				$('#modal-heart').modal('hide');
        })"); ?>
	</div>
</div>
</div>
