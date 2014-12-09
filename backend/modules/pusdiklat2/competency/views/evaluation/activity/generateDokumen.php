<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Inflector;
use hscstudio\heart\widgets\Box;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\pusdiklat\execution\models\TrainingClassSubjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = 'Generate Dokumen Umum #'.$model->name;
$this->params['breadcrumbs'][] = ['label' => 'Training Activity', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Dashboard'), 'url' => ['dashboard','id'=>14]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="activity-view  panel panel-default">
   <div class="panel-heading"> 
		<div class="pull-right">
        <?= (Yii::$app->request->isAjax)?'':Html::a('<i class="fa fa-fw fa-arrow-left"></i> Back', ['index'], ['class' => 'btn btn-xs btn-primary']) ?>
		</div>
		<h1 class="panel-title"><?= Html::encode($this->title) ?></h1> 
	</div>
	<div class="panel-body">
		<div class="row clearfix">
			<div class="col-md-3">
			<?php
			Box::begin([
				'type'=>'small', // ,small, solid, tiles
				'bgColor'=>'red', // , aqua, green, yellow, red, blue, purple, teal, maroon, navy, light-blue
				'bodyOptions' => [],
				'icon' => 'glyphicon glyphicon-eye-open',
				'link' => ['./evaluation/activity-generate/letter-assignment','id'=>$model->id],
				'footerOptions' => [
					'class' => 'dashboard-hide',
				],
				'footer' => 'More info <i class="fa fa-arrow-circle-right"></i>',
			]);
			?>
			<h3>Surat Tugas</h3>
			<p>Surat Tugas Terkait Diklat</p>
			<?php
			Box::end();
			?>
			</div>
			
			<div class="col-md-3">
			<?php
			Box::begin([
				'type'=>'small', // ,small, solid, tiles
				'bgColor'=>'yellow', // , aqua, green, yellow, red, blue, purple, teal, maroon, navy, light-blue
				'bodyOptions' => [],
				'icon' => 'glyphicon glyphicon-home',
				'link' => ['./evaluation/activity-generate/appraisal-form','id'=>$model->id],
				'footerOptions' => [
					'class' => 'dashboard-hide',
				],
				'footer' => 'More info <i class="fa fa-arrow-circle-right"></i>',
			]);
			?>
			<h3>Form Penilaian</h3>
			<p>Form Penilaian Peserta</p>
			<?php
			Box::end();
			?>
			</div>
			
            <div class="col-md-3">
			<?php
			Box::begin([
				'type'=>'small', // ,small, solid, tiles
				'bgColor'=>'navy', // , aqua, green, yellow, red, blue, purple, teal, maroon, navy, light-blue
				'bodyOptions' => [],
				'icon' => 'fa fa-fw fa-building-o',
				'link' => ['./evaluation/activity-generate/training-trainer-list','id'=>$model->id],
				'footerOptions' => [
					'class' => 'dashboard-hide',
				],
				'footer' => 'More info <i class="fa fa-arrow-circle-right"></i>',
			]);
			?>
			<h3>Daftar Pengajar</h3>
			<p>Daftar Pengajar</p>
			<?php
			Box::end();
			?>
			</div>
            
            <div class="col-md-3">
			<?php
			Box::begin([
				'type'=>'small', // ,small, solid, tiles
				'bgColor'=>'maroon', // , aqua, green, yellow, red, blue, purple, teal, maroon, navy, light-blue
				'bodyOptions' => [],
				'icon' => 'fa fa-fw fa-graduation-cap',
				'link' => ['./evaluation/activity-generate/evaluation-document','id'=>$model->id],
				'footerOptions' => [
					'class' => 'dashboard-hide',
				],
				'footer' => 'More info <i class="fa fa-arrow-circle-right"></i>',
			]);
			?>
			<h3>Dok.Evaluasi TM</h3>
			<p>Cetak Dokumen Evaluasi Tatap MuKa</p>
			<?php
			Box::end();
			?>
			</div>
            <div class="col-md-3">
			<?php
			Box::begin([
				'type'=>'small', // ,small, solid, tiles
				'bgColor'=>'blue', // , aqua, green, yellow, red, blue, purple, teal, maroon, navy, light-blue
				'bodyOptions' => [],
				'icon' => 'fa fa-fw fa-book',
				'link' => ['./evaluation/activity-generate/honor-transport','id'=>$model->id],
				'footerOptions' => [
					'class' => 'dashboard-hide',
				],
				'footer' => 'More info <i class="fa fa-arrow-circle-right"></i>',
			]);
			?>
			<h3>Honor Transport</h3>
			<p>Cetak Honor Transportasi</p>
			<?php
			Box::end();
			?>
			</div>
            
            <div class="col-md-3">
			<?php
			Box::begin([
				'type'=>'small', // ,small, solid, tiles
				'bgColor'=>'aqua', // , aqua, green, yellow, red, blue, purple, teal, maroon, navy, light-blue
				'bodyOptions' => [],
				'icon' => 'fa fa-fw fa-book',
				'link' => ['./evaluation/activity-generate/letter-assignment','id'=>$model->id],
				'footerOptions' => [
					'class' => 'dashboard-hide',
				],
				'footer' => 'More info <i class="fa fa-arrow-circle-right"></i>',
			]);
			?>
			<h3>Rekap Monitoring</h3>
			<p>Rekap Monitoring Diklat Harian</p>
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
				'icon' => 'fa fa-fw fa-book',
				'link' => ['./evaluation/activity-generate/letter-assignment','id'=>$model->id],
				'footerOptions' => [
					'class' => 'dashboard-hide',
				],
				'footer' => 'More info <i class="fa fa-arrow-circle-right"></i>',
			]);
			?>
			<h3>Amplop</h3>
			<p>Amplop Evaluasi Pengajar</p>
			<?php
			Box::end();
			?>
			</div>
		</div>		
	</div>
</div>