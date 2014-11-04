<?php

use yii\helpers\Html;
use yii\grid\GridView as Gridview2;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel frontend\modules\student\models\TrainingScheduleTrainerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
$this->title = 'Training Evaluation Of Trainers Class '.\frontend\models\TrainingClass::findOne(['id'=>$training_class_id])->class;
$this->params['breadcrumbs'][] = ['label' => 'Training Activities', 'url' => ['activity/index']];
$this->params['breadcrumbs'][] = ['label' => 'Dashboard', 'url' => ['activity/dashboard','training_id'=>$training_id,'training_student_id'=>$training_student_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="training-schedule-trainer-index">
	
<!--
    <h1><?= Html::encode($this->title) ?></h1>
-->

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

           
				[
					'attribute' => 'type',
					'vAlign'=>'middle',
					'hAlign'=>'center',
					'headerOptions'=>['class'=>'kv-sticky-column'],
					'contentOptions'=>['class'=>'kv-sticky-column'],
					'value' => function ($data) {
						return \frontend\models\Reference::findOne(['id'=>$data->type])->name;
					}
				],
            
				[
					'attribute' => 'trainer_id',
					'vAlign'=>'middle',
					'hAlign'=>'center',
					'headerOptions'=>['class'=>'kv-sticky-column'],
					'contentOptions'=>['class'=>'kv-sticky-column'],
					'value' => function ($data) {
						return \frontend\models\Person::findOne(['id'=>$data->trainer_id])->name;
					}
				],
            
				[
					'label' => 'Program Subject ID',
					'vAlign'=>'middle',
					'hAlign'=>'center',
					'headerOptions'=>['class'=>'kv-sticky-column'],
					'contentOptions'=>['class'=>'kv-sticky-column'],
					'value' => function ($data) {
						return \frontend\models\ProgramSubject::findOne(['id'=>$data->trainingSchedule->trainingClassSubject->program_subject_id])->name;
					}
				],
            	
				[
				'class' => 'kartik\grid\ActionColumn',
				'template' => '{questioner}',
				'buttons' => [
					'questioner' => function ($url, $model) use($training_id,$training_student_id){
								$icon='<span class="fa fa-fw fa-newspaper-o"></span>';
								return ($model->status!=2 AND $model->status!=1)?'':Html::a($icon,\yii\helpers\Url::to('../training-class-subject-trainer-evaluation/index.aspx?training_class_subject_id='.\hscstudio\heart\helpers\Kalkun::AsciiToHex(base64_encode($model->trainingSchedule->training_class_subject_id)).'&trainer_id='.\hscstudio\heart\helpers\Kalkun::AsciiToHex(base64_encode($model->trainer_id)).'&training_id='.$training_id.'&training_student_id='.$training_student_id),[
									'class'=>'btn btn-default btn-xs',
									'data-pjax'=>'0',
								]);
							},
					],	
				],

            //['class' => 'kartik\grid\ActionColumn'],
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
