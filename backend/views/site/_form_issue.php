<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;
/* @var $this yii\web\View */
/* @var $model backend\models\Issue */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="issue-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->errorSummary($model) ?> <!-- ADDED HERE -->
    <?php if(!$model->isNewRecord) echo $form->field($model, 'parent_id')->textInput() ?>

    <?= $form->field($model, 'subject')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>
	
	<?php
	echo $form->field($model, 'attachment')->widget(FileInput::classname(), [
		'pluginOptions' => [
			'showUpload' => false,
		]
	])->label(); 
	?>
	
    <?php if(!$model->isNewRecord) echo $form->field($model, 'label')->textInput(['maxlength' => 255]) ?>

    <?php if(!$model->isNewRecord) echo $form->field($model, 'status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div> 