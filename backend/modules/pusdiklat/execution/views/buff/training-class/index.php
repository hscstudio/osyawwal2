<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Inflector;
use yii\bootstrap\Dropdown;
use kartik\grid\GridView;
use hscstudio\heart\widgets\Box;

$this->title = 'Classes : '.Inflector::camel2words($training->name);
$this->params['breadcrumbs'][] = ['label' => 'Trainings', 'url' => \yii\helpers\Url::to(['/'.$this->context->module->uniqueId.'/training/index'])];
$this->params['breadcrumbs'][] = $this->title;

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
?>
<div class="training-class-index">

	<div class="panel" id="panel-heading-dashboard" style="display:none;" >
		<a href="<?= yii\helpers\Url::to(["training/dashboard","id"=>$training->id]) ?>" style="color:#666;padding:5px;display:block;text-align:center;background:#ddd;border-bottom: 1px solid #ddd;border-radius:4px 4px 0 0">
			<span class="badge"><i class="fa fa-arrow-circle-left"></i> Back To Dashboard</span>
		</a>
		<?php
		Box::begin([
			'type'=>'small', // ,small, solid, tiles
			'bgColor'=>'green', // , aqua, green, yellow, red, blue, purple, teal, maroon, navy, light-blue
			'options' => [
			],
			'headerOptions' => [
				'button' => ['collapse','remove'],
				'position' => 'right', //right, left
				'color' => '', //primary, info, warning, success, danger
				'class' => '',
			],
			'header' => 'T',
			'bodyOptions' => [],
			'icon' => 'fa fa-home',
			//'link' => ['./training-class','tb_training_id'=>$training->id],
			'footerOptions' => ['class'=>'hide'],
			//'footer' => 'More info <i class="fa fa-arrow-circle-right"></i>',
		]);
		?>
			<h3>Class</h3>
			<p>Class of Training</p>
		<?php
		Box::end();
		?>
	</div>
	<?php
	$this->registerJs('
		$("div#panel-heading-dashboard").slideToggle("slow");
	');
	?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],            
			[
				'class' => 'kartik\grid\EditableColumn',
				'attribute' => 'class',
				'vAlign'=>'middle',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'editableOptions'=>['header'=>'Class', 'size'=>'md','formOptions'=>['action'=>\yii\helpers\Url::to('editable')]]
			],

			[
				'format' => 'raw',
				'label' => 'Attendance',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'80px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function ($data) {
					return Html::a('<i class="fa fa-fw fa-tasks"></i>', Url::to(['attendance', 'tb_training_class_id' => $data->id]), [
							'class' => 'btn btn-default btn-xs'
						]);
				}
			],

			[
				'format' => 'raw',
				'label' => 'Subject',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'80px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function ($data)
				{
					$classSubjectCount = \backend\models\TrainingClassSubject::find()->where(['tb_training_class_id' => $data->id])->count();
					$SubjectCount = \backend\models\ProgramSubjectHistory::find()->where([
						'tb_program_id' => $data->training->tb_program_id,
						'revision'=> $data->training->tb_program_revision,
						'status'=>1,
					])->count();
					
					if($SubjectCount>$classSubjectCount){
						return Html::a($classSubjectCount, 
							['/'.$this->context->module->uniqueId.'/training-class-subject/create',
							'tb_training_class_id'=>$data->id], 
							['title'=>$classSubjectCount,
							'class' => 'label label-default','data-pjax'=>0,'data-toggle'=>"tooltip",
							'data-placement'=>"top"]);
					}
					else{
						return Html::a($classSubjectCount, 
							['/'.$this->context->module->uniqueId.'/training-class-subject/index',
							'tb_training_class_id'=>$data->id], 
							['title'=>$classSubjectCount,
							'class' => 'label label-default','data-pjax'=>0,'data-toggle'=>"tooltip",
							'data-placement'=>"top"]);
					}
				}
			],
			[
				'format' => 'raw',
				'label' => 'Schedule',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'80px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function ($model){
					return Html::a('SET',
						\yii\helpers\Url::to(['schedule','tb_training_class_id'=>$model->id]),
						['class'=>'label label-default']);
				}
			],
			[
				'format' => 'raw',
				'label' => 'Student',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'80px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function ($model){
					$studentCount = \backend\models\TrainingClassStudent::find()
						->where([
							'tb_training_class_id'=>$model->id,
							'status'=>1
						])->count();
					return Html::a($studentCount,
						\yii\helpers\Url::to([
							'/'.$this->context->module->uniqueId.'/training-class-student/index',
							'tb_training_id'=>$model->tb_training_id,
							'tb_training_class_id'=>$model->id,
						]),
						[
							'class'=>'label label-default',
						]);
				}
			],
			[
				'format' => 'raw',
				'attribute' => 'status',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'80px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function ($data){
					$icon = ($data->status==1)?'<span class="glyphicon glyphicon-ok"></span>':'<span class="glyphicon glyphicon-remove"></span>';
					return $icon;						
				}
			],
            [
				'class' => 'kartik\grid\ActionColumn',
				'template'=>'{delete}',
			],
        ],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i></h3>',
			//'type'=>'primary',
			'before'=>
				Html::a('<i class="fa fa-fw fa-arrow-left"></i> Back To Training', \yii\helpers\Url::to(['/'.$this->context->module->uniqueId.'/training/index']), ['class' => 'btn btn-warning']).' '.
				Html::a('<i class="fa fa-fw fa-plus"></i> Create Training Class', ['create','tb_training_id'=>$training->id], ['class' => 'btn btn-success']),
			'after'=>Html::a('<i class="fa fa-fw fa-repeat"></i> Reset Grid', ['index','tb_training_id'=>$training->id], ['class' => 'btn btn-info']),
			'showFooter'=>false
		],
		'responsive'=>true,
		'hover'=>true,
    ]); ?>
	<?php 	
	echo Html::beginTag('div', ['class'=>'row']);
		echo Html::beginTag('div', ['class'=>'col-md-2']);
			echo Html::beginTag('div', ['class'=>'dropdown']);
				echo Html::button('PHPExcel <span class="caret"></span></button>', 
					['type'=>'button', 'class'=>'btn btn-default', 'data-toggle'=>'dropdown']);
				echo Dropdown::widget([
					'items' => [
						['label' => 'EXport XLSX', 'url' => ['php-excel?filetype=xlsx&template=yes']],
						['label' => 'EXport XLS', 'url' => ['php-excel?filetype=xls&template=yes']],
						['label' => 'Export PDF', 'url' => ['php-excel?filetype=pdf&template=no']],
					],
				]); 
			echo Html::endTag('div');
		echo Html::endTag('div');
	
		echo Html::beginTag('div', ['class'=>'col-md-2']);
			echo Html::beginTag('div', ['class'=>'dropdown']);
				echo Html::button('OpenTBS <span class="caret"></span></button>', 
					['type'=>'button', 'class'=>'btn btn-default', 'data-toggle'=>'dropdown']);
				echo Dropdown::widget([
					'items' => [
						['label' => 'EXport DOCX', 'url' => ['open-tbs?filetype=docx']],
						['label' => 'EXport ODT', 'url' => ['open-tbs?filetype=odt']],
						['label' => 'EXport XLSX', 'url' => ['open-tbs?filetype=xlsx']],
					],
				]); 
			echo Html::endTag('div');
		echo Html::endTag('div');	
		
		
	echo Html::endTag('div');
	?>

</div>
