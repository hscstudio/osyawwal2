<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use kartik\datecontrol\DateControl;
use kartik\widgets\SwitchInput;
use backend\models\Person;
use backend\models\Employee;
/* @var $this yii\web\View */
/* @var $model backend\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>
	
	<?= $form->errorSummary($model) ?> <!-- ADDED HERE -->
	
	<?= $form->field($model, 'username')->textInput(['maxlength' => 255]) ?>

	<?= $form->field($model, 'new_password')->passwordInput(['maxlength' => 255, 'autocomplete' => 'off']) ?>

	<?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>
	
	<?php
	$roles = backend\models\AuthAssignment::find()
		->where([
			'user_id' => $model->id
		])
		->column();
	$model->role=$roles;
	$data = ArrayHelper::map(
		backend\models\Role::find()
			->select([
				'*','role.id as ROLE_ID','name',
			])
			->joinWith('organisation')
			->where([
				'JNS_KANTOR' => 3
			])
			->asArray()
			->all(), 'name', 'name');
			
	echo $form->field($model, 'role')->widget(Select2::classname(), [
		'data' => $data,
		'options' => [
			'placeholder' => 'Choose role ...',
			'multiple' => true,
		],
		'pluginOptions' => [
			'allowClear' => true,
		],
	]); ?>
	
	<?= $form->field($model, 'status')->widget(SwitchInput::classname(), [
		'pluginOptions' => [
			'onText' => 'Active',
			'offText' => 'Blocked',
		]
	]) ?> 
	
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

	<?php $this->registerCss('label{display:block !important;}'); ?>
	
</div>
