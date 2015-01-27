<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Inflector;
use kartik\grid\GridView;

use backend\models\Reference;
use backend\models\Activity;
use backend\models\Person;

/* @var $this yii\web\View */
/* @var $model backend\models\Activity */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = 'Perbarui '. Inflector::camel2words($model->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'BPPK_TEXT_MEETING'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

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

$dibuatOleh = Person::findOne($model->created_by)->name; // baikin ya :DDDD

$terakhirDiubahOleh = Person::findOne($model->modified_by)->name; // baikin ya :DDDD
// dah

?>
<div class="activity-view  panel panel-default">

   <div class="panel-heading"> 
		<div class="pull-right">
        <?= (Yii::$app->request->isAjax)?'':Html::a('<i class="fa fa-fw fa-arrow-left"></i> Back', ['index'], ['class' => 'btn btn-xs btn-primary']) ?>
		</div>
		<h1 class="panel-title"><?= Html::encode($this->title) ?></h1> 
	</div>
	<div class="panel-body">
		<ul class="nav nav-tabs" role="tablist" id="tab_wizard">
			<li class="active"><a href="#property" role="tab" data-toggle="tab">Info Umum <span class='label label-info'>1</span></a></li>
			<li class=""><a href="#history" role="tab" data-toggle="tab">Riwayat Revisi <span class='label label-warning'>2</span></a></li>
		</ul>
		<div class="tab-content" style="border: 1px solid #ddd; border-top-color: transparent; padding:10px; background-color: #fff;">
			<div class="tab-pane fade-in active" id="property">
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
			<div class="tab-pane fade" id="history">
				<?= GridView::widget([
					'dataProvider' => $dataProvider,
					'columns' => [
						'revision',
						[
							'attribute' => 'name',
							'vAlign'=>'middle',
							'hAlign'=>'left',
							'headerOptions'=>['class'=>'kv-sticky-column'],
							'contentOptions'=>['class'=>'kv-sticky-column'],	
							'format'=>'raw',
							'value' => function ($data){
								return Html::a($data->name,'#',[
									'title'=>$data->description.
									'<br>'.$data->location.
									'<br>'.$data->hostel,
									'data-toggle'=>"tooltip",
									'data-placement'=>"top",
									'data-html'=>'true',
								]);
							},
						],            
						[
							'attribute' => 'start',
							'vAlign'=>'middle',
							'hAlign'=>'center',
							'headerOptions'=>['class'=>'kv-sticky-column'],
							'contentOptions'=>['class'=>'kv-sticky-column'],
							'width'=>'100px',
							'format'=>'raw',
							'value' => function ($data){
								return Html::tag('span',date('d M Y',strtotime($data->start)),[
									'class'=>'label label-info',
								]);
							},
						],
					
						[
							'attribute' => 'end',
							'vAlign'=>'middle',
							'hAlign'=>'center',
							'headerOptions'=>['class'=>'kv-sticky-column'],
							'contentOptions'=>['class'=>'kv-sticky-column'],
							'width'=>'100px',
							'format'=>'raw',
							'value' => function ($data){
								return Html::tag('span',date('d M Y',strtotime($data->end)),[
									'class'=>'label label-info',
								]);
							},
						],
						[
							'label' => 'Hadirin',
							'vAlign'=>'left',
							'hAlign'=>'center',
							'width' => '75px',
							'headerOptions'=>['class'=>'kv-sticky-column'],
							'contentOptions'=>['class'=>'kv-sticky-column'],
							'format'=>'raw',
							'value' => function ($data){
								return Html::a($data->meeting->attendance_count_plan,'#',
									[
										'class'=>'label label-primary',
										'data-pjax'=>'0',
										'data-toggle'=>'tooltip',
										'title' => 'Click to view student spread plan',
									]);
							},
						],
						
						[
							'format' => 'raw',
							'label' => 'PIC',
							'vAlign'=>'middle',
							'hAlign'=>'center',
							'width'=>'70px',
							'headerOptions'=>['class'=>'kv-sticky-column'],
							'contentOptions'=>['class'=>'kv-sticky-column'],
							'value' => function ($data){
								$object_person=\backend\models\ObjectPerson::find()
									->where([
										'object'=>'activity',
										'object_id'=>$data->id,
										'type'=>'organisation_1213020100', // CEK KD_UNIT_ORG 1213020100 IN TABLE ORGANISATION IS SUBBIDANG PROGRAM
									])
									->one();
								
								$options = [
											'class'=>'label label-info',
											'data-toggle'=>'tooltip',
											'data-pjax'=>'0',
											'data-html'=>'true',
											'title'=>($object_person!=null)?'PIC Program <br> '.$object_person->person->name.'':'PIC tidak tersedia',
										];
								$person_name = ($object_person!=null)?substr($object_person->person->name,0,5).'.':'-';
								return Html::tag('span',$person_name,$options);
							}
						],
						
						[
							'attribute' => 'status',
							'filter' => false,
							'label' => 'Status',
							'vAlign'=>'middle',
							'hAlign'=>'center',
							'width'=>'75px',
							'headerOptions'=>['class'=>'kv-sticky-column'],
							'contentOptions'=>['class'=>'kv-sticky-column'],
							'format'=>'raw',
							'value' => function ($data){
								
								$status_icons = [
									'0'=>'<i class="fa fa-fw fa-fire"></i> Rencana',
									'1'=>'<i class="fa fa-fw fa-refresh"></i> Siap',
									'2'=>'<i class="fa fa-fw fa-check-circle"></i> Berjalan',
									'3'=>'<i class="fa fa-fw fa-times-circle"></i> Batal'
								];
								$status_classes = ['0'=>'warning','1'=>'info','2'=>'success','3'=>'danger'];
								$status_title = ['0'=>'Rencana','1'=>'Siap','2'=>'Berjalan','3'=>'Batal'];
								return Html::tag(
									'span',
									$status_icons[$data->status],
									[
										'class'=>'label label-'.$status_classes[$data->status],
										'data-toggle'=>'tooltip',
										'data-pjax'=>'0',
										'title'=>$status_title[$data->status],
									]
								);
							},
						],
						[
							'attribute' => 'modified',
							'vAlign'=>'middle',
							'hAlign'=>'center',
							'headerOptions'=>['class'=>'kv-sticky-column'],
							'contentOptions'=>['class'=>'kv-sticky-column'],
							'width'=>'140px',
							'format'=>'raw',
							'value' => function ($data){
								return Html::tag('span',date('d M Y H:i:s',strtotime($data->modified)),[
									'class'=>'label label-info',
								]);
							},
						],
					]
				])
				?>
			</div>
		</div>
	</div>
</div>
