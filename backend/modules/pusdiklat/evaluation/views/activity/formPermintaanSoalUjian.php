<?php

use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\widgets\DetailView;

use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\widgets\DatePicker;

use hscstudio\heart\widgets\Box;

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = 'Generate Dokumen Khusus';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'BPPK_TEXT_TRAINING_ACTIVITIES'), 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Dokumen Khusus '. Inflector::camel2words($model->name);
?>
<div class="activity-view  panel panel-default"><div class="panel-heading"> 
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
								], ['class' => 'btn btn-success btn-block']
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
					<?php
						$form = ActiveForm::begin(
						[
						    'enableAjaxValidation' => true,
						    'type' => ActiveForm::TYPE_HORIZONTAL
						]);
						
						echo '<div class="col-md-12">';
							echo '<div class="form-group">';
								echo '<div class="col-md-4">';
									echo Html::label('Deadline', 'tanggal_deadline');
								echo '</div>';
								echo '<div class="col-md-8">';
									echo DatePicker::widget([
									    'name' => 'tanggal_deadline',
									    'type' => DatePicker::TYPE_INPUT,
									    'value' => $tanggal_deadline,
									    'pluginOptions' => [
									        'autoclose'=>true,
									        'format' => 'dd-M-yyyy'
									    ]
									]);
								echo '</div>';
							echo '</div>';
							echo '<div class="form-group">';
								echo '<div class="col-md-4">';
									echo Html::label('TTD', 'ttd');
								echo '</div>';
								echo '<div class="col-md-8">';
									echo Html::dropDownList('ttd', 0, $ttd, [
											'placeholder' => 'TTD',
											'class' => 'form-control'
										]);
								echo '</div>';
							echo '</div>';
							/*echo '<div class="form-group">';
								echo '<div class="col-md-4">';
									echo Html::label('NIP', 'nip');
								echo '</div>';
								echo '<div class="col-md-8">';
									echo Html::input('text', 'nip', $nip, [
											'placeholder' => 'NIP',
											'class' => 'form-control'
										]);
								echo '</div>';
							echo '</div>';
							echo '<div class="form-group">';
								echo '<div class="col-md-4">';
									echo Html::label('Jabatan', 'jabatan');
								echo '</div>';
								echo '<div class="col-md-8">';
									echo Html::input('text', 'jabatan', $jabatan, [
											'placeholder' => 'Jabatan',
											'class' => 'form-control'
										]);
								echo '</div>';
							echo '</div>';*/
						echo '</div>';

						echo '<div class="col-md-8">';
							

							echo Select2::widget([
								'name' => 'training_class_id', 
								'data' => $data,
								'options' => [
									'placeholder' => 'Pilih kelas',
									'class'=>'form-control', 
									'multiple' => false,
									'id'=>'training_class_id',
								],
							]);

						echo '</div>';

						echo '<div class="col-md-4">';
							echo Html::submitButton('<i class="fa fa-fw fa-download"></i> Unduh', ['class' => 'btn btn-primary btn-block']);
						echo '</div>';

						ActiveForm::end();
					?>
				</div>
			</div>

		</div>

	</div>

</div>