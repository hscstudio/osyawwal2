<?php

use yii\helpers\Html;
use yii\grid\GridView as Gridview2;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $searchModel frontend\modules\student\models\ActivitySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
$this->title = Yii::t('app', 'Training');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="activity-index">
	
<!--
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Activity',
]), ['create'], ['class' => 'btn btn-success']) ?>
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
					'hAlign'=>'center',
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
					'label' => 'Status',				
					'vAlign'=>'middle',
					'hAlign'=>'center',
					'width' => '75px',
					'headerOptions'=>['class'=>'kv-sticky-column'],
					'contentOptions'=>['class'=>'kv-sticky-column'],
					'format'=>'raw',
					'value' => function ($data){
						$status = \frontend\models\TrainingStudent::findOne(['training_id'=>$data->id,'student_id'=>Yii::$app->user->identity->id])->status;
						if($status==1)
						{return Html::a('Baru',['training-student-status','status'=>\hscstudio\heart\helpers\Kalkun::AsciiToHex(base64_encode($status)),'training_id'=>\hscstudio\heart\helpers\Kalkun::AsciiToHex(base64_encode($data->id))],[
							'class' => 'label label-info ',
							'data-pjax'=>0,
							'source'=>'',
							'modal-size'=>'modal-lg',
							'data-html'=>true,
							'title'=>$status,
							'data-toggle'=>'tooltip',
							]);}
						else if($status==2)
						{return Html::a('Mengulang',['training-student-status','status'=>\hscstudio\heart\helpers\Kalkun::AsciiToHex(base64_encode($status)),'training_id'=>\hscstudio\heart\helpers\Kalkun::AsciiToHex(base64_encode($data->id))],[
							'class' => 'label label-info ',
							'data-pjax'=>0,
							'source'=>'',
							'modal-size'=>'modal-lg',
							'data-html'=>true,
							'title'=>$status,
							'data-toggle'=>'tooltip',
							]);}
					},					
				],
				
				/*[
				 	'class' => 'kartik\grid\ActionColumn',
					'template' => '{update}',					
					'buttons' => [
						'update' => function ($url, $model) {
									$icon='<span class="glyphicon glyphicon-pencil"></span>';
									return ($model->status!=2 AND $model->status!=1)?'':Html::a($icon,\yii\helpers\Url::to('dashboard.aspx?training_id='.\hscstudio\heart\helpers\Kalkun::AsciiToHex(base64_encode($model->id))),[
										'data-pjax'=>"0",
									]);
								},
					],	
				],*/
				[
				'class' => 'kartik\grid\ActionColumn',
				'template' => '{dashboard}',
				'buttons' => [
					'dashboard' => function ($url, $data) {
								$icon='<span class="fa fa-fw fa-dashboard"></span>';
								return ($data->status!=2 AND $data->status!=1)?'':Html::a($icon,\yii\helpers\Url::to('dashboard.aspx?training_id='.\hscstudio\heart\helpers\Kalkun::AsciiToHex(base64_encode($data->id)).'&training_student_id='.\hscstudio\heart\helpers\Kalkun::AsciiToHex(base64_encode(\frontend\models\TrainingStudent::findOne(['student_id'=>Yii::$app->user->identity->id,'training_id'=>$data->id])->id))),[
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
			'before'=>'<div class="pull-right" style="margin-right:5px;">'.
				Select2::widget([
					'name' => 'year', 
					'data' => $year_training,
					'value' => $year,
					'options' => [
						'placeholder' => 'Year ...', 
						'class'=>'form-control', 
						'onchange'=>'
							$.pjax.reload({
								url: "'.\yii\helpers\Url::to(['index']).'?satker_id='.$satker_id.'&year="+$(this).val(), 
								container: "#pjax-gridview", 
								timeout: 1,
							});
						',	
					],
				]).
				'</div>'.
				'<div class="pull-right" style="margin-right:5px;">'.
				Select2::widget([
					'name' => 'satker_id', 
					'data' => $satker,
					'value' => $satker_id,
					'options' => [
						'placeholder' => 'Penyelenggara ...', 
						'class'=>'form-control', 
						'onchange'=>'
							$.pjax.reload({
								url: "'.\yii\helpers\Url::to(['index']).'?year='.$year.'&satker_id="+$(this).val(), 
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
	<?php $this->registerCss('#div-select2-status .select2-container{width:125px !important;}');  ?>
<?php \yii\widgets\Pjax::end(); ?>
</div>
