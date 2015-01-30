<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\widgets\SwitchInput;
/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>
	
	<?= $form->errorSummary($model) ?> <!-- ADDED HERE -->
	
    <?php
		echo Html::beginTag('label',['class'=>'control-label']).Yii::t('app', 'BPPK_TEXT_STUDENT').Html::endTag('label');
		echo Html::input('text','person_id',$model->trainingStudent->student->person->name,['class'=>'form-control','disabled'=>true,'id'=>'person_id']);
	?>
    <?php
		echo Html::beginTag('label',['class'=>'control-label']).'NIP'.Html::endTag('label');
		echo Html::input('text','person_id',$model->trainingStudent->student->person->nip,['class'=>'form-control','disabled'=>true,'id'=>'person_id']);
	?>
     <?php
		echo Html::beginTag('label',['class'=>'control-label']).'Training Number'.Html::endTag('label');
		echo Html::input('text','number_training',$model->training->number,['class'=>'form-control','width'=>20,'disabled'=>true,'id'=>'number_training']);
	?>
	<?php
		echo Html::beginTag('label',['class'=>'control-label']).'NPP'.Html::endTag('label');
		echo Html::input('text','npp',$model->number,['class'=>'form-control','id'=>'npp']);
	?>
	<div class="clearfix"><hr></div> 
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'SYSTEM_BUTTON_CREATE') : Yii::t('app', 'SYSTEM_BUTTON_UPDATE'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

	<?php $this->registerCss('label{display:block !important;}'); ?>
	
</div>
