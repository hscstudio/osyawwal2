<?php

use yii\helpers\Html;
use yii\grid\GridView as Gridview2;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PersonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
$this->title = Yii::t('app', 'BPPK_TEXT_PEOPLE');
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
								['class'=>'badge','title'=>'Ciptakan Pegawai dari Individu','data-toggle'=>'tooltip']);
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

            [
				'class' => 'kartik\grid\ActionColumn',
				'template' => '<div class="btn-group">{view} {update} {delete}</div>',
				'width' => '120px',
				'buttons' => [
					'view' => function ($url, $model) {
								$icon='<span class="fa fa-fw fa-eye"></span>';
								return Html::a($icon,$url,[
									'class'=>'btn btn-default btn-xs modal-heart',
									'modal-title' => '<i class="fa fa-fw fa-eye"></i> Informasi '.$model->name,
									'data-pjax'=>'0',
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
									'data-confirm'=>'Yakin ingin menghapus?',
									'data-method' => 'post'
								]);
							},
				],
			],
        ],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> '.Html::encode($this->title).'</h3>',
			'before'=>Html::a('<i class="fa fa-fw fa-plus"></i> '.Yii::t('app', 'SYSTEM_BUTTON_CREATE'), ['create'], [
					'class' => 'btn btn-success modal-heart',
					'modal-size' => 'modal-lg',
					'modal-title' => '<i class="fa fa-fw fa-plus-circle"></i> Buat Individu Baru',
					'data-pjax' => '0'
				]),
			'after'=>Html::a('<i class="fa fa-fw fa-repeat"></i> '.Yii::t('app', 'SYSTEM_BUTTON_RESET_GRID'), Url::to(''), ['class' => 'btn btn-info']),
			'showFooter'=>false
		],
		'responsive'=>true,
		'hover'=>true,
    ]); ?>
	<?= \hscstudio\heart\widgets\Modal::widget(['modalSize'=>'modal-lg']) ?>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
	<i class="glyphicon glyphicon-upload"></i> Unggah Pegawai Massal
	</div>
    <div class="panel-body">
		<div class="row clearfix">
			<div class="col-md-3">
			<?php
			echo Html::a('<i class="fa fa-fw fa-download"></i>Unduh Template',
						Url::to(['/file/download','file'=>'template/pusdiklat/general/employee_upload.xlsx']),
						[
							'class'=>'btn btn-default',
							'data-pjax'=>'0',
						]
					);
			?>
			</div>
			<div class="col-md-9">
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
					'uploadLabel'=>"Impor Excel",
				]
			]);
			\yii\bootstrap\ActiveForm::end();
			?>
			</div>
			
		</div>
	</div>
</div>