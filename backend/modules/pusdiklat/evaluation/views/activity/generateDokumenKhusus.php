<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Inflector;
use hscstudio\heart\widgets\Box;

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = 'Generate Dokumen Khusus';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'BPPK_TEXT_TRAINING_ACTIVITIES'), 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Dokumen Khusus '. Inflector::camel2words($model->name);
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
        
        <div class="col-md-2 margin-top-small">
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
		<p>Anda disini</p>
		<?php
		Box::end();
		?>
		</div>
	</div>

	<div class="panel-body">

		<div class="container-fluid">

			<div class="row">
				<div class="col-md-4">
						<?php 
							echo Html::a('<i class="fa fa-fw fa-download"></i> Surat Permintaan Soal Ujian', ['generate-dokumen-handler', 
									'id' => $model->id, 
									'k' => 1
								], ['class' => 'btn btn-default btn-block']
							);

							echo Html::a('<i class="fa fa-fw fa-download"></i> Berita Acara Validasi Ujian', ['generate-dokumen-handler', 
									'id' => $model->id, 
									'k' => 2
								], ['class' => 'btn btn-default btn-block']
							);
							
							echo Html::a('<i class="fa fa-fw fa-download"></i> Daftar Hadir Ujian', ['generate-dokumen-handler', 
									'id' => $model->id, 
									'k' => 3
								], ['class' => 'btn btn-default btn-block']
							);
							
							echo Html::a('<i class="fa fa-fw fa-download"></i> Berita Acara Ujian', ['generate-dokumen-handler', 
									'id' => $model->id, 
									'k' => 4
								], ['class' => 'btn btn-default btn-block']
							);
							
							echo Html::a('<i class="fa fa-fw fa-download"></i> Surat Permintaan Koreksi Hasil Ujian', ['generate-dokumen-handler', 
									'id' => $model->id, 
									'k' => 5
								], ['class' => 'btn btn-default btn-block']
							);
						?>
				</div>
				<div class="col-md-8">
					<div class="jumbotron">
						<p><i class="fa fa-fw fa-arrow-circle-o-left"></i> Pilihlah dokumen yang ingin diunduh</p>
					</div>
				</div>
			</div>

		</div>

	</div>

</div>