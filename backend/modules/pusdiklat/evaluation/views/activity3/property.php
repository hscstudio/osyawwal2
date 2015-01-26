<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Inflector;
use hscstudio\heart\widgets\Box;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;

use backend\models\Reference;
use backend\models\Activity;
use backend\models\Person;

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = 'Informasi';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'BPPK_TEXT_TRAINING_ACTIVITIES'), 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Informasi '. Inflector::camel2words($model->name);


// Ngeformat
$satker = Reference::findOne($model->satker_id)->name;
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

$start = $namaHari[date('D', strtotime($model->start))].
			date(', d ', strtotime($model->start)).
			$namaBulan[date('F', strtotime($model->start))].
			date(' Y', strtotime($model->start)).
			' - pukul '.
			date('H:i', strtotime($model->start));
$end = $namaHari[date('D', strtotime($model->end))].
			date(', d ', strtotime($model->end)).
			$namaBulan[date('F', strtotime($model->end))].
			date(' Y', strtotime($model->end)).
			' - pukul '.
			date('H:i', strtotime($model->end));

$lokasi = explode('|', $model->location);
$lokasi[0] = Reference::findOne($model->location)->name;

$diasramakan_icons = [
	'1'=>'<i class="fa fa-fw fa-check-circle"></i> Ya',
	'0'=>'<i class="fa fa-fw fa-times-circle"></i> Tidak'
];
$diasramakan_classes = ['1'=>'success','0'=>'danger'];
$diasramakan_title = ['1'=>'Berjalan','0'=>'Batal'];
$diasramakan = Html::tag(
	'div',
	$diasramakan_icons[$model->hostel],
	[
		'class'=>'label label-'.$diasramakan_classes[$model->hostel],
	]
);

$dibuatOleh = Person::findOne($model->created_by)->name; // baikin ya :DDDD

$terakhirDiubahOleh = Person::findOne($model->modified_by)->name; // baikin ya :DDDD

$status_icons = [
	'0'=>'<i class="fa fa-fw fa-fire"></i> Rencana',
	'1'=>'<i class="fa fa-fw fa-refresh"></i> Siap',
	'2'=>'<i class="fa fa-fw fa-check-circle"></i> Berjalan',
	'3'=>'<i class="fa fa-fw fa-times-circle"></i> Batal'
];
$status_classes = ['0'=>'warning','1'=>'info','2'=>'success','3'=>'danger'];
$status_title = ['0'=>'Rencana','1'=>'Siap','2'=>'Berjalan','3'=>'Batal'];
$status = Html::tag(
	'div',
	$status_icons[$model->status],
	[
		'class'=>'label label-'.$status_classes[$model->status],
	]
);

$validation_status_icons = [
	'0'=>'<span class="glyphicon glyphicon-fire"></span>Draft',
	'1'=>'<span class="glyphicon glyphicon-refresh"></span>Proses',
	'2'=>'<span class="glyphicon glyphicon-check"></span>Valid',
	'3'=>'<span class="glyphicon glyphicon-remove"></span>Tidak Valid'
];
$validation_status_classes = ['0'=>'warning','1'=>'info','2'=>'success','3'=>'danger'];
$validation_status_title = ['0'=>'Draft','1'=>'Proses','2'=>'Valid','3'=>'Tidak Valid'];
$validation_status = Html::tag(
	'div',
	$validation_status_icons[$program->validation_status],
	[
		'class'=>'label label-'.$validation_status_classes[$program->validation_status],
	]
);

$program_status_icons = [
	1=>'<i class="fa fa-fw fa-check-circle"></i> Aktif',
	0=>'<i class="fa fa-fw fa-times-circle"></i> Tidak Aktif'
];
$program_status_classes = ['1'=>'success','0'=>'danger'];
$program_status = Html::tag(
	'div',
	$program_status_icons[$program->status],
	[
		'class'=>'label label-'.$program_status_classes[$program->status],
	]
);

$program_diujikan_icons = [
	'1'=>'<i class="fa fa-fw fa-check-circle"></i> Ya',
	'0'=>'<i class="fa fa-fw fa-times-circle"></i> Tidak'
];
$program_diujikan_classes = ['1'=>'success','0'=>'danger'];
$program_diujikan_title = ['1'=>'Berjalan','0'=>'Batal'];
$program_diujikan = Html::tag(
	'div',
	$program_diujikan_icons[$program->test],
	[
		'class'=>'label label-'.$program_diujikan_classes[$program->test],
	]
);

