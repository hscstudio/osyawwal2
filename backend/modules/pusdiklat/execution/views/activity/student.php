<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Inflector;
use hscstudio\heart\widgets\Box;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\pusdiklat\execution\models\TrainingClassSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = 'Peserta Diklat';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'BPPK_TEXT_TRAINING_ACTIVITIES'), 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Peserta Diklat '. Inflector::camel2words($model->name);
?>
<div class="training-class-index">
	<div class="panel panel-default">
	   	<div class="panel-heading"> 
			<div class="pull-right">
	        	<?= (Yii::$app->request->isAjax)?'':Html::a('<i class="fa fa-fw fa-arrow-left"></i> '.Yii::t('app', 'SYSTEM_BUTTON_BACK'), ['index'], ['class' => 'btn btn-xs btn-primary']) ?>
			</div>
			<h1 class="panel-title"><i class="fa fa-fw fa-ellipsis-h"></i>Navigasi</h1> 
		</div>

		<div class="row clearfix">
			<div class="col-md-3">
			<?php
			Box::begin([
				'type'=>'small', // ,small, solid, tiles
				'bgColor'=>'red', // , aqua, green, yellow, red, blue, purple, teal, maroon, navy, light-blue
				'bodyOptions' => [],
				'icon' => 'glyphicon glyphicon-eye-open',
				'link' => ['property','id'=>$model->id],
				'footerOptions' => [
					'class' => 'dashboard-hide',
				],
				'footer' => 'Buka <i class="fa fa-arrow-circle-right"></i>',
			]);
			?>
			<h3>Informasi</h3>
			<p>Informasi Diklat</p>
			<?php
			Box::end();
			?>
			</div>
			
			<div class="col-md-2">
			<?php
			Box::begin([
				'type'=>'small', // ,small, solid, tiles
				'bgColor'=>'aqua', // , aqua, green, yellow, red, blue, purple, teal, maroon, navy, light-blue
				'bodyOptions' => [],
				'icon' => 'fa fa-fw fa-inbox',
				'link' => ['room','id'=>$model->id],
				'footerOptions' => [
					'class' => 'dashboard-hide',
				],
				'footer' => 'Buka <i class="fa fa-arrow-circle-right"></i>',
			]);
			?>
			<h3>Ruangan</h3>
			<p>Pesan Ruangan</p>
			<?php
			Box::end();
			?>
			</div>
			
			<div class="col-md-2 margin-top-small">
			<?php
			Box::begin([
				'type'=>'small', // ,small, solid, tiles
				'bgColor'=>'green', // , aqua, green, yellow, red, blue, purple, teal, maroon, navy, light-blue
				'bodyOptions' => [],
				'icon' => 'fa fa-fw fa-users',
				'link' => ['student','id'=>$model->id],
				'footerOptions' => [
					'class' => 'dashboard-hide',
				],
				'footer' => 'Buka <i class="fa fa-arrow-circle-right"></i>',
			]);
			?>
			<h3>Peserta</h3>
			<p>Anda Disini</p>
			<?php
			Box::end();
			?>
			</div>
			
			<div class="col-md-2">
			<?php
			Box::begin([
				'type'=>'small', // ,small, solid, tiles
				'bgColor'=>'yellow', // , aqua, green, yellow, red, blue, purple, teal, maroon, navy, light-blue
				'bodyOptions' => [],
				'icon' => 'glyphicon glyphicon-home',
				'link' => ['class','id'=>$model->id],
				'footerOptions' => [
					'class' => 'dashboard-hide',
				],
				'footer' => 'Buka <i class="fa fa-arrow-circle-right"></i>',
			]);
			?>
			<h3>Kelas</h3>
			<p>Kelola kelas</p>
			<?php
			Box::end();
			?>
			</div>
			<div class="col-md-3">
			<?php
			Box::begin([
				'type'=>'small', // ,small, solid, tiles
				'bgColor'=>'teal', // , aqua, green, yellow, red, blue, purple, teal, maroon, navy, light-blue
				'bodyOptions' => [],
				'icon' => 'fa fa-fw fa-check',
				'link' => ['forma','id'=>$model->id],
				'footerOptions' => [
					'class' => 'dashboard-hide',
				],
				'footer' => 'Buka <i class="fa fa-arrow-circle-right"></i>',
			]);
			?>
			<h3>Form A</h3>
			<p>Kelola Form A</p>
			<?php
			Box::end();
			?>
			</div>
			
		</div>
	</div>

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
				'header' => '<div style="text-align:center">Nama</div>',
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
				'header' => '<div style="text-align:center">Nomor Induk Pegawai</div>',
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
				'header' => '<div style="text-align:center">Satker</div>',
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
				'header' => '<div style="text-align:center">Kelas</div>',
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
				'template' => '<div class="btn-group">{update} {delete}</div>',
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
										'data-confirm'=>'Yakin ingin menghapus?',
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
										'modal-title'=>'<i class="fa fa-fw fa-pencil"></i> Ubah Status',
										'modal-size'=>'modal-lg'
									]
								);
							},
				],	
			],
        ],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> Daftar Peserta Diklat '. Inflector::camel2words($model->name).'</h3>',
			'before'=> 
				Html::a('<i class="fa fa-fw fa-plus-circle"></i> Daftarkan Peserta', [
					'choose-student','id'=>$model->id
				], [
					'class' => 'btn btn-success','data-pjax'=>'0'
				]).
				'<div class="pull-right" style="margin-right:5px;">'.
				Select2::widget([
					'name' => 'status', 
					'data' => [1=>'Baru (Aktif)',2=>'Mengulang (Aktif)',3=>'Mengundurkan Diri',0=>'Batal'],
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
			'after'=>Html::a('<i class="fa fa-fw fa-repeat"></i> '.Yii::t('app', 'SYSTEM_BUTTON_RESET_GRID'), Url::to(''), ['class' => 'btn btn-info']),
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
	<i class="fa fa-fw fa-refresh upload"></i> Dokumen Generator
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
	<i class="glyphicon glyphicon-upload"></i> Unggah Peserta Massal
	</div>
    <div class="panel-body">
		<div class="row clearfix">
			<div class="col-md-2">
			<?php
			echo Html::a('<i class="fa fa-fw fa-download"></i>Unduh Template',
						Url::to(['/file/download','file'=>'template/pusdiklat/execution/student_upload.xlsx']),
						[
							'class'=>'btn btn-default',
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