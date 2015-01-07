<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use backend\models\Reference;
use backend\models\Activity;
use backend\models\Person;

/* @var $this yii\web\View */
/* @var $model backend\models\User */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = 'Update #'.$model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
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
$created = $namaHari[date('D', strtotime($model->created_at))].
                  date(', d ', strtotime($model->created_at)).
                  $namaBulan[date('F', strtotime($model->created_at))].
                  date(' Y', strtotime($model->created_at)).
                  ' - pukul '.
                  date('H:m', strtotime($model->created_at));
$modified = $namaHari[date('D', strtotime($model->updated_at))].
                  date(', d ', strtotime($model->updated_at)).
                  $namaBulan[date('F', strtotime($model->updated_at))].
                  date(' Y', strtotime($model->updated_at)).
                  ' - pukul '.
                  date('H:m', strtotime($model->updated_at));

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
// dah
?>
<div class="user-view  panel panel-default">

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
		            'username',
		            'auth_key',
		            'password_hash',
		            'password_reset_token',
		            'email:email',
		            // 'role',
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
                          'label' => 'Terakhir Diubah',
                          'value' => $modified
                    ],
				],
			]) ?>
	</div>
</div>
