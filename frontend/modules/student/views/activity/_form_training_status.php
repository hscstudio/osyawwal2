<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
//use backend\models\Person;
//use backend\models\Employee;
use yii\helpers\Url;
use kartik\widgets\SwitchInput;

/* @var $this yii\web\View */
/* @var $model backend\models\Program */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="program-form">
    <?php $form = ActiveForm::begin(); ?>	
	<?= $form->errorSummary($model) ?> <!-- ADDED HERE -->	
    <?= $form->field($model, 'status')->widget(Select2::classname(), [
					'data' => array(1=>'Baru',2=>'Mengulang'),
					'options' => ['placeholder' => 'Choose code ...'],
					'pluginOptions' => [
						'label' =>'Statusku',
						'allowClear' => true
					],
				]);
	?>
	
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
