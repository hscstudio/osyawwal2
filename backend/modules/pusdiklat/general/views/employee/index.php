<?php

use yii\helpers\Html;
use yii\grid\GridView as Gridview2;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\EmployeeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
$this->title = Yii::t('app', 'BPPK_TEXT_EMPLOYEE');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-index">
	
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
				'format' => 'raw',				
				'value' => function ($data){
					$person = \backend\models\Person::findOne($data->person_id);
					if(null!=$person){
						return Html::a($person->name,['./person/view','id'=>$person->id],
							['class'=>'modal-heart badge','title'=>$person->name,'source'=>'div.panel-body','data-toggle'=>'tooltip']);
					}							
				}				
			],
		
			[
				'attribute' => 'user_id',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],	
				'format' => 'raw',				
				'value' => function ($data){
					$user = \backend\models\User::findOne($data->user_id);
					if(null!=$user){
						return Html::a($user->username,['./user/view','id'=>$user->id],
							['class'=>'modal-heart badge','title'=>$user->username,'source'=>'div.panel-body','data-toggle'=>'tooltip']);
					}
				}
			],
			
			[
				'attribute' => 'satker_id',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],	
				'format' => 'raw',				
				'value' => function ($data){
					$satker = \backend\models\Reference::find()
						->where([
							'type'=>'satker',
							'id'=>$data->satker_id,
						])
						->one();
					if(null!=$satker){
						return Html::a($satker->name,'#',
							['class'=>'badge','title'=>$satker->value,'data-toggle'=>'tooltip']);
					}
				}
			],

            [
				'class' => 'kartik\grid\ActionColumn',
				'template' => '<div class="btn-group">{view} {update} {delete}</div>',
				'width' => '120px',
				'buttons' => [
					'view' => function ($url, $model) {
								$icon='<span class="fa fa-fw fa-eye"></span>';
								return Html::a($icon,$url,[
									'class'=>'btn btn-default btn-xs modal-heart',
									'modal-title' => '<i class="fa fa-fw fa-eye"></i> Informasi '.$model->person->name,
									'data-pjax'=>'0',
								]);
							},
					'update' => function ($url, $model) {
								$icon='<span class="fa fa-fw fa-pencil"></span>';
								return Html::a($icon,$url,[
									'class'=>'btn btn-default btn-xs modal-heart',
									'modal-title' => '<i class="fa fa-fw fa-pencil"></i> Ubah '.$model->person->name,
									'data-pjax'=>'0',
									'modal-size' => 'modal-lg'
								]);
							},
					'delete' => function ($url, $model) {
								$icon='<span class="fa fa-fw fa-trash-o"></span>';
								return Html::a($icon,$url,[
									'class'=>'btn btn-default btn-xs',
									'data-pjax'=>'0',
									'data-method' => 'post'
								]);
							},
				],
			],
        ],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> '.Html::encode($this->title).'</h3>',
			'before'=>' ',
			'after'=>Html::a('<i class="fa fa-fw fa-repeat"></i> '.Yii::t('app', 'SYSTEM_BUTTON_RESET_GRID'), Url::to(''), ['class' => 'btn btn-info']),
			'showFooter'=>false
		],
		'responsive'=>true,
		'hover'=>true,
    ]); ?>
	<?= \hscstudio\heart\widgets\Modal::widget(['modalSize'=>'modal-lg']) ?>
</div>
