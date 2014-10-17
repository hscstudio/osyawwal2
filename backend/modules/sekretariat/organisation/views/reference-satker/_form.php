<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\Reference */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="reference-form">

    <?php $form = ActiveForm::begin(); ?>
	<?= $form->errorSummary($model) ?> <!-- ADDED HERE -->

    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>
    
    <?php
	echo $form->field($model, 'value')->widget(Select2::classname(), [
		'data' => ([2=>'Eselon 2',3=>'Eselon 3']),
		'options' => ['placeholder' => 'Choose Eselon ...'],
		'pluginOptions' => [
			'allowClear' => true
		],
	]);
	?>

    <?= $form->field($model, 'sort')->textInput() ?>

    <?= $form->field($model, 'status')->widget(\kartik\widgets\SwitchInput::classname(), [
					'pluginOptions' => [
						'onText' => 'On',
						'offText' => 'Off',
					]
				]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
