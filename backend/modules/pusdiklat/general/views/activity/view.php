<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Inflector;

use backend\models\Reference;
use backend\models\Activity;
use backend\models\Person;

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = 'Informasi #'. Inflector::camel2words($model->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'BPPK_TEXT_TRAINING'), 'url' => ['index']];
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
        <?= (Yii::$app->request->isAjax)?'':Html::a('<i class="fa fa-fw fa-arrow-left"></i> '.Yii::t('app', 'SYSTEM_BUTTON_BACK'), ['index'], ['class' => 'btn btn-xs btn-primary']) ?>
		</div>
		<h1 class="panel-title"><?= Html::encode($this->title) ?></h1> 
	</div>
	<div class="panel-body">
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
</div>
