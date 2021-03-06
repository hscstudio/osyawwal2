<?php

use yii\grid\GridView as Gridview2;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Inflector;
use hscstudio\heart\widgets\Box;
use kartik\widgets\Select2;

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = 'Kelas';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'BPPK_TEXT_TRAINING_ACTIVITIES'), 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Kelas '. Inflector::camel2words($model->name);
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
				'footer' => 'Masuk <i class="fa fa-arrow-circle-right"></i>',
			]);
			?>
			<h3>Informasi</h3>
			<p>Informasi Diklat</p>
			<?php
			Box::end();
			?>
			</div>
			
			<div class="col-md-3">
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
				'footer' => 'Masuk <i class="fa fa-arrow-circle-right"></i>',
			]);
			?>
			<h3>Peserta</h3>
			<p>Kelola Peserta</p>
			<?php
			Box::end();
			?>
			</div>
			
			<div class="col-md-3 margin-top-small">
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
				'footer' => 'Masuk <i class="fa fa-arrow-circle-right"></i>',
			]);
			?>
			<h3>Kelas</h3>
			<p>Anda Disini</p>
			<?php
			Box::end();
			?>
			</div>
			<div class="col-md-3">
			<?php
			Box::begin([
				'type'=>'small', // ,small, solid, tiles
				'bgColor'=>'purple', // , aqua, green, yellow, red, blue, purple, teal, maroon, navy, light-blue
				'bodyOptions' => [
					'onclick'=>'alert()',
				],
				'icon' => 'fa fa-fw fa-money',
				'link' => ['honorarium','id'=>$model->id],
				'footerOptions' => [
					'class' => 'dashboard-hide',
				],
				'footer' => 'Masuk <i class="fa fa-arrow-circle-right"></i>',
			]);
			?>
			<h3>Honor</h3>
			<p>Kelola honorarium</p>
			<?php
			Box::end();
			?>
			</div>

		</div>
	</div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],		
			[
				'attribute' => 'class',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],					
			],

			[
				'format' => 'raw',
				'label' => Yii::t('app', 'BPPK_TEXT_ATTENDANCE'),
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'180px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function ($data) use ($model){
					return '<div class="btn-group">'.
					
						Html::a('<i class="fa fa-fw fa-pencil"></i><i class="fa fa-fw fa-tasks"></i>', Url::to(['attendance', 'training_class_id' => $data->id]), [
							'class' => 'btn btn-default btn-xs',
							'data-pjax' => '0',
							'title'=>'Input Data Kehadiran <br>Peserta dan Pengajar',
							'data-toggle'=>'tooltip',
							'data-html'=>'true',
							'data-container' => 'body'
						]).
						
						Html::a('<i class="fa fa-fw fa-print"></i><i class="fa fa-fw fa-child"></i>', Url::to(['recap', 'training_class_id' => $data->id, 'id' => $model->id]), [
							'class' => 'btn btn-default btn-xs',
							'data-pjax' => '0',
							'title' => 'Cetak Rekap Kehadiran Peserta <br>Pada Kelas Ini',
							'data-toggle'=>'tooltip',
							'data-html'=>'true',
							'data-container' => 'body'
						]).
						
						Html::a('<i class="fa fa-fw fa-print"></i><i class="fa fa-fw fa-user-md"></i>', Url::to(['recap-trainer', 'training_class_id' => $data->id, 'id' => $model->id]), [
							'class' => 'btn btn-default btn-xs',
							'data-pjax' => '0',
							'title' => 'Cetak Rekap Kehadiran Pengajar <br>Pada Kelas Ini',
							'data-toggle'=>'tooltip',
							'data-html'=>'true',
							'data-container' => 'body'
						]).
						
						'</div>';
				}
			],
			
			/* [
				'format' => 'raw',
				'label' => 'Subject',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'80px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function ($data)
				{
					$classSubjectCount = \backend\models\TrainingClassSubject::find()
						->where([
							'training_class_id' => $data->id
						])
						->count();
					$subjectCount = \backend\models\ProgramSubjectHistory::find()
						->where([
							'program_id' => $data->training->program_id,
							'program_revision' => $data->training->program_revision,
							'status'=>1
						])
						->count();
					
					if($subjectCount>$classSubjectCount){
						return Html::a($classSubjectCount.' <i class="fa fa-fw fa-plus-circle"></i>', 
								[
									'create-class-subject',
									'id'=>$data->training_id,
									'class_id'=>$data->id,
								], 
								[
									'title'=>$subjectCount,
									'class' => 'label label-info',
									'data-pjax'=>0,
									'data-toggle'=>'tooltip',
									'data-confirm'=>'Mata pelajaran akan digenerate, menyesuaikan dengan program diklatnya!'
								]);
					}
					else{
						return Html::a($classSubjectCount, 
								[
									'class-subject',
									'id'=>$data->training_id,
									'class_id'=>$data->id,
								], 
								[
									'title'=>$subjectCount,
									'class' => 'label label-info',
									'data-pjax'=>0,
									'data-toggle'=>'tooltip',
								]);
					}
				}
			], */

			[
				'format' => 'raw',
				'label' => Yii::t('app', 'BPPK_TEXT_SCHEDULE'),
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'80px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function ($data){
					return Html::a(' <i class="fa fa-fw fa-table"></i>', 
							[
								'class-schedule',
								'id'=>$data->training_id,
								'class_id'=>$data->id,
							], 
							[
								'title'=>'Klik untuk melihat jadwal',
								'class' => 'label label-info',
								'data-pjax'=>0,
								'data-toggle'=>'tooltip',
								/* 'data-confirm'=>'Process!' */
							]);			
				}
			],

			[
				'format' => 'raw',
				'label' => Yii::t('app', 'BPPK_TEXT_STUDENT'),
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'80px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function ($data)
				{
					$studentCount = \backend\models\TrainingStudent::find()
						->where([
							'training_id' => $data->training_id
						])
						->count();
						
					$classStudentCount = \backend\models\TrainingClassStudent::find()
						->where([
							'training_id' => $data->training_id,
							'training_class_id' => $data->id
						])
						->count();							
					
					if($studentCount>0){
						return Html::a($classStudentCount, 
							[
								'class-student',
								'id'=>$data->training_id,
								'class_id'=>$data->id,
							], 
							[
								'title'=>$classStudentCount.' peserta',
								'class' => 'label label-info',
								'data-pjax'=>'0',
								'data-toggle'=>'tooltip',
							]);
					}
					else{
						return Html::a(' <i class="fa fa-fw fa-plus-circle"></i>', 
								[
									'student',
									'id'=>$data->training_id,
								], 
								[
									'title'=>'Belum ada peserta yang didaftarkan pada diklat ini',
									'class' => 'label label-info',
									'data-pjax'=>0,
									'data-toggle'=>'tooltip',
									'data-confirm'=>'Process!'
								]);
					}				
					
				}
			],
            [
				'class' => 'kartik\grid\ActionColumn',
				'template' => '{delete}',
				'buttons' => [
					'delete' => function ($url, $model) {
								$icon='<span class="fa fa-fw fa-trash"></span>';
								return Html::a($icon,
									['delete-class','id'=>$model->training_id,'class_id'=>$model->id],
									[
										'class'=>'btn btn-default btn-xs',
										'data-pjax'=>'0',
										'data-confirm'=>'Yakin ingin menghapus?',
										'data-method'=>'post',
									]
								);
							},
				],	
			],
        ],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> Daftar Kelas '. Inflector::camel2words($model->name).'</h3>',
			'before'=>Html::a('<i class="fa fa-fw fa-plus"></i> '.Yii::t('app', 'SYSTEM_BUTTON_CREATE'), ['create-class','id'=>$model->id], ['class' => 'btn btn-success']).
			'<div class="btn-group pull-right" style="margin-right:5px">'.
				Html::a('<i class="fa fa-fw fa-print"></i> Cetak Semua Rekap Kehadiran', null,
				[
					'class' => 'btn btn-default',
					'data-pjax' => '0'
				]).
			  '<a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="#">
			    <span class="fa fa-caret-down"></span></a>
			  <ul class="dropdown-menu">
			    <li>'.Html::a('<i class="fa fa-fw fa-child"></i> Peserta', [
					'recap',
					'id' => $model->id
				],
				[
					'data-pjax' => '0'
				]).'</li>
				<li>'.Html::a('<i class="fa fa-fw fa-user-md"></i> Pengajar', [
					'recap-trainer',
					'id' => $model->id
				],
				[
					'data-pjax' => '0'
				]).'</li>
			  </ul>
			</div> ',
			'after'=>Html::a('<i class="fa fa-fw fa-repeat"></i> '.Yii::t('app', 'SYSTEM_BUTTON_RESET_GRID'), Url::to(''), ['class' => 'btn btn-info']),
			'showFooter'=>false
		],
		'responsive'=>true,
		'hover'=>true,
    ]); ?>

