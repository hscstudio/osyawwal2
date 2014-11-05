<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model backend\models\Issue */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="issue-form">

    <?php $form = ActiveForm::begin([
		'options'=>['enctype'=>'multipart/form-data']
	]); ?>
    <?= $form->errorSummary($model) ?> <!-- ADDED HERE -->
    <?php /* if(!$model->isNewRecord) echo $form->field($model, 'parent_id')->textInput() */ ?>

    <?= $form->field($model, 'subject')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>
	
	<?php
	echo $form->field($model, 'attachment')->widget(FileInput::classname(), [
		'pluginOptions' => [
			'showUpload' => false,
		]
	])->label(); 
	?>
	
    <?php 
	/* if(!$model->isNewRecord){
		if(\Yii::$app->user->can('BPPK')){
			$data = [
				'verified' => 'verified','critical' => 'critical',
				'bugfix' => 'bugfix','discussion' => 'discussion',
				'enhancement' => 'enhancement'
			];
			echo $form->field($model, 'label')->widget(Select2::classname(), [
				'data' => $data,
				'options' => ['placeholder' => 'Choose label ...'],
				'pluginOptions' => [
				'allowClear' => true
				],
			]); 
		}
	} */
	?>
	
	<?php
	/* if(!$model->isNewRecord){
		if(\Yii::$app->user->can('BPPK') or \Yii::$app->user->id==$model->created_by){
			$data = ['1' => 'Open','0' => 'Close'];
			echo $form->field($model, 'status')->widget(Select2::classname(), [
				'data' => $data,
				'options' => ['placeholder' => 'Choose status ...'],
				'pluginOptions' => [
				'allowClear' => true
				],
			]); 
		}
	} */
	?>
	
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div> 