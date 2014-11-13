<?php

use kartik\widgets\Select2;
use kartik\grid\GridView;
use hscstudio\heart\widgets\Box;

use yii\widgets\DetailView;
use yii\helpers\Inflector;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

use backend\models\Person;
use backend\models\Satker;
use backend\models\Program;

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = 'Informasi - '. Inflector::camel2words($model->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'BPPK_TEXT_TRAINING_ACTIVITIES'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// Persiapan format
$namaHari = [
			'Mon' => 'Senin',
			'Tue' => 'Selasa',
			'Wed' => 'Rabu',
			'Thu' => 'Kamis',
			'Fri' => 'Jumat',
			'Sat' => 'Sabtu',
			'Sun' => 'Minggu'
		];

		$namaBulan = [
			'January' => 'Januari',
			'February' => 'Febuari',
			'March' => 'Maret',
			'April' => 'April',
			'May' => 'Mei',
			'June' => 'Juni',
			'July' => 'Juli',
			'August' => 'Agustus',
			'September' => 'September',
			'October' => 'Oktober',
			'November' => 'November',
			'December' => 'Desember'
		];

function formatHostel($hostel) {
	if ($hostel == 1) {
		return '<i class="fa fa-fw fa-check-circle text-success"></i>';
	}
	elseif ($hostel == 0) {
		return '<i class="fa fa-fw fa-times-circle text-success"></i>';
	}
	else {
		return Yii::t('app', 'SYSTEM_TEXT_UNKNOWN_STATUS');
	}
}

function formatStatus($status) {
	if ($status == 1) {
		return '<span class="label label-info">Siap</span>';
	}
	elseif ($status == 2) {
		return '<span class="label label-success">Berjalan</span>';
	}
	elseif ($status == 3) {
		return '<span class="label label-danger">Ditolak</span>';
	}
	elseif ($status == 0) {
		return '<span class="label label-warning">Rencana</span>';
	}
	else {
		return Yii::t('app', 'SYSTEM_TEXT_UNKNOWN_STATUS');
	}
}

function formatValidasi($validasi) {
	if ($validasi == 1) {
		return '<span class="label label-info">Proses</span>';
	}
	elseif ($validasi == 2) {
		return '<span class="label label-success">Valid</span>';
	}
	elseif ($validasi == 3) {
		return '<span class="label label-danger">Ditolak</span>';
	}
	elseif ($validasi == 0) {
		return '<span class="label label-warning">Rencana</span>';
	}
	else {
		return Yii::t('app', 'SYSTEM_TEXT_UNKNOWN_STATUS');
	}
}

function formatCategory($category) {
	if ($category == 1) {
		return '<span class="label label-info">Dasar</span>';
	}
	elseif ($category == 2) {
		return '<span class="label label-success">Lanjutan</span>';
	}
	elseif ($category == 3) {
		return '<span class="label label-danger">Menengah</span>';
	}
	elseif ($category == 4) {
		return '<span class="label label-warning">Tinggi</span>';
	}
	else {
		return Yii::t('app', 'SYSTEM_TEXT_UNKNOWN_STATUS');
	}
}
// dah

?>
<div class="activity-view  panel panel-default">
	
	<div class="row">
		<div class="col-md-12">
			<?php echo Html::a('<i class="fa fa-fw fa-arrow-left"></i>'.Yii::t('app', 'SYSTEM_BUTTON_BACK'), ['index'], [
						'class' => 'btn btn-warning margin-left-large margin-top-large'
					]);
			?>
		</div>
	</div>

	<div class="panel-body">
		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist">
			<li class="active"><a href="#training" role="tab" data-toggle="tab">Training <span class="label label-primary">1</span> </a></li>
			<li><a href="#program" role="tab" data-toggle="tab">Program <span class="label label-warning">2</span> </a></li>
			<li><a href="#subject" role="tab" data-toggle="tab">Subject <span class="label label-success">3</span> </a></li>
			<li><a href="#document" role="tab" data-toggle="tab">Document <span class="label label-info">4</span> </a></li>
		</ul>
		<!-- Tab panes -->	
		<div class="tab-content" style="border: 1px solid #ddd; border-top-color: transparent; padding:5px; background-color: #fff;">
			<div class="tab-pane fade-in active" id="training">
				<?= DetailView::widget([
					'model' => $model,
					'attributes' => [
			            // 'id',
						[
							'attribute' => 'satker_id',
							'format' => 'raw',
							'label' => 'Satker',
							'value' => Satker::findOne($model->satker_id)->reference->name
						],
						[
							'attribute' => 'name',
							'format' => 'raw',
							'label' => Yii::t('app', 'BPPK_TEXT_NAME'),
						],
						[
							'attribute' => 'description',
							'format' => 'raw',
							'label' => Yii::t('app', 'BPPK_TEXT_DESCRIPTION'),
						],
						[
							'attribute' => 'start',
							'label' => Yii::t('app', 'BPPK_TEXT_START'),
							'format' => 'raw',
							'value' => 	$namaHari[date('D', strtotime($model->start))].
										date(', d ', strtotime($model->start)).
										$namaBulan[date('F', strtotime($model->start))].
										date(' Y H:i', strtotime($model->start))
						],
						[
							'attribute' => 'end',
							'label' => Yii::t('app', 'BPPK_TEXT_END'),
							'format' => 'raw',
							'value' => 	$namaHari[date('D', strtotime($model->end))].
										date(', d ', strtotime($model->end)).
										$namaBulan[date('F', strtotime($model->end))].
										date(' Y H:i', strtotime($model->end))
						],
						[
							'attribute' => 'location',
							'label' => Yii::t('app', 'BPPK_TEXT_LOCATION'),
							'format' => 'raw',
						],
						[
							'attribute' => 'hostel',
							'label' => Yii::t('app', 'BPPK_TEXT_HOSTEL'),
							'format' => 'raw',
							'value' => formatHostel($model->hostel)
						],
						[
							'attribute' => 'status',
							'format' => 'raw',
							'value' => formatStatus($model->status)
						],
						[
							'attribute' => 'created',
							'format' => 'raw',
							'label' => Yii::t('app', 'SYSTEM_TEXT_CREATED'),
							'value' => 	$namaHari[date('D', strtotime($model->created))].
										date(', d ', strtotime($model->created)).
										$namaBulan[date('F', strtotime($model->created))].
										date(' Y H:i', strtotime($model->created))
						],
						[
							'attribute' => 'created_by',
							'format' => 'raw',
							'label' => Yii::t('app', 'SYSTEM_TEXT_CREATED_BY'),
							'value' => Person::findOne($model->created_by)->name
						],
						[
							'attribute' => 'modified',
							'format' => 'raw',
							'label' => Yii::t('app', 'SYSTEM_TEXT_MODIFIED'),
							'value' => 	$namaHari[date('D', strtotime($model->modified))].
										date(', d ', strtotime($model->modified)).
										$namaBulan[date('F', strtotime($model->modified))].
										date(' Y H:i', strtotime($model->modified))
						],
						[
							'attribute' => 'modified_by',
							'format' => 'raw',
							'label' => Yii::t('app', 'SYSTEM_TEXT_MODIFIED_BY'),
							'value' => Person::findOne($model->modified_by)->name
						],
					]
				]); ?>
			</div>
			<div class="tab-pane fade" id="program">
				<?= DetailView::widget([
				'model' => $program,
				'attributes' => [
						[
							'attribute' => 'number',
							'label' => 'Kode',
						],
						[
							'attribute' => 'name',
							'format' => 'raw',
							'label' => Yii::t('app', 'BPPK_TEXT_NAME'),
						],
						[
							'attribute' => 'hours',
							'label' => Yii::t('app', 'BPPK_TEXT_HOURS'),
						],
						[
							'attribute' => 'days',
							'label' => Yii::t('app', 'BPPK_TEXT_DAYS'),
						],
						[
							'attribute' => 'test',
							'label' => Yii::t('app', 'BPPK_TEXT_TEST'),
							'format' => 'raw',
							'value' => formatHostel(Program::findOne($model->training->program_id)->test)
						],
						[
							'attribute' => 'note',
							'label' => Yii::t('app', 'BPPK_TEXT_NOTE'),
						],
						[
							'attribute' => 'category',
							'label' => Yii::t('app', 'BPPK_TEXT_CATEGORY'),
							'format' => 'raw',
							'value' => formatCategory(Program::findOne($model->training->program_id)->category)
						],
						[
							'attribute' => 'validation_status',
							'format' => 'raw',
							'label' => Yii::t('app', 'BPPK_TEXT_VALIDATION_STATUS'),
							'value' => 	formatValidasi(Program::findOne($model->training->program_id)->validation_status)
						],
						[
							'attribute' => 'validation_note',
							'label' => Yii::t('app', 'BPPK_TEXT_VALIDATION_NOTE'),
						],
						[
							'attribute' => 'status',
							'format' => 'raw',
							'value' => formatStatus($model->status)
						],
						[
							'attribute' => 'created',
							'format' => 'raw',
							'label' => Yii::t('app', 'SYSTEM_TEXT_CREATED'),
							'value' => 	$namaHari[date('D', strtotime(Program::findOne($model->training->program_id)->created))].
										date(', d ', strtotime(Program::findOne($model->training->program_id)->created)).
										$namaBulan[date('F', strtotime(Program::findOne($model->training->program_id)->created))].
										date(' Y H:i', strtotime(Program::findOne($model->training->program_id)->created))
						],
						[
							'attribute' => 'created_by',
							'format' => 'raw',
							'label' => Yii::t('app', 'SYSTEM_TEXT_CREATED_BY'),
							'value' => Person::findOne(Program::findOne($model->training->program_id)->created_by)->name
						],
						[
							'attribute' => 'modified',
							'format' => 'raw',
							'label' => Yii::t('app', 'SYSTEM_TEXT_MODIFIED'),
							'value' => 	$namaHari[date('D', strtotime(Program::findOne($model->training->program_id)->modified))].
										date(', d ', strtotime(Program::findOne($model->training->program_id)->modified)).
										$namaBulan[date('F', strtotime(Program::findOne($model->training->program_id)->modified))].
										date(' Y H:i', strtotime(Program::findOne($model->training->program_id)->modified))
						],
						[
							'attribute' => 'modified_by',
							'format' => 'raw',
							'label' => Yii::t('app', 'SYSTEM_TEXT_MODIFIED_BY'),
							'value' => Person::findOne(Program::findOne($model->training->program_id)->modified_by)->name
						],
		            
					],
				]) ?>
			</div>
			<div class="tab-pane fade" id="subject">
				<?= GridView::widget([
					'dataProvider' => $subject,
					//'filterModel' => $searchModel,
					'columns' => [
						['class' => 'kartik\grid\SerialColumn'],
						[
							'attribute' => 'type',
							'vAlign'=>'middle',
							'hAlign'=>'center',
							'width'=>'75px',
							'headerOptions'=>['class'=>'kv-sticky-column'],
							'contentOptions'=>['class'=>'kv-sticky-column'],
							'format'=>'raw',
							'value' => function ($data){
								return Html::tag('span',$data->reference->name,[
									'class'=>'label label-default','data-toggle'=>'tooltip','title'=>''
								]);
							},
						],
						'name',
						[
							'attribute' => 'hours',
							'vAlign'=>'middle',
							'hAlign'=>'center',
							'width'=>'75px',
							'headerOptions'=>['class'=>'kv-sticky-column'],
							'contentOptions'=>['class'=>'kv-sticky-column'],
							'format'=>'raw',
							'value' => function ($data){
								return Html::tag('span',$data->hours,[
									'class'=>'label label-default','data-toggle'=>'tooltip','title'=>''
								]);
							},
						],
						
						[
							'attribute' => 'test',
							'vAlign'=>'middle',
							'hAlign'=>'center',
							'width'=>'75px',
							'headerOptions'=>['class'=>'kv-sticky-column'],
							'contentOptions'=>['class'=>'kv-sticky-column'],
							'format'=>'raw',
							'value' => function ($data){
								if($data->test==1) {
									$icon='<span class="glyphicon glyphicon-check"></span>';
									return Html::a($icon,'#',['class'=>'label label-default','data-toggle'=>'tooltip','data-pjax'=>"0",'title'=>'Diklat dengan Ujian Akhir']);
								}
								else{
									$icon='<span class="glyphicon glyphicon-minus"></span>';
									return Html::a($icon,'#',['class'=>'badge','data-toggle'=>'tooltip','data-pjax'=>"0",'title'=>'Diklat tanpa Ujian Akhir']);
								}
							},
						],
						
						[
							'attribute' => 'sort',
							'vAlign'=>'middle',
							'hAlign'=>'center',
							'width'=>'75px',
							'headerOptions'=>['class'=>'kv-sticky-column'],
							'contentOptions'=>['class'=>'kv-sticky-column'],
							'format'=>'raw',
							'value' => function ($data){
								return Html::tag('span',$data->sort,[
									'class'=>'label label-default','data-toggle'=>'tooltip','title'=>''
								]);
							},
						],
						[
							'label' => 'Status',
							'vAlign'=>'middle',
							'hAlign'=>'center',
							'width'=>'100px',
							'headerOptions'=>['class'=>'kv-sticky-column'],
							'contentOptions'=>['class'=>'kv-sticky-column'],
							'format'=>'raw',
							'value' => function ($data){			
								$icon = ($data->status==1)?'<span class="glyphicon glyphicon-ok"></span>':'<span class="glyphicon glyphicon-remove"></span>';		
								return Html::tag('span', $icon, [
									'class'=>($data->status==1)?'label label-info':'label label-warning',
									'title'=>'Current status is '.(($data->status==1)?'publish':'draft'),
									'data-toggle'=>'tooltip',
								]);
							},
						],
						
					],
					'panel' => [
						'before'=>'',							
						'after'=>'',
						'showFooter'=>false
					],
					'responsive'=>true,
					'hover'=>true,
				]); ?>
			</div>
			<div class="tab-pane fade" id="document">
				<?= GridView::widget([
					'dataProvider' => $document,
					//'filterModel' => $searchModel,
					'columns' => [
						['class' => 'kartik\grid\SerialColumn'],
						[
							'attribute' => 'type',
							'vAlign'=>'middle',
							'hAlign'=>'center',
							'width'=>'100px',
							'headerOptions'=>['class'=>'kv-sticky-column'],
							'contentOptions'=>['class'=>'kv-sticky-column'],
							'format'=>'raw',
							'value' => function ($data){
								return Html::tag('span',$data->type,[
									'class'=>'label label-default','data-toggle'=>'tooltip','title'=>''
								]);
							},
						],
						[
							'label' => 'Document Download',
							'vAlign'=>'middle',
							'hAlign'=>'center',
							'headerOptions'=>['class'=>'kv-sticky-column'],
							'contentOptions'=>['class'=>'kv-sticky-column'],
							'format'=>'raw',
							'value' => function($data){
								return Html::a(
									''.$data->file->name,
									Url::to(['/file/download','file'=>$data->object.'/'.$data->object_id.'/'.$data->file->file_name]),
									[
										'class'=>'label label-default',
										'data-pjax'=>'0',
									]
								);
							},
						],
						[
							'label' => 'Upload Time',
							'vAlign'=>'middle',
							'hAlign'=>'center',
							'headerOptions'=>['class'=>'kv-sticky-column'],
							'contentOptions'=>['class'=>'kv-sticky-column'],
							'format'=>'raw',
							'value' => function($data){
								return $data->file->created;
							},
						],
						
					],
					'panel' => [
						'before'=>'',							
						'after'=>'',
						'showFooter'=>false
					],
					'responsive'=>true,
					'hover'=>true,
				]); ?>
			</div>
		</div>

			
	</div>
</div>
