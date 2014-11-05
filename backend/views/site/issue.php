<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\IssueSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$controller = $this->context;
$this->title = 'Issues';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="issue-index">
    
	<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            // 'id',
            
                [
                    'attribute' => 'parent_id',
                    'vAlign'=>'middle',
                    'hAlign'=>'center',
                    'headerOptions'=>['class'=>'kv-sticky-column'],
                    'contentOptions'=>['class'=>'kv-sticky-column'],                    
                ],
            
                [
                    'attribute' => 'subject',
                    'vAlign'=>'middle',
                    'hAlign'=>'center',
                    'headerOptions'=>['class'=>'kv-sticky-column'],
                    'contentOptions'=>['class'=>'kv-sticky-column'],                    
                ],
            'content:ntext',
            
                [
                    'attribute' => 'label',
                    'vAlign'=>'middle',
                    'hAlign'=>'center',
                    'headerOptions'=>['class'=>'kv-sticky-column'],
                    'contentOptions'=>['class'=>'kv-sticky-column'],                    
                ],
            
                [
                    'attribute' => 'status',
                    'vAlign'=>'middle',
                    'hAlign'=>'center',
                    'headerOptions'=>['class'=>'kv-sticky-column'],
                    'contentOptions'=>['class'=>'kv-sticky-column'],                    
                ],
            // 'created',
            // 'created_by',
            // 'modified',
            // 'modified_by',

            ['class' => 'kartik\grid\ActionColumn'],
        ],
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> '.Html::encode($this->title).'</h3>',
            'before'=>
				Html::a('<i class="fa fa-fw fa-plus"></i> Create ', ['create-issue'], ['class' => 'btn btn-success']).
				'<div class="pull-right" style="margin-right:5px;">'.
				Select2::widget([
					'name' => 'status', 
					'data' => ['all'=>'All','1'=>'Open','2'=>'Close'],
					'value' => $status,
					'options' => [
						'placeholder' => 'Status ...', 
						'class'=>'form-control', 
						'onchange'=>'
							$.pjax.reload({
								url: "'.Url::to(['issue']).'?status="+$(this).val(), 
								container: "#pjax-gridview", 
								timeout: 1000,
							});
						',	
					],
				]).
				'</div>',
            'after'=>Html::a('<i class="fa fa-fw fa-repeat"></i> Reset Grid', Url::to(''), ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
        'responsive'=>true,
        'hover'=>true,
    ]); ?>

</div> 