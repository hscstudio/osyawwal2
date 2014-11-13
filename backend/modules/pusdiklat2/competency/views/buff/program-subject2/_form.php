<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\models\ProgramSubject */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="program-subject-form">
<div class="panel panel-default">
	<div class="panel-heading">
		<div class="pull-right">
		<?= Html::a('<i class="fa fa-arrow-left"></i> BACK',['index','tb_program_id'=>$tb_program_id],
						['class'=>'btn btn-xs btn-primary',
						 'title'=>'Back to Index',
						]) ?>
		</div>
		<i class="fa fa-fw fa-globe"></i> 
		ProgramSubject	</div>
	<div style="margin:10px">
    <?php $form = ActiveForm::begin([
		'type' => ActiveForm::TYPE_HORIZONTAL,
		'options'=>['enctype'=>'multipart/form-data']
	]); ?>
	<?= $form->errorSummary($model) ?>
	
	<div class="row">
	<div class="col-md-6">
	<?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'hours')->textInput(['maxlength' => 5]) ?>

	<?php 
	if($model->isNewRecord) $sort = 0; else $sort=$model->sort;
	?>
    <?= $form->field($model, 'sort')->textInput(['maxlength' => 3, 'value'=>$sort]) ?>
	</div>
	<div class="col-md-6">
	<?php
	$data = ArrayHelper::map(\backend\models\SubjectType::find()->select(['id','name'])->orderBy('id')->asArray()->all(), 'id', 'name');
	echo $form->field($model, 'ref_subject_type_id')->widget(Select2::classname(), [
		'data' => $data,
		'options' => ['placeholder' => 'Choose Type ...'],
		'pluginOptions' => [
		'allowClear' => true
		],
	]);
	?>
	
    <?= $form->field($model, 'test')->widget(\kartik\widgets\SwitchInput::classname(), [
					'pluginOptions' => [
						'onText' => 'On',
						'offText' => 'Off',
					]
				]) ?>

    <?= $form->field($model, 'status')->widget(\kartik\widgets\SwitchInput::classname(), [
					'pluginOptions' => [
						'onText' => 'On',
						'offText' => 'Off',
					]
				]) ?>

    <div class="form-group">
		<label class="col-md-2 control-label"></label>
		<div class="col-md-10">
        <?= Html::submitButton(
			$model->isNewRecord ? '<span class="fa fa-fw fa-save"></span> '.'Create' : '<span class="fa fa-fw fa-save"></span> '.'Update', 
			['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		</div>
	</div>
	
    <?php ActiveForm::end(); ?>
	</div>
</div>
</div>
