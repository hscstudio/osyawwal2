<?php

use yii\helpers\Html;
use yii\grid\GridView as Gridview2;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Inflector;
use hscstudio\heart\widgets\Box;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\bdk\execution\models\TrainingClassSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = 'Student #'. Inflector::camel2words($model->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Training Activities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="training-class-index">
	<?php
	Box::begin([
		'type'=>'small', // ,small, solid, tiles
		'bgColor'=>'green', // , aqua, green, yellow, red, blue, purple, teal, maroon, navy, light-blue
		'bodyOptions' => [],
		'icon' => 'fa fa-user-md',
		'link' => ['dashboard','id'=>$model->id],
		'footerOptions' => [
			'class' => 'dashboard-hide',
		],
		'footer' => '<i class="fa fa-arrow-circle-left"></i> Back',
	]);
	?>
	<h3>Student</h3>
	<p>Student of Training</p>
	<?php
	Box::end();
	?>
	
	<?php \yii\widgets\Pjax::begin([
		'id'=>'pjax-gridview',
	]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],		
			[
				'header' => '<div style="text-align:center">Name</div>',
				'vAlign'=>'middle',
				'hAlign'=>'left',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){
					return Html::tag('span',
						$data->student->person->name,
						[
							'class'=>'',
							'data-toggle'=>'tooltip',
							'data-html'=>'true',
						]
					);
				},
			],
			[
				'header' => '<div style="text-align:center">NIP</div>',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'150px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){
					return Html::tag('span',
						$data->student->person->nip,
						[
							'class'=>'label label-info',
							'data-toggle'=>'tooltip',
							'data-html'=>'true',
						]
					);
				},
			],
			[
				'header' => '<div style="text-align:center">SATKER</div>',
				'vAlign'=>'middle',
				'hAlign'=>'left',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){
					$unit = "-";
					$object_reference = \backend\models\ObjectReference::find()
						->where([
							'object' => 'person',
							'object_id' => $data->student->person->id,
							'type' => 'unit',
						])
						->one();
					if(null!=$object_reference){
						$unit = $object_reference->reference->name;
					}
					if($data->student->satker==2){
						if(!empty($data->student->eselon2)){
							$unit = $data->student->eselon2;
						}
					}
					else if($data->student->satker==3){
						if(!empty($data->student->eselon3)){
							$unit = $data->student->eselon3;
						}
					}
					else if($data->student->satker==4){
						if(!empty($data->student->eselon4)){
							$unit = $data->student->eselon4;
						}
					}
					return Html::tag('span',
						$unit,
						[
							'class'=>'label label-default',
							'data-toggle'=>'tooltip',
							'data-html'=>'true',
						]
					);
				},
			],
			[
				'header' => '<div style="text-align:center">CLASS</div>',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'50px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){
					$trainingClassStudent = \backend\models\TrainingClassStudent::find()
						->where([
							'training_student_id'=>$data->id
						])
						->one();
					$class = '-';
					$options = [
							'class'=>'label label-info',
							'data-toggle'=>'tooltip',
							'data-html'=>'true',
						];
					if(!empty($trainingClassStudent)){
						$class = $trainingClassStudent->trainingClass->class;
						return Html::a(
							$class,
							[
								'class',
								'id'=>$trainingClassStudent->training_id
							],
							$options
						);
					}
					else{
						return Html::tag('span',
							$class,
							$options
						);
					}
				},
			],
        ],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> '.Html::encode($this->title).'</h3>',
			'before'=> '<div class="pull-right" style="margin-right:5px;">'.
				Select2::widget([
					'name' => 'status', 
					'data' => ['1'=>'Active','0'=>'Cancel'],
					'value' => $status,
					'options' => [
						'placeholder' => 'Status ...', 
						'class'=>'form-control', 
						'onchange'=>'
							$.pjax.reload({
								url: "'.\yii\helpers\Url::to(['student','id'=>$model->id]).'&status="+$(this).val(), 
								container: "#pjax-gridview", 
								timeout: 1000,
							});
						',	
					],
				]).
				'</div>',
			'after'=>Html::a('<i class="fa fa-fw fa-repeat"></i> Reset Grid', Url::to(''), ['class' => 'btn btn-info']),
			'showFooter'=>false
		],
		'responsive'=>true,
		'hover'=>true,
    ]); ?>
	<?= \hscstudio\heart\widgets\Modal::widget() ?>
	<?php \yii\widgets\Pjax::end(); ?>
</div>