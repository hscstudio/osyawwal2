<?php

use yii\helpers\Html;
use yii\grid\GridView as Gridview2;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\pusdiklat\execution\models\StudentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
$this->title = Yii::t('app', 'BPPK_TEXT_STUDENT');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="student-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
			[
				'attribute' => 'person_id',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value'=>function ($data){
					return $data->person->name;
				}
			],            
			[
				'attribute' => 'username',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],					
			],
            [
				'attribute' => 'satker',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value'=>function ($data){
					$satker = "";
					$es[2] = $data->eselon2;
					$es[3] = $data->eselon3;
					$es[4] = $data->eselon4;
					if(isset($es[$data->satker])) $satker = $es[$data->satker];
					return $satker;
				}				
			],
				
            // 'eselon4',
            // 'satker',
            // 'no_sk',
            // 'tmt_sk',
            // 'status',

            [
				'class' => 'kartik\grid\ActionColumn',
				'width' => '125px',
				'template' => '<div class="btn-group">{view} {update} {delete}</div>',
				'buttons' => [
					'view' => function($url,$model,$key){
						$icon = '<i class="fa fa-fw fa-eye"></i>';
						return Html::a(
							$icon,
							['view','id'=>$model->person_id],
							[
								'class'=>'btn btn-default btn-xs modal-heart',
								'modal-title' => '<i class="fa fa-fw fa-eye"></i> Informasi '.$model->person->name,
								'data-pjax'=>'0',
								'data-toggle'=>'tooltip',
								'data-container' => 'body',
								'title'=>'Informasi',
							]
						);							
					},
					'update' => function($url,$model,$key){
						$icon = '<i class="fa fa-fw fa-pencil"></i>';
						return Html::a(
							$icon,
							['update','id'=>$model->person_id],
							[
								'class'=>'btn btn-default btn-xs',
								'title'=>'Ubah',
								'data-pjax'=>'0',
								'data-toggle'=>'tooltip',
								'data-container' => 'body',
								'modal-size' => 'modal-lg'
							]
						);							
					},
					'delete' => function($url,$model,$key){
						$icon = '<i class="fa fa-fw fa-trash"></i>';
						return Html::a(
							$icon,
							$url,
							[
								'class'=>'btn btn-default btn-xs',
								'title'=>'Hapus',
								'data-pjax'=>'0',
								'data-toggle'=>'tooltip',
								'data-container' => 'body',
								'data-method'=>'post',
								'data-confirm'=>'Apakah Anda yakin akan menghapus pengajar ini!'
							]
						);	
					}
				]
			],
        ],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> Daftar '.Html::encode($this->title).'</h3>',
			'before'=>Html::a('<i class="fa fa-fw fa-plus"></i> '.Yii::t('app', 'SYSTEM_BUTTON_CREATE'), ['person'], ['class' => 'btn btn-success']),
			'after'=>Html::a('<i class="fa fa-fw fa-repeat"></i> '.Yii::t('app', 'SYSTEM_BUTTON_RESET_GRID'), Url::to(''), ['class' => 'btn btn-info']),
			'showFooter'=>false
		],
		'responsive'=>true,
		'hover'=>true,
    ]); ?>

</div>
