<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap\Dropdown;
use kartik\widgets\Select2;
use kartik\widgets\AlertBlock;

/* @var $searchModel backend\models\RoomSearch */

$this->title = $activity->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'BPPK_TEXT_MEETING'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
?>
<div class="room-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
	<?php \yii\widgets\Pjax::begin([
		'id'=>'pjax-gridview-room',
	]); ?>
	<?php 
	if (Yii::$app->request->isAjax){	
		echo AlertBlock::widget([
			'useSessionFlash' => true,
			'type' => AlertBlock::TYPE_ALERT
		]); 
	}
	?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
			[
				'attribute' => 'code',
				'vAlign'=>'middle',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
			],            
			[
				'attribute' => 'name',
				'vAlign'=>'middle',
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
				//'editableOptions'=>['header'=>'Capacity', 'size'=>'md','formOptions'=>['action'=>\yii\helpers\Url::to('editable')]]
			],
		
			[
				
				'attribute' => 'computer',
				'vAlign'=>'middle',
				'format' => 'raw',
				'hAlign'=>'center',
				'width'=>'100px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function($model) {
					$computer_icons = [
						'1'=>'<i class="fa fa-fw fa-check-circle"></i> Ya',
						'0'=>'<i class="fa fa-fw fa-times-circle"></i> Tidak'
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
				'vAlign'=>'middle',
				'format' => 'raw',
				'hAlign'=>'center',
				'width'=>'100px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function($model) {
					$hostel_icons = [
						'1'=>'<i class="fa fa-fw fa-check-circle"></i> Ya',
						'0'=>'<i class="fa fa-fw fa-times-circle"></i> Tidak'
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
				'label' => 'Ketersediaan',
				'width'=>'80px',
				'value' => function ($data) use ($activity) {
					$activityRoom = \backend\models\ActivityRoom::find()
							->where('
								room_id = :room_id 
								AND
								activity_id = :activity_id
							',
							[
								':room_id' => $data->id,
								':activity_id' => $activity->id,
							]);
					$text_status = "<span class='label label-warning'>Menunggu</span>";
					if($activityRoom->count()>0){ 
						$mAR = $activityRoom->one();
						$status = $mAR->status;
						if($status==1) $text_status = "<span class='label label-info'>Proses</span>".Html::a('<span class="fa fa-times"></span>', ['unset','activity_id'=>$activity->id,'room_id'=>$data->id], ['class' => 'label label-danger link-post','data-pjax'=>0]);
						else if($status==2) $text_status = "<span class='label label-success'>Disetujui</span>";
						else if($status==3) $text_status = "<span class='label label-danger' title='".$mAR->note."'>Ditolak</span>";
					}
					return $text_status;
				}
			],
        ],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> Lihat Status Ruangan</h3>',
			'before'=>
				'<div class="pull-right" style="margin-right:5px;">'.
				Select2::widget([
					'name' => 'satker_id', 
					'data' => $satkers,
					'value' => $satker_id,
					'options' => [
						'width'=> '200px;',
						'placeholder' => 'Satker ...', 
						'class'=>'form-control', 
						'id'=>'select2-satker_id',
						'onchange'=>'
							$.pjax.reload({
								url: "'.\yii\helpers\Url::to(['room','activity_id'=>$activity_id]).'&satker_id="+$(this).val(), 
								container: "#pjax-gridview-room", 
								timeout: 1000,
							});
						',	
					],
				]).
				'</div>',
			'after'=>Html::a('<i class="fa fa-fw fa-repeat"></i> '.Yii::t('app', 'SYSTEM_BUTTON_RESET_GRID'), ['room','activity_id'=>$activity_id,'satker_id'=>$satker_id], ['class' => 'btn btn-info']),
			'showFooter'=>false
		],
		'responsive'=>true,
		'hover'=>true,
    ]); ?>
	<?php 
	$this->registerCss('.select2-container { width: 250px !important; }');
	?>
	<?php \yii\widgets\Pjax::end(); ?>

</div>
