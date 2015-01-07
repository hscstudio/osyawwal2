<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use backend\models\Reference;
use backend\models\Activity;
use backend\models\Person;

/* @var $this yii\web\View */
/* @var $model backend\models\Person */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = 'Informasi '.$model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'People'), 'url' => ['index']];
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

$birthday = $namaHari[date('D', strtotime($model->birthday))].
                  date(', d ', strtotime($model->birthday)).
                  $namaBulan[date('F', strtotime($model->birthday))].
                  date(' Y', strtotime($model->birthday)).
                  ' - pukul '.
                  date('H:m', strtotime($model->birthday));
$created = $namaHari[date('D', strtotime($model->created))].
                  date(', d ', strtotime($model->created)).
                  $namaBulan[date('F', strtotime($model->created))].
                  date(' Y', strtotime($model->created)).
                  ' - pukul '.
                  date('H:m', strtotime($model->created));
$modified = $namaHari[date('D', strtotime($model->modified))].
                  date(', d ', strtotime($model->modified)).
                  $namaBulan[date('F', strtotime($model->modified))].
                  date(' Y', strtotime($model->modified)).
                  ' - pukul '.
                  date('H:m', strtotime($model->modified));

$jabatan_icons = [
      '1'=>'Eselon 1',
      '2'=>'Eselon 2',
      '3'=>'Eselon 3',
      '4'=>'Eselon 4',
      '5'=>'Pelaksana'
];
if ($model->position != null) {
      $jabatan = Html::tag(
            'div',
            $jabatan_icons[$model->position]
      );
}
else {
      $jabatan = '-';
}

$gender_icons = [
      '1'=>'Wanita',
      '0'=>'Pria',
];
$gender = Html::tag(
      'div',
      $gender_icons[$model->gender]
);

$married_icons = [
      '1'=>'Menikah',
      '0'=>'Tidak',
];
$married = Html::tag(
      'div',
      $married_icons[$model->married]
);

$status_icons = [
      '0'=>'<i class="fa fa-fw fa-fire"></i> Banned',
      '1'=>'<i class="fa fa-fw fa-refresh"></i> Aktif',
];
$status_classes = ['0'=>'warning','1'=>'info','2'=>'success','3'=>'danger'];
$status = Html::tag(
      'div',
      $status_icons[$model->status],
      [
            'class'=>'label label-'.$status_classes[$model->status],
      ]
);

if ($model->created_by != null) {
      $dibuatOleh = Person::findOne($model->created_by)->name; // baikin ya :DDDD
}
else {
      $dibuatOleh = '-';
}

if ($model->modified_by != null) {
      $terakhirDiubahOleh = Person::findOne($model->modified_by)->name; // baikin ya :DDDD
}
else {
      $terakhirDiubahOleh = '-';
}
// dah

?>
<div class="person-view  panel panel-default">

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
                        'nip',
                        'name',
                        'nickname',
                        'front_title',
                        'back_title',
                        'nid',
                        'npwp',
                        'born',
                        [
                              'label' => Yii::t('app', 'BPPK_TEXT_BIRTHDAY'),
                              'value' => $birthday
                        ],
                        [
                              'label' => Yii::t('app', 'BPPK_TEXT_GENDER'),
                              'format' => 'raw',
                              'value' => $gender
                        ],
                        'phone',
                        'email:email',
                        'homepage',
                        'address',
                        'office_phone',
                        'office_fax',
                        'office_email:email',
                        'office_address',
                        'bank_account',
                        [
                              'label' => Yii::t('app', 'BPPK_TEXT_MARRIED'),
                              'format' => 'raw',
                              'value' => $married
                        ],
                        'blood',
                        'graduate_desc',
                        [
                              'label' => 'Jabatan',
                              'format' => 'raw',
                              'value' => $jabatan,
                        ],
                        'position_desc',
                        'organisation',
                        [
                              'label' => 'Status',
                              'format' => 'raw',
                              'value' => $status,
                        ],
                        [
                              'label' => 'Dibuat',
                              'value' => $created
                        ],
                        [
                              'label' => 'Dibuat Oleh',
                              'value' => $dibuatOleh
                        ],
                        [
                              'label' => 'Terakhir Diubah',
                              'value' => $modified
                        ],
                        [
                              'label' => 'Terakhir Diubah Oleh',
                              'value' => $terakhirDiubahOleh
                        ],
			],
		]) ?>
	</div>
</div>
