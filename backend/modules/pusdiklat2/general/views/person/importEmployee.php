<?php

use yii\helpers\Html;
use yii\grid\GridView as Gridview2;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Inflector;
use hscstudio\heart\widgets\Box;
use kartik\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\pusdiklat\execution\models\TrainingClassSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = 'Employee Import ';

?>
<div class="training-class-index">
	<?php	
	$form = ActiveForm::begin();
	?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
		'pjax' => true, // pjax is set to always true for this demo
        /* 'filterModel' => $searchModel, */
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
			[
				'attribute'=>'name',
				
				'format' => 'raw',
				'value' => function ($data) {
					return 
						Html::input('text','name[]',$data['name'],['class'=>'form-control']).
						Html::input('hidden','row[]',$data['row']).
						Html::input('hidden','person_id[]',$data['person_id']).
						Html::input('hidden','employee_id[]',$data['employee_id']).
						Html::input('hidden','user_id[]',$data['user_id']).
						Html::input('hidden','password[]',$data['password']);
				}
			],
			[
				'attribute'=>'nip',
				'width'=> '200px',
				'format' => 'raw',
				'value' => function ($data){
					return Html::input('text','nip[]',$data['nip'],['class'=>'form-control']);
				}
			],
			[
				'header'=>'Jabatan',
				'format' => 'raw',
				'width'=> '200px',
				'value' => function ($data){
					$datas = [
						'1' => 'Pelaksana',
						'2' => 'Pejabat',
						'3' => 'Widyaiswara',
						'4' => 'Pranata Komputer',
					];
					return \kartik\widgets\Select2::widget([
						'name' => 'jabatan_id[]',
						'value'=> $data['jabatan_id'],
						'data' => $datas,
						'options' => ['placeholder' => 'Choose jabatan ...'],
						'pluginOptions' => [
							'allowClear' => true
						],
					]);
				}
			],
			[
				'header'=>'Organisation',
				'format' => 'raw',
				'value' => function ($data){
					/* return Html::input('text','unit[]',$data['unit'],['class'=>'form-control']); */
					$datas = ArrayHelper::map(
						\backend\models\Organisation::find()
							->select(['ID','concat(KD_UNIT_ORG," - ",SUBSTRING(NM_UNIT_ORG,1,20)) as code_name'])
							->where(['JNS_KANTOR'=>'13'])
							->asArray()
							->all(), 
							'ID', 'code_name'
					);
					return \kartik\widgets\Select2::widget([
						'name' => 'organisation_id[]',
						'value'=> $data['organisation_id'],
						'data' => $datas,
						'options' => ['placeholder' => 'Choose organisation ...'],
						'pluginOptions' => [
							'allowClear' => true
						],
					]);
				}
			],
			[
				'class' => 'kartik\grid\CheckboxColumn',
			],
        ],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i></h3>',
			'after'=>
				Html::a('<i class="fa fa-fw fa-arrow-left"></i> Back', \yii\helpers\Url::to(['index']), ['class' => 'btn btn-warning']).' '.
				Html::submitButton('Submit', ['class'=>'btn btn-primary']),
			'showFooter'=>true
		],
		'responsive'=>true,
		'hover'=>true,
    ]); ?>
	<?php
	ActiveForm::end();
	?>
	<?= \hscstudio\heart\widgets\Modal::widget() ?>
</div>
<?php

?>