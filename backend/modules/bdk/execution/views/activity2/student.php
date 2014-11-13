<?php

use yii\helpers\Html;
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
				'attribute'=>'name',
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
				'attribute'=>'nip',
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
					if(!empty($object_reference)){
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
            [
				'class' => 'kartik\grid\ActionColumn',
				'template' => '{update} {delete}',
				'buttons' => [
					'delete' => function ($url, $model) {
								$icon='<span class="fa fa-fw fa-trash"></span>';
								return Html::a($icon,
									[
										'delete-student','id'=>$model->training_id,'student_id'=>$model->student_id,
										'training_student_id'=>$model->id
									],
									[
										'class'=>'btn btn-default btn-xs',
										'data-pjax'=>'0',
										'data-confirm'=>'Areyou sure you want delete this item!',
										'data-method'=>'post',
									]
								);
							},
					'update' => function ($url, $model) {
								$icon='<span class="fa fa-fw fa-pencil"></span>';
								return Html::a($icon,
									['update-student','id'=>$model->training_id,'student_id'=>$model->student_id],
									[
										'class'=>'modal-heart btn btn-default btn-xs',
										'data-pjax'=>'0',
										'modal-title'=>'',
										'modal-size'=>'modal-lg'
									]
								);
							},
				],	
			],
        ],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> '.Html::encode($this->title).'</h3>',
			'before'=> 
				Html::a('<i class="fa fa-fw fa-plus-circle"></i> Daftarkan Peserta', [
					'choose-student','id'=>$model->id
				], [
					'class' => 'btn btn-success','data-pjax'=>'0'
				]).
				'<div class="pull-right" style="margin-right:5px;">'.
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

<div class="panel panel-default">
	<div class="panel-heading">
	<i class="fa fa-fw fa-refresh upload"></i> Document Generator
	</div>
    <div class="panel-body">
		<div class="row clearfix">
			<div class="col-md-2">
			<?php
			echo Html::a('<i class="fa fa-fw fa-file"></i> Data Peserta Diklat',
						Url::to(['export-student','id'=>$model->id,'status'=>$status]),
						[
							'class'=>'btn btn-default',
							'data-pjax'=>'0',
						]
					);
			?>
			</div>			
			
		</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
	<i class="glyphicon glyphicon-upload"></i> Batch Upload
	</div>
    <div class="panel-body">
		<div class="row clearfix">
			<div class="col-md-2">
			Upload Student
			</div>
			<div class="col-md-2">
			<?php
			echo Html::a('template',
						Url::to(['/file/download','file'=>'template/pusdiklat/execution/student_upload.xlsx']),
						[
							'class'=>'label label-default',
							'data-pjax'=>'0',
						]
					);
			?>
			</div>
			<div class="col-md-8">
			<?php
			$form = \yii\bootstrap\ActiveForm::begin([
				'options'=>['enctype'=>'multipart/form-data'],
				'action'=>['import-student','id'=>$model->id], 
			]);
			echo \kartik\widgets\FileInput::widget([
				'name' => 'importFile', 
				//'options' => ['multiple' => true], 
				'pluginOptions' => [
					'previewFileType' => 'any',
					'uploadLabel'=>"Import Excel",
				]
			]);
			\yii\bootstrap\ActiveForm::end();
			?>
			</div>
			
		</div>
	</div>
</div>