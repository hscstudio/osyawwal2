<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Inflector;
use yii\web\JsExpression;
use kartik\widgets\ActiveForm;
use kartik\widgets\AlertBlock;
use kartik\widgets\Growl;
use kartik\grid\GridView;
use backend\models\ProgramSubject;
use backend\models\ProgramSubjectHistory;
use backend\models\TrainingSchedule;
use backend\models\TrainingScheduleTrainer;

$this->title = 'Pengajar';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'BPPK_TEXT_TRAINING_ACTIVITIES'), 'url' => ['activity2/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'BPPK_TEXT_CLASS').' '.Inflector::camel2words($trainingClass->training->activity->name), 'url' => ['activity2/class','id'=>$trainingClass->training_id]];
$this->params['breadcrumbs'][] = ['label' => 'Input Kehadiran Kelas '.$trainingClass->class, 'url' => ['activity2/attendance','training_class_id'=> $training_class_id]];
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
    			'label' => Yii::t('app', 'BPPK_TEXT_NAME'),
				'vAlign'=>'middle',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function ($model) {
					return $model->trainer->person->name;
				}
			],

    		[
    			'label' => Yii::t('app', 'BPPK_TEXT_NIP'),
				'vAlign'=>'middle',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function ($model) {
					return $model->trainer->person->nip;
				}
			],

    		[
    			'label' => Yii::t('app', 'BPPK_TEXT_ORGANISATION'),
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
    		$programSubjectName = ProgramSubjectHistory::find()
    			->where([
    				'id' => $modelTrainingSchedule->trainingClassSubject->program_subject_id,
					'program_id' => $modelTrainingSchedule->trainingClassSubject->trainingClass->training->program_id,
					'program_revision' => $modelTrainingSchedule->trainingClassSubject->trainingClass->training->program_revision,
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
											message: "Jamlat tidak boleh lebih dari " + maxVal,
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
														title: " <strong>Berhasil disimpan!</strong> ",
														message: "Nilai yang disimpan adalah " + data.hours,
													}, {
														type: "success",
													});
												}
												else {
													$.growl({
														icon: "fa fa-fw fa-exclamation-circle",
														title: " <strong>Jamlat error!</strong> ",
														message: "Jamlat tidak boleh lebih dari " + data.hours,
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
					'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> Absen Pengajar</h3>',
					'before'=>
						Html::a('<i class="fa fa-fw fa-arrow-left"></i> '.Yii::t('app', 'SYSTEM_BUTTON_BACK'), [
								'activity2/attendance',
								'training_class_id' => $training_class_id
							], ['class' => 'btn btn-warning']
						).
						Html::a('<i class="fa fa-fw fa-print"></i> Cetak Form Kehadiran/Absensi', [
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
			                ['content'=>'Pengajar', 'options'=>['colspan'=>4, 'class'=>'text-center warning']], 
			                ['content'=>'Input Kehadiran', 'options'=>['colspan'=>count($idSchedule), 'class'=>'text-center warning']], 
			            ],
			            'options'=>['class'=>'skip-export'] // remove this row from export
			        ]
			    ],
    		]);

    ?>

</div>
<?php
	$this->registerCss('
		.grid-view th {
			white-space:normal;
		}
	');