<?php

use yii\helpers\Html;
use yii\grid\GridView as Gridview2;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Inflector;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\pusdiklat\execution\models\TrainingClassSubjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = 'Peserta';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'BPPK_TEXT_TRAINING_ACTIVITIES'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Kelas '.Inflector::camel2words($activity->name), 'url' => ['class','id'=>$activity->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'BPPK_TEXT_CLASS').' '.$class->class;
?>
<div class="training-class-subject-index">
	
	<?php \yii\widgets\Pjax::begin([
		'id'=>'pjax-gridview',
	]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
			[
				'header' => '<div style="text-align:center;">Nama</div>',
				'attribute'=>'name',
				'vAlign'=>'middle',
				'hAlign'=>'left',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){
					return Html::tag('span',$data->trainingStudent->student->person->name,[
						'class'=>'','data-toggle'=>'tooltip','title'=>''
					]);
				},
			],
			[
				'header' => Yii::t('app', 'BPPK_TEXT_NIP'),
				'vAlign'=>'middle',
				'attribute'=>'nip',
				'width'=>'150px',
				'hAlign'=>'center',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){
					return Html::tag('span',$data->trainingStudent->student->person->nip,[
						'class'=>'label label-info','data-toggle'=>'tooltip','title'=>''
					]);
				},
			],
			[
				'header' => '<div style="text-align:center;">Satker</div>',
				'vAlign'=>'middle',
				'hAlign'=>'left',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){
					$student = $data->trainingStudent->student;
					$unit = $student->person->unit->reference->name;
					
					if($student->satker==2){
						if(!empty($student->eselon2)){
							$unit = $student->eselon2;
						}
					}
					else if($student->satker==3){
						if(!empty($student->eselon3)){
							$unit = $student->eselon3;
						}
					}
					else if($student->satker==4){
						if(!empty($student->eselon4)){
							$unit = $student->eselon4;
						}
					}
					return Html::tag('span',$unit,[
						'class'=>'label label-default','data-toggle'=>'tooltip','title'=>''
					]);
				},
			],
			[
				'class' => 'kartik\grid\ActionColumn',
				'template' => '<div class="btn-group">{class} {delete}</div>',
				'buttons' => [
					'delete' => function ($url, $model) {
						$icon='<span class="fa fa-fw fa-trash"></span>';
						return Html::a($icon,
							['delete-class-student','id'=>$model->training_id,'class_id'=>$model->training_class_id,'training_class_student_id'=>$model->id],
							[
								'class'=>'btn btn-default btn-xs',
								'data-pjax'=>'0',
								'data-confirm'=>'Yakin ingin menghapus?',
								'data-method'=>'post',
							]
						);
					},
					'class' => function ($url, $model) {
						$icon='<span class="fa fa-fw fa-sign-out"></span>';
						return Html::a($icon,
							['change-class-student','id'=>$model->training_id,'class_id'=>$model->training_class_id,'training_class_student_id'=>$model->id],
							[
								'class'=>'modal-heart btn btn-default btn-xs',
								'data-pjax'=>'0',
								'data-toggle' => 'tooltip',
								'modal-size'=>'modal-md',
								'data-container' => 'body',
								'title' => 'Pindahkan ke Kelas Lain',
								'modal-title'=>'<i class="fa fa-fw fa-sign-out"></i>Pindahkan Peserta ke Kelas lain',
								
							]
						);
					},
				]
			]
			
        ],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> Daftar Peserta pada Kelas '.$class->class.'</h3>',
			'before'=>
				Html::a('<i class="fa fa-fw fa-arrow-circle-left"></i> '.Yii::t('app', 'SYSTEM_BUTTON_BACK'), ['class','id'=>$activity->id], ['class' => 'btn btn-warning']).' ',
			'after'=>Html::a('<i class="fa fa-fw fa-repeat"></i> '.Yii::t('app', 'SYSTEM_BUTTON_RESET_GRID'), Url::to(''), ['class' => 'btn btn-info']),
			'showFooter'=>false
		],
		'responsive'=>true,
		'hover'=>true,
    ]); ?>
	<?= \hscstudio\heart\widgets\Modal::widget() ?>
	<?php \yii\widgets\Pjax::end(); ?>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
	<i class="glyphicon glyphicon-upload"></i> Fitur Pembagian Peserta ke Kelas
	</div>
    <div class="panel-body">
		<?php
		$form = \yii\bootstrap\ActiveForm::begin([
			'options'=>[
				'id'=>'myform',
				'onsubmit'=>'
					
				',
			],
			'action'=>[
				'class-student','id'=>$activity->id,
				'class_id'=>$class->id
			], 
		]);
		?>
		<div class="row clearfix">
			<div class="col-md-3">
			<?php
			echo Html::beginTag('label',['class'=>'control-label']).'Peserta belum mendapatkan kelas'.Html::endTag('label');
			echo Html::input('text','',$trainingStudentCount,['class'=>'form-control','disabled'=>'disabled','id'=>'stock']);
			?>
			</div>
			<div class="col-md-3">
			<?php
			echo Html::beginTag('label',['class'=>'control-label']).'Jumlah peserta yang diacak ke kelas'.Html::endTag('label');
			echo Html::input('text','student','',['class'=>'form-control','id'=>'count']);
			?>
			</div>
			<div class="col-md-3">
			<?php
			echo '<label class="control-label">Kriteria Pembagian Acak</label>';
			echo Select2::widget([
				'name' => 'baseon', 
				'data' => [
					'person.name' =>'Nama', 
					'person.gender' => 'Jenis Kelamin', 
					'object_reference.reference_id' => 'Unit',					
				],
				'options' => [
					'placeholder' => 'Acak berdasarkan ...', 
					'class'=>'form-control', 
					'multiple' => true,
					'id'=>'baseon',
				],
			]);
			?>
			</div>
			<div class="col-md-3">
			<?php
			echo Html::beginTag('label',['class'=>'control-label']).' '.Html::endTag('label');
			echo Html::submitButton('<i class="fa fa-fw fa-random"></i>Bagi', ['class' => 'btn btn-success','style'=>'display:block;']);
			?>
			</div>
		</div>
		<?php \yii\bootstrap\ActiveForm::end(); ?>
		<?php
		$this->registerJs("
			$('#myform').on('beforeSubmit', function () {
				var count = parseInt($('#count').val());
				var stock = parseInt($('#stock').val());
				var baseon = $('#baseon').val();
				if(stock<=0 || isNaN(stock)){
					alert('Tidak ada stock peserta!');
					return false;
				}
				else if(count<=0 || isNaN(count)){
					alert('Jumlah permintaan peserta tidak boleh nol!');
					$('#count').select();
					return false;
				}
				else if(stock<count){
					alert('Jumlah permintaan tidak boleh melebihi stock peserta!'+x+y);
					$('#count').select();
					return false;
				}			
				else if(baseon==null){
					alert('Dasar pengacakan harus ditentukan!');
					$('#baseon').select();	
					return false;					
				}	
			});
		");
		?>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
	<i class="fa fa-fw fa-refresh upload"></i> Dokumen Generator
	</div>
    <div class="panel-body">
		<table class="table table-striped table-condensed table-hover">
		<tr>
			<td style="width:50px"><i class="fa fa-fw fa-link"></i></td>
			<td>Data Registrasi</td>
			<td>
				<?php
				echo Html::a('<i class="fa fa-fw fa-print"></i> Cetak',
							Url::to(['registration','id'=>$activity->id,'class_id'=>$class->id]),
							[
								'class'=>'btn btn-default btn-xs modal-heart',
								'title'=>'Cetak Data Registrasi Peserta',
								'data-pjax'=>'0',
								'data-toggle'=>"tooltip",
								'data-placement'=>"top",
							]
						);
				?>
			</td>
		</tr>
		<tr>
			<td><i class="fa fa-fw fa-link"></i></td>
			<td>Deskplate Peserta</td>
			<td>
				<?php
				echo Html::a('<i class="fa fa-fw fa-print"></i> Cetak',
							Url::to(['deskplate','id'=>$activity->id,'class_id'=>$class->id]),
							[
								'class'=>'btn btn-default btn-xs modal-heart',
								'title'=>'Cetak Deskplate Peserta',
								'data-pjax'=>'0',
								'data-toggle'=>"tooltip",
								'data-placement'=>"top",
							]
						);
				?>
			</td>
		</tr>
		
		<tr>
			<td><i class="fa fa-fw fa-link"></i></td>
			<td>Tanda Terima Bahan Ajar & ATK</td>
			<td>
				<?php
				echo Html::a('<i class="fa fa-fw fa-print"></i> Cetak',
							Url::to(['receipt','id'=>$activity->id,'class_id'=>$class->id]),
							[
								'class'=>'btn btn-default btn-xs modal-heart',
								'title'=>'Cetak Tanda Terima Bahan Ajar & ATK',
								'data-pjax'=>'0',
								'data-toggle'=>"tooltip",
								'data-placement'=>"top",
							]
						);
				?>
			</td>
		</tr>
		<tr>
			<td><i class="fa fa-fw fa-link"></i></td>
			<td>Surat Mengikuti Diklat</td>
			<td>
				<?php
				echo Html::a('<i class="fa fa-fw fa-print"></i> Cetak',
							Url::to(['follow-training','id'=>$activity->id,'class_id'=>$class->id]),
							[
								'class'=>'btn btn-default btn-xs modal-heart',
								'title'=>'Cetak Surat Mengikuti Diklat',
								'data-pjax'=>'0',
								'data-toggle'=>"tooltip",
								'data-placement'=>"top",
							]
						);
				?>
			</td>
		</tr>
		</table>
	</div>
</div>
