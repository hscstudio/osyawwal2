<?php

use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\widgets\DetailView;

use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;

use hscstudio\heart\widgets\Box;

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = 'Generate Dokumen Khusus #'.$model->name;
$this->params['breadcrumbs'][] = ['label' => 'Training Activity', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Dashboard'), 'url' => ['dashboard','id'=>14]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="activity-view  panel panel-default">
    
    <div class="panel-heading"> 
		<div class="pull-right">
        <?= (Yii::$app->request->isAjax)?'':Html::a('<i class="fa fa-fw fa-arrow-left"></i> Back', ['dashboard', 'id' => $model->id], ['class' => 'btn btn-xs btn-primary']) ?>
		</div>
		<h1 class="panel-title"><?= Html::encode($this->title) ?></h1> 
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
						    'type' => ActiveForm::TYPE_VERTICAL
						]);
						
						echo '<div class="col-md-12">';
							echo '<div class="form-group">';
								echo Html::input('text', 'Jabatan', $nama_ujian, [
										'placeholder' => 'Nama Ujian',
										'class' => 'form-control'
									]);
							echo '</div>';
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