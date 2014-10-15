<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use kartik\widgets\ActiveForm;
use kartik\widgets\AlertBlock;
use kartik\widgets\Growl;
use kartik\grid\GridView;
use backend\models\ProgramSubject;
use backend\models\TrainingSchedule;
use backend\models\TrainingScheduleTrainer;

$this->title = 'Update Trainer Attendance';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Training Activities'), 'url' => ['activity2/index']];
$this->params['breadcrumbs'][] = ['label' => 'Training Classes', 'url' => ['activity2/class','id'=>$trainingClass->training_id]];
$this->params['breadcrumbs'][] = ['label' => 'Schedule : Class '.$trainingClass->class, 'url' => ['activity2/attendance','training_class_id'=> $training_class_id]];
$this->params['breadcrumbs'][] = $this->title;
$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

echo AlertBlock::widget([
    'useSessionFlash' => true,
    'type' => AlertBlock::TYPE_ALERT
]);
?>

<div class="training-schedule-trainer-attendance-update">

    <?php

    	$columns = [
    		['class' => 'kartik\grid\SerialColumn'],

    		[
    			'label' => 'Name',
				'vAlign'=>'middle',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function ($model) {
					return $model->trainer->person->name;
				}
			],

    		[
    			'label' => 'NIP',
				'vAlign'=>'middle',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function ($model) {
					return $model->trainer->person->nip;
				}
			],

    		[
    			'label' => 'Organization',
				'vAlign'=>'middle',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function ($model) {
					return $model->trainer->person->organisation;
				}
			],
    	];

    	for ($i = 0; $i < count($idSchedule); $i++) {
    		$currentSchedule = $idSchedule[$i];
    		$modelTrainingSchedule = TrainingSchedule::findOne($idSchedule[$i]);
    		$programSubjectName = ProgramSubject::find()
    			->where([
    				'id' => $modelTrainingSchedule->trainingClassSubject->program_subject_id,
    				'status' => 1
    			])
    			->one()
    			->name;
    		$columns[] = [
    			'header' => $programSubjectName.'<br>'.date('H:i', strtotime($modelTrainingSchedule->start)).'<br>'.$modelTrainingSchedule->hours,
				'vAlign'=>'middle',
				'format' => 'raw',
				'width' => '80px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function($model) use ($currentSchedule, $modelTrainingSchedule) {
					$modelTrainingScheduleTrainer = TrainingScheduleTrainer::find()
						->where([
							'trainer_id' => $model->trainer_id,
							'training_schedule_id' => $currentSchedule
						])
						->one();
					if ($modelTrainingScheduleTrainer) {
						return Html::input('text', 'hours', $modelTrainingScheduleTrainer->hours, [
								'class' => 'form-control',
								'onchange' => new JsExpression('
									var maxVal = '.$modelTrainingSchedule->hours.';
									var currEle = $(this);
									if ( currEle.val() > maxVal) {
										$.growl({
											icon: "fa fa-fw fa-exclamation-circle",
											title: " <strong>Jamlat error!</strong> ",
											message: "Jamlat value should not greater than " + maxVal,
										}, {
											type: "warning",
										});
										currEle.select();
									}
									else {
						    			$.ajax({
											type: "post",
											url: "'.Url::to(['editable']).'",
											data: {
												hours: $(this).val(),
												id: "'.$modelTrainingScheduleTrainer->id.'",
											},
											success: function(data) {
												data = JSON.parse(data);
												if (data.error != "max") {
													$.growl({
														icon: "fa fa-fw fa-check-circle",
														title: " <strong>Saved!</strong> ",
														message: "New value is " + data.hours,
													}, {
														type: "success",
													});
												}
												else {
													$.growl({
														icon: "fa fa-fw fa-exclamation-circle",
														title: " <strong>Jamlat error!</strong> ",
														message: "Jamlat value should not greater than " + data.hours,
													}, {
														type: "warning",
													});
													currEle.select();
												}
											}
										})
									}
						    	')
							]);
					}
					else {
						return '';
					}
				}
			];
    	}

    	echo GridView::widget([
    			'dataProvider' => $dataProvider,
    			'columns' => $columns,
    			'striped' => true,
    			'hover' => true,
    			'responsive' => true,
    			'panel' => [
					'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> Attendance</h3>',
					'before'=>
						Html::a('<i class="fa fa-fw fa-arrow-left"></i> Back', [
								'activity2/attendance',
								'training_class_id' => $training_class_id
							], ['class' => 'btn btn-warning']
						).
						Html::a('<i class="fa fa-fw fa-print"></i> Print Form Attendance', [
								'print',
								'training_schedule_id' => $training_schedule_id
							],
							[
								'class' => 'btn btn-default pull-right',
								'style' => 'margin-right:5px',
								'data-pjax' => '0'
							]),
					'after' => '',
					'showFooter' => false
				],
				'beforeHeader'=>[
			        [
			            'columns'=>[
			                ['content'=>'Student', 'options'=>['colspan'=>4, 'class'=>'text-center warning']], 
			                ['content'=>'Attendance', 'options'=>['colspan'=>count($idSchedule), 'class'=>'text-center warning']], 
			            ],
			            'options'=>['class'=>'skip-export'] // remove this row from export
			        ]
			    ],
    		]);

    ?>

</div>
