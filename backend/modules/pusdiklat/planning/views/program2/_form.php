<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use kartik\widgets\SwitchInput;
use backend\models\Reference;

/* @var $this yii\web\View */
/* @var $model backend\models\Program */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="program-form">

    <?php $form = ActiveForm::begin(); ?>
	<?= $form->errorSummary($model) ?> <!-- ADDED HERE -->
    <?php //$form->field($model, 'number')->textInput(['maxlength' => 15]) ?>
	
	<?php
	$data = ArrayHelper::map(
		\backend\models\Reference::find()
			->select(['id','value', 'CONCAT(name," => ",value) as name_value'])
			->where(['type'=>'program_code'])
			->orderBy(['sort'=> SORT_ASC])			
			->asArray()
			->all(), 
			'value', 'name_value'
	);
	
	echo $form->field($model, 'number')->widget(Select2::classname(), [
		'data' => $data,
		'options' => ['placeholder' => 'Choose code ...'],
		'pluginOptions' => [
			'allowClear' => true
		],
	]);
	?>

	
	
    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>
    
    <?= $form->field($model, 'note')->textInput(['maxlength' => 255]) ?>	
	
	<div class="row">
		<div class="col-md-3">
			<?= $form->field($model, 'hours')->textInput(['maxlength' => 5]) ?>
		</div>
		<div class="col-md-3">
			<?= $form->field($model, 'days')->textInput() ?>
		</div>
		<div class="col-md-3">
			<?= $form->field($model, 'test')->widget(SwitchInput::classname(), [
				'pluginOptions' => [
					'onText' => 'On',
					'offText' => 'Off',
				]
			]) ?>
		</div>
	</div>

    <?php		
	/* $data = ArrayHelper::map(Reference::find()
			->select(['id', 'name'])
			->where(['type'=>'stage'])
			->asArray()
			->all()
			, 'name', 'name');
	
	$model->stage = explode(',',$model->stage); 
	echo $form->field($model, 'stage')->widget(Select2::classname(), [
		'data' => $data,
		'options' => [
			'placeholder' => 'Choose stage ...',
			'multiple' => true,
		],
		'pluginOptions' => [
			'allowClear' => true,
		],
	]);
	*/
	?>
		
	<?= $form->field($model, 'stage')->textInput(['maxlength' => 255]) ?>
	
	<?php
	$data = ['1'=>'Dasar','2'=>'Lanjutan','3'=>'Menengah','4'=>'Tinggi'];
	echo $form->field($model, 'category')->widget(Select2::classname(), [
		'data' => $data,
		'options' => [
			'placeholder' => 'Choose category ...',
		],
		'pluginOptions' => [
			'allowClear' => true,
		],
	]);
	?>
	
    <div class="form-group">
        <?php
		$countTrainingSubject = \backend\models\TrainingClassSubject::find()
			->where([
				'program_subject_id'=>\backend\models\ProgramSubjectHistory::find()
					->select('id')
					->where([
						'program_id'=>$model->id,
					])
					->column(),
				'training_class_id'=>\backend\models\TrainingClass::find()
					->select('id')
					->where([
						'training_id'=>\backend\models\Training::find()
							->select('activity_id')
							->where([
								'program_id'=>$model->id,
								'program_revision'=>\backend\models\ProgramHistory::getRevision($model->id)
							])
							->column(),
					])
					->column(),
			])
			->count();
		if($countTrainingSubject==0){
			echo Html::submitButton('<i class="fa fa-fw fa-save"></i> '.Yii::t('app', 'Update'), ['class' => 'btn btn-primary']);
		}
		?>
		<?php if (!$model->isNewRecord){ ?>
		<?= Html::submitButton('<i class="fa fa-fw fa-save"></i> '. Yii::t('app', 'Update as Revision'), ['class' => 'btn btn-warning', 'name' => 'create_revision',]) ?>
		<?php } ?>
	</div>
	<div class="well">
		<?php
		$countTraining = \backend\models\Training::find()
			->where([
				'program_id'=>$model->id,
			])
			->count();
		if($countTraining>0){
			echo "<blockquote>Program ini telah digunakan oleh diklat</blockquote>";
		}
		?>
		<blockquote>Update as Revision artinya jika Anda menginginkan agar data lama tetap tersimpan dalam history. Fungsi ini sebaiknya Anda gunakan apabila perubahan yang Anda lakukan memang sangat mendasar atau signifikan. Adapun perubahan kecil seperti salah penulisan atau ejaan, maka cukup gunakan fungsi Update saja.</blockquote>
	</div>

    <?php ActiveForm::end(); ?>
	
	<?php $this->registerCss('label{display:block !important;}'); ?>
</div>
