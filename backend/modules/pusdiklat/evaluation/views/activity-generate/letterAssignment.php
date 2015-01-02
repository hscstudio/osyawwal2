<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use backend\models\Person;
use backend\models\Employee;
use backend\models\ActivityRoom;
use backend\models\Room;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\Program */
/* @var $form yii\widgets\ActiveForm */
$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
$this->title = Yii::t('app', 'Generate {modelClass}: '.$model->name, [
    'modelClass' => 'Surat Tugas Terkait Diklat',]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Generate Dokumen Umum'), 'url' => ['activity/generate-dokumen','id'=>$model->id]];
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
         	<div class="row clearfix">
                <div class="col-md-4">Tempat</div>
                <div class="col-md-4">Tugas</div>
                <div class="col-md-4">TTD</div>                
            </div>
            <div class="row clearfix">
                <div class="col-md-4">
                <?php
				$form = ActiveForm::begin([
					'options'=>[
						'id'=>'myform',
						'onsubmit'=>'',
					],
					'action'=>[
						'letter-assignment-word','id'=>$model->id
					], 
				]);
			?>
            
            <?= $form->errorSummary($model) ?> <!-- ADDED HERE -->
            <?php
			echo Html::input('text','ruang','',['class'=>'form-control','id'=>'ruang']);
			?>	
                </div>
                <div class="col-md-4">
                <?php
				echo Html::input('text','tugas','menjadi pengamat dan pengawas ujian',['class'=>'form-control','id'=>'tugas']);
				?>	
                </div>
                <div class="col-md-4">
                <?php
			$data = ArrayHelper::map(Person::find()
				->select(['id','name'])
				->where([
					'id'=>Employee::find()
						->select('person_id')
						->where([
							'satker_id'=>Yii::$app->user->identity->employee->satker_id,
							'chairman'=>1,// CEK ID 393 IN TABLE ORGANISATION IS SUBBIDANG PROGRAM
						])
						->column(),
				])		
				->active()
				->asArray()
				->all()
				, 'id', 'name');
			
			echo Select2::widget([
				'name' => 'ttd', 
				'data' => $data,
				'options' => [
					'placeholder' => 'Select TTD ...', 
					'class'=>'form-control', 
					'multiple' => false,
					'id'=>'ttd',
				],
			]);
			?>	
                </div>                
            </div>
            <div class="row clearfix">
                <div class="col-md-12">Petugas</div>                
            </div>
            <div class="clearfix"><hr></div>
            <div class="row clearfix">
                <div class="col-md-3">NAMA</div>
                <div class="col-md-3">NIP</div>
                <div class="col-md-6">TANGGAL</div>
            </div>
				
			<?php
			$dataProvider = new ActiveDataProvider([
            'query' => Employee::find()
									->where(['organisation_id'=>'400'])
			]);
			$no=1;
			foreach($dataProvider->getModels() as $person){
				echo "<div class='row clearfix'>";
				echo "<div class='col-md-3'><input type='checkbox' name='admin[]' value='".$person->person_id."'> ".$person->person->name."</div>";
				echo "<div class='col-md-3'>".$person->person->nip."</div>";
				echo "<div class='col-md-3'>".DatePicker::widget([
						'name' => 'start['.$person->person_id.']',
						'type' => DatePicker::TYPE_COMPONENT_PREPEND,
						'value' => date('d-M-Y'),
						'pluginOptions' => [
							'autoclose'=>true,
							'format' => 'dd-M-yyyy',
							'todayHighlight' => true
						]
					])."</div>";
				echo "<div class='col-md-3'>".
						DatePicker::widget([
						'name' => 'finish['.$person->person_id.']',
						'type' => DatePicker::TYPE_COMPONENT_PREPEND,
						'value' => date('d-M-Y'),
						'pluginOptions' => [
							'autoclose'=>true,
							'format' => 'dd-M-yyyy',
							'todayHighlight' => true
						]
					])."</div>";
				echo "</div>";
				$no++;
			} 
			
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


