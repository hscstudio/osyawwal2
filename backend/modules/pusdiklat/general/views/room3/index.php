<?php

use yii\helpers\Html;
use yii\grid\GridView as Gridview2;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\pusdiklat\general\models\RoomSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
$this->title = Yii::t('app', 'BPPK_TEXT_ROOM');
$this->params['breadcrumbs'][] = $this->title;

$template = '<div class="btn-group">{view} {update} {delete}</div>';
if($satker_id!=(int)Yii::$app->user->identity->employee->satker_id){
	$template = '';
}
?>
<div class="room-index">
	<?php \yii\widgets\Pjax::begin([
		'id'=>'pjax-gridview',
	]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            
			[
				'attribute' => 'code',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'100px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],					
			],
		
			[
				'attribute' => 'name',
				'header' => '<div style="text-align:center;">Name</div>',
				'vAlign'=>'middle',
				'hAlign'=>'left',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],					
			],
		
			[
				'attribute' => 'capacity',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'100px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
			],
		
			[
				'attribute' => 'computer',
				'format' => 'raw',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'100px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function($model) {
					$computer_icons = [
						1=>'<i class="fa fa-fw fa-check-circle"></i>',
						0=>'<i class="fa fa-fw fa-times-circle"></i>'
					];
					$computer_classes = ['1'=>'success','0'=>'danger'];
					return Html::tag(
						'div',
						$computer_icons[$model->computer],
						[
							'class'=>'label label-'.$computer_classes[$model->computer],
						]
					);
				}
			],
		
			[
				'attribute' => 'hostel',
				'format' => 'raw',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'100px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function($model) {
					$hostel_icons = [
						'1'=>'<i class="fa fa-fw fa-check-circle"></i>',
						'0'=>'<i class="fa fa-fw fa-times-circle"></i>'
					];
					$hostel_classes = ['1'=>'success','0'=>'danger'];
					return Html::tag(
						'div',
						$hostel_icons[$model->hostel],
						[
							'class'=>'label label-'.$hostel_classes[$model->hostel],
						]
					);
				}
			],
			[
				'format' => 'raw',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'label' => 'Internal',
				'width'=>'80px',
				'value' => function ($data){
					if($data->satker_id==Yii::$app->user->identity->employee->satker_id){
						$countWaiting = \backend\models\ActivityRoom::find()
							->joinWith('activity')
							->where([
								'activity_room.status' =>  [0,1],
								'room_id' => \backend\models\Room::find()
									->where([
										'satker_id' => Yii::$app->user->identity->employee->satker_id,
										'status' => 1
									])
									->column()
							])
							->andWhere('satker_id='.Yii::$app->user->identity->employee->satker_id)
							->count();
						return Html::a($countWaiting, ['activity-room','id'=>$data->id], ['class' => 'label label-warning','data-pjax'=>'0']);
					}
					else{
						return '-';
					}
				}
			],
			[
				'format' => 'raw',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'label' => 'External',
				'width'=>'80px',
				'value' => function ($data) {
					
					$countWaiting = \backend\models\ActivityRoom::find()
						->joinWith('activity')
						->where([
							'activity_room.status' => [0,1],								
							'room_id' => \backend\models\Room::find()
								->where([
									'status' => [1]
								])
								->column()
						])
						->andWhere('satker_id!='.Yii::$app->user->identity->employee->satker_id)
						->count();
					
					return Html::a($countWaiting, ['activity-room','id'=>$data->id], ['class' => 'label label-warning','data-pjax'=>'0']);
					
				}
			],
            // 'computer',
            // 'hostel',
            // 'address',
            // 'status',
            // 'created',
            // 'created_by',
            // 'modified',
            // 'modified_by',

            [
			'class' => 'kartik\grid\ActionColumn',
			'template'=>$template,
			'width' => '120px',
				'buttons' => [
					'view' => function ($url, $model) {
								$icon='<span class="fa fa-fw fa-eye"></span>';
								return Html::a($icon,$url,[
									'class'=>'btn btn-default btn-xs modal-heart',
									'modal-title' => '<i class="fa fa-fw fa-eye"></i> Informasi '.$model->name,
									'data-pjax'=>'0',
									'modal-size' => 'modal-lg'
								]);
							},
					'update' => function ($url, $model) {
								$icon='<span class="fa fa-fw fa-pencil"></span>';
								return Html::a($icon,$url,[
									'class'=>'btn btn-default btn-xs modal-heart',
									'modal-title' => '<i class="fa fa-fw fa-pencil"></i> Ubah '.$model->name,
									'data-pjax'=>'0',
									'modal-size' => 'modal-lg'
								]);
							},
					'delete' => function ($url, $model) {
								$icon='<span class="fa fa-fw fa-trash-o"></span>';
								return Html::a($icon,$url,[
									'class'=>'btn btn-default btn-xs',
									'data-pjax'=>'0',
									'data-confirm' => 'Yakin ingin menghapus?',
									'data-method' => 'post'
								]);
							},
				],
			],
        ],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> '.Html::encode($this->title).'</h3>',
			'before'=>
				Html::a('<i class="fa fa-fw fa-plus"></i> '.Yii::t('app', 'SYSTEM_BUTTON_CREATE'), ['create'], [
					'class' => 'btn btn-success modal-heart',
					'modal-title' => '<i class="fa fa-fw fa-plus-circle"></i>'.Yii::t('app', 'SYSTEM_BUTTON_CREATE'),
					'data-pjax' => '0',
				]). ' '.
				'<div class="pull-right" style="margin-right:5px;">'.
				Select2::widget([
					'name' => 'status', 
					'data' => ['1'=>'Published','0'=>'Unpublished','all'=>'-- All --'],
					'value' => $status,
					'options' => [
						'placeholder' => 'Status ...', 
						'class'=>'form-control input-medium', 
						'onchange'=>'
							$.pjax.reload({
								url: "'.\yii\helpers\Url::to(['index']).'?satker_id='.$satker_id.'&status="+$(this).val(), 
								container: "#pjax-gridview", 
								timeout: 1,
							});
						',	
					],
				]).
				'</div>'.
				'<div class="pull-right" style="margin-right:5px;" id="div-select2-satker">'.
				Select2::widget([
					'name' => 'satker_id', 
					'data' => $satkers,
					'value' => $satker_id,
					'options' => [
						'width'=> 'resolve',
						'placeholder' => 'Satker ...', 
						'class'=>'form-control ', 
						'onchange'=>'
							$.pjax.reload({
								url: "'.\yii\helpers\Url::to(['index']).'?status='.$status.'&satker_id="+$(this).val(), 
								container: "#pjax-gridview", 
								timeout: 1,
							});
						',	
					],
				]).
				'</div>',
			'after'=>Html::a('<i class="fa fa-fw fa-repeat"></i> '.Yii::t('app', 'SYSTEM_BUTTON_RESET_GRID'), Url::to(''), ['class' => 'btn btn-info']),
			'showFooter'=>false
		],
		'responsive'=>true,
		'hover'=>true,
    ]); ?>
	<?= \hscstudio\heart\widgets\Modal::widget(['modalSize'=>'modal-lg']); ?>
	<?php 
	$this->registerCss('#div-select2-satker .select2-container { width: 275px !important; }');
	?>
	<?php \yii\widgets\Pjax::end(); ?>
</div>
