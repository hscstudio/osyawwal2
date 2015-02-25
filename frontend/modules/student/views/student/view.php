<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use yii\bootstrap\Modal;
use kartik\widgets\FileInput;

/* @var $this yii\web\View */
/* @var $model frontend\models\Student */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Students', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
$data = \frontend\models\Person::find()->where(['id'=>Yii::$app->user->identity->id])->One();
$tgllahir = explode('-',$data->birthday);
?>
<div class="student-view">
    <?= DetailView::widget([
        'model' => $model,
		'mode'=>DetailView::MODE_VIEW,
		'panel'=>[
			'heading'=>'<i class="fa fa-fw fa-globe"></i> '.'Students',
			'type'=>DetailView::TYPE_DEFAULT,
		],
		
		'buttons1'=> Html::a('<i class="fa fa-fw fa-arrow-left"></i> BACK',['update?id='.Yii::$app->user->identity->id],
						['class'=>'btn btn-xs btn-primary',
						 'title'=>'Back to Index',
						])
					
		,
        'attributes' => [
            //'id',
            'front_title',
			'name',
            'back_title',
            'nip',
			'nickname',
			'npwp',
			'born',
			'birthday',
           
            'phone',
            'email:email',
            'address',
			
            'blood',
            'position',
            'graduate_desc',
          
        ],
    ]) ?>
</div>