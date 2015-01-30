<?php
use yii\helpers\Html;
use yii\grid\GridView as Gridview2;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Inflector;
/* @var $this yii\web\View */
/* @var $searchModel backend\modules\pusdiklat\planning\models\TrainerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = Yii::t('app', 'Pilih Peserta');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'BPPK_TEXT_TRAINING_ACTIVITIES'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Peserta '.Inflector::camel2words($model->training->activity->name)), 'url' => ['student','id'=>$model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trainer-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            
			[
				'attribute' => 'name',
				'header' => '<div style="text-align:center">Nama</div>',
				'vAlign'=>'middle',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],	
				'format' => 'raw',				
				'value' => function ($data){
					return Html::a($data->person->front_title.' '.$data->person->name.' '.$data->person->back_title,['./person/view','id'=>$data->person_id],
						['class'=>'modal-heart','title'=>$data->person->name,'source'=>'div.panel-body','data-toggle'=>'tooltip']);							
				}					
			],
			[
				'attribute' => 'phone',
				'label' => 'Telepon',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],	
				'format' => 'raw',				
				'value' => function ($data){
					return Html::a($data->person->phone,['./person/view','id'=>$data->person_id],
						['class'=>'modal-heart badge','title'=>$data->person->phone,'source'=>'div.panel-body','data-toggle'=>'tooltip']);							
				}					
			],
			
			[
				'label' => Yii::t('app', 'BPPK_TEXT_NID'),
				'attribute' => 'nid',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'150px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],	
				'format' => 'raw',				
				'value' => function ($data){
					return Html::tag('span',$data->person->nid,['class'=>'label label-default']);							
				}					
			],
			
			[
				'label' => Yii::t('app', 'BPPK_TEXT_NIP'),
				'attribute' => 'nip',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'150px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],	
				'format' => 'raw',				
				'value' => function ($data){
					return Html::tag('span',$data->person->nip,['class'=>'label label-default']);							
				}					
			],
            [
				'label' => 'Tindakan',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'100px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data) use ($model){
					$trainingStudentCount = \backend\models\TrainingStudent::find()
						->where([
							'training_id' => $model->id,
							'student_id' => $data->person_id,
						])
						->count();
					if($trainingStudentCount>0){
						return Html::tag('span', '<i class="fa fa-fw fa-check-square-o"></i>', [
							'class'=>'label label-success',
							'title'=>'Peserta Diklat',
							'data-toggle'=>'tooltip',
						]);
					}
					$icon = '<span class="fa fa-fw fa-square-o"></span>';			
					return Html::a($icon, 
						['set-student','id'=>$model->id,'student_id'=>$data->person_id], 
						[						
							'class'=>'label label-warning modal-heart',
							'data-toggle'=>'tooltip',
							'data-pjax'=>'0',
							'data-html'=>'true',
							'title'=>'Daftarkan',
							'modal-title'=>'',
							'modal-size'=>'modal-lg'
						]);
				}, 
			],		
        ],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> Daftar Peserta yang Dapat Dipilih </h3>',
			'before'=>
				Html::a('<i class="fa fa-fw fa-arrow-circle-left"></i> '.Yii::t('app', 'SYSTEM_BUTTON_BACK'), ['student','id'=>$model->id], ['class' => 'btn btn-warning']).' ',
			 'after'=>Html::a('<i class="fa fa-fw fa-repeat"></i> '.Yii::t('app', 'SYSTEM_BUTTON_RESET_GRID'), Url::to(''), ['class' => 'btn btn-info']),
			'showFooter'=>false
		],
		'responsive'=>true,
		'hover'=>true,
    ]); ?>
	
	<?= \hscstudio\heart\widgets\Modal::widget() ?>

</div>