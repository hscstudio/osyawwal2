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
use backend\models\TrainingClassStudentAttendance;
use backend\models\ObjectReference;

$this->title = 'Nilai Akhir';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Training Activities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Inflector::camel2words($modelTraining->activity->name), 'url' => ['view', 'id' => $modelTraining->activity->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

echo AlertBlock::widget([
    'useSessionFlash' => true,
    'type' => AlertBlock::TYPE_ALERT
]);
?>

<div class="final-score-update">

    <?php

    	$columns = [
    		['class' => 'kartik\grid\SerialColumn'],

    		[
    			'label' => 'Name',
				'vAlign'=>'middle',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function ($model) {
					return $model->trainingStudent->student->person->name;
				}
			],

    		[
    			'label' => 'NIP',
				'vAlign'=>'middle',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function ($model) {
					return $model->trainingStudent->student->person->nip;
				}
			],

    		[
    			'label' => 'Unit',
				'vAlign'=>'middle',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function ($model) {
					$objectReference = ObjectReference::find()
						->where([
							'object' => 'person',
							'object_id' => $model->trainingStudent->student->person->id,
							'type' => 'unit'
						])
						->one();
					return $objectReference->reference->name;
				}
			],
    		[
    			'header' => 'Nilai Akhir',
				'vAlign'=>'middle',
				'format' => 'raw',
				'width' => '80px',
				'headerOptions'=>[
					'class'=>'kv-sticky-column',
				],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function($model) {
					return Html::input('text', 'test', $model->test, [
							'class' => 'form-control',
							'onchange' => new JsExpression('
								var maxVal = 100;
								var currEle = $(this);
								if ( currEle.val() > maxVal) {
									$.growl({
										icon: "fa fa-fw fa-exclamation-circle",
										title: " <strong>Nilai error!</strong> ",
										message: "Nilai tidak boleh lebih besar dari " + maxVal,
									}, {
										type: "warning",
									});
									currEle.select();
								}
								else {
					    			$.ajax({
										type: "post",
										url: "'.Url::to(['editable-nilai-akhir']).'",
										data: {
											test: $(this).val(),
											training_class_student_id: "'.$model->id.'",
										},
										success: function(data) {
											data = JSON.parse(data);
											if (data.error != "error") {
												$.growl({
													icon: "fa fa-fw fa-check-circle",
													title: " <strong>Saved!</strong> ",
													message: "Nilai " + data.test + " tersimpan",
												}, {
													type: "success",
												});
											}
											else {
												currEle.select();
												$.growl({
													icon: "fa fa-fw fa-exclamation-circle",
													title: " <strong>Kesalahan!</strong> ",
													message: data.test,
												}, {
													type: "warning",
												});
											}
										}
									})
								}
					    	')
						]);
				}
			]
    	];


    	echo GridView::widget([
    			'dataProvider' => $dataProvider,
    			'filterModel' => $searchModel,
    			'columns' => $columns,
    			'striped' => true,
    			'hover' => true,
    			'responsive' => true,
    			'panel' => [
					'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> Nilai Akhir</h3>',
					'before'=>
						Html::a('<i class="fa fa-fw fa-arrow-left"></i> Back', [
								'activity2/index'
							], ['class' => 'btn btn-warning']
						),
					'after' => '',
					'showFooter' => false
				],
				'beforeHeader'=>[
			        [
			            'options'=>['class'=>'skip-export'] // remove this row from export
			        ]
			    ],
    		]);

    ?>

</div>
