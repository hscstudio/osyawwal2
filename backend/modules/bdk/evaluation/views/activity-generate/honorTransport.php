<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use backend\models\Person;
use backend\models\Employee;
use yii\helpers\Url;
use kartik\widgets\DatePicker;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
/* @var $model backend\models\Program */
/* @var $form yii\widgets\ActiveForm */
$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
$this->title = Yii::t('app', 'Generate {modelClass}: ', [
    'modelClass' => 'Honor Transport',]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Generate Dokumen Umum'), 'url' => ['activity/generate-dokumen','id'=>$model->id]];
$this->params['breadcrumbs'][] = ['label' => 'Honor Transport'];
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
						'training-honor-transport-excel','id'=>$model->id
					], 
				]);
			?>
            
            <?= $form->errorSummary($model) ?> <!-- ADDED HERE -->
            <?php
				echo Html::beginTag('label',['class'=>'control-label']).'Pembayaran'.Html::endTag('label');
				echo Html::input('textarea','pembayaran','Pembayaran Uang Transport Monitoring '.$model->name,['class'=>'form-control','id'=>'pembayaran']);
			?>	
           
            <?php
				echo Html::beginTag('label',['class'=>'control-label']).'Surat'.Html::endTag('label');
			?>	
            <div class="row clearfix">
                <div class="col-md-4"><?php echo Html::input('text','txtsurat','Surat Keputusan Kepala ',['class'=>'form-control','id'=>'txtsurat']);?></div>
                <div class="col-md-4"><?php echo Html::input('text','nosurat','ST-   /PP.',['class'=>'form-control','id'=>'nosurat']);?></div>
                <div class="col-md-4"><?php
                echo DatePicker::widget([
						'name' => 'tgl_surat',
						'type' => DatePicker::TYPE_COMPONENT_PREPEND,
						'pluginOptions' => [
							'autoclose'=>true,
							'format' => 'dd-M-yyyy',
							'todayHighlight' => true
						]
					]);?>
				</div>                
            </div>
            <div class="row clearfix">
                <div class="col-md-12">Yang Ditugaskan</div>                
            </div>
            <div class="clearfix"><hr></div>
            <div class="row clearfix">
                <div class="col-md-4">NAMA</div>
                <div class="col-md-4">NIP</div>
                <div class="col-md-4">FREK</div>
            </div>
            <?php
			$dataProvider = new ActiveDataProvider([
            'query' => Employee::find()
									->where(['organisation_id'=>'400'])
			]);
			$no=1;
			foreach($dataProvider->getModels() as $person){
				echo "<div class='row clearfix'>";
				echo "<div class='col-md-4'><input type='checkbox' name='admin[]' value='".$person->person_id."'> ".$person->person->name."</div>";
				echo "<div class='col-md-4'>".$person->person->nip."</div>";
				echo "<div class='col-md-4'>".Html::input('textarea','frek['.$person->person_id.']','1',['class'=>'form-control','id'=>'frek'])."</div>";
				echo "</div>";
				$no++;
			} 			
			?>
            <div class="clearfix"><hr></div>
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
			echo '<label class="control-label">Atasan Pembuat Daftar</label>';
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


