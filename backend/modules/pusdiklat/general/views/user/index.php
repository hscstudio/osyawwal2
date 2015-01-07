<?php

use yii\helpers\Html;
use yii\grid\GridView as Gridview2;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
$this->title = Yii::t('app', 'BPPK_TEXT_USERS');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

			[
				'attribute' => 'username',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],					
			],
            
			[
				'label' => Yii::t('app', 'BPPK_TEXT_EMPLOYEE'),
				'format' => 'raw',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function ($data){
					$employee = \backend\models\Employee::find()
						->where([
							'user_id'=>$data->id,							
						])
						->one();
					if(null!=$employee){
						return Html::a($employee->person->name,['./employee/view','id'=>$employee->person_id],
							['class'=>'modal-heart badge','title'=>$employee->person->name,'source'=>'div.panel-body','data-toggle'=>'tooltip']);
					}							
				}
			],
			
            // 'password_hash',
            // 'password_reset_token',
            'email:email',       
            
			[
				'attribute' => 'status',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width' => '100px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format' => 'raw',
				'value' => function ($data){
					if($data->status==1){
						return Html::a('Aktif',['block','id'=>$data->id],[
							'data-method'=>'post',
							'data-confirm'=>'Yakin ingin mem-banned pengguna ini?',
							'class'=>'label label-success',
							'title'=>'Klik untuk mem-Banned',
							'data-toggle'=>'tooltip',
						]);
					}
					else{
						return Html::a('Banned',['unblock','id'=>$data->id],[
							'data-method'=>'post',
							'data-confirm'=>'Yakin ingin meng-unbanned pengguna ini?',
							'class'=>'label label-danger',
							'title'=>'Klik untuk Unbanned',
							'data-toggle'=>'tooltip',
						]);
					}
					
					return Html::tag('span',($data->status==1)?'Active':'Blocked',['class'=>($data->status==1)?'label label-info':'label label-warning']);
				}
			],
            // 'created_at',
            // 'updated_at',

            [
				'class' => 'kartik\grid\ActionColumn',
				'template' => '<div class="btn-group">{view} {update} {delete}</div>',
				'width' => '120px',
				'buttons' => [
					'view' => function ($url, $model) {
								$icon='<span class="fa fa-fw fa-eye"></span>';
								return Html::a($icon,$url,[
									'class'=>'btn btn-default btn-xs modal-heart',
									'modal-title' => '<i class="fa fa-fw fa-eye"></i> Informasi Pengguna',
									'data-pjax'=>'0',
								]);
							},
					'update' => function ($url, $model) {
								$icon='<span class="fa fa-fw fa-pencil"></span>';
								return Html::a($icon,$url,[
									'class'=>'btn btn-default btn-xs modal-heart',
									'modal-title' => '<i class="fa fa-fw fa-pencil"></i> Ubah Pengguna',
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
			'before'=>' ',
			'after'=>Html::a('<i class="fa fa-fw fa-repeat"></i> '.Yii::t('app', 'SYSTEM_BUTTON_RESET_GRID'), Url::to(''), ['class' => 'btn btn-info']),
			'showFooter'=>false
		],
		'responsive'=>true,
		'hover'=>true,
    ]); ?>
	<?= \hscstudio\heart\widgets\Modal::widget(['modalSize'=>'']) ?>
</div>
