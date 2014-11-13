<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ProgramSubjectDocument */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label'=>'Program','url'=>['program/index']];
$this->params['breadcrumbs'][] = ['label'=> \yii\helpers\Inflector::camel2words($program_name),'url'=>['program-subject/index','tb_program_id'=>(int)$tb_program_id]];
$this->params['breadcrumbs'][] = ['label' => 'Document : '.$program_subject_name, 'url' => ['index','tb_program_id'=>(int)$tb_program_id,'tb_program_subject_id'=>(int)$tb_program_subject_id]];
$this->params['breadcrumbs'][] = $this->title;
$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
?>
<div class="program-subject-document-view">

    <?= DetailView::widget([
        'model' => $model,
		'mode'=>DetailView::MODE_VIEW,
		'panel'=>[
			'heading'=>'<i class="fa fa-fw fa-globe"></i> '.'Program Subject Documents # ' . $model->id,
			'type'=>DetailView::TYPE_DEFAULT,
		],
		'buttons1'=> Html::a('<i class="fa fa-fw fa-arrow-left"></i> BACK',['index','tb_program_id'=>(int)$tb_program_id,'tb_program_subject_id'=>(int)$tb_program_subject_id],
						['class'=>'btn btn-xs btn-primary',
						 'title'=>'Back to Index',
						]),
        'attributes' => [
            'id',
            [
				'attribute' => 'tb_program_subject_id',
				'label' => 'Program Subject',
				'value' => $model->programSubject->name,
			],
            [
				'format' => 'html',
				'attribute' => 'revision',
				'value' => Html::a(($model->revision>0)?$model->revision.'x':'-', '#', [
						'class'=>'label label-danger',
					]),
			],
            'name',
            'type',
            [
				'format' => 'html',
				'attribute' => 'filename',
				'label' => 'Filename',
				'value' => Html::a($model->filename, ['/file/download','file'=>'program/'.$tb_program_id.'/subject/'.$tb_program_subject_id.'/document/'.$model->filename], [
								'class' => 'badge',
								'data-pjax' => '0',
							]),
			],
            'description',
            [
				'format' => 'html',
				'attribute' => 'status',
				'value' => Html::a(($model->status==1)?'<span class="glyphicon glyphicon-ok"></span> Published':'<span class="glyphicon glyphicon-remove"></span> Unpublished', '#', [
						'class'=>($model->status==1)?'label label-info':'label label-warning',
					]),
			],
            'created',
            'createdBy',
            'modified',
            'modifiedBy',
            'deleted',
            'deletedBy',
        ],
    ]) ?>

</div>
