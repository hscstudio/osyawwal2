<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Student */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = 'Update #'.$model->person_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Students'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// ngeformat
$status_icon = [
	0 => '<i class="fa fa-fw fa-ban"></i> Diblokir',
	1 => '<i class="fa fa-fw fa-check-circle"></i> Aktif'
];
$status_class = [
	0 => 'danger',
	1 => 'success'
];
$status = '<div class="label label-'.$status_class[$model->status].'">'.$status_icon[$model->status].'</div>';
// dah

?>
<div class="student-view  panel panel-default">

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
		            'person_id',
		            'username',
		            'password_hash',
		            'auth_key',
		            'password_reset_token',
		            'eselon2',
		            'eselon3',
		            'eselon4',
		            'satker',
		            'no_sk',
		            'tmt_sk',
		            [
		            	'label' => 'Status',
		            	'format' => 'raw',
		            	'value' => $status
		            ]
				],
			]) ?>
	</div>
</div>
