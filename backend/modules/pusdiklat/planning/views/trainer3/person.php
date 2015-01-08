<?php

use yii\helpers\Html;
use yii\grid\GridView as Gridview2;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\pusdiklat\planning\models\PersonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
$this->title = Yii::t('app', 'BPPK_TEXT_SELECT_PERSON');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'BPPK_TEXT_TRAINER'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="well">
	<p class="lead">
	Jika data pengajar tidak ditemukan, maka Anda di perkenankan untuk memasukkan data pengajar baru.
	</p>
	<p class="lead" style="text-align:center">
	<?php
	echo Html::a('<i class="fa fa-fw fa-plus"></i> Tambah Data Pengajar Baru ', ['create-person'], [
		'class' => 'btn btn-success',
		'data-confirm' => 'Apakah Anda yakin akan menambah data pengajar baru! pastikan tidak terjadi duplikasi data!',
	]);
	?>
	</p>
</div>
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
				'attribute' => 'nip',
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
				'attribute' => 'organisation',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],					
			],	
            [
				'attribute' => 'phone',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],					
			],
			[
				'label' => Yii::t('app', 'BPPK_TEXT_TRAINER'),
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'100px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){
					if(null != $data->trainer){
						return Html::tag('span', '<i class="fa fa-fw fa-check-square-o"></i>', [
							'class'=>'label label-success',
							'title'=>'Orang ini adalah Pengajar',
							'data-toggle'=>'tooltip',
						]);
					}
					$icon = '<span class="fa fa-fw fa-square-o"></span>';			
					return Html::a($icon, ['create','person_id'=>$data->id], [						
						'class'=>'label label-warning modal-heart',
						'data-toggle'=>'tooltip',
						'data-pjax'=>'0',
						'data-html'=>'true',
						'title'=>'Pilih orang ini sebagai pengajar',
						'modal-title'=>'',
						'modal-size'=>'modal-lg'
					]);
				},
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
            // 'blood',
            // 'graduate_desc',
            // 'position',
            // 'position_desc',
            // 'organisation',
            // 'status',
            // 'created',
            // 'created_by',
            // 'modified',
            // 'modified_by',
        ],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> '.Html::encode($this->title).'</h3>',
			'before'=>
				Html::a('<i class="fa fa-fw fa-arrow-circle-left"></i> '.Yii::t('app', 'SYSTEM_BUTTON_BACK'), Url::to(['index']), ['class' => 'btn btn-warning','data-pjax'=>'0']).' ',				
			'after'=>Html::a('<i class="fa fa-fw fa-repeat"></i> '.Yii::t('app', 'SYSTEM_BUTTON_RESET_GRID'), Url::to(''), ['class' => 'btn btn-info']),
			'showFooter'=>false
		],
		'responsive'=>true,
		'hover'=>true,
    ]); ?>
	<?= \hscstudio\heart\widgets\Modal::widget() ?>

</div>

<div class="well">
	<p class="lead">
	Jika data pengajar tidak ditemukan, maka Anda di perkenankan untuk memasukkan data pengajar baru.
	</p>
	<p class="lead" style="text-align:center">
	<?php
	echo Html::a('<i class="fa fa-fw fa-plus"></i> Tambah Data Pengajar Baru ', ['create-person'], [
		'class' => 'btn btn-success',
		'data-confirm' => 'Apakah Anda yakin akan menambah data pengajar baru! pastikan tidak terjadi duplikasi data!',
	]);
	?>
	</p>
</div>