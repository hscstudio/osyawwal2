<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Dropdown;
use yii\widgets\Pjax;

use kartik\grid\GridView;
use kartik\widgets\Select2;
use kartik\widgets\AlertBlock;
use kartik\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use kartik\widgets\TimePicker;
use kartik\datecontrol\DateControl;
use kartik\checkbox\CheckboxX;

use backend\models\ActivityRoom;
use backend\models\ProgramSubjectHistory;
use backend\models\ProgramSubject;
use backend\models\TrainingScheduleTrainer;
use backend\models\TrainingSchedule;

use backend\modules\pusdiklat\execution\models\TrainingScheduleExtSearch;

$this->title = 'Schedule : Class '.$trainingClass->class;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Training Activities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Training Classes', 'url' => ['class','id'=>$trainingClass->training_id]];
$this->params['breadcrumbs'][] = $this->title;

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
?>
<div class="schedule-index">
	
	<?php 
		Pjax::begin([
			'id'=>'pjax-gridview-schedule',
		]); 
	?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
			[
				'label' => 'Datetime',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'200px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value'=>function($model){
					$modelTrainingSchedule = TrainingSchedule::find()
						->where([
							'training_class_id' => $model->training_class_id,
							'date(start)' => date('Y-m-d', strtotime($model->start))
						])
						->andWhere('training_class_subject_id > 0')
						->all();

					$out = '';

					foreach ($modelTrainingSchedule as $row) {
						$start = date('d-M-Y H:i',strtotime($row->start));
						$finish = date('d-M-Y H:i',strtotime($row->end));
						$startDate = date('d-M-Y',strtotime($row->start));
						$finishDate = date('d-M-Y',strtotime($row->end));
						$start = date('H:i',strtotime($row->start));
						$end = date('H:i',strtotime($row->end));
						
						if($start==$finish){
							$out .= $start;
						}
						else if($startDate==$finishDate){
							$out .= '<span class="label label-info">'.$startDate .'</span> <span class="label label-default">' .$start. ' s.d ' . $end.'</span><br>';
						}
						else{
							$out .= '<span class="label label-info">'.$start .
									'</span> <span class="label label-default"> s.d </span>&nbsp;<span class="label label-info">'.$finish.'</span><br>';
						}
					}

					return $out;
					
				}
			],
			[
				'label' => 'Activity',
				'vAlign'=>'middle',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value'=>function($model){
					$modelTrainingSchedule = TrainingSchedule::find()
						->where([
							'training_class_id' => $model->training_class_id,
							'date(start)' => date('Y-m-d', strtotime($model->start))
						])
						->andWhere('training_class_subject_id > 0')
						->all();

					$out = '';

					foreach ($modelTrainingSchedule as $row) {
						
						
						$programSubject= \backend\models\ProgramSubjectHistory::find()
							->where([
								'id' => $row->trainingClassSubject->program_subject_id,
								'program_id' => $row->trainingClass->training->program_id,
								'program_revision' => $row->trainingClass->training->program_revision,
								'status'=>1
							])
							->one();
						if(null!==$programSubject){
							$out .= Html::tag('span',$programSubject->reference->name,[
								'class'=>'label label-default','data-toggle'=>'tooltip','title'=>''
							]);
							$out .= ' '.$programSubject->name.'<br>';
						}						
					}

					return $out;

				}
			],
			[
				'label' => 'Hours',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'50px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value'=>function($model){
					$modelTrainingSchedule = TrainingSchedule::find()
						->where([
							'training_class_id' => $model->training_class_id,
							'date(start)' => date('Y-m-d', strtotime($model->start))
						])
						->andWhere('training_class_subject_id > 0')
						->all();

					$out = '';

					foreach ($modelTrainingSchedule as $row) {
						$out .= $row->hours.'<br>';
					}

					return $out;
					
				}
			],

			[
				'format' => 'raw',
				'label' => 'Action',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'80px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function ($model) {


					$modelTrainingSchedule = TrainingSchedule::find()
						->where([
							'training_class_id' => $model->training_class_id,
							'date(start)' => date('Y-m-d', strtotime($model->start))
						])
						->andWhere('training_class_subject_id > 0')
						->all();

					$out = '';

					$idTrainingClassSubject = [];
					$idSchedule = [];

					foreach ($modelTrainingSchedule as $row) {

						$idTrainingClassSubject[] = $row->training_class_subject_id;
						$idSchedule[] = $row->id;
					}

					$idTrainingClassSubject = implode('_', $idTrainingClassSubject);
					$idSchedule = implode('_', $idSchedule);

					$out .= '<div class="btn-group" style="margin-bottom:3px;">';
					$out .= Html::a('<i class="fa fa-fw fa-mortar-board"></i>', Url::to([
							'training-schedule-trainer-attendance/update', 
							'idSubjects' => $idTrainingClassSubject,
							'training_schedule_id' => $idSchedule,
						]), ['class' => 'btn btn-default btn-xs']
					).' '.
					Html::a('<i class="fa fa-fw fa-child"></i>', Url::to([
							'training-class-student-attendance/update', 
							'idSubjects' => $idTrainingClassSubject,
							'training_schedule_id' => $idSchedule,
						]), ['class' => 'btn btn-default btn-xs']
					);
					$out .= '</div>';

					return $out;
				}
			],
			
        ],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> Schedule</h3>',
			'before'=>
				Html::a('<i class="fa fa-fw fa-arrow-left"></i> Back To Training Class', [
					'class',
					'id'=>$trainingClass->training_id
				], ['class' => 'btn btn-warning'])
				,
			'after'=>Html::a('<i class="fa fa-fw fa-repeat"></i> Reset Grid', ['schedule','training_class_id'=>$trainingClass->id], ['class' => 'btn btn-info']),
			'showFooter'=>false
		],
		'responsive'=>true,
		'hover'=>true,
    ]); ?>

	<?php \yii\widgets\Pjax::end(); ?>
	
	<?php 
	
	$this->registerJs('
		$("#booking-schedule").slideToggle("slow");
		$("#trainingscheduleextsearch-activity").prop("disabled",true);
		$("#trainingscheduleextsearch-pic").prop("disabled",true);
		$("#trainingscheduleextsearch-minutes").prop("disabled",true);
		$("#trainingscheduleextsearch-hours").prop("disabled",false);
	'); 			
	?>
	<?= \hscstudio\heart\widgets\Modal::widget(['modalSize'=>'','registerAsset'=>false]) ?>
</div>
