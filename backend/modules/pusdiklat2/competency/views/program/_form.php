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

$disabled = false;

if(!$model->isNewRecord){
	$program_revision = (int) \backend\models\ProgramHistory::getRevision($model->id);
	$countTraining = \backend\models\Training::find()
		->joinWith(['activity','program'])
		->where([
			'program_id'=>$model->id,
			'program_revision'=> $program_revision,
			'activity.status' => [2], // Status Ready
		])
		->count();
	if($countTraining>0) $disabled = true;
}
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

	/* Aku wrap ke tag row, biar konsisten. Yang hours, days, dan test pake wrap, masak yg lain engga :) */
	echo '<div class="row">';
		echo '<div class="col-md-12">';
			echo $form->field($model, 'number')->widget(Select2::classname(), [
				'data' => $data,
				'options' => [
					'placeholder' => 'Pilih kode program ...',
					'disabled'=>$disabled,
				],
				'pluginOptions' => [
					'allowClear' => true
				],
			])->label('Kode');
		echo '</div>';
	echo '</div>';
	?>

	<div class="row">
		<div class="col-md-8">
    		<?= $form->field($model, 'name')->textInput(['maxlength' => 255,'disabled'=>$disabled,])->label('Nama Program'); ?>
    	</div>
		<div class="col-md-4">
			<?php
			$data = ['1'=>'Dasar','2'=>'Lanjutan','3'=>'Menengah','4'=>'Tinggi'];
			echo $form->field($model, 'category')->widget(Select2::classname(), [
				'data' => $data,
				'options' => [
					'placeholder' => 'Tentukan kategori program ...',
				],
				'pluginOptions' => [
					'allowClear' => true,
				],
			])
			->label('Kategori');
			?>
    	</div>
    </div>

	<div class="row">
    	<div class="col-md-12">
			<?= $form->field($model, 'stage')->textInput(['maxlength' => 255])->label('Rumpun') ?>
    	</div>
    </div>
	
	<div class="row">
		<div class="col-md-4">
			<?= $form->field($model, 'hours')->textInput(['maxlength' => 5,'disabled'=>$disabled])->label('JP'); ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'days')->textInput(['disabled'=>$disabled])->label('Hari'); ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'test')->widget(SwitchInput::classname(), [
				'options'=>[
					'disabled'=>$disabled
				],
				'pluginOptions' => [
					'onText' => 'Ya',
					'offText' => 'Tidak',
				]
			])
			->label('Ujian?') ?>
		</div>
	</div>
    
    <div class="row">
    	<div class="col-md-12">
    		<?= $form->field($model, 'note')->textInput(['maxlength' => 255])->label('Catatan'); ?>	
    	</div>
    </div>
	
	<?php if(!$model->isNewRecord){ ?>
		<?php
		$permit = \Yii::$app->user->can('pusdiklat2-competency');
		if($permit){			
			$form->field($model, 'status')->widget(SwitchInput::classname(), [
				'options' => [
					'placeholder' => 'Tentukan status...',
				],
				'pluginOptions' => [
					'onText' => 'Tampil',
					'offText' => 'Sembunyi',
				]
			]);
		}
		?>
		<?php		
		if($countTraining>0){
			echo "<div class='well2'><blockquote>Program ini telah digunakan oleh diklat</blockquote></div>";
		}
		?>
	<?php } ?>
	
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', '<i class="fa fa-fw fa-plus-circle"></i> Buat') : Yii::t('app', '<i class="fa fa-fw fa-save"></i> Perbarui	'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
						
	<?php $this->registerCss('label{display:block !important;}'); ?>
</div>
