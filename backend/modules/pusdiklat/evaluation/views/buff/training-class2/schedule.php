<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap\Dropdown;
use kartik\widgets\Select2;
use kartik\widgets\AlertBlock;
use kartik\widgets\ActiveForm;
use \kartik\widgets\DatePicker;
use \kartik\widgets\TimePicker;
use \kartik\datecontrol\DateControl;
use kartik\checkbox\CheckboxX;
/* @var $searchModel backend\models\RoomSearch */

$this->title = 'Schedule : Class '.$trainingClass->class;
$this->params['breadcrumbs'][] = ['label' => 'Trainings', 'url' => \yii\helpers\Url::to(['/'.$this->context->module->uniqueId.'/training2/index'])];
$this->params['breadcrumbs'][] = ['label' => 'Training Classes', 'url' => ['index','tb_training_id'=>$trainingClass->tb_training_id]];
$this->params['breadcrumbs'][] = $this->title;

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
?>
<div class="schedule-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

	<?php \yii\widgets\Pjax::begin([
		'id'=>'pjax-gridview-schedule',
	]); ?>
	<?php 
	if (Yii::$app->request->isAjax){	
		echo AlertBlock::widget([
			'useSessionFlash' => true,
			'type' => AlertBlock::TYPE_ALERT
		]); 
	}
	
	if (!isset($start) or empty($start)) {
		$start = $trainingClass->training->start;
	}
	$model = new \backend\models\TrainingScheduleExtSearch();
	$model->scheduleDate=$start;
	$form = ActiveForm::begin([]);
	ActiveForm::end();
	?>	
	
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
			[
				'label' => 'Datetime',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'200px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value'=>function($model){
					$start = date('d-M-Y H:i',strtotime($model->startTime));
					$finish = date('d-M-Y H:i',strtotime($model->finishTime));
					$startDate = date('d-M-Y',strtotime($model->startTime));
					$finishDate = date('d-M-Y',strtotime($model->finishTime));
					$startTime = date('H:i',strtotime($model->startTime));
					$finishTime = date('H:i',strtotime($model->finishTime));
					
					if($start==$finish){
						return $start;
					}
					else if($startDate==$finishDate){
						return '<span class="label label-info">'.$startDate .'</span> <span class="label label-default">' .$startTime. ' s.d ' . $finishTime.'</span>';
					}
					else{
						return '<span class="label label-info">'.$start .'</span>&nbsp;<span class="label label-default"> s.d </span>&nbsp;<span class="label label-info">'.$finish.'</span>';
					}
				}
			],
			[
				'label' => 'Activity',
				'vAlign'=>'middle',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value'=>function($model){
					if($model->tb_training_class_subject_id>0){
						$trainingClassSubject = \backend\models\TrainingClassSubject::findOne($model->tb_training_class_subject_id);
						if($trainingClassSubject!=null){
							$tb_program_subject_id = $trainingClassSubject->tb_program_subject_id;
							$tb_program_id = $trainingClassSubject->trainingClass->training->tb_program_id;
							$tb_program_revision = $trainingClassSubject->trainingClass->training->tb_program_revision;
							$programSubjectHistory = \backend\models\ProgramSubjectHistory::find()
							->where([
								'tb_program_subject_id'=>$tb_program_subject_id,
								'tb_program_id'=>$tb_program_id,
								'revision'=>$tb_program_revision,
								'status'=>1
							])
							->one();
							if(null!=$programSubjectHistory){
								$name = $programSubjectHistory->subjectType->name.' '.$programSubjectHistory->name;
								return $name;
							}
							else{
								return "Undefined??? hello??";
							}
							
						}
						else{
							return "Undefined??? hello??";
						}
					}
					else{
						return $model->activity;
					}
				}
			],
			[
				'label' => 'Hours',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'50px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value'=>function($model){
					if($model->tb_training_class_subject_id>0){
						return $model->hours;
					}
					else{
						return '';
					}
				}
			],
			[
				'label' => 'PIC/Narasumber',
				'vAlign'=>'middle',
				'width'=>'200px;',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value'=>function($model){
					if($model->tb_training_class_subject_id>0){
						// FIND PENGAJAR pada tb_training_schedule_trainer (id, tb_training_schedule_id, tb_trainer_id, status);
						$content = Html::a('<i class="fa fa-plus"></i> Add',['trainer','id'=>$model->id],[
							'class' => 'label label-success modal-heart',
							'data-pjax'=>0,
							'title'=>'Click to add trainer!',
							'data-toggle'=>"tooltip",
							'data-placement'=>"top",
						]);
						
						$trainingScheduleTrainer = \backend\models\TrainingScheduleTrainer::find()
							->where([
								'tb_training_schedule_id'=>$model->id,
								'status'=>1,
							])
							->orderBy('ref_trainer_type_id ASC')
							->all();
						$ref_trainer_type_id= "-1";	
						$idx = 1;
						foreach($trainingScheduleTrainer as $trainer){
							if($ref_trainer_type_id!=$trainer->ref_trainer_type_id){
								$content .="<hr style='margin:2px 0'>";
								$content .="<strong>".$trainer->trainerType->name."</strong>";
								$content .="<hr style='margin:2px 0'>";
								$ref_trainer_type_id=$trainer->ref_trainer_type_id;
								$idx=1;
							}
							
							$content .="<div>";
							$content .="<span  class='label label-default' data-toggle='tooltip' title='".$trainer->trainer->organization." - ".$trainer->trainer->phone."'>".$idx++.". ".$trainer->trainer->name."</span> ";
							$content .=Html::a('<span class="glyphicon glyphicon-trash"></span>', 
							[
							'delete-trainer',
							'id'=>$model->id,
							'tb_trainer_id'=>$trainer->tb_trainer_id,
							], 
							[
							'class' => 'label label-danger link-post',
							'data-pjax'=>0,
							'title'=>'click to delete it!',
							//'data-confirm'=>'Are sure delete it!',
							'data-toggle'=>"tooltip",
							'data-placement'=>"top",
							]);	
							$content .="</div>";							
						}
					}
					else{
						$content = $model->pic;
					}
					
					
					return $content;
				}
			],
			[
				'label' => 'Room',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'50px;',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value'=>function($model){
					if($model->tb_activity_room_id>0){
						$ar = \backend\models\ActivityRoom::findOne($model->tb_activity_room_id);
						$room = $ar->room->name;
						$ref_satker_id = (int)Yii::$app->user->identity->employee->ref_satker_id;
						if($ar->room->ref_satker_id!=$ref_satker_id){
							$room .= ' ['.$ar->room->satker->name.'] ';
						} 
						return Html::a($ar->id,'#',[
							'class' => 'label label-warning modal-heart',
							'data-pjax'=>0,
							'title'=>$room,
							'data-toggle'=>"tooltip",
							'data-placement'=>"top",
						]);
					}
					else{
						return Html::a('-','#',[
							'class' => 'label label-warning modal-heart',
							'data-pjax'=>0,
							'title'=>'Click to set room!',
							'data-toggle'=>"tooltip",
							'data-placement'=>"top",
						]);
					}
				}
			],
			[
				'label' => 'S',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'10px;',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value'=>function($model){
					if($model->tb_training_class_subject_id>0){
						return Html::a($model->session,'#',[
							'class' => 'label label-primary modal-heart',
							'data-pjax'=>0,
							'title'=>'Click to set session!',
							'data-toggle'=>"tooltip",
							'data-placement'=>"top",
						]);
					}
					else{
						return Html::a('-','#',[
							'class' => 'label label-primary modal-heart',
							'data-pjax'=>0,
							'title'=>'Click to set session!',
							'data-toggle'=>"tooltip",
							'data-placement'=>"top",
						]);
					}
				}
			],			
        ],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> Schedule</h3>',
			'before'=>
				Html::a('<i class="fa fa-fw fa-arrow-left"></i> Back To Training Class', ['index','tb_training_id'=>$trainingClass->tb_training_id], ['class' => 'btn btn-warning']).' '.
				
				'<div class="pull-right" style="margin-right:5px; width:150px;">'.
				$form->field($model, 'scheduleDate')->widget(DateControl::classname(), [
					'type'=>DateControl::FORMAT_DATE,
					'options'=>[  // this will now become the widget options for DatePicker
						'pluginOptions'=>[
							'autoclose'=>true,
							'startDate'=>date('d-m-Y',strtotime($trainingClass->training->start)),
							'endDate'=>date('d-m-Y',strtotime($trainingClass->training->finish)),
							
						],
						'pluginEvents' => [
							//"show" => "function(e) {  # `e` here contains the extra attributes }",
							//"hide" => "function(e) {  # `e` here contains the extra attributes }",
							//"clearDate" => "function(e) {  # `e` here contains the extra attributes }",
							"changeDate" => "function(e) { 
								date = new Date(e.date);
								year = date.getFullYear(); 
								month = date.getMonth()+1; 
								day = date.getDate(); 
								var start = year+'-'+month+'-'+day;
								$.pjax.reload({
									url: '".\yii\helpers\Url::to(['schedule','tb_training_class_id'=>$trainingClass->id])."&start='+start,
									container: '#pjax-gridview-schedule', 
									timeout: 3000,
								});	
								
								var startF = $('#trainingscheduleextsearch-scheduledate-disp').val()
								
							}",
							//"changeYear" => "function(e) {  # `e` here contains the extra attributes }",
							//"changeMonth" => "function(e) {  # `e` here contains the extra attributes }",
						],
						// datepicker plugin options
						'convertFormat'=>true, // autoconvert PHP date to JS date format,
						
					]
				])->label(false).
				'</div>',
			'after'=>Html::a('<i class="fa fa-fw fa-repeat"></i> Reset Grid', ['schedule','tb_training_class_id'=>$trainingClass->id], ['class' => 'btn btn-info']),
			'showFooter'=>false
		],
		'responsive'=>true,
		'hover'=>true,
    ]); ?>
	<?php
	$this->registerJs('
		
			$( "a.link-post" ).click(function() {	
				if(!confirm("Are you sure delete it??")) return false;	
				$.ajax({
					url: $(this).attr("href"),
					type: "post",
					//data: $("#form-available-room").serialize(),
					success: function(data) {
						var datas = data.split("|");	
						if(datas[1]==0){
							alert(datas[2]);
						}
						else{
							$("#trainingscheduleextsearch-starttime").val(datas[4]);
							$("#trainingscheduleextsearch-starttime-disp").val(datas[4]);
							$.pjax.reload({
								url: "'.\yii\helpers\Url::to(['schedule','tb_training_class_id'=>$trainingClass->id]).'&start="+datas[3],
								container: "#pjax-gridview-schedule", 
								timeout: 3000,
							});					
						}
					},
					error:  function( jqXHR, textStatus, errorThrown ) {
						alert(jqXHR.responseText);
					}
				});	
				return false;
			});	
			
			$(".modal-heart").on("click", function () {
				var $modal = $("#modal-heart");
				var $link = $(this);
				var $source = $link.attr("source")
				$modal.find(".modal-refresh").attr("href", $link.attr("href"));
				if ($link.attr("title")) {
					$modal.find(".modal-title").text($link.attr("title"));
				}
				else {
					$modal.find(".modal-title").html($link.attr("modal-title")); // warning: klo attribut title dan modal-title ada 2-2 nya
																				 // yang menang bakal yang title.
				}
				$modal.find(".modal-body .content").html("Loading ...");
				$modal.modal("show");
				
				$.ajax({
					type: "POST",
					cache: false,
					url: $link.prop("href"),
					data: $(".form-heart form").serializeArray(),
					success: function (data) {		
						if ($source) 
							result = $(data).find($source);
						else
							result = data;
						$modal.find(".modal-body .content").html(result);
						$modal.find(".modal-body .content").css("max-height", ($(window).height() - 200) + "px");
					},
					error: function (XMLHttpRequest, textStatus, errorThrown) {
						$modal.find(".modal-body .content").html("<div class=\"error\">" + XMLHttpRequest.responseText + "</div>");
					}
				});
				return false;
			});
			
			$("[data-toggle=\'tooltip\']").tooltip();

	');
	?>
	<?php \yii\widgets\Pjax::end(); ?>
	
	<?php 
	if (Yii::$app->request->isAjax){	
	
	}
	else{
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
	} 			
	?>
	<?= \hscstudio\heart\widgets\Modal::widget(['modalSize'=>'','registerAsset'=>false]) ?>
</div>
