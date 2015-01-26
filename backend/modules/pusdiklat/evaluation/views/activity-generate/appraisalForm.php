<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use backend\models\Person;
use backend\models\Employee;
use backend\models\TrainingScheduleTrainer;
use backend\models\TrainingSchedule;
use backend\models\TrainingClass;
use yii\helpers\Url;
use kartik\widgets\DatePicker;
use kartik\widgets\DepDrop;
use hscstudio\heart\widgets\Box;

/* @var $this yii\web\View */
/* @var $model backend\models\Program */
/* @var $form yii\widgets\ActiveForm */
$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
$this->title = Yii::t('app', 'Buat {modelClass}', [
    'modelClass' => 'Form Penilaian',]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'BPPK_TEXT_TRAINING_ACTIVITIES'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Dokumen Umum').' '.$model->name, 'url' => ['activity/generate-dokumen','id'=>$model->id]];
$this->params['breadcrumbs'][] = ['label' => 'Form Penilaian'];
?>
<div class="activity-update panel panel-default">
	
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
			'link' => ['./activity/property','id'=>$model->id],
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
			'link' => ['./activity/class','id'=>$model->id],
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
			'link' => ['./activity/execution-evaluation','id'=>$model->id],
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
			'link' => ['./activity/trainer-training-evaluation','id'=>$model->id],
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
			'link' => ['./activity/generate-dokumen','id'=>$model->id],
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
			'link' => ['./activity/generate-dokumen-khusus','id'=>$model->id],
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
							'class' => 'btn btn-default btn-block',
							'data-pjax' => '0',
							'modal-title' => '<i class="fa fa-fw fa-gear"></i> Pengaturan Pencetakan Surat Tugas Terkait Diklat'
						]);
				?>
				<?php
					echo Html::a('<i class="fa fa-fw fa-print"></i> Cetak Form Penilaian Peserta', ['./activity-generate/appraisal-form','id'=>$model->id], [
							'class' => 'btn btn-success btn-block',
							'data-pjax' => '0',
							'modal-title' => '<i class="fa fa-fw fa-gear"></i> Pengaturan Pencetakan Form Penilaian Peserta'
						]);
				?>
				<?php
					echo Html::a('<i class="fa fa-fw fa-print"></i>Cetak Daftar Pengajar', ['./activity-generate/training-trainer-list','id'=>$model->id], [
							'class' => 'btn btn-default btn-block',
							'data-pjax' => '0',
							'modal-title' => '<i class="fa fa-fw fa-gear"></i> Pengaturan Pencetakan Daftar Pengajar'
						]);
				?>
				<?php
					echo Html::a('<i class="fa fa-fw fa-print"></i>Cetak Dokumen Evaluasi Tatap Muka', ['./activity-generate/evaluation-document','id'=>$model->id], [
							'class' => 'btn btn-default btn-block',
							'data-pjax' => '0',
							'modal-title' => '<i class="fa fa-fw fa-gear"></i> Pengaturan Pencetakan Dokumen Evaluasi Tatap Muka'
						]);
				?>
				<?php
					echo Html::a('<i class="fa fa-fw fa-print"></i>Cetak Honor Transportasi', ['./activity-generate/honor-transport','id'=>$model->id], [
							'class' => 'btn btn-default btn-block',
							'data-pjax' => '0',
							'modal-title' => '<i class="fa fa-fw fa-gear"></i> Pengaturan Pencetakan Honor Transportasi'
						]);
				?>
				<?php
					echo Html::a('<i class="fa fa-fw fa-print"></i> Cetak Rekap Monitoring Diklat Harian', ['./activity-generate/daily-training-monitoring','id'=>$model->id], [
							'class' => 'btn btn-default btn-block',
							'data-pjax' => '0',
							'modal-title' => '<i class="fa fa-fw fa-gear"></i> Pengaturan Pencetakan Rekap Monitoring Diklat Harian'
						]);
				?>
				<?php
					echo Html::a('<i class="fa fa-fw fa-print"></i>Cetak Amplop Evaluasi Pengajar', ['./activity-generate/trainer-evaluation-envelope','id'=>$model->id], [
							'class' => 'btn btn-default btn-block',
							'data-pjax' => '0',
							'modal-title' => '<i class="fa fa-fw fa-gear"></i> Pengaturan Pencetakan Amplop Evaluasi Pengajar'
						]);
				?>
			</div>
			<div class="col-md-8">
				<div class="letter-assignment-form">
					<?php
						$form = ActiveForm::begin([
							'options'=>[
								'id'=>'myform',
								'onsubmit'=>'',
							],
							'action'=>[
								'form-student-evaluation-excel','id'=>$model->id
							], 
						]);
					?>
		            
		            <?= $form->errorSummary($model) ?> <!-- ADDED HERE -->
		            
		            <?php
					$data_form = [
								'0'=>'Nilai Aktivitas',
								'1'=>'Nilai Ujian',
						];
						echo '<label class="control-label">Jenis Form</label>';
						echo Select2::widget([
							'name' => 'jenis_form', 
							'data' => $data_form,
							'options' => [
								'placeholder' => 'Select Jenis Form ...', 
								'class'=>'form-control', 
								'multiple' => false,
								'id'=>'jenis_form',
							],
						]);
					?>	
		            
		            <?php
					$data = ArrayHelper::map(TrainingClass::find()
						->where(['training_id'=>$model->id])		
						->asArray()
						->all()
						, 'id', 'class');
					echo '<label class="control-label">Kelas</label>';
					echo Select2::widget([
						'name' => 'class', 
						'data' => $data,
						'options' => [
							'placeholder' => 'Select Kelas ...', 
							'class'=>'form-control', 
							'multiple' => false,
							'id'=>'class',
						],
					]);
					?>
		            
		            <?php
						    echo '<label class="control-label">Tanggal</label>';
							echo DatePicker::widget([
								'name' => 'tanggal',
								'type' => DatePicker::TYPE_COMPONENT_PREPEND,
								'pluginOptions' => [
								'autoclose'=>true,
								'format' => 'dd-mm-yyyy'
								]
							]);
					?>	
		            <?php
						echo Html::beginTag('label',['class'=>'control-label']).'Waktu'.Html::endTag('label');
						echo Html::input('text','waktu','08:00-12:00 WIB',['class'=>'form-control','id'=>'waktu']);
					?>	
		            <?php
					$data = ArrayHelper::map(Person::find()
						->select(['id', 'name'])
						->where([
							'id'=>TrainingScheduleTrainer::find()
								->select('trainer_id')
								->where([
									'training_schedule_id'=>TrainingSchedule::find()
															->select('id')
															->where([
																	 'training_class_id'=>TrainingClass::find()
																	 					->select('id')
																						->where([
																								 'training_id'=>$model->id
																								 ])
																	 ]), // CEK ID 393 IN TABLE ORGANISATION IS SUBBIDANG PROGRAM
								])
								//->currentSatker()
								->column(),
						])		
						->active()
						->asArray()
						->all()
						, 'id', 'name');
					echo '<label class="control-label">Pengajar</label>';
					echo Select2::widget([
						'name' => 'trainer', 
						'data' => $data,
						'options' => [
							'placeholder' => 'Select Pengajar...', 
							'onchange'=>'
								$.post( "'.Url::to(['mapelku']).'?id="+$(this).val(), 
									function( data ) {
									  $( "input#mapel" ).val( data + " ");
									  $( "input#mapel" ).focus();
									});
							',
							'class'=>'form-control', 
							'multiple' => false,
							'id'=>'trainer',
						],
					]);
					?>
		            <?php
						echo Html::beginTag('label',['class'=>'control-label']).'Mata Pelajaran'.Html::endTag('label');
						echo Html::input('text','mapel','',['class'=>'form-control','id'=>'mapel']);
					?>	
		            
		            <div class="clearfix"><hr></div> 
		            <div class="form-group">
		                <?= Html::submitButton('<i class="fa fa-fw fa-print"></i>'.($model->isNewRecord ? Yii::t('app', 'SYSTEM_BUTTON_PRINT') : Yii::t('app', 'SYSTEM_BUTTON_PRINT')), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		            </div>
		        
		            <?php ActiveForm::end(); ?>
		            
		            <?php $this->registerCss('label{display:block !important;}'); ?>
		        </div>
	        </div>
	    </div>
	</div>
</div>


