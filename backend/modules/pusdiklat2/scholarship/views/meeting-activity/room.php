<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap\Dropdown;
use kartik\widgets\Select2;
use kartik\widgets\AlertBlock;

/* @var $searchModel backend\models\RoomSearch */

$this->title = $activity->name;
$this->params['breadcrumbs'][] = ['label' => 'Meetings', 'url' => ['index']];
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
				//'class' => 'kartik\grid\EditableColumn',
				'attribute' => 'capacity',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'100px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				//'editableOptions'=>['header'=>'Capacity', 'size'=>'md','formOptions'=>['action'=>\yii\helpers\Url::to('editable')]]
			],
		
			[
				//'class' => 'kartik\grid\EditableColumn',
				'attribute' => 'computer',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'100px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value'=>function ($data){
					return $data->computer==0?'-':'Ada';
				}
			],
		
			[
				//'class' => 'kartik\grid\EditableColumn',
				'attribute' => 'hostel',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'100px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value'=>function ($data){
					return $data->hostel==0?'-':'Ada';
				}
			],
			[
				'format' => 'raw',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'label' => 'Availability',
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
					$text_status = "<span class='label label-warning'>Waiting</span>";
					if($activityRoom->count()>0){ 
						$mAR = $activityRoom->one();
						$status = $mAR->status;
						if($status==1) $text_status = "<span class='label label-info'>Process</span>".Html::a('<span class="fa fa-times"></span>', ['unset','activity_id'=>$activity->id,'room_id'=>$data->id], ['class' => 'label label-danger link-post','data-pjax'=>0]);
						else if($status==2) $text_status = "<span class='label label-success'>Approved</span>";
						else if($status==3) $text_status = "<span class='label label-danger' title='".$mAR->note."'>Rejected</span>";
					}
					return $text_status;
				}
			],
        ],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> Set Room</h3>',
			//'type'=>'primary',
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
			'after'=>Html::a('<i class="fa fa-fw fa-repeat"></i> Reset Grid', ['room','activity_id'=>$activity_id,'satker_id'=>$satker_id], ['class' => 'btn btn-info']),
			'showFooter'=>false
		],
		'responsive'=>true,
		'hover'=>true,
    ]); ?>
	<?php 
	$this->registerCss('.select2-container { width: 250px !important; }');
	if (Yii::$app->request->isAjax){
		$this->registerJs('
			//$("#select2-satker_id").select2().select2("val", '.($activity->location-1).');
			$("a.link-post").on("click", function () {
				var $link = $(this);
				$.ajax({
					type: "POST",
					cache: false,
					url: $link.prop("href"),
					//data: $link.serializeArray(),
					success: function (data) {		
						$.pjax.reload({
							url: "'.\yii\helpers\Url::to(['room','activity_id'=>$activity_id,'satker_id'=>$satker_id]).'", 
							container: "#pjax-gridview-room",
							timeout: 1000,
						});
						$.growl(data, {	type: "success"	});
					},
					error: function (XMLHttpRequest, textStatus, errorThrown) {
						alert(XMLHttpRequest.responseText);
					}
				});
				return false;
			});
			$("#modal-heart").on("hidden.bs.modal", function (e) {
				$.pjax.reload({
					url: "'.\yii\helpers\Url::to(['index','organisation_id'=>$activity->meeting->organisation_id]).'", 
					container: "#pjax-gridview",
					timeout: 1,
				});
			});
		');
	}
	?>
	<?php \yii\widgets\Pjax::end(); ?>

</div>
