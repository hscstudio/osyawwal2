<?php

use yii\helpers\Html;
//use yii\widgets\DetailView;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\SatkerPic */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Satker Pics', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu']=$menus;
?>
<div class="satker-pic-view">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->
	<!--
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
	-->
    <?= DetailView::widget([
        'model' => $model,
		'mode'=>DetailView::MODE_VIEW,
		'panel'=>[
			'heading'=>'Satker Pics # ' . $model->id,
			'type'=>DetailView::TYPE_DEFAULT,
		],
        'attributes' => [
            'id',
            [
				'attribute' => 'ref_satker_id',
				'value' => function ($model) {
					return $model->satker->name;
				}
			],
            'ref_satker_id',
            'code',
            'name',
            'value',
            'status',
            'created',
            'createdBy',
            'modified',
            'modifiedBy',
            'deleted',
            'deletedBy',
        ],
    ]) ?>

</div>
