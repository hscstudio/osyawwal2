<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use backend\models\Person;
use backend\models\Employee;
use backend\models\ActivityRoom;
use backend\models\Room;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\Program */
/* @var $form yii\widgets\ActiveForm */
$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
$this->title = Yii::t('app', 'Generate {modelClass}: ', [
    'modelClass' => 'Surat Tugas Terkait Diklat',]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Generate Dokumen Umum'), 'url' => ['./evaluation/activity/generate-dokumen','id'=>$model->id]];
$this->params['breadcrumbs'][] = ['label' => 'Surat Tugas Terkait Diklat'];
?>
<div class="activity-update panel panel-default">
	
    <div class="panel-heading">		
		<div class="pull-right">
        <?= (Yii::$app->request->isAjax)?'':Html::a('<i class="fa fa-fw fa-arrow-left"></i> Back', ['index'], ['class' => 'btn btn-xs btn-primary']) ?>
		</div>
		<h1 class="panel-title"><?= Html::encode($this->title) ?></h1>
	</div>
	<div class="panel-body">
		<div class="letter-assignment-form">
			<?php
				$form = ActiveForm::begin([
					'options'=>[
						'id'=>'myform',
						'onsubmit'=>'',
					],
					'action'=>[
						'class','id'=>$model->id
					], 
				]);
			?>
            
            <?= $form->errorSummary($model) ?> <!-- ADDED HERE -->
            <?php
				$data = ArrayHelper::map(ActivityRoom::find()
				->joinWith('room')
				->where(['activity_id'=>$model->id,'activity_room.status'=>2])
				->asArray()
				->all()
				, 'room_id','room.name');
			echo '<label class="control-label">Tempat</label>';
			echo Select2::widget([
				'name' => 'ruang', 
				'data' => $data,
				'options' => [
					'placeholder' => 'Select Room ...', 
					'class'=>'form-control', 
					'multiple' => false,
					'id'=>'ruang',
				],
			]);
			?>	
            <?php
				echo Html::beginTag('label',['class'=>'control-label']).'Tugas'.Html::endTag('label');
				echo Html::input('text','student','menjadi pengamat dan pengawas ujian',['class'=>'form-control','id'=>'count']);
			?>	
            <?php
			$data = ArrayHelper::map(Person::find()
				->select(['id', 'name'])
				->where([
					'id'=>Employee::find()
						->select('person_id')
						->where([
							'organisation_id'=>69, // CEK ID 393 IN TABLE ORGANISATION IS SUBBIDANG PROGRAM
						])
						//->currentSatker()
						->column(),
				])		
				->active()
				->asArray()
				->all()
				, 'id', 'name');
			echo '<label class="control-label">Petugas</label>';
			echo Select2::widget([
				'name' => 'baseon', 
				'data' => $data,
				'options' => [
					'placeholder' => 'Select Petugas ...', 
					'class'=>'form-control', 
					'multiple' => true,
					'id'=>'baseon',
				],
			]);
			?>
            <?php
			$data = ArrayHelper::map(Person::find()
				->select(['id','name','position'])
				->where([
					'id'=>Employee::find()
						->select('person_id')
						->where([
							'organisation_id'=>56,// CEK ID 393 IN TABLE ORGANISATION IS SUBBIDANG PROGRAM
						])
						//->currentSatker()
						->column(),
				])		
				->active()
				->asArray()
				->all()
				, 'id', 'name');
			echo '<label class="control-label">TTD</label>';
			echo Select2::widget([
				'name' => 'baseon', 
				'data' => $data,
				'options' => [
					'placeholder' => 'Select TTD ...', 
					'class'=>'form-control', 
					'multiple' => false,
					'id'=>'baseon_satu',
				],
			]);
			?>	
            <?php
				echo Html::beginTag('label',['class'=>'control-label']).'NIP'.Html::endTag('label');
				echo Html::input('text','student','',['class'=>'form-control','id'=>'count']);
			?>	
            <?php
				echo Html::beginTag('label',['class'=>'control-label']).'Jabatan'.Html::endTag('label');
				echo Html::input('text','student','',['class'=>'form-control','id'=>'count']);
			?>	
            <div class="clearfix"><hr></div> 
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Generate') : Yii::t('app', 'Generate'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        
            <?php ActiveForm::end(); ?>
            
            <?php $this->registerCss('label{display:block !important;}'); ?>
        </div>
	</div>
</div>


