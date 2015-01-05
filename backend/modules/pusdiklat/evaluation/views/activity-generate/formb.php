<?php
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use backend\models\TrainingClassStudent;
use backend\models\Reference;
use backend\models\Organisation;
use backend\models\Person;
use backend\models\Employee;

/* @var $this yii\web\View */
/* @var $model backend\models\Program */
/* @var $form yii\widgets\ActiveForm */
$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
$this->title = Yii::t('app', 'Generate {modelClass}: '.$model->name, [
    'modelClass' => 'Form B',]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Generate Dokumen Umum'), 'url' => ['activity2/dashboard','id'=>$model->id]];
$this->params['breadcrumbs'][] = ['label' => 'Form B'];
?>
<div class="activity-update panel panel-default">
	<?php



    /* @var $this yii\web\View */
    /* @var $model backend\models\TrainingClassStudentCertificate */
    /* @var $form yii\widgets\ActiveForm */
    $training = $model->training;
    $numbers = explode('-',$training->number);
    // 2014-03-00-2.2.1.0.2 to /2.3.1.2.138/07/00/2014
    $number = '';
    if(isset($numbers[3]) and strlen($numbers[3])>3){
        $number .= '/'.$numbers[3];
    }
    if(isset($numbers[1]) and strlen($numbers[1])==2){
        $number .= '/'.$numbers[1];
    }
    if(isset($numbers[2]) and strlen($numbers[2])==2){
        $number .= '/'.$numbers[2];
    }
    if(isset($numbers[0]) and strlen($numbers[0])==4){
        $number .= '/'.$numbers[0];
    }
    ?>
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
						'id'=>'myformb',
						'onsubmit'=>'',
					],
					'action'=>[
						'generate-formb','id'=>$model->id
					], 
				]);
			?>
            
            <?= $form->errorSummary($model) ?> <!-- ADDED HERE -->
            <div class="row clearfix">
                <div class="col-md-12">
                <?php
				echo Html::beginTag('label',['class'=>'control-label']).'Nomor Form B'.Html::endTag('label');
				echo Html::input('text','nomor_formb',$model->training->number_formb,['class'=>'form-control','id'=>'nomor_formb']);
				?>
                </div>               
            </div>
            <div class="row clearfix">
                <div class="col-md-6">
                <?php
				echo Html::beginTag('label',['class'=>'control-label']).'SKPP Diklat Awal'.Html::endTag('label');
                echo Html::input('text','skpp_awal',$skpp_awal.$number,['class'=>'form-control','id'=>'skpp_awal']);
				?>
                </div> 
                <div class="col-md-6">
                <?php
				echo Html::beginTag('label',['class'=>'control-label']).'SKPP Diklat Akhir'.Html::endTag('label');
				echo Html::input('text','skpp_akhir',$skpp_akhir.$number,['class'=>'form-control','id'=>'skpp_akhir']);
				?>
                </div>               
            </div>
            <div class="row clearfix">
                <div class="col-md-6">
                <?php
				echo Html::beginTag('label',['class'=>'control-label']).'Jabatan Penandatangan I'.Html::endTag('label');
				echo Html::input('text','jabatan_ttd_satu','Kepala '.Reference::findOne(['id'=>Yii::$app->user->identity->employee->satker_id])->name,['class'=>'form-control','id'=>'jabatan_ttd_satu']);
				?>
                </div>
                <div class="col-md-6">
                <?php
				echo Html::beginTag('label',['class'=>'control-label']).'Jabatan Penandatangan II'.Html::endTag('label');
				echo Html::input('text','jabatan_ttd_dua','Kepala Bidang Evaluasi dan Pelaporan Kinerja',['class'=>'form-control','id'=>'jabatan_ttd_dua']);
				?>
                </div>
            </div>  
            <div class="row clearfix">
                <div class="col-md-6">
                <?php
				echo Html::beginTag('label',['class'=>'control-label']).'Nama Penandatangan I'.Html::endTag('label');
				echo Html::input('text','nama_ttd_satu',Person::findOne(['id'=>Employee::findOne(['satker_id'=>Yii::$app->user->identity->employee->satker_id,'organisation_id'=>'387','chairman'=>'1'])->person_id])->name,['class'=>'form-control','id'=>'ttd_satu']);
				?>
                </div>
                <div class="col-md-6">
                <?php
				echo Html::beginTag('label',['class'=>'control-label']).'Nama Penandatangan II'.Html::endTag('label');
				echo Html::input('text','nama_ttd_dua',Person::findOne(['id'=>Employee::findOne(['satker_id'=>Yii::$app->user->identity->employee->satker_id,'organisation_id'=>'399','chairman'=>'1'])->person_id])->name,['class'=>'form-control','id'=>'ttd_dua']);
				?>
                </div>
            </div>    
            <div class="row clearfix">
                <div class="col-md-6">
                <?php
				echo Html::beginTag('label',['class'=>'control-label']).'NIP Penandatangan I'.Html::endTag('label');
				echo Html::input('text','nip_ttd_satu',Person::findOne(['id'=>Employee::findOne(['satker_id'=>Yii::$app->user->identity->employee->satker_id,'organisation_id'=>'387','chairman'=>'1'])->person_id])->nip,['class'=>'form-control','id'=>'nip_ttd_satu']);
				?>
                </div>
                <div class="col-md-6">
                <?php
				echo Html::beginTag('label',['class'=>'control-label']).'NIP Penandatangan II'.Html::endTag('label');
				echo Html::input('text','nip_ttd_dua',Person::findOne(['id'=>Employee::findOne(['satker_id'=>Yii::$app->user->identity->employee->satker_id,'organisation_id'=>'399','chairman'=>'1'])->person_id])->nip,['class'=>'form-control','id'=>'nip_ttd_dua']);
				?>
                </div>
            </div>
            <div class="row clearfix">
                <div class="col-md-12">
                <?php
				echo Html::beginTag('label',['class'=>'control-label']).'Keterangan Lain'.Html::endTag('label');
				echo Html::input('text','note_formb',$model->training->note_formb,['class'=>'form-control','id'=>'note_formb']);
				?>
                </div>               
            </div>
                 
            <div class="clearfix"><hr></div> 
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Generate') : Yii::t('app', 'Generate Form B'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        
            <?php ActiveForm::end(); ?>
            
            <?php $this->registerCss('label{display:block !important;}'); ?>
        </div>
	</div>
</div>


