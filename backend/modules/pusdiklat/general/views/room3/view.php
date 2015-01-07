<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use backend\models\Reference;
use backend\models\Activity;
use backend\models\Person;

/* @var $this yii\web\View */
/* @var $model backend\models\Room */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = 'Update #'.$model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Rooms'), 'url' => ['index']];
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

$computer_icons = [
	1=>'<i class="fa fa-fw fa-check-circle"></i>',
	0=>'<i class="fa fa-fw fa-times-circle"></i>'
];
$computer_classes = ['1'=>'success','0'=>'danger'];
$computer =  Html::tag(
	'div',
	$computer_icons[$model->computer],
	[
		'class'=>'label label-'.$computer_classes[$model->computer],
	]
);

$hostel_icons = [
	1=>'<i class="fa fa-fw fa-check-circle"></i>',
	0=>'<i class="fa fa-fw fa-times-circle"></i>'
];
$hostel_classes = ['1'=>'success','0'=>'danger'];
$hostel =  Html::tag(
	'div',
	$hostel_icons[$model->hostel],
	[
		'class'=>'label label-'.$hostel_classes[$model->hostel],
	]
);

$owner_icons = [
	1=>'<i class="fa fa-fw fa-check-circle"></i>',
	0=>'<i class="fa fa-fw fa-times-circle"></i>'
];
$owner_classes = ['1'=>'success','0'=>'danger'];
$owner =  Html::tag(
	'div',
	$owner_icons[$model->owner],
	[
		'class'=>'label label-'.$owner_classes[$model->owner],
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
<div class="room-view  panel panel-default">

   <div class="panel-heading"> 
		<div class="pull-right">
        <?=
 Html::a('<i class="fa fa-fw fa-arrow-left"></i> Back', ['index'], ['class' => 'btn btn-xs btn-primary']) ?>
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
		            'code',
		            'name',
		            'capacity',
		            [
		            	'label' => Yii::t('app', 'BPPK_TEXT_OWNER'),
		            	'format' => 'raw',
		            	'value' => $owner
		            ],
		            [
		            	'label' => Yii::t('app', 'BPPK_TEXT_COMPUTER'),
		            	'format' => 'raw',
		            	'value' => $computer
		            ],
		            [
		            	'label' => Yii::t('app', 'BPPK_TEXT_HOSTEL'),
		            	'format' => 'raw',
		            	'value' => $hostel
		            ],
		            'address',
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
