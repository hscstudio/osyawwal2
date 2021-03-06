<?php

use yii\helpers\Html;
use yii\grid\GridView as Gridview2;
use kartik\grid\GridView;
use yii\helpers\Url;

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
$this->title = Yii::t('app', 'People');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="person-index">
	
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
		
			[
				'attribute' => 'name',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],					
			],
            
			[
				'attribute' => 'nid',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],					
			],
			
			[
				'header' => '<i class="fa fa-fw fa-user"></i>',
				'format'=>'raw',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],	
				'value' => function ($data){
					if(!empty($data->employee->person_id)){
						return Html::a($data->employee->person->name,['./employee/view','id'=>$data->employee->person_id],
								['class'=>'modal-heart badge','title'=>$data->employee->person->name,'source'=>'div.panel-body','data-toggle'=>'tooltip']);
					}
					else{
						return Html::a('<i class="fa fa-fw fa-plus"></i>',['./employee/create','person_id'=>$data->id],
								['class'=>'badge','title'=>'Create Employee Base On Person','data-toggle'=>'tooltip']);
					}					
					
				}
			],
            // 'nid',
            // 'npwp',
            // 'born',
            // 'birthday',
            // 'gender',
            // 'phone',
            // 'email:email',
            // 'homepage',
            // 'address',
            // 'office_phone',
            // 'office_fax',
            // 'office_email:email',
            // 'office_address',
            // 'bank_account',
            // 'married',
            // 'photo',
            // 'blood',
            // 'graduate',
            // 'graduate_desc',
            // 'position',
            // 'position_desc',
            // 'organisation',
            // 'status',
            // 'created',
            // 'created_by',
            // 'modified',
            // 'modified_by',

            ['class' => 'kartik\grid\ActionColumn'],
        ],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> '.Html::encode($this->title).'</h3>',
			'before'=>Html::a('<i class="fa fa-fw fa-plus"></i> Create ', ['create'], ['class' => 'btn btn-success']),
			'after'=>Html::a('<i class="fa fa-fw fa-repeat"></i> Reset Grid', Url::to(''), ['class' => 'btn btn-info']),
			'showFooter'=>false
		],
		'responsive'=>true,
		'hover'=>true,
    ]); ?>
	<?= \hscstudio\heart\widgets\Modal::widget(['modalSize'=>'modal-lg']) ?>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
	<i class="glyphicon glyphicon-upload"></i> Batch Upload
	</div>
    <div class="panel-body">
		<div class="row clearfix">
			<div class="col-md-2">
			Upload Pegawai
			</div>
			<div class="col-md-2">
			<?php
			echo Html::a('template',
						Url::to(['/file/download','file'=>'template/pusdiklat/general/employee_upload_bdk.xlsx']),
						[
							'class'=>'label label-default',
							'data-pjax'=>'0',
						]
					);
			?>
			</div>
			<div class="col-md-8">
			<?php
			$form = \yii\bootstrap\ActiveForm::begin([
				'options'=>['enctype'=>'multipart/form-data'],
				'action'=>['import-employee'], 
			]);
			echo \kartik\widgets\FileInput::widget([
				'name' => 'importFile', 
				//'options' => ['multiple' => true], 
				'pluginOptions' => [
					'previewFileType' => 'any',
					'uploadLabel'=>"Import Excel",
				]
			]);
			\yii\bootstrap\ActiveForm::end();
			?>
			</div>
			
		</div>
	</div>
</div>