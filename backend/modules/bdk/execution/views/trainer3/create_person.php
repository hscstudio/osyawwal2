<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Person */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Person',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Trainers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="person-create  panel panel-default">

    <div class="panel-heading"> 
		<div class="pull-right">
        <?=
 Html::a('<i class="fa fa-fw fa-arrow-left"></i> Back', ['index'], ['class' => 'btn btn-xs btn-primary']) ?>
		</div>
		<h1 class="panel-title"><?= Html::encode($this->title) ?></h1> 
	</div>
	<div class="panel-body">
		<?php
		$renders = [];
		$renders['model'] = $model;
		$renders['trainer'] = $trainer;
		$renders['object_references_array'] = $object_references_array;
		foreach($object_references_array as $object_reference=>$label){;
			$renders[$object_reference] = ${$object_reference};
		}
		$renders['object_file_array'] = $object_file_array;
		foreach($object_file_array as $object_file=>$label){
			$renders[$object_file] = ${$object_file};
			$renders[$object_file.'_file'] = ${$object_file.'_file'};
		}
		?>
		<?= $this->render('_form_person', $renders) ?>
	</div>
</div>
