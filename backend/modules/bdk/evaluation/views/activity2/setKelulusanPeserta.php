<?php
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use backend\models\TrainingClassStudent;
use backend\models\TrainingClass;
use backend\models\Reference;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
/* @var $model backend\models\Program */
/* @var $form yii\widgets\ActiveForm */
$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
$this->title = Yii::t('app', 'Set Kelulusan {modelClass}: ', [
    'modelClass' => 'Peserta',]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Class Student'), 'url' => ['class','id'=>$model->id]];
$this->params['breadcrumbs'][] = ['label' => 'Set Kelulusan Peserta'];
?>
<div class="activity-update panel panel-default">
	
    <div class="panel-heading">		
		<div class="pull-right">
        <?= (Yii::$app->request->isAjax)?'':Html::a('<i class="fa fa-fw fa-arrow-left"></i> Back', ['class','id'=>$model->id], ['class' => 'btn btn-xs btn-primary']) ?>
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
						'./activity-generate/set-kelulusan-peserta-diklat','id'=>$model->id,'class_id'=>$class_id,
					], 
				]);
			?>
            
            <?= $form->errorSummary($model) ?> <!-- ADDED HERE -->
            <div class="row clearfix">
                <div class="col-md-12">
                <?php
				echo '<label class="control-label">Nama Diklat</label>';
				echo Html::input('text','training',$model->name,['class'=>'form-control','disabled'=>true,'id'=>'training'])
				?>
                </div>               
            </div>
            <div class="row clearfix">
                <div class="col-md-12">
                <?php
				echo '<label class="control-label">Nama Kelas</label>';
				echo Html::input('text','class',TrainingClass::findOne(['id'=>$class_id])->class,['class'=>'form-control','disabled'=>true,'id'=>'class'])
				?>
                </div>               
            </div>
            <div class="row clearfix">
                <div class="col-md-4">NAMA PEGAWAI</div>
                <div class="col-md-3">NIP</div>
                <div class="col-md-5">STATUS KELULUSAN</div>
            </div>
            <?php
			$dataProvider = new ActiveDataProvider([
            'query' => TrainingClassStudent::find()
									->joinWith('trainingStudent')
									->joinWith('trainingStudent.student')
									->joinWith('trainingStudent.student.person')
									->where(['training_class_student.training_id'=>$model->id,'training_class_student.training_class_id'=>$class_id])
									->orderBy('name')
			]);
			$dataProvider->setPagination(false);
			$no=1;
			foreach($dataProvider->getModels() as $person){
				echo "<div class='row clearfix'>";
				echo "<div class='col-md-4'><input type='checkbox' checked='checked' name='admin[]' value='".$person->id."'> ".$person->trainingStudent->student->person->name."</div>";
				echo "<div class='col-md-3'>".$person->trainingStudent->student->person->nip."</div>";
				echo "<div class='col-md-5'>".
												Select2::widget([
												'name' => 'status_lulus['.$person->id.']', 
												'data' => [0=>'Tidak Lulus',1=>'Lulus',2=>'Mengulang'],
												'value' => $person->status,
												'options' => [
													'placeholder' => 'Select Status Kelulusan ...', 
													//'class'=>'form-control', 
													'multiple' => false,
													//'id'=>'status_lulus['.$person->training_student_id.']',
												],
				])."</div>";
				echo "</div>";
				$no++;
			} 
			
			?>                          
            <div class="clearfix"><hr></div> 
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Update') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        

            <?php ActiveForm::end(); ?>
            
            <?php $this->registerCss('label{display:block !important;}'); ?>
        </div>
	</div>
</div>


