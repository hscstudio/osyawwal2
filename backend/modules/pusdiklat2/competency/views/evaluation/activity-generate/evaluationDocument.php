<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use backend\models\Person;
use backend\models\Employee;
use yii\helpers\Url;
use backend\models\ActivityRoom;
use kartik\widgets\DepDrop;


/* @var $this yii\web\View */
/* @var $model backend\models\Program */
/* @var $form yii\widgets\ActiveForm */
$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
$this->title = Yii::t('app', 'Generate {modelClass}: ', [
    'modelClass' => 'Dokumen Evaluasi Tatap Muka',]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Generate Dokumen Umum'), 'url' => ['./evaluation/activity/generate-dokumen','id'=>$model->id]];
$this->params['breadcrumbs'][] = ['label' => 'Dokumen Evaluasi Tatap Muka'];
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
						'training-direct-evaluation-word','id'=>$model->id
					], 
				]);
			?>
            
            <?= $form->errorSummary($model) ?> <!-- ADDED HERE -->
            <?php
				echo Html::beginTag('label',['class'=>'control-label']).'Tempat'.Html::endTag('label');
				echo Html::input('text','ruang','',['class'=>'form-control','id'=>'ruang']);
			?>	
           
            <?php
			$data = ArrayHelper::map(Person::find()
				->select(['id','name'])
				->where([
					'id'=>Employee::find()
						->select('person_id')
						->where([
							'satker_id'=>18,
							'chairman'=>1,// CEK ID 393 IN TABLE ORGANISATION IS SUBBIDANG PROGRAM
						])
						->column(),
				])		
				->active()
				->asArray()
				->all()
				, 'id', 'name');
			echo '<label class="control-label">TTD</label>';
			echo Select2::widget([
				'name' => 'ttd', 
				'data' => $data,
				'options' => [
					'placeholder' => 'Select TTD ...',
					'onchange'=>'
						$.post( "'.Url::to(['ttdnip']).'?id="+$(this).val(), 
							function( data ) {
							  $( "input#ttdnip" ).val( data + " ");
							  $( "input#ttdnip" ).focus();
							});
					',
					'class'=>'form-control', 
					'multiple' => false,
					'id'=>'ttd',
				],
			]);
			?>	
            <?php
				echo Html::beginTag('label',['class'=>'control-label']).'NIP'.Html::endTag('label');
				echo Html::input('text','ttdnip','',['class'=>'form-control','id'=>'ttdnip']);
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