</div>

<div class="panel panel-default">
	<div class="panel-heading">
	<i class="glyphicon glyphicon-upload"></i> Fitur Pembagian Peserta ke Kelas
	</div>
    <div class="panel-body">
		<?php
		$form = \yii\bootstrap\ActiveForm::begin([
			'options'=>[
				'id'=>'myform',
				'onsubmit'=>'
					
				',
			],
			'action'=>[
				'class','id'=>$model->id
			], 
		]);
		?>
		<div class="row clearfix">
			<div class="col-md-3">
			<?php
			echo Html::beginTag('label',['class'=>'control-label']).'Peserta belum mendapatkan kelas'.Html::endTag('label');
			echo Html::input('text','',$trainingStudentCount,['class'=>'form-control','disabled'=>'disabled','id'=>'stock']);
			?>
			</div>
			<div class="col-md-3">
			<?php
			echo Html::beginTag('label',['class'=>'control-label']).'Jumlah peserta yang diacak ke kelas'.Html::endTag('label');
			echo Html::input('text','student','',['class'=>'form-control','id'=>'count']);
			?>
			</div>
			<div class="col-md-3">
			<?php
			echo '<label class="control-label">Kriteria Pembagian Acak</label>';
			echo Select2::widget([
				'name' => 'baseon', 
				'data' => [
					'person.name' =>'Nama', 
					'person.gender' => 'Jenis Kelamin', 
					'object_reference.reference_id' => 'Unit',					
				],
				'options' => [
					'placeholder' => 'Acak berdasarkan ...', 
					'class'=>'form-control', 
					'multiple' => true,
					'id'=>'baseon',
				],
			]);
			?>
			</div>
			<div class="col-md-3">
			<?php
			echo Html::beginTag('label',['class'=>'control-label']).' '.Html::endTag('label');
			echo Html::submitButton('<i class="fa fa-fw fa-random"></i>Bagi', ['class' => 'btn btn-success','style'=>'display:block;']);
			?>
			</div>
		</div>
		<?php \yii\bootstrap\ActiveForm::end(); ?>
		<?php
		$this->registerJs("
			$('#myform').on('beforeSubmit', function () {
				var count = parseInt($('#count').val());
				var stock = parseInt($('#stock').val());
				var baseon = $('#baseon').val();
				if(stock<=0 || isNaN(stock)){
					alert('Tidak ada stock peserta!');
					return false;
				}
				else if(count<=0 || isNaN(count)){
					alert('Jumlah permintaan peserta tidak boleh nol!');
					$('#count').select();
					return false;
				}
				else if(stock<count){
					alert('Jumlah permintaan tidak boleh melebihi stock peserta!'+x+y);
					$('#count').select();
					return false;
				}			
				else if(baseon==null){
					alert('Dasar pengacakan harus ditentukan!');
					$('#baseon').select();	
					return false;					
				}	
			});
		");
		?>
	</div>
</div>