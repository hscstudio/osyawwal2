<?php

use yii\helpers\Html;
use yii\grid\GridView as Gridview2;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\widgets\Select2;


/* @var $this yii\web\View */
/* @var $searchModel backend\modules\sekretariat\general\models\ActivityRoomSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
$this->title = 'Activities';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="activity-index">
	
<!--
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Activity', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
-->
	<?php \yii\widgets\Pjax::begin([
		'id'=>'pjax-gridview',
	]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

				[
					'attribute' => 'name',
					'vAlign'=>'middle',
					'hAlign'=>'left',
					'headerOptions'=>['class'=>'kv-sticky-column'],
					'contentOptions'=>['class'=>'kv-sticky-column'],					
				],
            
				[
					'attribute' => 'start',
					'vAlign'=>'middle',
					'hAlign'=>'center',
					'headerOptions'=>['class'=>'kv-sticky-column'],
					'contentOptions'=>['class'=>'kv-sticky-column'],					
				],
            
				[
					'attribute' => 'end',
					'vAlign'=>'middle',
					'hAlign'=>'center',
					'headerOptions'=>['class'=>'kv-sticky-column'],
					'contentOptions'=>['class'=>'kv-sticky-column'],					
				],
				
				[
				'label' => 'Peserta',
				
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width' => '75px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){
					return Html::tag('span', $data->meeting->attendance_count_plan,
						[
							'class'=>'label label-primary',
							'data-pjax'=>'0',
							'data-toggle'=>'tooltip',
							'title' => 'Rencana jumlah  peserta',
						]); 
				},
            ],
			
			[
				'format' => 'raw',
				'label' => 'PIC',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'70px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function ($data){
					// CEK AUTHORISE ACCESS
					$permit = \hscstudio\heart\helpers\Heart::OrganisationAuthorized(
						[
							'1213020100', // CEK KD_UNIT_ORG 1213020100 IN TABLE ORGANISATION IS SUBBIDANG PROGRAM
							'1213020000', // BIDANG RENBANG
							'1213000000', // PUSDIKLAT
						],
						[
							1, // 1= HEAD OF KD_UNIT_ORG
						]
					);
					$object_person=\backend\models\ObjectPerson::find()
						->where([
							'object'=>'activity',
							'object_id'=>$data->id,
							'type'=>'organisation_1213020100', // CEK KD_UNIT_ORG 1213020100 IN TABLE ORGANISATION IS SUBBIDANG PROGRAM
						])
						->one();
					
					if($permit){
						$options = [
									'class'=>'label label-info modal-heart',
									'data-toggle'=>'tooltip',
									'data-pjax'=>'0',
									'data-html'=>'true',
									'title'=>($object_person!=null)?'CURRENT PIC PROGRAM <br> '.$object_person->person->name.'.<br> CLICK HERE TO CHANGE PIC':'CLICK HERE TO SET PIC',
									'modal-title'=>($object_person!=null)?'CHANGE PIC':'SET PIC',
									'modal-size'=>'modal-md',
								];
						$person_name = ($object_person!=null)?substr($object_person->person->name,0,5).'.':'-';
						return Html::a($person_name,['pic','id'=>$data->id],$options);
					}
					else{
						$options = [
									'class'=>'label label-info',
									'data-toggle'=>'tooltip',
									'data-pjax'=>'0',
									'data-html'=>'true',
									'title'=>($object_person!=null)?'CURRENT PIC PROGRAM <br> '.$object_person->person->name.'':'PIC IS UNAVAILABLE',
								];
						$person_name = ($object_person!=null)?substr($object_person->person->name,0,5).'.':'-';
						return Html::tag('span',$person_name,$options);
					}
				}
			],
			
			[
				//'attribute' => 'classCount',
				'format'=>'raw',
				'label'=>'Room',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'100px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function ($data) {
					$activityRoom = \backend\models\ActivityRoom::find()
								->where('activity_id=:activity_id',
								[
									':activity_id' => $data->id
								]);		
					if($activityRoom->count()==0){ 
						return Html::a('SET', ['room','activity_id'=>$data->id], 
							[							
							'class' => 'label label-warning modal-heart',
							'data-pjax'=>0,
							'source'=>'',
							'modal-size'=>'modal-lg',
							]);
					}		
					else{
						$statuss = [
							'0' => 'Waiting',
							'1' => 'Process',
							'2' => 'Approved',
							'3' => 'Rejected',
						];
						
						$ars = $activityRoom->all();
						$rooms = [];
						foreach($ars as $ar){
							$rooms[] = $ar->room->name.'=>'.$statuss[$ar->status];
						}
						$rooms = implode('<br>',$rooms);
						return Html::a($activityRoom->count(), ['room','activity_id'=>$data->id], [
							'class' => 'label label-info modal-heart ',
							'data-pjax'=>0,
							'source'=>'',
							'modal-size'=>'modal-lg',
							'data-html'=>true,
							'title'=>$rooms,
							'data-toggle'=>'tooltip',
						]);
					}
				}
			],
			[
				'attribute' => 'organisation_id',
				'format'=>'raw',
				'label'=>'Bidang',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'100px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function ($data) {
					return Html::tag(
						'span',
						$data->meeting->organisation->ID,
						[
							'class'=>'label label-default',
							'data-toggle'=>'tooltip',
							'data-pjax'=>'0',
							'title'=>$data->meeting->organisation->NM_UNIT_ORG,
						]
					);
				}
			],
			[
				'attribute' => 'status',
				'filter' => false,
				'label' => 'Status',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'75px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){					
					$status_icons = [
						'0'=>'<span class="glyphicon glyphicon-fire"></span>',
						'1'=>'<span class="glyphicon glyphicon-check"></span>'
					];
					$status_classes = ['0'=>'warning','1'=>'info'];
					$status_title = ['0'=>'Draft','1'=>'Ready'];					
					return Html::tag(
						'span',
						$status_icons[$data->status],
						[
							'class'=>'label label-'.$status_classes[$data->status],
							'data-toggle'=>'tooltip',
							'data-pjax'=>'0',
							'title'=>$status_title[$data->status],
						]
					);
				},
			],

            //['class' => 'kartik\grid\ActionColumn'],
        ],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> '.Html::encode($this->title).'</h3>',
			'before'=>'<div class="pull-right" style="margin-right:5px;">'.
				Select2::widget([
					'name' => 'year', 
					'data' => $year_meeting,
					'value' => $year,
					'options' => [
						'placeholder' => 'Year ...', 
						'class'=>'form-control', 
						'onchange'=>'
							$.pjax.reload({
								url: "'.\yii\helpers\Url::to(['index']).'?status='.$status.'&organisation_id='.$organisation_id.'&year="+$(this).val(), 
								container: "#pjax-gridview", 
								timeout: 1,
							});
						',	
					],
				]).
				'</div>'.
				'<div class="pull-right" style="margin-right:5px;">'.
				Select2::widget([
					'name' => 'status', 
					'data' => ['all'=>'- All -','0'=>'Draft','1'=>'Publish'],
					'value' => $status,
					'options' => [
						'placeholder' => 'Status ...', 
						'class'=>'form-control', 
						'onchange'=>'
							$.pjax.reload({
								url: "'.\yii\helpers\Url::to(['index']).'?year='.$year.'&organisation_id='.$organisation_id.'&status="+$(this).val(), 
								container: "#pjax-gridview", 
								timeout: 1000,
							});
						',	
					],
				]).
				'</div>'.
				'<div class="pull-right" style="margin-right:5px;" id="div_organisation_id">'.
				Select2::widget([
					'name' => 'organisation_id', 
					'data' => $organisations,
					'value' => $organisation_id,
					'options' => [
						'placeholder' => 'Organisation ...', 
						'class'=>'form-control', 
						'onchange'=>'
							$.pjax.reload({
								url: "'.\yii\helpers\Url::to(['index']).'?year='.$year.'&status='.$status.'&organisation_id="+$(this).val(), 
								container: "#pjax-gridview", 
								timeout: 1000,
							});
						',	
					],
				]).
				'</div>',
			'after'=>Html::a('<i class="fa fa-fw fa-repeat"></i> Reset Grid', Url::to(''), ['class' => 'btn btn-info']),
			'showFooter'=>false
		],
		'responsive'=>true,
		'hover'=>true,
    ]); ?>
	<?= \hscstudio\heart\widgets\Modal::widget() ?>
	<?php $this->registerCss('
		#div_organisation_id .select2-container{ width:350px !important; }
	'); ?>
	<?php \yii\widgets\Pjax::end(); ?>
</div>
