<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use backend\models\Person;
use backend\models\Employee;
use backend\models\TrainingScheduleTrainer;
use backend\models\TrainingSchedule;
use backend\models\TrainingClass;
use yii\helpers\Url;
use kartik\widgets\DatePicker;
use kartik\widgets\DepDrop;

/* @var $this yii\web\View */
/* @var $model backend\models\Program */
/* @var $form yii\widgets\ActiveForm */
$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
$this->title = Yii::t('app', 'Generate {modelClass}: ', [
    'modelClass' => 'Form Penilaian',]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Generate Dokumen Umum'), 'url' => ['activity/generate-dokumen','id'=>$model->id]];
$this->params['breadcrumbs'][] = ['label' => 'Form Penilaian'];
?>
<div class="activity-update panel panel-default">
	
    <div class="panel-heading">		
		<div class="pull-right">
        <?= (Yii::$app->request->isAjax)?'':Html::a('<i class="fa fa-fw fa-arrow-left"></i> Back', ['index'], ['class' => 'btn btn-xs btn-primary']) ?>
		</div>
		<h1 class="panel-title"><?= Html::encode($this->title) ?></h1>
	</div>
	<div class="panel-body">
		<div class="letter-assignment-form">
			<?php
				$form = ActiveForm::begin([
					'options'=>[
						'id'=>'myform',
						'onsubmit'=>'',
					],
					'action'=>[
						'form-student-evaluation-excel','id'=>$model->id
					], 
				]);
			?>
            
            <?= $form->errorSummary($model) ?> <!-- ADDED HERE -->
            
            <?php
			$data_form = [
						'0'=>'Nilai Aktivitas',
						'1'=>'Nilai Ujian',
				];
				echo '<label class="control-label">Jenis Form</label>';
				echo Select2::widget([
					'name' => 'jenis_form', 
					'data' => $data_form,
					'options' => [
						'placeholder' => 'Select Jenis Form ...', 
						'class'=>'form-control', 
						'multiple' => false,
						'id'=>'jenis_form',
					],
				]);
			?>	
            
            <?php
			$data = ArrayHelper::map(TrainingClass::find()
				->where(['training_id'=>$model->id])		
				->asArray()
				->all()
				, 'id', 'class');
			echo '<label class="control-label">Kelas</label>';
			echo Select2::widget([
				'name' => 'class', 
				'data' => $data,
				'options' => [
					'placeholder' => 'Select Kelas ...', 
					'class'=>'form-control', 
					'multiple' => false,
					'id'=>'class',
				],
			]);
			?>
            
            <?php
				    echo '<label class="control-label">Tanggal</label>';
					echo DatePicker::widget([
						'name' => 'tanggal',
						'type' => DatePicker::TYPE_COMPONENT_PREPEND,
						'pluginOptions' => [
						'autoclose'=>true,
						'format' => 'dd-mm-yyyy'
						]
					]);
			?>	
            <?php
				echo Html::beginTag('label',['class'=>'control-label']).'Waktu'.Html::endTag('label');
				echo Html::input('text','waktu','08:00-12:00 WIB',['class'=>'form-control','id'=>'waktu']);
			?>	
            <?php
			$data = ArrayHelper::map(Person::find()
				->select(['id', 'name'])
				->where([
					'id'=>TrainingScheduleTrainer::find()
						->select('trainer_id')
						->where([
							'training_schedule_id'=>TrainingSchedule::find()
													->select('id')
													->where([
															 'training_class_id'=>TrainingClass::find()
															 					->select('id')
																				->where([
																						 'training_id'=>$model->id
																						 ])
															 ]), // CEK ID 393 IN TABLE ORGANISATION IS SUBBIDANG PROGRAM
						])
						//->currentSatker()
						->column(),
				])		
				->active()
				->asArray()
				->all()
				, 'id', 'name');
			echo '<label class="control-label">Pengajar</label>';
			echo Select2::widget([
				'name' => 'trainer', 
				'data' => $data,
				'options' => [
					'placeholder' => 'Select Pengajar...', 
					'onchange'=>'
						$.post( "'.Url::to(['mapelku']).'?id="+$(this).val(), 
							function( data ) {
							  $( "input#mapel" ).val( data + " ");
							  $( "input#mapel" ).focus();
							});
					',
					'class'=>'form-control', 
					'multiple' => false,
					'id'=>'trainer',
				],
			]);
			?>
            <?php
				echo Html::beginTag('label',['class'=>'control-label']).'Mata Pelajaran'.Html::endTag('label');
				echo Html::input('text','mapel','',['class'=>'form-control','id'=>'mapel']);
			?>	
            
            <div class="clearfix"><hr></div> 
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Generate') : Yii::t('app', 'Generate'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        
            <?php ActiveForm::end(); ?>
            
            <?php $this->registerCss('label{display:block !important;}'); ?>
        </div>
	</div>
</div>


