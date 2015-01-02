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
		'id'=>'form-registration',
		'action' => ['registration','id'=>$activity->id,'class_id'=>$class->id],
		'enableAjaxValidation' => false,
        'enableClientValidation' => true,
	]); ?>
	
	<hr>
    <?= Html::submitButton(
		'<span class="fa fa-fw fa-print"></span> Generate', 
		['class' => 'btn btn-success']) ?>
	
    <?php ActiveForm::end(); ?>
	</div>
</div>
</div>
