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
	
	<?= $form->field($model, 'username')->textInput(['maxlength' => 255]) ?>

	<?php
				echo Html::beginTag('label',['class'=>'control-label']).'New Password'.Html::endTag('label');
				echo Html::input('text','password_hash','',['class'=>'form-control','id'=>'password_hash']);
				?>
	
	<?= $form->field($model, 'status')->widget(SwitchInput::classname(), [
		'pluginOptions' => [
			'onText' => 'On',
			'offText' => 'Off',
		]
	]) ?> 
	
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

	<?php $this->registerCss('label{display:block !important;}'); ?>
	
</div>
