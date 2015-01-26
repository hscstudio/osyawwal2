<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use backend\models\Person;
use backend\models\Employee;
use yii\helpers\Url;
use backend\models\ActivityRoom;
use kartik\widgets\DepDrop;
use hscstudio\heart\widgets\Box;


/* @var $this yii\web\View */
/* @var $model backend\models\Program */
/* @var $form yii\widgets\ActiveForm */
$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
$this->title = Yii::t('app', 'Dok. {modelClass}', [
    'modelClass' => 'Evaluasi Tatap Muka',]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'BPPK_TEXT_TRAINING_ACTIVITIES'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Dokumen Umum').' '.$model->name, 'url' => ['activity/generate-dokumen','id'=>$model->id]];
$this->params['breadcrumbs'][] = ['label' => 'Dokumen Evaluasi Tatap Muka'];
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
							'class' => 'btn btn-default btn-block',
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
							'class' => 'btn btn-success btn-block',
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
								'training-direct-evaluation-word','id'=>$model->id
							], 
						]);
					?>
		            
		            <?= $form->errorSummary($model) ?> <!-- ADDED HERE -->
		            <?php
						echo Html::beginTag('label',['class'=>'control-label']).'Tempat'.Html::endTag('label');
						echo Html::input('text','ruang','',['class'=>'form-control','id'=>'ruang']);
					?>	
		           
		            <?php
					$data = ArrayHelper::map(Person::find()
						->select(['id','name'])
						->where([
							'id'=>Employee::find()
								->select('person_id')
								->where([
									'satker_id'=>Yii::$app->user->identity->employee->satker_id,
									'chairman'=>1,// CEK ID 393 IN TABLE ORGANISATION IS SUBBIDANG PROGRAM
								])
								->column(),
						])		
						->active()
						->asArray()
						->all()
						, 'id', 'name');
					echo '<label class="control-label">TTD</label>';
					echo Select2::widget([
						'name' => 'ttd', 
						'data' => $data,
						'options' => [
							'placeholder' => 'Select TTD ...',
							'onchange'=>'
								$.post( "'.Url::to(['ttdnip']).'?id="+$(this).val(), 
									function( data ) {
									  $( "input#ttdnip" ).val( data + " ");
									  $( "input#ttdnip" ).focus();
									});
							',
							'class'=>'form-control', 
							'multiple' => false,
							'id'=>'ttd',
						],
					]);
					?>	
		            <?php
						echo Html::beginTag('label',['class'=>'control-label']).'NIP'.Html::endTag('label');
						echo Html::input('text','ttdnip','',['class'=>'form-control','id'=>'ttdnip']);
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


