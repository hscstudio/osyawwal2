<?php

use yii\helpers\Html;
use yii\grid\GridView as Gridview2;
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

$this->title = 'Evaluasi Pelaksanaan Diklat';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'BPPK_TEXT_TRAINING_ACTIVITIES'), 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Evaluasi Pelaksanaan '. Inflector::camel2words($model->name);
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
				'footer' => 'Masuk <i class="fa fa-arrow-circle-right"></i>',
			]);
			?>
			<h3>Kelas</h3>
			<p>Kelola Kelas</p>
			<?php
			Box::end();
			?>
			</div>
			
	        <div class="col-md-2 margin-top-small">
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
			<p>Anda disini</p>
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
				'label' => Yii::t('app', 'SYSTEM_TEXT_ACTIONS'),
				'format' => 'raw',
				'width'=>'180px',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function ($data) {
					return Html::a('<i class="fa fa-fw fa-download"></i> Rekap',['rekap-execution-evaluation','training_id'=>$data->training_id,'training_class_id'=>$data->id],
						[
							'class'=>'btn btn-primary btn-xs',
							'data-pjax'=>'0',
							'data-toggle' => 'tooltip',
							'title' => 'Unduh Rekap Evaluasi Penyelenggaran',
							'data-container' => 'body'
						]);
				}
			],
			/*[
				'format' => 'raw',
				'label' => Yii::t('app', 'BPPK_TEXT_STUDENT'),
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'80px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function ($data){
					return Html::a($data->training->student_count_plan,['student-execution-evaluation','training_id'=>$data->training_id,'training_class_id'=>$data->id],
						[
							'class'=>'label label-primary',
							'data-pjax'=>'0',
							'data-toggle'=>'tooltip',
							'title' => 'Click to view student spread plan',
						]);
				},
			],    */
        ],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> '.Html::encode($this->title).'</h3>',
			'before'=>Html::a(''),
			'after'=>Html::a('<i class="fa fa-fw fa-repeat"></i> Reset Grid', Url::to(''), ['class' => 'btn btn-info']),
			'showFooter'=>false
		],
		'responsive'=>true,
		'hover'=>true,
    ]); ?>

</div>