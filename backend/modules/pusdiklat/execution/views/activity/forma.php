<?php
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use backend\models\TrainingClassStudent;

/* @var $this yii\web\View */
/* @var $model backend\models\Program */
/* @var $form yii\widgets\ActiveForm */
$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
$this->title = Yii::t('app', 'Generate {modelClass}: ', [
    'modelClass' => 'Form A',]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Generate Dokumen Form A'), 'url' => ['./evaluation/activity/generate-dokumen','id'=>$model->id]];
$this->params['breadcrumbs'][] = ['label' => 'Form A'];
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
						'forma-generate','id'=>$model->id
					], 
				]);
			?>
            
            <?= $form->errorSummary($model) ?> <!-- ADDED HERE -->
            <div class="row clearfix">
                <div class="col-md-12">
                <?php
				echo Html::beginTag('label',['class'=>'control-label']).'Nomor Form A'.Html::endTag('label');
				echo Html::input('text','nomor_forma','',['class'=>'form-control','id'=>'nomor_forma']);
				?>
                </div>               
            </div>
            <div class="row clearfix">
                <div class="col-md-12">
                <?php
				$jml_peserta = TrainingClassStudent::find()
							->where(['training_id'=>$model->id])
							->count();
				echo Html::beginTag('label',['class'=>'control-label']).'Jumlah Peserta Diklat'.Html::endTag('label');
				echo Html::input('text','jml_peserta',$jml_peserta,['class'=>'form-control','disabled'=>true,'id'=>'nomor_forma']);
				?>
                </div>               
            </div>
            <div class="row clearfix">
                <div class="col-md-6">
                <?php
				echo Html::beginTag('label',['class'=>'control-label']).'NPP Diklat Awal'.Html::endTag('label');
                echo Html::input('text','npp_awal',$model->training->number."-".$npp_awal,['class'=>'form-control','disabled'=>true,'id'=>'npp_awal']);
				?>
                </div> 
                <div class="col-md-6">
                <?php
				echo Html::beginTag('label',['class'=>'control-label']).'NPP Diklat Akhir'.Html::endTag('label');
				echo Html::input('text','npp_akhir',$model->training->number."-".$npp_akhir,['class'=>'form-control','disabled'=>true,'id'=>'npp_akhir']);
				?>
                </div>               
            </div>
                 
            <div class="clearfix"><hr></div> 
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Generate') : Yii::t('app', 'Generate'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        
            <?php ActiveForm::end(); ?>
            
            <?php $this->registerCss('label{display:block !important;}'); ?>
        </div>
	</div>
</div>


