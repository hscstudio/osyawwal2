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

$this->title = 'Update #'.Inflector::camel2words($model->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Programs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
// Ngeformat
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

$dibuat = $namaHari[date('D', strtotime($model->created))].
			date(', d ', strtotime($model->created)).
			$namaBulan[date('F', strtotime($model->created))].
			date(' Y', strtotime($model->created)).
			' - pukul '.
			date('H:i', strtotime($model->created));
$terakhirDiubah = $namaHari[date('D', strtotime($model->modified))].
			date(', d ', strtotime($model->modified)).
			$namaBulan[date('F', strtotime($model->modified))].
			date(' Y', strtotime($model->modified)).
			' - pukul '.
			date('H:i', strtotime($model->modified));

$dibuatOleh = Person::findOne($model->created_by)->name; // baikin ya :DDDD

$terakhirDiubahOleh = Person::findOne($model->modified_by)->name; // baikin ya :DDDD

$test_icons = [
	1=>'<i class="fa fa-fw fa-check-circle"></i>',
	0=>'<i class="fa fa-fw fa-times-circle"></i>'
];
$test_classes = ['1'=>'success','0'=>'danger'];
$test =  Html::tag(
	'div',
	$test_icons[$model->test],
	[
		'class'=>'label label-'.$test_classes[$model->test],
	]
);

$status_icons = [
	1=>'<i class="fa fa-fw fa-check-circle"></i> Aktif',
	0=>'<i class="fa fa-fw fa-times-circle"></i> Tidak Aktif'
];
$status_classes = ['1'=>'success','0'=>'danger'];
$status =  Html::tag(
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
	$validation_status_icons[$model->validation_status],
	[
		'class'=>'label label-'.$validation_status_classes[$model->validation_status],
	]
);


$category_status_icons = [
	'0'=>'Belum di pilih',
	'1'=>'Dasar',
	'2'=>'Lanjutan',
	'3'=>'Menengah',
	'4'=>'Tinggi'
];
$category_status = Html::tag(
	'div',
	$category_status_icons[$model->category]
);
// dah

?>
<div class="program-view  panel panel-default">

   <div class="panel-heading"> 
		<div class="pull-right">
        <?= (Yii::$app->request->isAjax)?'':Html::a('<i class="fa fa-fw fa-arrow-left"></i> Back', ['index'], ['class' => 'btn btn-xs btn-primary']) ?>
		</div>
		<h1 class="panel-title"><?= Html::encode($this->title) ?></h1> 
	</div>
	<div class="panel-body">
			<?= DetailView::widget([
				'model' => $model,
				'attributes' => [
		            'number',
		            'name',
		            'hours',
		            'days',
		            [
		            	'label' => Yii::t('app', 'BPPK_TEXT_TEST'),
		            	'format' => 'raw',
		            	'value' => $test
		            ],
		            'note',
		            'stage',
		            [
		            	'label' => 'Kategori',
		            	'format' => 'raw',
		            	'value' => $category_status,
		            ],
		            [
		            	'label' => 'Status Validasi',
		            	'format' => 'raw',
		            	'value' => $validation_status,
		            ],
		            'validation_note',
		            [
		            	'label' => 'Status',
		            	'format' => 'raw',
		            	'value' => $status,
		            ],
		            [
		            	'label' => 'Dibuat',
		            	'value' => $dibuat
		            ],
		            [
		            	'label' => 'Dibuat Oleh',
		            	'value' => $dibuatOleh
		            ],
		            [
		            	'label' => 'Terakhir Diubah',
		            	'value' => $terakhirDiubah
		            ],
		            [
		            	'label' => 'Terakhir Diubah Oleh',
		            	'value' => $terakhirDiubahOleh
		            ],
				],
			]) ?>
	</div>
</div>