$program_dibuat = $namaHari[date('D', strtotime($program->created))].
			date(', d ', strtotime($program->created)).
			$namaBulan[date('F', strtotime($program->created))].
			date(' Y', strtotime($program->created)).
			' - pukul '.
			date('H:i', strtotime($program->created));
$program_terakhirDiubah = $namaHari[date('D', strtotime($program->modified))].
			date(', d ', strtotime($program->modified)).
			$namaBulan[date('F', strtotime($program->modified))].
			date(' Y', strtotime($program->modified)).
			' - pukul '.
			date('H:i', strtotime($program->modified));
$program_dibuatOleh = Person::findOne($program->created_by)->name; // baikin ya :DDDD

$program_terakhirDiubahOleh = Person::findOne($program->modified_by)->name; // baikin ya :DDDD
// dah
?>
<div class="activity-view  panel panel-default">
	<div class="panel-heading"> 
		<div class="pull-right">
        	<?= (Yii::$app->request->isAjax)?'':Html::a('<i class="fa fa-fw fa-arrow-left"></i> '.Yii::t('app', 'SYSTEM_BUTTON_BACK'), ['index'], ['class' => 'btn btn-xs btn-primary']) ?>
		</div>
		<h1 class="panel-title"><i class="fa fa-fw fa-ellipsis-h"></i>Navigasi</h1> 
	</div>

	<div class="row clearfix">
		<div class="col-md-12 margin-top-small">
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
		<p>Anda disini</p>
		<?php
		Box::end();
		?>
		</div>
	</div>

	<div class="panel-body">
		<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist">
			<li class="active"><a href="#training" role="tab" data-toggle="tab">Diklat <span class="label label-primary">1</span> </a></li>
			<li><a href="#program" role="tab" data-toggle="tab">Program <span class="label label-warning">2</span> </a></li>
			<li><a href="#subject" role="tab" data-toggle="tab">Mata Pelajaran <span class="label label-success">3</span> </a></li>
			<li><a href="#document" role="tab" data-toggle="tab">Dokumen <span class="label label-info">4</span> </a></li>
		</ul>
		<!-- Tab panes -->	
		<div class="tab-content" style="border: 1px solid #ddd; border-top-color: transparent; padding:5px; background-color: #fff;">
			<div class="tab-pane fade-in active" id="training">
				<?= DetailView::widget([
				'model' => $model,
				'attributes' => [
						[
							'label' => 'Satker',
							'value' => $satker,
						],
						'name',
						'description:ntext',
			            [
			            	'label' => 'Mulai',
			            	'value' => $start,
			            ],
			            [
			            	'label' => 'Akhir',
			            	'value' => $end,
			            ],
			            [ 
			            	'label' => 'Lokasi',
			            	'value' => $lokasi[0].'. '.$lokasi[1],
			            ],
			            [
			            	'label' => 'Diasramakan',
			            	'format' => 'raw',
			            	'value' => $diasramakan,
			            ],
			            [
			            	'label' => 'Status',
			            	'format' => 'raw',
			            	'value' => $status,
			            ],
			            [
			            	'label' => 'Dibuat Oleh',
			            	'value' => $dibuatOleh
			            ],
			            [
			            	'label' => 'Terakhir Diubah Oleh',
			            	'value' => $terakhirDiubahOleh
			            ],
					],
				]) ?>
			</div>
			<div class="tab-pane fade" id="program">
				<?= DetailView::widget([
				'model' => $program,
				'attributes' => [
						'number',
						'name',
						'hours',
						'days',
			            [
			            	'label' => 'Diujikan',
			            	'format' => 'raw',
			            	'value' => $program_diujikan,
			            ],
						'note',
						'stage',
						'category',
			            [
			            	'label' => 'Status Validasi',
			            	'format' => 'raw',
			            	'value' => $validation_status,
			            ],
						'validation_note',
			            [
			            	'label' => 'Status',
			            	'format' => 'raw',
			            	'value' => $program_status,
			            ],
			            [
			            	'label' => 'Dibuat',
			            	'value' => $program_dibuat
			            ],
			            [
			            	'label' => 'Dibuat Oleh',
			            	'value' => $program_dibuatOleh
			            ],
			            [
			            	'label' => 'Terakhir Diubah',
			            	'value' => $program_terakhirDiubah
			            ],
			            [
			            	'label' => 'Terakhir Diubah Oleh',
			            	'value' => $program_terakhirDiubahOleh
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
						'heading' => '<h3 class="panel-title"><i class="fa fa-fw fa-book"></i></h3>',
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
							'label' => 'Unduh Dokumen',
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
							'label' => 'Waktu Upload',
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
						'heading' => '<h3 class="panel-title"><i class="fa fa-fw fa-download"></i></h3>',
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
