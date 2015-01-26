<?php

use yii\helpers\Html;
use yii\grid\GridView as Gridview2;
use kartik\grid\GridView;
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
			<div class="col-md-2">
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
			<h3>Info</h3>
			<p>Informasi diklat</p>
			<?php
			Box::end();
			?>
			</div>
						
			<div class="col-md-2 margin-top-small">
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
			<p>Anda disini</p>
			<?php
			Box::end();
			?>
			</div>
			
	        <div class="col-md-2">
			<?php
			Box::begin([
				'type'=>'small', // ,small, solid, tiles
				'bgColor'=>'navy', // , aqua, green, yellow, red, blue, purple, teal, maroon, navy, light-blue
				'bodyOptions' => [],
				'icon' => 'fa fa-fw fa-building-o',
				'link' => ['execution-evaluation','id'=>$model->id],
				'footerOptions' => [
					'class' => 'dashboard-hide',
				],
				'footer' => 'Masuk <i class="fa fa-arrow-circle-right"></i>',
			]);
			?>
			<h3>Pelaksanaan</h3>
			<p>Evaluasi pelaksanaan</p>
			<?php
			Box::end();
			?>
			</div>
	        
	        <div class="col-md-2">
			<?php
			Box::begin([
				'type'=>'small', // ,small, solid, tiles
				'bgColor'=>'maroon', // , aqua, green, yellow, red, blue, purple, teal, maroon, navy, light-blue
				'bodyOptions' => [],
				'icon' => 'fa fa-fw fa-graduation-cap',
				'link' => ['trainer-training-evaluation','id'=>$model->id],
				'footerOptions' => [
					'class' => 'dashboard-hide',
				],
				'footer' => 'Masuk <i class="fa fa-arrow-circle-right"></i>',
			]);
			?>
			<h3>Pengajar</h3>
			<p>Evaluasi pengajar</p>
			<?php
			Box::end();
			?>
			</div>

	        <div class="col-md-2">
			<?php
			Box::begin([
				'type'=>'small', // ,small, solid, tiles
				'bgColor'=>'blue', // , aqua, green, yellow, red, blue, purple, teal, maroon, navy, light-blue
				'bodyOptions' => [],
				'icon' => 'fa fa-fw fa-book',
				'link' => ['generate-dokumen','id'=>$model->id],
				'footerOptions' => [
					'class' => 'dashboard-hide',
				],
				'footer' => 'Masuk <i class="fa fa-arrow-circle-right"></i>',
			]);
			?>
			<h3>Umum</h3>
			<p>Buat dokumen umum</p>
			<?php
			Box::end();
			?>
			</div>
	        
	        <div class="col-md-2">
			<?php
			Box::begin([
				'type'=>'small', // ,small, solid, tiles
				'bgColor'=>'green', // , aqua, green, yellow, red, blue, purple, teal, maroon, navy, light-blue
				'bodyOptions' => [],
				'icon' => 'fa fa-fw fa-file',
				'link' => ['generate-dokumen-khusus','id'=>$model->id],
				'footerOptions' => [
					'class' => 'dashboard-hide',
				],
				'footer' => 'Masuk <i class="fa fa-arrow-circle-right"></i>',
			]);
			?>
			<h3>Khusus</h3>
			<p>Buat dokumen khusus</p>
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
				'label' => Yii::t('app', 'BPPK_TEXT_SUBJECT'),
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
					$subjectCount = \backend\models\ProgramSubject::find()
						->where([
							'program_id' => $data->training->program_id,
							/* DIPIKIRIN NANTI.. PUYENG AKU 'revision'=> $data->training->tb_program_revision, */
							'status'=>1,
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
								'title'=>$classStudentCount,
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
					
					/* $subjectCount = \backend\models\ProgramSubject::find()
						->where([
							'program_id' => $data->training->program_id,
							DIPIKIRIN NANTI.. PUYENG AKU 'revision'=> $data->training->tb_program_revision, 
							'status'=>1,
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
					} */
				}
			],
			[
				'format' => 'raw',
				'label' => Yii::t('app', 'BPPK_TEXT_SCHEDULE'),
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'80px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function ($data)
				{
					return Html::a(' <i class="fa fa-fw fa-table"></i>', 
							[
								'class-schedule',
								'id'=>$data->training_id,
								'class_id'=>$data->id,
							], 
							[
								'title'=>'Klik untuk mengatur jadwal',
								'class' => 'label label-info',
								'data-pjax'=>0,
								'data-toggle'=>'tooltip',
								'data-confirm'=>'Proses!'
							]);							
				}
			],
			[
				'format' => 'raw',
				'attribute' => 'status',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'80px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function ($data){
					$icon = ($data->status==1)?'<span class="glyphicon glyphicon-ok"></span>':'<span class="glyphicon glyphicon-remove"></span>';
					return $icon;						
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
			'before'=>Html::a('<i class="fa fa-fw fa-plus"></i> '.Yii::t('app', 'SYSTEM_BUTTON_CREATE'), ['create-class','id'=>$model->id], ['class' => 'btn btn-success']),
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
