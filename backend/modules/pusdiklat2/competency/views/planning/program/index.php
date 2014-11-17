<?php

use yii\helpers\Html;
use yii\grid\GridView as Gridview2;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
$this->title = Yii::t('app', 'PAGE_TITLE_SUB_BID_PROGRAM');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="program-index">

	<?php
	$program_code = ArrayHelper::map(
		\backend\models\Reference::find()
			->select(['id','value', 'name'])
			->where(['type'=>'program_code'])
			->orderBy(['sort'=> SORT_ASC])			
			->asArray()
			->all(), 
			'value', 'name'
	);
	?>
	
	<?php \yii\widgets\Pjax::begin([
		'id'=>'pjax-program-gridview',
	]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            // 'id',
            
			[
				'attribute' => Yii::t('app', 'BPPK_TEXT_NUMBER'),
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'150px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data) use ($program_code){
					return Html::tag('span',$data->number,['class'=>'label label-default','data-toggle'=>'tooltip','title'=>$program_code[$data->number]]);
				},				
			],
		
			[
				'attribute' => 'name',
				'label' => Yii::t('app', 'BPPK_TEXT_NAME'),
				'vAlign'=>'middle',
				'hAlign'=>'left',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],	
				'format'=>'raw',
				'value' => function ($data){
					return Html::a($data->name,'#',['title'=>$data->note,'data-toggle'=>"tooltip",'data-placement'=>"top"]);
				},
			],
		
			/* [
				'header' => 'Docs',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'70px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){
					return Html::tag('span','<i class="fa fa-book"></i>',['class'=>' label-','data-toggle'=>'tooltip','title'=>'Setara dengan '.$data->days.' Hari']);
				},
			], */
			
			[
				'label' => 'JP',
				'attribute' => 'hours',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'70px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){
					return Html::tag('span',$data->hours,['class'=>'label label-default','data-toggle'=>'tooltip','title'=>'Setara dengan '.$data->days.' Hari']);
				},
			],
		
			/* [
				'attribute' => 'test',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'70px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){
					if($data->test==1) {
						$icon='<span class="glyphicon glyphicon-check"></span>';
						return Html::a($icon,'#',['class'=>'label label-default','data-toggle'=>'tooltip','data-pjax'=>"0",'title'=>'Diklat dengan Ujian Akhir']);
					}
					else{
						$icon='<span class="glyphicon glyphicon-minus"></span>';
						return Html::a($icon,'#',['class'=>'badge','data-toggle'=>'tooltip','data-pjax'=>"0",'title'=>'Diklat tanpa Ujian Akhir']);
					}
				},
			], */
			
			[
				'attribute' => 'validation_status',
				'label' => Yii::t('app', 'BPPK_TEXT_VALIDATE'),
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'70px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){
					$validation_icons = [
						'0'=>'<span class="glyphicon glyphicon-fire"></span>',
						'1'=>'<span class="glyphicon glyphicon-refresh"></span>',
						'2'=>'<span class="glyphicon glyphicon-check"></span>',
						'3'=>'<span class="glyphicon glyphicon-remove"></span>'
					];
					$validation_classes = ['0'=>'warning','1'=>'info','2'=>'success','3'=>'danger'];
					return Html::a(
						$validation_icons[$data->validation_status],
						['validation','id'=>$data->id],
						[
							'class'=>' modal-heart label label-'.$validation_classes[$data->validation_status],
							'data-toggle'=>'tooltip',
							'data-pjax'=>"0",
							'title'=>$data->validation_note,
							'modal-title'=>'Form Validation',
							'modal-size'=>'modal-lg',
						]
					);
				},
			],
			[
				'label' => Yii::t('app', 'BPPK_TEXT_TRAINING'),
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'70px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){
					$training = \backend\models\Training::find()
						->where([
							'program_id'=>$data->id
						])
						->count();
					return Html::a(
						$training,
						['training','id'=>$data->id],
						[
							'class'=>' modal-heart label label-info',
							'data-toggle'=>'tooltip',
							'data-pjax'=>"0",
							'title'=>'Diklat '.($data->name),
							'modal-title'=>'Diklat '.($data->name),
							'modal-size'=>'modal-lg',
						]
					);
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
					$permit = \Yii::$app->user->can('pusdiklat2-competency');
					if($permit){
						$object_person=\backend\models\ObjectPerson::find()
							->where([
								'object'=>'program',
								'object_id'=>$data->id,
								'type'=>'organisation_67', // CEK ID 393 IN TABLE ORGANISATION IS SUBBIDANG PROGRAM
							])
							->one();
						if($object_person!=null){						
							return Html::a(substr($object_person->person->name,0,5).'.',['pic','id'=>$data->id],
								[
									'class'=>'label label-info modal-heart',
									'data-toggle'=>'tooltip',
									'data-pjax'=>'0',
									'data-html'=>'true',
									'title'=>'Current PIC Program : '.$object_person->person->name.'.<br> Click to change pic program',
									'modal-title'=>'Change PIC',
									'modal-size'=>'modal-md',
								]);								
						}
						else{
							return Html::a('-',['pic','id'=>$data->id],
								[
									'class'=>'label label-warning modal-heart',
									'data-toggle'=>'tooltip',
									'data-pjax'=>'0',
									'data-html'=>'true',
									'title'=>'Click to set PIC program',
									'modal-title'=>'Set PIC',
									'modal-size'=>'modal-md'
								]);
						}
					}
					else{
						return '-';
					}
				}
			],
			
			[
				'format' => 'raw',
				'attribute' => 'status',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'70px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function ($data){
					$permit = \Yii::$app->user->can('pusdiklat2-competency');
					if($permit){
						$icon = ($data->status==1)?'<span class="glyphicon glyphicon-ok"></span>':'<span class="glyphicon glyphicon-remove"></span>';
						return Html::a($icon, ['status','status'=>$data->status, 'id'=>$data->id], [
							'onclick'=>'
								$.pjax.reload({url: "'.\yii\helpers\Url::to(['status','status'=>$data->status, 'id'=>$data->id]).'", container: "#pjax-gridview", timeout: 3000});
								return false;
							',
							'class'=>($data->status==1)?'label label-info':'label label-warning',
						]);
					}
					else{
						return '-';
					}
				}
			],
			
            // 'stage',
            // 'category',
            // 'validation_status',
            // 'validation_note',
            // 'status',
            // 'created',
            // 'created_by',
            // 'modified',
            // 'modified_by',

            [
				'class' => 'kartik\grid\ActionColumn',
				'width' => '120px',
				'buttons' => [
					'view' => function ($url, $model) {
						$icon='<span class="fa fa-fw fa-eye"></span>';
						return Html::a($icon,$url,[
							'class'=>'modal-heart btn btn-default btn-xs',
							'data-pjax'=>"0",
							'modal-size'=>'modal-lg',
							'modal-title' => '<i class="fa fa-fw fa-eye"></i> '.$model->name
						]);
					},
					'update' => function ($url, $model) {
						$icon='<span class="fa fa-fw fa-pencil"></span>';
						$countTraining = \backend\models\Training::find()
							->where([
								'program_id'=>$model->id,
							])
							->count();
						$msg = '';
						if ($countTraining>0) $msg ='. This program have used by '.$countTraining.' training';
						return Html::a($icon,$url,[
							'class'=>'modal-heart btn btn-default btn-xs',
							'data-pjax'=>"0",
							'modal-title'=>'<i class="fa fa-fw fa-pencil-square"></i> '.$model->name,
							'modal-size'=>'modal-lg',
							/* 'data-confirm'=>"Are you sure to update this item? ".$msg, */
							]
						);
					},
					'delete' => function ($url, $model) {
						$icon='<span class="fa fa-fw fa-trash"></span>';
						$countTraining = \backend\models\Training::find()
							->where([
								'program_id'=>$model->id,
							])
							->count();
						if($countTraining==0){
							return Html::a($icon,$url,[
								'data-method'=>'post',
								'class'=>'btn btn-default btn-xs',
								'data-pjax'=>'0',
								'data-confirm'=>"Are you sure to delete this item?",
								'title'=>'Click to delete'
							]);
						}
					},
				],
			],
        ],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> '.Html::encode(Yii::t('app','PAGE_TITLE_TABLE_PROGRAM')).'</h3>',
			'before'=>
				Html::a('<i class="fa fa-fw fa-plus-circle"></i> '.Yii::t('app', 'SYSTEM_BUTTON_CREATE'), ['create'], [
					'class' => 'btn btn-success modal-heart ',
					'data-toggle'=>'tooltip',
					'data-pjax'=>"0",
					'modal-title'=>'<i class="fa fa-fw fa-plus-circle"></i> Buat Program Baru',
					'modal-size'=>'modal-lg',
				]).
				'<div class="pull-right" style="margin-right:5px;">'.
				Select2::widget([
					'name' => 'status', 
					'data' => ['1'=> Yii::t('app', 'SYSTEM_TEXT_PUBLISHED'),'0'=>Yii::t('app', 'SYSTEM_TEXT_UNPUBLISHED'),'all'=>Yii::t('app', 'SYSTEM_TEXT_SHOW_ALL')],
					'value' => $status,
					'options' => [
						'placeholder' => 'Status ...', 
						'class'=>'form-control', 
						'onchange'=>'
							$.pjax.reload({
								url: "'.Url::to(['index']).'?status="+$(this).val(), 
								container: "#pjax-program-gridview", 
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
	<?= \hscstudio\heart\widgets\Modal::widget() ?>
	<?php /* $this->registerCss('.select2-container{width:125px !important;}'); */ ?>
	<?php \yii\widgets\Pjax::end(); ?>
	
</div>

<div class="panel panel-default">
	<div class="panel-heading">
	<i class="fa fa-fw fa-print"></i> Document Generator
	</div>
    <div class="panel-body">
		<?php
		$form = \yii\bootstrap\ActiveForm::begin([
			'method'=>'get',
			'action'=>['export-program','status'=>$status],
		]);
		echo Html::submitButton('<i class="fa fa-fw fa-download"></i> '.Yii::t('app', 'SYSTEM_TEXT_DOWNLOAD').' Data Program', ['class' => 'btn btn-default','style'=>'display:block;']);
		\yii\bootstrap\ActiveForm::end(); 
		?>
	</div>
</div>
