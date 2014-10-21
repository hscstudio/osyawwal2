<?php

use yii\helpers\Html;
use yii\grid\GridView as Gridview2;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel frontend\modules\student\models\TrainingClassSubjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
$this->title = 'Training Class Subjects';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="training-class-subject-index">
	
<!--
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Training Class Subject', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
-->

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            
				[
					'attribute' => 'program_subject_id',
					'vAlign'=>'middle',
					'hAlign'=>'center',
					'headerOptions'=>['class'=>'kv-sticky-column'],
					'contentOptions'=>['class'=>'kv-sticky-column'],
					'value' => function ($data) {
						return \frontend\models\ProgramSubject::findOne(['id'=>$data->program_subject_id])->name;
					}
				],
				[
					'format' => 'html',
					'label' => 'Trainer',
					'vAlign'=>'middle',
					'hAlign'=>'center',
					'headerOptions'=>['class'=>'kv-sticky-column'],
					'contentOptions'=>['class'=>'kv-sticky-column'],
					'value' => function($data){
						return Html::a(\frontend\models\TrainingSchedule::findOne(['training_class_subject_id'=>$data->id])->id);	
					}
				],
            
				[
					'format' => 'html',
					'label' => 'status',
					'vAlign'=>'middle',
					'hAlign'=>'center',
					'headerOptions'=>['class'=>'kv-sticky-column'],
					'contentOptions'=>['class'=>'kv-sticky-column'],	
					'value' => function($data){
						return Html::a($data->status=0?'Off':'On');	
					}
				],

            	[
				'class' => 'kartik\grid\ActionColumn',
				'template' => '{questioner}',
				'buttons' => [
					'questioner' => function ($url, $data) {
								$icon='<span class="fa fa-fw fa-newspaper-o"></span>';
								return ($data->status!=2 AND $data->status!=1)?'':Html::a($icon,\yii\helpers\Url::to('questioner.aspx?training_class_subject_id='.$data->id),[
									'class'=>'btn btn-default btn-xs',
									'data-pjax'=>'0',
								]);
							},
					],	
				],
        ],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> '.Html::encode($this->title).'</h3>',
			'before'=>Html::a(''),
			'after'=>Html::a('<i class="fa fa-fw fa-repeat"></i> Reset Grid', Url::to(''), ['class' => 'btn btn-info']),
			'showFooter'=>false
		],
		'responsive'=>true,
		'hover'=>true,
    ]); ?>

</div>
