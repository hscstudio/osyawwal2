<?php
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use backend\models\TrainingClassStudent;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
/* @var $model backend\models\Program */
/* @var $form yii\widgets\ActiveForm */
$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
$this->title = Yii::t('app', 'Generate {modelClass}: ', [
    'modelClass' => 'NPP Peserta',]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Generate NPP Peserta'), 'url' => ['','id'=>$model->id]];
$this->params['breadcrumbs'][] = ['label' => 'NPP Peserta'];
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
						'npp-generate','id'=>$model->training_id,'class_id'=>$model->training_class_id,
					], 
				]);
			?>
            
            <?= $form->errorSummary($model) ?> <!-- ADDED HERE -->
            <div class="row clearfix">
                <div class="col-md-12">
                <?php
				echo '<label class="control-label">Nama Diklat</label>';
				echo Html::input('text','training',$model->training->activity->name,['class'=>'form-control','disabled'=>true,'id'=>'training'])
				?>
                </div>               
            </div>
            <div class="row clearfix">
                <div class="col-md-12">
                <?php
				echo '<label class="control-label">Nama Kelas</label>';
				echo Html::input('text','class',$model->trainingClass->class,['class'=>'form-control','disabled'=>true,'id'=>'class'])
				?>
                </div>               
            </div>
            <div class="row clearfix">
                <div class="col-md-4">NAMA PEGAWAI</div>
                <div class="col-md-4">NIP</div>
                <div class="col-md-3">NPP</div>
                <div class="col-md-1"></div>
            </div>
            <?php
			$dataProvider = new ActiveDataProvider([
            'query' => TrainingClassStudent::find()
									->joinWith('trainingStudent')
									->joinWith('trainingStudent.student')
									->joinWith('trainingStudent.student.person')
									->where(['training_class_student.training_id'=>$model->training_id,'training_class_student.training_class_id'=>$model->training_class_id])
									->orderBy('name')
			]);
			$no=$max_npp_awal;
			foreach($dataProvider->getModels() as $person){
				echo "<div class='row clearfix'>";
				echo "<div class='col-md-4'><input type='checkbox' checked='checked' name='admin[]' value='".$person->id."'> ".$person->trainingStudent->student->person->name."</div>";
				echo "<div class='col-md-4'>".$person->trainingStudent->student->person->nip."</div>";
				echo "<div class='col-md-3'>".Html::input('text','number_training',$model->training->number."-",['class'=>'form-control','disabled'=>true,'id'=>'number_training'])."</div>";
				echo "<div class='col-md-1'>".Html::input('text','number['.$person->id.']',$no,['class'=>'form-control','id'=>'number['.$person->id.']'])."</div>";
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


