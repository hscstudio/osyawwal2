<?php

use yii\helpers\Html;
use yii\grid\GridView as Gridview2;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Inflector;
use hscstudio\heart\widgets\Box;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\pusdiklat\execution\models\TrainingClassSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = 'Student #'. Inflector::camel2words($model->training->activity->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Training Activities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="training-class-index">
	<?php
	Box::begin([
		'type'=>'small', // ,small, solid, tiles
		'bgColor'=>'navy', // , aqua, green, yellow, red, blue, purple, teal, maroon, navy, light-blue
		'bodyOptions' => [],
		'icon' => 'fa fa-fw fa-building-o',
		'link' => ['dashboard','id'=>$model->training_id],
		'footerOptions' => [
			'class' => 'dashboard-hide',
		],
		'footer' => '<i class="fa fa-arrow-circle-left"></i> Back',
	]);
	?>
	<h3>Execution</h3>
	<p>Evaluation Execution of Training</p>
	<?php
	Box::end();
	?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],		
			[
				'header' => '<div style="text-align:center">Nama</div>',
				'vAlign'=>'middle',
				'hAlign'=>'left',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){
					return Html::tag('span',
						$data->trainingStudent->student->person->name,
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
				'hAlign'=>'left',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){
					return Html::tag('span',
						$data->trainingStudent->student->person->nip,
						[
							'class'=>'',
							'data-toggle'=>'tooltip',
							'data-html'=>'true',
						]
					);
				},
			],
			
			[
				'header' => '<div style="text-align:center">Evaluasi</div>',
				'vAlign'=>'middle',
				'hAlign'=>'left',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){
					if(!empty(\backend\models\TrainingExecutionEvaluation::findOne(['training_class_student_id'=>$data->id])->id))
					{return Html::a('<span class="fa fa-fw fa-check"></span>');}
					else
					{return Html::a('<span class="fa fa-fw fa-minus"></span>');}
				},
			],
        ],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> '.Html::encode($this->title).'</h3>',
			'before'=> 
				Html::a('<i class="fa fa-fw fa-download"></i> Download Rekap Evaluasi Penyelenggara', [
					'rekap-execution-evaluation','training_id'=>$training_id,'training_class_id'=>$training_class_id
				], [
					'class' => 'btn btn-success','data-pjax'=>'0'
				]),
			'after'=>Html::a('<i class="fa fa-fw fa-repeat"></i> Reset Grid', Url::to(''), ['class' => 'btn btn-info']),
			'showFooter'=>false
		],
		'responsive'=>true,
		'hover'=>true,
    ]); ?>
	<?= \hscstudio\heart\widgets\Modal::widget() ?>   
</div>