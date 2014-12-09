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

/* @var $this yii\web\View */
/* @var $model backend\models\Program */
/* @var $form yii\widgets\ActiveForm */
$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
$this->title = Yii::t('app', 'Generate {modelClass}: ', [
    'modelClass' => 'Form Penilaian',]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Generate Dokumen Umum'), 'url' => ['./evaluation/activity/generate-dokumen','id'=>$model->id]];
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
						'class','id'=>$model->id
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
				echo Html::input('text','student','',['class'=>'form-control','id'=>'count']);
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
					'class'=>'form-control', 
					'multiple' => false,
					'id'=>'trainer',
				],
			]);
			?>
            <?php
				echo Html::beginTag('label',['class'=>'control-label']).'Mata Pelajaran'.Html::endTag('label');
				echo Html::input('text','student','',['class'=>'form-control','id'=>'count']);
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


