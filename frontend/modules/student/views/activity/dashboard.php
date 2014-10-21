<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Inflector;
use hscstudio\heart\widgets\Box;
/* @var $this yii\web\View */
/* @var $model backend\models\Activity */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = 'Dashboard #'. Inflector::camel2words($model->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Training Activities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="activity-view  panel panel-default">

   <div class="panel-heading"> 
		<div class="pull-right">
        <?= (Yii::$app->request->isAjax)?'':Html::a('<i class="fa fa-fw fa-arrow-left"></i> Back', ['index'], ['class' => 'btn btn-xs btn-primary']) ?>
		</div>
		<h1 class="panel-title"><?= Html::encode($this->title) ?></h1> 
	</div>
	<div class="panel-body">
		<div class="row clearfix">
			<div class="col-md-3">
			<?php
			Box::begin([
				'type'=>'small', // ,small, solid, tiles
				'bgColor'=>'red', // , aqua, green, yellow, red, blue, purple, teal, maroon, navy, light-blue
				'bodyOptions' => [],
				'icon' => 'fa fa-fw fa-cutlery',
				'link' => ['property','training_id'=>\hscstudio\heart\helpers\Kalkun::AsciiToHex(base64_encode($model->id))],
				'footerOptions' => [
					'class' => 'dashboard-hide',
				],
				'footer' => 'More info <i class="fa fa-arrow-circle-right"></i>',
			]);
			?>
			<h3>Property</h3>
			<p>Property of Training</p>
			<?php
			Box::end();
			?>
			</div>
			<div class="col-md-3">
			<?php
			Box::begin([
				'type'=>'small', // ,small, solid, tiles
				'bgColor'=>'yellow', // , aqua, green, yellow, red, blue, purple, teal, maroon, navy, light-blue
				'bodyOptions' => [],
				'icon' => 'glyphicon glyphicon-home',
				'link' => ['class','training_id'=>\hscstudio\heart\helpers\Kalkun::AsciiToHex(base64_encode($model->id))],
				'footerOptions' => [
					'class' => 'dashboard-hide',
				],
				'footer' => 'More info <i class="fa fa-arrow-circle-right"></i>',
			]);
			?>
			<h3>Class</h3>
			<p>Class of Training</p>
			<?php
			Box::end();
			?>
			</div>
			<div class="col-md-3">
			<?php
			Box::begin([
				'type'=>'small', // ,small, solid, tiles
				'bgColor'=>'green', // , aqua, green, yellow, red, blue, purple, teal, maroon, navy, light-blue
				'bodyOptions' => [],
				'icon' => 'fa fa-fw fa-user-md',
				'link' => ['student','training_id'=>\hscstudio\heart\helpers\Kalkun::AsciiToHex(base64_encode($model->id))],
				'footerOptions' => [
					'class' => 'dashboard-hide',
				],
				'footer' => 'More info <i class="fa fa-arrow-circle-right"></i>',
			]);
			?>
			<h3>Student</h3>
			<p>Student of Training</p>
			<?php
			Box::end();
			?>
			</div>
            <div class="col-md-3">
			<?php
			Box::begin([
				'type'=>'small', // ,small, solid, tiles
				'bgColor'=>'blue', // , aqua, green, yellow, red, blue, purple, teal, maroon, navy, light-blue
				'bodyOptions' => [],
				'icon' => 'fa fa-fw fa-graduation-cap',
				'link' => ['./training-schedule-trainer/index','training_id'=>\hscstudio\heart\helpers\Kalkun::AsciiToHex(base64_encode($model->id)),'training_student_id'=>$training_student_id],
				'footerOptions' => [
					'class' => 'dashboard-hide',
				],
				'footer' => 'More info <i class="fa fa-arrow-circle-right"></i>',
			]);
			?>
			<h3>Trainer</h3>
			<p>Evaluation Trainer of Training</p>
			<?php
			Box::end();
			?>
			</div>
             <div class="col-md-3">
			<?php
			Box::begin([
				'type'=>'small', // ,small, solid, tiles
				'bgColor'=>'purple', // , aqua, green, yellow, red, blue, purple, teal, maroon, navy, light-blue
				'bodyOptions' => [],
				'icon' => 'fa fa-fw fa-building-o',
				'link' => ['./training-execution-evaluation','training_id'=>\hscstudio\heart\helpers\Kalkun::AsciiToHex(base64_encode($model->id))],
				'footerOptions' => [
					'class' => 'dashboard-hide',
				],
				'footer' => 'More info <i class="fa fa-arrow-circle-right"></i>',
			]);
			?>
			<h3>Execution</h3>
			<p>Evaluation Execution of Training</p>
			<?php
			Box::end();
			?>
			</div>
		</div>		
	</div>
</div>
