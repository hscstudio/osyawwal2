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
					'icon' => 'glyphicon glyphicon-eye-open',
					'link' => ['property','id'=>$model->id],
					'footerOptions' => [
						'class' => 'dashboard-hide',
					],
					'footer' => 'Masuk <i class="fa fa-arrow-circle-right"></i>',
				]);
				?>
				<h3>Informasi</h3>
				<p>Lihat semua data pada diklat ini, termasuk dokumen dan mata diklat</p>
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
					'link' => ['student','id'=>$model->id],
					'footerOptions' => [
						'class' => 'dashboard-hide',
					],
					'footer' => 'Masuk <i class="fa fa-arrow-circle-right"></i>',
				]);
				?>
				<h3>Peserta</h3>
				<p>Input data peserta untuk dikumpulkan dahulu sebelum input Kelas</p>
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
					'link' => ['class','id'=>$model->id],
					'footerOptions' => [
						'class' => 'dashboard-hide',
					],
					'footer' => 'Masuk <i class="fa fa-arrow-circle-right"></i>',
				]);
				?>
				<h3>Kelas</h3>
				<p>Kelola kelas, jadwal, dan assign pengajar ke jadwal. Semua pada fitur ini</p>
				<?php
				Box::end();
				?>
			</div>
			
			<div class="col-md-3">
			<?php
			Box::begin([
				'type'=>'small', // ,small, solid, tiles
				'bgColor'=>'purple', // , aqua, green, yellow, red, blue, purple, teal, maroon, navy, light-blue
				'bodyOptions' => [
					'onclick'=>'alert()',
				],
				'icon' => 'fa fa-fw fa-money',
				'link' => ['honorarium','id'=>$model->id],
				'footerOptions' => [
					'class' => 'dashboard-hide',
				],
				'footer' => 'Masuk <i class="fa fa-arrow-circle-right"></i>',
			]);
			?>
			<h3>Honor</h3>
			<p>Kelola honorarium untuk setiap pengajar dan cetak dokumennya</p>
			<?php
			Box::end();
			?>
			</div>
		</div>		
	</div>
</div>
