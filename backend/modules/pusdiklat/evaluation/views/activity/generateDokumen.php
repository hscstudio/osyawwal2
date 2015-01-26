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

$this->title = 'Generate Dokumen Umum';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'BPPK_TEXT_TRAINING_ACTIVITIES'), 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Dokumen Umum '. Inflector::camel2words($model->name);
?>
<div class="activity-view  panel panel-default">
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

        <div class="col-md-2 margin-top-small">
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
		<p>Anda disini</p>
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
	<div class="panel-body">
		<div class="row">
			<div class="col-md-4">
				<?php
					echo Html::a('<i class="fa fa-fw fa-print"></i>Cetak Surat Tugas Terkait Diklat', ['./activity-generate/letter-assignment','id'=>$model->id], [
							'class' => 'btn btn-default btn-block modal-heart',
							'data-pjax' => '0',
							'modal-title' => '<i class="fa fa-fw fa-gear"></i> Pengaturan Pencetakan Surat Tugas Terkait Diklat'
						]);
				?>
				<?php
					echo Html::a('<i class="fa fa-fw fa-print"></i> Cetak Form Penilaian Peserta', ['./activity-generate/appraisal-form','id'=>$model->id], [
							'class' => 'btn btn-default btn-block modal-heart',
							'data-pjax' => '0',
							'modal-title' => '<i class="fa fa-fw fa-gear"></i> Pengaturan Pencetakan Form Penilaian Peserta'
						]);
				?>
				<?php
					echo Html::a('<i class="fa fa-fw fa-print"></i>Cetak Daftar Pengajar', ['./activity-generate/training-trainer-list','id'=>$model->id], [
							'class' => 'btn btn-default btn-block modal-heart',
							'data-pjax' => '0',
							'modal-title' => '<i class="fa fa-fw fa-gear"></i> Pengaturan Pencetakan Daftar Pengajar'
						]);
				?>
				<?php
					echo Html::a('<i class="fa fa-fw fa-print"></i>Cetak Dokumen Evaluasi Tatap Muka', ['./activity-generate/evaluation-document','id'=>$model->id], [
							'class' => 'btn btn-default btn-block modal-heart',
							'data-pjax' => '0',
							'modal-title' => '<i class="fa fa-fw fa-gear"></i> Pengaturan Pencetakan Dokumen Evaluasi Tatap Muka'
						]);
				?>
				<?php
					echo Html::a('<i class="fa fa-fw fa-print"></i>Cetak Honor Transportasi', ['./activity-generate/honor-transport','id'=>$model->id], [
							'class' => 'btn btn-default btn-block modal-heart',
							'data-pjax' => '0',
							'modal-title' => '<i class="fa fa-fw fa-gear"></i> Pengaturan Pencetakan Honor Transportasi'
						]);
				?>
				<?php
					echo Html::a('<i class="fa fa-fw fa-print"></i> Cetak Rekap Monitoring Diklat Harian', ['./activity-generate/daily-training-monitoring','id'=>$model->id], [
							'class' => 'btn btn-default btn-block modal-heart',
							'data-pjax' => '0',
							'modal-title' => '<i class="fa fa-fw fa-gear"></i> Pengaturan Pencetakan Rekap Monitoring Diklat Harian'
						]);
				?>
				<?php
					echo Html::a('<i class="fa fa-fw fa-print"></i>Cetak Amplop Evaluasi Pengajar', ['./activity-generate/trainer-evaluation-envelope','id'=>$model->id], [
							'class' => 'btn btn-default btn-block modal-heart',
							'data-pjax' => '0',
							'modal-title' => '<i class="fa fa-fw fa-gear"></i> Pengaturan Pencetakan Amplop Evaluasi Pengajar'
						]);
				?>
			</div>
			<div class="col-md-8">
				<div class="jumbotron">
					<p><i class="fa fa-fw fa-arrow-circle-o-left"></i> Pilih dokumen mana yang ingin dicetak</p>
				</div>
			</div>
		</div>

		<!-- <div class="row clearfix">
			<div class="col-md-3">
			<?php
			Box::begin([
				'type'=>'small', // ,small, solid, tiles
				'bgColor'=>'red', // , aqua, green, yellow, red, blue, purple, teal, maroon, navy, light-blue
				'bodyOptions' => [],
				'icon' => 'glyphicon glyphicon-eye-open',
				'link' => ['./activity-generate/letter-assignment','id'=>$model->id],
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
				'link' => ['./activity-generate/appraisal-form','id'=>$model->id],
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
				'link' => ['./activity-generate/training-trainer-list','id'=>$model->id],
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
				'link' => ['./activity-generate/evaluation-document','id'=>$model->id],
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
				'link' => ['./activity-generate/honor-transport','id'=>$model->id],
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
				'link' => ['./activity-generate/daily-training-monitoring','id'=>$model->id],
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
				'link' => ['./activity-generate/trainer-evaluation-envelope','id'=>$model->id],
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
            
		</div>		 -->
	</div>
</div>