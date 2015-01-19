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
use hscstudio\heart\widgets\Box;

$this->title = 'Pesan Ruangan';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'BPPK_TEXT_TRAINING_ACTIVITIES'), 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Pesan Ruangan '.\yii\helpers\Inflector::camel2words($model->name);

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
?>
<div class="room-index">
	<div class="panel panel-default">
	   	<div class="panel-heading"> 
			<div class="pull-right">
	        	<?= (Yii::$app->request->isAjax)?'':Html::a('<i class="fa fa-fw fa-arrow-left"></i> '.Yii::t('app', 'SYSTEM_BUTTON_BACK'), ['index'], ['class' => 'btn btn-xs btn-primary']) ?>
			</div>
			<h1 class="panel-title"><i class="fa fa-fw fa-ellipsis-h"></i>Navigasi</h1> 
		</div>

		<div class="row clearfix">
			<div class="col-md-3">
			<?php
			Box::begin([
				'type'=>'small', // ,small, solid, tiles
				'bgColor'=>'red', // , aqua, green, yellow, red, blue, purple, teal, maroon, navy, light-blue
				'bodyOptions' => [],
				'icon' => 'glyphicon glyphicon-eye-open',
				'link' => ['property','id'=>$model->id],
				'footerOptions' => [
					'class' => 'dashboard-hide',
				],
				'footer' => 'Buka <i class="fa fa-arrow-circle-right"></i>',
			]);
			?>
			<h3>Informasi</h3>
			<p>Informasi Diklat</p>
			<?php
			Box::end();
			?>
			</div>
			
			<div class="col-md-2 margin-top-small">
			<?php
			Box::begin([
				'type'=>'small', // ,small, solid, tiles
				'bgColor'=>'aqua', // , aqua, green, yellow, red, blue, purple, teal, maroon, navy, light-blue
				'bodyOptions' => [],
				'icon' => 'fa fa-fw fa-inbox',
				'link' => ['room','id'=>$model->id],
				'footerOptions' => [
					'class' => 'dashboard-hide',
				],
				'footer' => 'Buka <i class="fa fa-arrow-circle-right"></i>',
			]);
			?>
			<h3>Ruangan</h3>
			<p>Anda Disini</p>
			<?php
			Box::end();
			?>
			</div>
			
			<div class="col-md-2">
			<?php
			Box::begin([
				'type'=>'small', // ,small, solid, tiles
				'bgColor'=>'green', // , aqua, green, yellow, red, blue, purple, teal, maroon, navy, light-blue
				'bodyOptions' => [],
				'icon' => 'fa fa-fw fa-users',
				'link' => ['student','id'=>$model->id],
				'footerOptions' => [
					'class' => 'dashboard-hide',
				],
				'footer' => 'Buka <i class="fa fa-arrow-circle-right"></i>',
			]);
			?>
			<h3>Peserta</h3>
			<p>Input data peserta</p>
			<?php
			Box::end();
			?>
			</div>
			
			<div class="col-md-2">
			<?php
			Box::begin([
				'type'=>'small', // ,small, solid, tiles
				'bgColor'=>'yellow', // , aqua, green, yellow, red, blue, purple, teal, maroon, navy, light-blue
				'bodyOptions' => [],
				'icon' => 'glyphicon glyphicon-home',
				'link' => ['class','id'=>$model->id],
				'footerOptions' => [
					'class' => 'dashboard-hide',
				],
				'footer' => 'Buka <i class="fa fa-arrow-circle-right"></i>',
			]);
			?>
			<h3>Kelas</h3>
			<p>Kelola kelas</p>
			<?php
			Box::end();
			?>
			</div>
			<div class="col-md-3">
			<?php
			Box::begin([
				'type'=>'small', // ,small, solid, tiles
				'bgColor'=>'teal', // , aqua, green, yellow, red, blue, purple, teal, maroon, navy, light-blue
				'bodyOptions' => [],
				'icon' => 'fa fa-fw fa-check',
				'link' => ['forma','id'=>$model->id],
				'footerOptions' => [
					'class' => 'dashboard-hide',
				],
				'footer' => 'Buka <i class="fa fa-arrow-circle-right"></i>',
			]);
			?>
			<h3>Form A</h3>
			<p>Kelola Form A</p>
			<?php
			Box::end();
			?>
			</div>
			
		</div>
	</div>

	<div class="panel panel-default" id="booking-room">
	<div class="panel-heading">
		 <h3 class="panel-title"><i class="fa fa-fw fa-search"></i> Cari Ruang yang Tersedia</h3>
	</div>
	<div class="kv-panel-before">
	<div class="row">
		<div class="col-md-4">
		<?php 
		$form = ActiveForm::begin([
			'action' => ['available-room','id'=>$model->id],
			'enableAjaxValidation' => false,
			'enableClientValidation' => true,
			'options'=>[
				'id'=>'form-available-room',
				'onsubmit'=>"
					$.ajax({
						url: $(this).attr('action'),
						type: 'post',
						data: $(this).serialize(),
						success: function(data) {
							$('#available-room').html(data);
						},
						error:  function( jqXHR, textStatus, errorThrown ) {
							$('#available-room').html(jqXHR.responseText);
						}
					});	

					return false;
				",
			],
		]); 
		?>
		<?php
		echo '<label class="control-label">Waktu Mulai</label>';		
		echo "<div class='clearfix' style='width:275px;'>";
		echo "<div style='width:150px;' class='pull-left'>";
		echo $form->field($searchActivityRoomModel, 'startDateX')->widget(DateControl::classname(), [
			'type'=>DateControl::FORMAT_DATE,
			'displayFormat' => 'php:d-m-Y',
			'saveFormat' => 'php:Y-m-d',
			'ajaxConversion' => false,
			'options'=>[  // this will now become the widget options for DatePicker
				'pluginOptions'=>[
					'autoclose'=>true,
					'startDate'=>date('d-m-Y',strtotime($model->start)),
					'endDate'=>date('d-m-Y',strtotime($model->end)),
				],// datepicker plugin options
				'convertFormat'=>true, // autoconvert PHP date to JS date format,
				
			]
		])->label(false);
		echo "</div>";
		echo "<div style='width:100px;' class='pull-right'>";
		echo $form->field($searchActivityRoomModel, 'startTimeX')->widget(DateControl::classname(), [
			'type'=>DateControl::FORMAT_TIME,
			'ajaxConversion' => false,
			'options'=>[  // this will now become the widget options for DatePicker
				'pluginOptions'=>[
					'autoclose'=>true,
					'showMeridian' => false,
					'minuteStep' => 1,
					'defaultTime'=> '08:00',
				],// datepicker plugin options
				'convertFormat'=>true, // autoconvert PHP date to JS date format,
			]
		])->label(false);
		echo "</div>";
		echo "</div>";
		?>
		
		<?php
		echo '<label class="control-label">Waktu Selesai</label>';		
		echo "<div class='clearfix' style='width:275px;'>";
		echo "<div style='width:150px;' class='pull-left'>";
		echo $form->field($searchActivityRoomModel, 'endDateX')->widget(DateControl::classname(), [
			'type'=>DateControl::FORMAT_DATE,
			'displayFormat' => 'php:d-m-Y',
			'saveFormat' => 'php:Y-m-d',
			'ajaxConversion' => false,
			'options'=>[  // this will now become the widget options for DatePicker
				'pluginOptions'=>[
					'autoclose'=>true,
					'startDate'=>date('d-m-Y',strtotime($model->start)),
					'endDate'=>date('d-m-Y',strtotime($model->end)),
				],// datepicker plugin options
				'convertFormat'=>true, // autoconvert PHP date to JS date format,
				
			]
		])->label(false);
		echo "</div>";
		echo "<div style='width:100px;' class='pull-right'>";
		echo $form->field($searchActivityRoomModel, 'endTimeX')->widget(DateControl::classname(), [
			'type'=>DateControl::FORMAT_TIME,
			'ajaxConversion' => false,
			'options'=>[  // this will now become the widget options for DatePicker
				'pluginOptions'=>[
					'autoclose'=>true,
					'showMeridian' => false,
					'minuteStep' => 1,
					'defaultTime'=> '17:00',
				],// datepicker plugin options
				'convertFormat'=>true, // autoconvert PHP date to JS date format,
			]
		])->label(false);
		echo "</div>";
		echo "</div>";
		?>
		
		<div class="row clearfix">
		<div class="col-md-6">
		<?= $form->field($searchActivityRoomModel, 'computer')->widget(CheckboxX::classname(), [
			'pluginOptions'=>['threeState'=>false,'size'=>'sm','inline'=>true, ],
			'options'=>[
				//'value'=>1
			],
		])->label('Ada fasilitas komputer'); ?>
		</div>
		<div class="col-md-6">
		<?= $form->field($searchActivityRoomModel, 'hostel')->widget(CheckboxX::classname(), [
			'pluginOptions'=>['threeState'=>false,'size'=>'sm','inline'=>true, ]
		])->label('Bisa menginap/asrama'); ?>
		</div>
		</div>
		
		<div class="row clearfix">
			<div class="col-md-4">
			<?= $form->field($searchActivityRoomModel, 'capacity')->textInput(['maxlength' => 5])->label('Berapa daya tampung?') ?>
			</div>
		</div>
		
		<div class="row clearfix">
			<div class="col-md-10">
			<?php
			echo $form->field($searchActivityRoomModel, 'location')->widget(Select2::classname(), [
				'data' => $satkers,
				'options' => [
					'placeholder' => 'Pilih Satker ...'
				],
				'pluginOptions' => [
					'allowClear' => true
				],
			])->label('Lokasi');
			
			?>
			</div>
		</div>
		
		<?= Html::submitButton(
			'<span class="fa fa-fw fa-search"></span> Cari', 
			['class' => 'btn btn-primary']) ?>
		</div>			
		<div class="col-md-8" id="available-room">
		
		</div>			
	</div>
	</div>
	</div>
	<div class="clearfix"></div>
	<?php ActiveForm::end(); ?>
	
	<?php 
	/* $this->registerCss('.select2-container { width: 200px !important; }'); */
	$this->registerJs('
			$("#form-available-room").submit();
	');
	?>
	
	<?php \yii\widgets\Pjax::begin([
		'id'=>'pjax-gridview-room',
	]); ?>
	
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
			[
				'label' => 'Waktu',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'250px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value'=>function($model){
					$start = date('d-M-Y H:i',strtotime($model->start));
					$end = date('d-M-Y H:i',strtotime($model->end));
					$startDate = date('d-M-Y',strtotime($model->start));
					$endDate = date('d-M-Y',strtotime($model->end));
					$startTime = date('H:i',strtotime($model->start));
					$endTime = date('H:i',strtotime($model->end));
					
					if($start==$end){
						return '<span class="label label-info">'.$start.'</span>';
					}
					else if($startDate==$endDate){
						return '<span class="label label-info">'.$startDate .'</span> <span class="label label-default">' .$startTime. ' s.d ' . $endTime.'</span>';
					}
					else{
						return '<span class="label label-info">'.$start .'</span>&nbsp;<span class="label label-default"> s.d </span>&nbsp;<span class="label label-info">'.$end.'</span>';
					}
				}
			],
			[
				'header' => '<div class="kv-sticky-column kv-align-center kv-align-middle">Ruang</div>',
				'vAlign'=>'middle',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value'=>function($data){
					$room = $data->room->name;
					$satker_id = (int)Yii::$app->user->identity->employee->satker_id;
					if($data->room->satker_id!=$satker_id){
						$room .= '<br><span class="badge">'.$data->room->satker->name.'</span>';
					} 
					return $room;
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
					if ($data->status==1){
						$label='label label-info';
						$title='Proses';
					}	
					else if ($data->status==2){ 
						$label='label label-success';
						$title='Disetujui';
					}
					else if ($data->status==3){ 
						$label='label label-danger';
						$title='Ditolak';
					}
					else {
						$label='label label-warning';
						$title='Menunggu';
					}
					return Html::tag('span', $title, ['class'=>$label,'title'=>$data->note,'data-toggle'=>"tooltip",'data-placement'=>"top",'style'=>'cursor:pointer']);
				}
			],
			[
				'format' => 'raw',
				'label' => 'Tindakan',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'80px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function ($data) use ($model){
					$satker_id = (int)Yii::$app->user->identity->employee->satker_id;
					$delete = false;
					if($data->room->satker_id==$satker_id){
						if($data->status==1) $delete=true;
					}
					else{
						if($data->status==0) $delete=true;
					}
					
					if($delete){
						return Html::a('<span class="fa fa-times"></span>', 
							[
							'unset-room',
							'id'=>$model->id,
							'room_id'=>$data->room->id,
							], 
							[
							'class' => 'label label-danger link-post-2','data-pjax'=>0,
							'title'=>'Klik untuk membatalkan pesanan',
							'data-toggle'=>"tooltip",
							'data-placement'=>"top",
							'data-method' => "post",
							]);
					}
				}
			],
			
        ],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> Daftar Ruangan yang Telah Dipesan</h3>',
			'before'=>Html::a('<i class="fa fa-fw fa-plus"></i> Pesan Ruangan', '#', ['class' => 'btn btn-success','onclick'=>"$('#booking-room').slideToggle('slow');return false;",'pjax'=>0]),
			'after'=>Html::a('<i class="fa fa-fw fa-repeat"></i>'.Yii::t('app', 'SYSTEM_BUTTON_RESET_GRID'), ['room','id'=>$model->id], ['class' => 'btn btn-info']),
			'showFooter'=>false
		],
		'responsive'=>true,
		'hover'=>true,
    ]); ?>
	
	<?php \yii\widgets\Pjax::end(); ?>	
	

</div>
