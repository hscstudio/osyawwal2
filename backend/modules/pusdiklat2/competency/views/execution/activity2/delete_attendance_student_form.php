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
$this->title = Yii::t('app', 'Hapus {modelClass}: ', [
    'modelClass' => 'Data Peserta',]);
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Generate NPP Peserta'), 'url' => ['','id'=>$model->id]];
$this->params['breadcrumbs'][] = ['label' => 'Training Student'];
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
						'delete-student','id'=>$model->training_id,'student_id'=>$model->student_id,'training_student_id'=>$model->id,
					], 
				]);
			?>
            
            <?= $form->errorSummary($model) ?> <!-- ADDED HERE -->
            <div class="row clearfix">
                <div class="col-md-12">
                <?php
				echo '<label class="control-label">Nama Peserta</label>';
				echo Html::input('text','nama_peserta',$model->student->person->name,['class'=>'form-control','disabled'=>true,'id'=>'nama_peserta'])
				?>
                </div>               
            </div>
            <div class="row clearfix">
                <div class="col-md-12">
                <?php
				echo '<label class="control-label">NIP</label>';
				echo Html::input('text','nip',$model->student->person->nip,['class'=>'form-control','disabled'=>true,'id'=>'nip'])
				?>
                </div>               
            </div>
            <div class="row clearfix">
                <div class="col-md-12">
                <?php
				echo '<label class="control-label">Nama Diklat</label>';
				echo Html::input('text','training',$model->training->activity->name,['class'=>'form-control','disabled'=>true,'id'=>'training'])
				?>
                </div>               
            </div>
            <div class="clearfix"><hr></div> 
            <table class="table table-condensed">
            <tr class="active">
            	<td align="center">Kelas</td>
  				<td align="center">Data Kehadiran</td>
                <td align="center">Data Sertifikat</td>
            </tr>  
            <tr class="danger">
            	<td align="center"><?php echo $model_data_kelas;?></td>
  				<td align="center"><?php echo $model_data_kehadiran;?></td>
                <td align="center"><?php echo $model_data_sertifikat;?></td>
            </tr>            
            </table>
            <div class="well">
                <p class="lead">Apakah Anda Sudah Yakin Untuk Menghapus Data Peserta Ini ??</p>
                <p class="lead" style="text-align:center"> 
                <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Delete') : Yii::t('app', 'Ya...Delete'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?></p>
            </div>                          
            <?php ActiveForm::end(); ?>
            
            <?php $this->registerCss('label{display:block !important;}'); ?>
        </div>
	</div>
</div>


