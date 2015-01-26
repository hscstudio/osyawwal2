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
			<p>Informasi diklat</p>
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
				'icon' => 'fa fa-fw fa-user-md',
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
			<p>Anda disini</p>
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
				'link' => ['./activity-generate/formb','id'=>$model->id],
				'footerOptions' => [
					'class' => 'dashboard-hide',
				],
				'footer' => 'Masuk <i class="fa fa-arrow-circle-right"></i>',
			]);
			?>
			<h3>Form B</h3>
			<p>Kelola Form B</p>
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

			/*[
				'format' => 'raw',
				'label' => Yii::t('app', 'BPPK_TEXT_ATTENDANCE'),
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'80px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function ($data) {
					return Html::a('<i class="fa fa-fw fa-tasks"></i>', Url::to(['attendance', 'training_class_id' => $data->id]), [
							'class' => 'btn btn-default btn-xs',
							'data-pjax' => '0'
						]);
				}
			],*/
			
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

			/*[
				'format' => 'raw',
				'label' => Yii::t('app', 'BPPK_TEXT_SCHEDULE'),
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'80px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function ($model){
					return Html::a('Lihat',
						Url::to(['schedule','training_class_id'=>$model->id]),
						['class'=>'label label-default', 'data-pjax' => '0']);
				}
			],*/

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
									'data-confirm'=>'Proses'
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
        ],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> Daftar Kelas '. Inflector::camel2words($model->name).'</h3>',
			'before'=>'',
			'after'=>Html::a('<i class="fa fa-fw fa-repeat"></i> '.Yii::t('app', 'SYSTEM_BUTTON_RESET_GRID'), Url::to(''), ['class' => 'btn btn-info']),
			'showFooter'=>false
		],
		'responsive'=>true,
		'hover'=>true,
    ]); ?>

</div>

<?php
/*<div class="panel panel-default">
	<div class="panel-heading">
	<i class="glyphicon glyphicon-upload"></i> Dokumen Generator
	</div>
    <div class="panel-body">

	</div>
</div>*/
?>