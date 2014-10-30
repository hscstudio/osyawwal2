<?php

use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use kartik\widgets\SwitchInput;
use kartik\widgets\FileInput;
use yii\helpers\Url;
use kartik\grid\GridView;
use backend\models\Reference;
/* @var $this yii\web\View */
/* @var $model backend\models\Program */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = Yii::t('app', 'Subject {modelClass}: ', [
    'modelClass' => 'Program',
]) . ' ' . Inflector::camel2words($model->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Programs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Subject');
?>
<div class="program-update panel panel-default">
	

	<div class="panel-body">
		<?php		
		$program_revision = (int) \backend\models\ProgramHistory::getRevision($model->id);
		$countTraining = \backend\models\Training::find()
			->joinWith(['activity','program'])
			->where([
				'program_id'=>$model->id,
				'program_revision'=> $program_revision,
				'activity.status' => [2], // Status Ready
			])
			->count();
		
		$disabled = false;
		if(!$program_subject->isNewRecord){
			if($countTraining>0) $disabled = true;
		}
		
		
		if($program_subject->isNewRecord and $countTraining>0){
			echo '<div class="alert alert-warning">';
			echo '<blockquote>Program pada revisi ini sudah digunakan oleh diklat dan status execute</blockquote>';
			echo '</div>';
		}
		else{
		?>
			<div id="div_form">
			<?php $form = ActiveForm::begin([
			]); ?>
				<div class="row clearfix">
					<div class="col-md-4">
					<?php
					$data = ArrayHelper::map(Reference::find()
						->select(['id', 'name'])
						->where(['type'=>'subject_type'])
						->asArray()
						->all()
						, 'id', 'name');
					echo $form->field($program_subject, 'type')->widget(Select2::classname(), [
						'data' => $data,
						'options' => [
							'placeholder' => 'Choose type ...',
							'disabled' => $disabled,
						],
						'pluginOptions' => [
							'allowClear' => true,
						],
					]);
					?>
					</div>
					<div class="col-md-8">
					<?php
					echo $form->field($program_subject, 'name')->textInput(['disabled'=>$disabled,]);
					?>
					</div>
				</div>
				
				<div class="row clearfix">
					<div class="col-md-2">
					<?php
					echo $form->field($program_subject, 'hours')->textInput(['disabled'=>$disabled,]);
					?>
					</div>
					<div class="col-md-2">
						<?= $form->field($program_subject, 'test')->widget(SwitchInput::classname(), [
							'options'=>[
								'disabled' => $disabled,
							],
							'pluginOptions' => [
								'onText' => 'On',
								'offText' => 'Off',
							]
						]) ?>
					</div>				
					<div class="col-md-8">
					<?php		
					$data = ArrayHelper::map(Reference::find()
							->select(['id', 'name'])
							->where(['type'=>'stage'])
							->asArray()
							->all()
							, 'name', 'name');
					
					$program_subject->stage = explode('|',$program_subject->stage); 
					echo $form->field($program_subject, 'stage')->widget(Select2::classname(), [
						'data' => $data,
						'options' => [
							'placeholder' => 'Choose stage ...',
							'multiple' => true,
						],
						'pluginOptions' => [
							'allowClear' => true,
						],
					]);
					?>
					</div>
				</div>	
				<div class="row clearfix">
					<div class="col-md-2">
					<?php
					echo $form->field($program_subject, 'sort')->textInput(['disabled'=>$disabled,]);
					?>
					</div>
					<div class="col-md-2">
						<?= $form->field($program_subject, 'status')->widget(SwitchInput::classname(), [
							'options'=>[
								'disabled' => $disabled,
							],
							'pluginOptions' => [
								'onText' => 'On',
								'offText' => 'Off',
							]
						]) ?>
					</div>
					<div class="col-md-2">
						<label class="control-label" style="display:block">&nbsp;</label>
						<?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
					</div>
				</div>
			<?php ActiveForm::end(); ?>
			</div>
		<?php 
		}
		?>
		<hr>
		<?php \yii\widgets\Pjax::begin([
			'id'=>'pjax-gridview',
		]); ?>
		<?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
			[
				'attribute' => 'type',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'75px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){
					return Html::tag('span',$data->reference->name,[
						'class'=>'label label-default','data-toggle'=>'tooltip','title'=>''
					]);
				},
			],
			[
				'attribute' => 'name',
				'vAlign'=>'middle',
				'hAlign'=>'left',
				'pageSummary' => 'Total',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){
					return Html::tag('span',$data->hours,[
						'class'=>'label label-default','data-toggle'=>'tooltip','title'=>''
					]);
				},
			],			
			
			[
				'attribute' => 'hours',
				'vAlign'=>'middle',
				'hAlign'=>'right',
				'pageSummary' => function ($summary, $data, $widget) {  
					return Html::tag('span',$summary,[
						'class'=>'label label-default','data-toggle'=>'tooltip','title'=>''
					]);
				},
				'format'=>['decimal', 2],
				'pageSummaryFunc' => GridView::F_SUM,
				'width'=>'75px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column bg-warning'],
				'format'=>'raw',
				'value' => function ($data){
					return $data->hours;
					/* return Html::tag('span',$data->hours,[
						'class'=>'label label-default','data-toggle'=>'tooltip','title'=>''
					]); */
				},
			],
			
			[
				'attribute' => 'test',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'75px',
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
			],
			
			[
				'attribute' => 'sort',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'75px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){
					return Html::tag('span',$data->sort,[
						'class'=>'label label-default','data-toggle'=>'tooltip','title'=>''
					]);
				},
			],
			[
				'label' => 'Status',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'100px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){			
					$icon = ($data->status==1)?'<span class="glyphicon glyphicon-ok"></span>':'<span class="glyphicon glyphicon-remove"></span>';		
					return Html::tag('span', $icon, [
						'class'=>($data->status==1)?'label label-info':'label label-warning',
						'title'=>'Current status is '.(($data->status==1)?'publish':'draft'),
						'data-toggle'=>'tooltip',
					]);
				},
			],
			[
				'class' => 'kartik\grid\ActionColumn',
				'template' => '{update} {delete}',
				'buttons' => [
					'delete' => function ($url, $data) use ($countTraining) {
						// CEK AUTHORISE ACCESS
						$permit = \Yii::$app->user->can('Subbidang Kurikulum');
						if($permit and $countTraining==0){
							$icon='<span class="fa fa-fw fa-trash"></span>';
							return Html::a($icon,['subject-delete','id'=>$data->program_id,'subject_id'=>$data->id],[
								'data-method'=>'post',
								'class'=>'btn btn-default btn-xs',
								'data-pjax'=>'0',
								'data-confirm'=>"Are you sure to delete this item?",
								'title'=>'Click to delete'
							]);
						}
						else{
							return '';
						}
					},
					'update' => function ($url, $data) {
						// CEK AUTHORISE ACCESS
						$permit = \Yii::$app->user->can('Subbidang Kurikulum');
						if($permit){
							$icon='<span class="fa fa-fw fa-pencil"></span>';
							return Html::a($icon,['subject','id'=>$data->program_id,'action'=>'update','subject_id'=>$data->id],[
								'class'=>'btn btn-default btn-xs',
								'data-pjax'=>'0',
								'title'=>'Click to update'
							]);
						}
						else{
							return '';
						}
					},
				],
			],
		],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> '.Html::encode($this->title).' ('.$model->hours.' JP) '.'</h3>',
			'before'=>
				Html::a('<i class="fa fa-fw fa-arrow-circle-left"></i> Back', Url::to(['index']), ['class' => 'btn btn-warning','data-pjax'=>'0']).' '.
				Html::a('<i class="fa fa-fw fa-arrow-circle-up"></i> Create', ['subject','id'=>$model->id,'action'=>'create'], ['class' => 'btn btn-success','data-pjax'=>'0','onclick'=>"$('#div_formX').slideToggle('slow');return true;"]).' '.
				'<div class="pull-right" style="margin-right:5px;width:125px;">'.
				Select2::widget([
					'name' => 'status', 
					'data' => ['1'=>'Published','0'=>'Unpublished','all'=>'All'],
					'value' => $status,
					'options' => [
						'placeholder' => 'Status ...', 
						'class'=>'form-control', 
						'onchange'=>'
							$.pjax.reload({
								url: "'.Url::to(['subject','id'=>$model->id]).'&status="+$(this).val(), 
								container: "#pjax-gridview", 
								timeout: 1,
							});
						',	
					],
				]).
				'</div>',
			'after'=>Html::a('<i class="fa fa-fw fa-repeat"></i> Reset Grid', Url::to(''), ['class' => 'btn btn-info']),
			'showFooter'=>false
		],
		'showPageSummary' => true,
		'pageSummaryRowOptions'=>[
			'class' => 'kv-page-summary warning',
		],
		'responsive'=>true,
		'hover'=>true,
    ]); ?>	
	<?php \yii\widgets\Pjax::end(); ?>
	<?php 
	$this->registerCss('label{display:block !important;}'); 	
	if (!in_array(Yii::$app->request->get('action'),['update','create'])){
		$this->registerJs('
			$("#div_form").slideToggle("slow");
		');
	} 	
	?>
	</div>
</div>
