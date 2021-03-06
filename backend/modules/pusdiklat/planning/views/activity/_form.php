<?php use yii\helpers\Html; use kartik\widgets\ActiveForm; use
kartik\widgets\Select2; use yii\helpers\ArrayHelper; use
kartik\datecontrol\DateControl; use kartik\widgets\SwitchInput; use
backend\models\Reference; use backend\models\Program; use
kartik\widgets\FileInput; use yii\helpers\Url; use kartik\checkbox\CheckboxX;

$edited = 1; // permit
if(!$model->isNewRecord){
	if($model->status==2){
		$edited = 2; // permit with warning
	}
		
	$countTrainingSubject = \backend\models\TrainingClassSubject::find()
		->where([
			'training_class_id'=>\backend\models\TrainingClass::find()
				->where([
					'training_id' => $model->id
				])
				->column(),		
		])
		->count();
	if($countTrainingSubject>0){
		$edited = 3; // refused
	}
}
?>

<div class="activity-form">

    <?php $form = ActiveForm::begin(); ?>
	<?= $form->errorSummary($model) ?> <!-- ADDED HERE -->
	
	<ul class="nav nav-tabs" role="tablist" id="tab_wizard">
		<li class="active"><a href="#activity" role="tab" data-toggle="tab">Data Umum <span class='label label-info'>1</span></a></li>
		<li class=""><a href="#training" role="tab" data-toggle="tab">Data Diklat <span class='label label-warning'>2</span></a></li>
	</ul>
	<div class="tab-content" style="border: 1px solid #ddd; border-top-color: transparent; padding:10px; background-color: #fff;">
		<div class="tab-pane fade-in active" id="activity">

			<div class="row clearfix">
				<div class="col-md-4">
					<?php
					$data = ArrayHelper::map(
						Program::find()
							->select([
								'id','name', 'num_name' => 'CONCAT(number," - ",name)'
							])
							->currentSatker()
							->active()
							->asArray()
							->all(), 'id', 'num_name');		
					echo $form->field($training, 'program_id')->widget(Select2::classname(), [
						'data' => $data,
						'options' => [
							'placeholder' => 'Pilih Program ...',
							'onchange'=>'
								$.post( "'.Url::to(['program-name']).'?id="+$(this).val(), function( data ) {
								  $( "input#activity-name" ).val( data + " ");
								  $( "input#activity-name" ).focus();
								});
							',
							'disabled' => (in_array($edited,[2,3]))?true:false,
						],
						'pluginOptions' => [
							'allowClear' => true,
						],
					]); ?>
				</div>
				<div class="col-md-8">
					<?= $form->field($model, 'name')->textInput(['maxlength' => 255,'disabled' => (in_array($edited,[2,3]))?true:false,]) ?>
				</div>
			</div>


			<?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>
			
			<?php if(!in_array($edited,[2,3])){ ?>
			<div class="row clearfix">
				<div class="col-md-3">
				<?= $form->field($model, 'start')->widget(DateControl::classname(), [
					'type' => DateControl::FORMAT_DATE,
					'displayFormat' => 'php:d-m-Y',
					'saveFormat' => 'php:Y-m-d',
					'ajaxConversion' => false,					
					'options' => [
				        'pluginOptions' => [
				            'autoclose' => true,							
				        ],
				    ]
				]); ?>
				</div>
				<div class="col-md-3">
				<?= $form->field($model, 'end')->widget(DateControl::classname(), [
					'type' => DateControl::FORMAT_DATE,
					'displayFormat' => 'php:d-m-Y',
					'saveFormat' => 'php:Y-m-d',
					'ajaxConversion' => false,
					'options' => [
				        'pluginOptions' => [
				            'autoclose' => true
				        ],
						/* 'htmlOptions'=> [
							'disabled' => (in_array($edited,[2,3]))?true:false,
						] */
				    ]
				]); ?>
				</div>
				<div class="col-md-3">
					<?php 
					$model->location=explode('|',$model->location);

					if(empty($model->location[0]))
						$model->location = [(int)Yii::$app->user->identity->employee->satker_id];
					?>
				</div>
				<div class="col-md-3">
					<?= $form->field($model, 'hostel')->widget(SwitchInput::classname(), [
						'pluginOptions' => [
							'onText' => 'Ya',
							'offText' => 'Tidak',
						],
						'options'=>[
							'disabled' => (in_array($edited,[2,3]))?true:false,
						]
					])->label('Diasramakan?') ?>
				</div>
			</div>
			<?php } ?>

			<div class="row clearfix">
				<div class="col-md-3">
				<?php
				$data = ArrayHelper::map(
					\backend\models\Reference::find()
						->select(['id','name'])
						->where(['type'=>'satker'])
						->all(), 
						'id', 'name'
				);
				echo $form->field($model, 'location[0]')->widget(Select2::classname(), [
					'data' => $data,
					'options' => ['placeholder' => 'Pilih code ...'],
					'pluginOptions' => [
						'allowClear' => true
					],
					'options'=>[
						'disabled' => (in_array($edited,[2,3]))?true:false,
					]
				]);
				?>
				</div>
				<div class="col-md-9">		
				<?= $form->field($model, 'location[1]')->textInput(['maxlength' => 250,'disabled' => (in_array($edited,[2,3]))?true:false,])->label('Catatan Terkait Lokasi'); ?>
				</div>
			</div>
			

			<?php 
			$permit = \Yii::$app->user->can('pusdiklat-planning-1');
			
			if(!in_array($edited,[2,3])){
			if(!$model->isNewRecord and $permit){ ?>
				<div class='row'>
					<div class='col-md-3'>				
					<?php
					$data = [
							'0'=>'Rencana',
							'1'=>'Siap',
							//'2'=>'EXECUTE',
							'3'=>'Batal'
					];
					echo $form->field($model, 'status')->widget(Select2::classname(), [
						'data' => $data,
						'options' => ['placeholder' => 'Pilih Status ...'],
						'pluginOptions' => [
							'allowClear' => true,
						],
					]); ?>
					</div>
					<div class='col-md-6'>

					</div>	
				</div>
			<?php
				}
			}
			?>
			
			<a class="btn btn-default tendangKePojok" onclick="$('#tab_wizard a[href=#training]').tab('show')">
				Berikutnya
				<i class="fa fa-fw fa-arrow-circle-o-right"></i>
			</a>
		</div>
		<div class="tab-pane fade" id="training">	
			<?php if(!$model->isNewRecord){ ?>
				<?= $form->field($training, 'number', [
						'addon' => ['prepend' => ['content'=>'Generate Number '.CheckboxX::widget([
											'name'=>'generate_number','value'=>1,
											'options'=>[
												'disabled' => (in_array($edited,[2,3]))?true:false,
											],
											'pluginOptions'=>['threeState'=>false,'size'=>'xs']
						])]]
					])->textInput([
						'disabled' => (in_array($edited,[2,3]))?true:false,
					]) ?>
			<?php } ?>
			
			<div class="row clearfix">
				<div class="col-md-2">
				<?= $form->field($training, 'regular')->widget(SwitchInput::classname(), [
					'pluginOptions' => [
						'onText' => 'Ya',
						'offText' => 'Tidak',
					]
				]) ?>
				</div>
				<div class="col-md-10">
				<?= $form->field($training, 'stakeholder')->textInput(['maxlength' => 255]) ?>
				</div>
			</div>
			
			<div class="row clearfix">
				<div class="col-md-3">
					<?= $form->field($training, 'student_count_plan')->textInput() ?>
				</div>
				<div class="col-md-3">
					<?= $form->field($training, 'class_count_plan')->textInput() ?>
				</div>
				<div class="col-md-3">
					<?= $form->field($training, 'cost_source')->textInput(['maxlength' => 255]) ?>
				</div>
				<div class="col-md-3">
					<?= $form->field($training, 'cost_plan')->textInput(['maxlength' => 15]) ?>
				</div>
			</div>			
			
			<?= $form->field($training, 'note')->textInput(['maxlength' => 255]) ?>
			
			<a class="btn btn-default tendangKePojok" onclick="$('#tab_wizard a[href=#activity]').tab('show')">
				Sebelumnya 
				<i class="fa fa-fw fa-arrow-circle-o-left"></i>
			</a>
			
			<div class="clearfix"><hr></div>  
			
			<div class="form-group">
				<?= Html::submitButton('<i class="fa fa-fw fa-save"></i> '. ($model->isNewRecord ? Yii::t('app', 'SYSTEM_BUTTON_CREATE') : Yii::t('app', 'SYSTEM_BUTTON_UPDATE')), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
				<?php if (!$model->isNewRecord){ ?>
				<?= Html::submitButton('<i class="fa fa-fw fa-save"></i> '. Yii::t('app', 'SYSTEM_BUTTON_UPDATE_REVISION'), ['class' => 'btn btn-warning', 'name' => 'create_revision',]) ?>
				<?php } ?>
			</div>
			<?php if (!$model->isNewRecord){ ?>
				<div class="well">
					<?php echo Yii::t('app', 'SYSTEM_BUTTON_UPDATE_REVISION');?> artinya jika Anda menginginkan agar data lama tetap tersimpan dalam history. Fungsi ini sebaiknya Anda gunakan 
					apabila perubahan yang Anda lakukan memang sangat mendasar atau signifikan. Adapun perubahan kecil seperti salah penulisan atau ejaan, maka 
					cukup gunakan fungsi Update saja.
				</div>
			<?php } ?>
			<?php // PENYELENGGARAAN ONLY ?>
			<?php // $form->field($training, 'execution_sk')->textInput(['maxlength' => 255]) ?>
			<?php // $form->field($training, 'cost_real')->textInput(['maxlength' => 15]) ?>
			
			<?php // EVALUASI ONLY ?>
			<?php // $form->field($training, 'result_sk')->textInput(['maxlength' => 255]) ?>		
			
			<?php // BDK ONLY ?>
			<?php // $form->field($training, 'approved_status')->textInput() ?>
			<?php // $form->field($training, 'approved_note')->textInput(['maxlength' => 255]) ?>
			<?php // $form->field($training, 'approved_date')->textInput() ?>
			<?php // $form->field($training, 'approved_by')->textInput() ?>
		</div>
	</div>
	 

    <?php ActiveForm::end(); ?>
	<?php $this->registerCss('label{display:block !important;}'); ?>

</div>

<?php
	$this->registerCss('
		.tendangKePojok {
			position: absolute;
			top: 30px;
			right: 30px;
		}
	');
?>