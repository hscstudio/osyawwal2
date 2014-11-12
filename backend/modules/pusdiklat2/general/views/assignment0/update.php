<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\User */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'User',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="user-update panel panel-default">
	
    <div class="panel-heading">		
		<div class="pull-right">
        <?=
 Html::a('<i class="fa fa-fw fa-arrow-left"></i> Back', ['index'], ['class' => 'btn btn-xs btn-primary']) ?>
		</div>
		<h1 class="panel-title"><?= Html::encode($this->title) ?></h1>
	</div>
	<div class="panel-body">
		<?php
		$position = 'Pelaksana';
		if (@$model->employee->chairman>0){
			$position = @$model->employee->person->position;
		}
		?>
		<table class="table table-striped table-condensed">
		<tr>
			<td>Nama </td><td>:</td><td><?= @$model->employee->person->name; ?></td>
		</tr>
		<tr>
			<td>Jabatan </td><td>:</td><td><?= $position; ?> </td>
		</tr>
		<tr>
			<td>Bagian/Bidang </td><td>:</td><td><?= @$model->employee->organisation->NM_UNIT_ORG; ?> </td>
		</tr>
		</table>
		<?= $this->render('_form', [
			'model' => $model,
		]) ?>
	</div>
</div>
