<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap\Dropdown;

/* @var $searchModel backend\models\TestingSearch */

$this->title = 'Testings';
$this->params['breadcrumbs'][] = $this->title;

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
?>
<div class="testing-index">

    <?php
	$abjad = hscstudio\heart\helpers\Heart::abjad(1,1000);
	echo hscstudio\heart\helpers\Heart::terbilang(100000000000);
	echo hscstudio\heart\helpers\Heart::twodate('','',1);
	
	use hscstudio\heart\widgets\FullCalendar; 
	use yii\helpers\Url;
	
	echo FullCalendar::widget([
		'options'=>[
			'id'=>'calendar',
			'header'=>[
				'left'=>'prevYear,prev,next,nextYear today',
				'center'=>'title',
				'right'=>'month,agendaWeek,agendaDay',
			],			
			'editable'=> false,
			'eventLimit'=>true, // allow "more" link when too many events
			'eventClick'=> new yii\web\JsExpression('function(calEvent, jsEvent, view){alert(1)}'),
			'events'=> Url::to(['events']),
		],
    ]);
	?>

</div>
