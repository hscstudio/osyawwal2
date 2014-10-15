<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap\Dropdown;

/* @var $searchModel backend\models\TrainingClassSubjectSearch */

$this->title = 'Subject : Class '.$trainingClass->class;
$this->params['breadcrumbs'][] = ['label' => 'Trainings', 'url' => \yii\helpers\Url::to(['/'.$this->context->module->uniqueId.'/training/index'])];
$this->params['breadcrumbs'][] = ['label' => \yii\helpers\Inflector::camel2words($trainingClass->training->name), 'url' => \yii\helpers\Url::to(['/'.$this->context->module->uniqueId.'/training-class/index','tb_training_id'=>$trainingClass->tb_training_id])];
$this->params['breadcrumbs'][] = $this->title;

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
?>
<div class="training-class-subject-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
			[
				'label'=>'Type',
				'hAlign'=>'center',
				'value' => function ($data) {
					$program = $data->trainingClass->training->tb_program_id;
					$program_revision = $data->trainingClass->training->tb_program_revision;
					$programSubjects=\backend\models\ProgramSubjectHistory::find()
						->where([
							'tb_program_subject_id'=>$data->tb_program_subject_id,'tb_program_id'=>$program,
							'revision'=>$program_revision,'status'=>1
						])
						->one();
					$subjectType=backend\models\SubjectType::find()
						->where(['id'=>$programSubjects->ref_subject_type_id])
						->one();
					return $subjectType->name;
				}
			],
			[
				'attribute' => 'tb_program_subject_id',
				'label'=>'Program Subject',
				'value' => function ($data) {
					$program = $data->trainingClass->training->tb_program_id;
					$program_revision = $data->trainingClass->training->tb_program_revision;
					$programSubjects=\backend\models\ProgramSubjectHistory::find()
						->where([
							'tb_program_subject_id'=>$data->tb_program_subject_id,
							'tb_program_id'=>$program,
							'revision'=>$program_revision,'status'=>1
						])
						->one();
					return $programSubjects->name;
				}
			],
			[
				'label'=>'JP',
				'hAlign'=>'center',
				'value' => function ($data) {
					$program = $data->trainingClass->training->tb_program_id;
					$program_revision = $data->trainingClass->training->tb_program_revision;
					$programSubjects=\backend\models\ProgramSubjectHistory::find()
						->where([
							'tb_program_subject_id'=>$data->tb_program_subject_id,'tb_program_id'=>$program,
							'revision'=>$program_revision,'status'=>1
						])
						->one();
					return $programSubjects->hours;
				}
			],
            [
				'label'=>'Test',
				'hAlign'=>'center',
				'value' => function ($data) {
					$program = $data->trainingClass->training->tb_program_id;
					$program_revision = $data->trainingClass->training->tb_program_revision;
					$programSubjects=\backend\models\ProgramSubjectHistory::find()
						->where([
							'tb_program_subject_id'=>$data->tb_program_subject_id,'tb_program_id'=>$program,
							'revision'=>$program_revision,'status'=>1
						])
						->one();
					return ($programSubjects->test==1)?'Yes':'No';
				}
			],
			[
				'label'=>'Sort',
				'hAlign'=>'center',
				'value' => function ($data) {
					$program = $data->trainingClass->training->tb_program_id;
					$program_revision = $data->trainingClass->training->tb_program_revision;
					$programSubjects=\backend\models\ProgramSubjectHistory::find()
						->where([
							'tb_program_subject_id'=>$data->tb_program_subject_id,'tb_program_id'=>$program,
							'revision'=>$program_revision,'status'=>1
						])
						->one();
					return $programSubjects->sort;
				}
			],
			[
				'label'=>'Trainer',
				'format'=>'raw',
				'hAlign'=>'center',
				'value' => function ($data) {
					$trainingSchedule=\backend\models\TrainingSchedule::find()
								->select('id')
								->where([
									'tb_training_class_subject_id'=>$data->id,
									'status'=>1,
								])
								->groupBy('tb_training_class_subject_id')
								->asArray()
								->all();
					$tsid=[];			
					foreach($trainingSchedule as $ts){
						$tsid[] = $ts['id'];
					}
					$trainingScheduleTrainerCount=\backend\models\TrainingScheduleTrainer::find()
						->where([
							'tb_training_schedule_id'=>$tsid,
							'status'=>1,
						])
						->count();
					return Html::a($trainingScheduleTrainerCount,
						\yii\helpers\Url::to(['trainer',
							'id'=>$data->id,
							'tb_training_class_id'=>$data->tb_training_class_id,
						]),
						['class'=>'label label-default']);
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
			'before'=>
				Html::a('<i class="fa fa-fw fa-arrow-left"></i> Back To Training Class', \yii\helpers\Url::to(['/'.$this->context->module->uniqueId.'/training-class/index','tb_training_id'=>$trainingClass->tb_training_id]), ['class' => 'btn btn-warning']).' '.
				Html::a('<i class="fa fa-fw fa-plus"></i> Create Training Class Subject', ['create','tb_training_class_id'=>$trainingClass->id], ['class' => 'btn btn-success']),
			'after'=>Html::a('<i class="fa fa-fw fa-repeat"></i> Reset Grid', ['index','tb_training_class_id'=>$trainingClass->id], ['class' => 'btn btn-info']),
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
