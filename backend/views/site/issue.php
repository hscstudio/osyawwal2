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
    <?php \yii\widgets\Pjax::begin([
		'id'=>'pjax-gridview',
	]); ?>
	<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
				[
					'attribute' => 'subject',
                    'vAlign'=>'middle',
                    'hAlign'=>'left',
                    'headerOptions'=>['class'=>'kv-sticky-column'],
                    'contentOptions'=>['class'=>'kv-sticky-column'],  
					'format'=>'raw',
					'value' => function ($data){
						$content = '<strong>'.$data->subject.'</strong><br>';
						$content .= '<small>#'.$data->id.' ';
						$content .= 'open at '.$data->created.' ';
						$user = \backend\models\User::findOne($data->created_by);						
						if(!empty($user)){
						$content .= 'by '.$user->employee->person->name.' ';
						}
						$content .= '</small>';
						$label = $data->getLastLabel($data->id);
						$labelt = '';
						if(!empty($label)){
							if($label=='verified') $labelt = '<span class="label label-warning">Status: To be '.$label.'</span>';
							if($label=='critical') $labelt = '<span class="label label-danger">'.$label.'</span>';
							if($label=='bugfix') $labelt = '<span class="label label-primary">'.$label.'</span>';
							if($label=='discussion') $labelt = '<span class="label label-info">'.$label.'</span>';
							if($label=='enhancement') $labelt = '<span class="label label-success">'.$label.'</span>';
						}
						else{
							$labelt = '';//<span class="label label-default">-</span>';
						}
						return $content.$labelt;
					}
                ],
				
                [
                    'width'=>'100px',
					'attribute' => 'status',
                    'vAlign'=>'middle',
                    'hAlign'=>'center',
                    'headerOptions'=>['class'=>'kv-sticky-column'],
                    'contentOptions'=>['class'=>'kv-sticky-column'],  
					'format'=>'html',
					'value' => function ($data){
						if($data->status==1){
							return '<span class="label label-warning">OPEN</span>';
						}
						else{
							return '<span class="label label-success">CLOSE</span>';
						}
					}
                ],
            // 'created',
            // 'created_by',
            // 'modified',
            // 'modified_by',

            [
				'class' => 'kartik\grid\ActionColumn',
				'template' => '{view} {update}',
				'buttons'=> [
					'view' => function ($url, $data){
						$icon = "<i class='fa fa-fw fa-eye'></i>";
						return Html::a($icon,['view-issue','id'=>$data->id],['class'=>'btn btn-xs btn-default']);
					},
					'update' => function ($url, $data){
						if(\Yii::$app->user->can('BPPK') or \Yii::$app->user->id==$model->created_by){
							$icon = "<i class='fa fa-fw fa-pencil'></i>";
							return Html::a($icon,['update-issue','id'=>$data->id],['class'=>'btn btn-xs btn-default']);
						}
					}
				],
			],
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
								timeout: 1,
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
	<?php \yii\widgets\Pjax::end(); ?>
</div> 