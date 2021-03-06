<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap\Dropdown;
use hscstudio\heart\widgets\Box;

/* @var $searchModel backend\models\TrainingClassSearch */

$this->title = 'Classes : '.\yii\helpers\Inflector::camel2words($training->name);
$this->params['breadcrumbs'][] = ['label' => 'Trainings', 'url' => \yii\helpers\Url::to(['/'.$this->context->module->uniqueId.'/training2/index'])];
$this->params['breadcrumbs'][] = $this->title;

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
?>
<div class="training-class-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
	<div class="panel" id="panel-heading-dashboard" style="display:none;" >
		<a href="<?= yii\helpers\Url::to(["training2/dashboard","id"=>$training->id]) ?>" style="color:#666;padding:5px;display:block;text-align:center;background:#ddd;border-bottom: 1px solid #ddd;border-radius:4px 4px 0 0">
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
				'label' => 'Schedule',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'80px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function ($model){
					return Html::a('View',
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
						
					$certificateCount = '';
						/*\backend\models\TrainingClassStudentCertificate::find()
						->where([
							'tb_training_class_student_id'=>
								\backend\models\TrainingClassStudent::find()
									->select('id')
									->where([
										'tb_training_class_id'=>$model->id,
										'status'=>1
									])->column()
							,
							'status'=>1
						])->count();*/
					return Html::a($studentCount,
						\yii\helpers\Url::to([
							'/'.$this->context->module->uniqueId.'/training-class-student2/index',
							'tb_training_id'=>$model->tb_training_id,
							'tb_training_class_id'=>$model->id,
						]),
						[
							'title'=>' '.$certificateCount.' ',
							'class' => 'label label-default','data-pjax'=>0,'data-toggle'=>"tooltip",
							'data-placement'=>"top"
						]);
				}
			],	
			[
				'format' => 'raw',
				'label' => 'Certificate',
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
						
					$certificateCount = \backend\models\TrainingClassStudentCertificate::find()
						->where([
							'tb_training_class_student_id'=>
								\backend\models\TrainingClassStudent::find()
									->select('id')
									->where([
										'tb_training_class_id'=>$model->id,
										'status'=>1
									])->column()
							,
							'status'=>1
						])->count();
					return Html::a($certificateCount,
						\yii\helpers\Url::to([
							'/'.$this->context->module->uniqueId.'/training-class-student2/certificate',
							'tb_training_id'=>$model->tb_training_id,
							'tb_training_class_id'=>$model->id,
						]),
						[
							'title'=>'From '.$studentCount.' students',
							'class' => 'label label-default','data-pjax'=>0,'data-toggle'=>"tooltip",
							'data-placement'=>"top"
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
        ],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i></h3>',
			//'type'=>'primary',
			'before'=>
				Html::a('<i class="fa fa-fw fa-arrow-left"></i> Back To Training', \yii\helpers\Url::to(['/'.$this->context->module->uniqueId.'/training2/index']), ['class' => 'btn btn-warning']).' ',
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
