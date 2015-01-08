<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use kartik\datecontrol\DateControl;
use kartik\widgets\SwitchInput;
use backend\models\Reference;
use backend\models\Program;
use kartik\widgets\FileInput;
use yii\helpers\Url;
use kartik\checkbox\CheckboxX;

/* @var $this yii\web\View */
/* @var $model backend\models\Activity */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="activity-form">

    <?php $form = ActiveForm::begin(); ?>
	<?= $form->errorSummary($model) ?> <!-- ADDED HERE -->
	
	<ul class="nav nav-tabs" role="tablist" id="tab_wizard">
		<li class="active"><a href="#activity" role="tab" data-toggle="tab">Data Umum <span class='label label-info'>1</span></a></li>
		<li class=""><a href="#meeting" role="tab" data-toggle="tab">Data Rapat <span class='label label-warning'>2</span></a></li>
	</ul>
	<div class="tab-content" style="border: 1px solid #ddd; border-top-color: transparent; padding:10px; background-color: #fff;">
		<div class="tab-pane fade-in active" id="activity">

			<?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

			<?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>

			<div class="row clearfix">
				<div class="col-md-4">
				<?= $form->field($model, 'start')->widget(DateControl::classname(), [
						'type' => DateControl::FORMAT_DATE,
					]); ?>
				</div>
				<div class="col-md-4">
				<?= $form->field($model, 'end')->widget(DateControl::classname(), [
						'type' => DateControl::FORMAT_DATE,
					]); ?>
				</div>
				<div class="col-md-4">
					<?= $form->field($model, 'hostel')->widget(SwitchInput::classname(), [
						'pluginOptions' => [
							'onText' => 'Ya',
							'offText' => 'Tidak',
						]
					])->label('Diasramakan?') ?>
				</div>
			</div>

			<?php 
			$model->location=explode('|',$model->location); 
			if(empty($model->location[0]))
				$model->location = [(int)Yii::$app->user->identity->employee->satker_id];
			?>
			<div class="row clearfix">
				<div class="col-md-3">
				<?php
				$data = ArrayHelper::map(
					\backend\models\Reference::find()
						->select(['id','name'])
						->where(['type'=>'satker'])
						->all(), 
						'id', 'name'
				);
				echo $form->field($model, 'location[0]')->widget(Select2::classname(), [
					'data' => $data,
					'options' => ['placeholder' => 'Pilih kode ...'],
					'pluginOptions' => [
						'allowClear' => true
					],
				]);
				?>
				</div>
				<div class="col-md-9">		
				<?= $form->field($model, 'location[1]')->textInput(['maxlength' => 250])->label('Catatan Terkait Lokasi'); ?>
				</div>
			</div>

			<?php
			$permit = \Yii::$app->user->can('pusdiklat-planning-2');
			if(!$model->isNewRecord and $permit){ ?>
			<div class='row'>
				<div class='col-md-6'>				
				<?php
				$data = [
						'0'=>'DRAFT',
						'1'=>'PUBLISH',
				];
				echo $form->field($model, 'status')->widget(Select2::classname(), [
					'data' => $data,
					'options' => ['placeholder' => 'Pilih Status ...'],
					'pluginOptions' => [
						'allowClear' => true,
					],
				]); ?>
				</div>
				<div class='col-md-6'>

				</div>	
			</div>
			<?php } ?>
			
			<a class="btn btn-default tendangKePojok" onclick="$('#tab_wizard a[href=#meeting]').tab('show')">
				Berikutnya 
				<i class="fa fa-fw fa-arrow-circle-o-right"></i>
			</a>
		</div>
		<div class="tab-pane fade" id="meeting">	
			
			<div class="row clearfix">
				<div class="col-md-3">
				<?php echo $form->field($meeting, 'attendance_count_plan')->textInput() ?>
				</div>
				<div class="col-md-3">
				<?php
				/* $data = ArrayHelper::map(Organisation::find()
						->select(['ID', 'NM_UNIT_ORG'])
						->where(['JNS_KANTOR'=>13])
						->asArray()
						->all()
						, 'ID', 'NM_UNIT_ORG');

				echo $form->field($meeting, 'organisation_id')->widget(Select2::classname(), [
					'data' => $data,
					'options' => ['placeholder' => 'Choose organisation ...'],
					'pluginOptions' => [
					'allowClear' => true
					],
				]);  */?>
				</div>
			</div>			
			
			
			
			<a class="btn btn-default tendangKePojok" onclick="$('#tab_wizard a[href=#activity]').tab('show')">
				Sebelumnya 
				<i class="fa fa-fw fa-arrow-circle-o-left"></i>
			</a>
			
			<div class="clearfix"><hr></div>  
			
			<div class="form-group">
				<?= Html::submitButton('<i class="fa fa-fw fa-save"></i> '. ($model->isNewRecord ? Yii::t('app', 'SYSTEM_BUTTON_CREATE') : Yii::t('app', 'SYSTEM_BUTTON_UPDATE')), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
			</div>
		</div>
	</div>
	 

    <?php ActiveForm::end(); ?>
	<?php $this->registerCss('label{display:block !important;}'); ?>

</div>

<?php
	$this->registerCss('
		.tendangKePojok {
			position: absolute;
			top: 30px;
			right: 30px;
		}
	');
?>
