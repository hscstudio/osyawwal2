<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\grid\GridView as Gridview2;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\widgets\Select2;

$controller = $this->context;
//$menus = $controller->module->getMenuItems('SIMBPPK');
//$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
$this->title = Yii::t('app', 'Data Diklat BPPK');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">
    <div class="container-fluid">
        <div class="row">            
            <div class="col-md-12">
                <div class="row">                    
                    <div class="col-md-3">                        
                        <div class="row">
                            <div class="col-md-12">
								<div class="jumbotron">
                                    <h2>Selamat Datang</h2>                            
                                    <p class="lead">Di Aplikasi Kediklatan SIMBPPK</p>                            
                                    <p><a class="btn btn-lg btn-success" href="<?php echo Yii::$app->homeUrl."/site/login.aspx";?>">Login Peserta</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">                        
                      <div class="activity-index">                      	
    <?php \yii\widgets\Pjax::begin([
		'id'=>'pjax-gridview',
	]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            
				[
					'attribute' => 'name',
					'vAlign'=>'middle',
					'hAlign'=>'center',
					'headerOptions'=>['class'=>'kv-sticky-column'],
					'contentOptions'=>['class'=>'kv-sticky-column'],					
				],
            
				[
					'attribute' => 'start',
					'vAlign'=>'middle',
					'hAlign'=>'center',
					'headerOptions'=>['class'=>'kv-sticky-column'],
					'contentOptions'=>['class'=>'kv-sticky-column'],	
					'format'=>'raw',
					'value' => function ($data){
						return Html::tag('span',date('d M Y',strtotime($data->start)),[
							'class'=>'label label-info',
						]);
					},
				],
            
				[
					'attribute' => 'end',
					'vAlign'=>'middle',
					'hAlign'=>'center',
					'headerOptions'=>['class'=>'kv-sticky-column'],
					'contentOptions'=>['class'=>'kv-sticky-column'],
					'format'=>'raw',
					'value' => function ($data){
						return Html::tag('span',date('d M Y',strtotime($data->end)),[
							'class'=>'label label-info',
						]);
					},
				],								
        ],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> '.Html::encode($this->title).'</h3>',
			'before'=>'<div class="pull-right" style="margin-right:5px;">'.
				Select2::widget([
					'name' => 'year', 
					'data' => $year_training,
					'value' => $year,
					'options' => [
						'placeholder' => 'Year ...', 
						'class'=>'form-control', 
						'onchange'=>'
							$.pjax.reload({
								url: "'.\yii\helpers\Url::to(['index']).'?satker_id='.$satker_id.'&year="+$(this).val(), 
								container: "#pjax-gridview", 
								timeout: 1,
							});
						',	
					],
				]).
				'</div>'.
				'<div class="pull-right" style="margin-right:5px;">'.
				Select2::widget([
					'name' => 'satker_id', 
					'data' => $satker,
					'value' => $satker_id,
					'options' => [
						'placeholder' => 'Penyelenggara ...', 
						'class'=>'form-control', 
						'onchange'=>'
							$.pjax.reload({
								url: "'.\yii\helpers\Url::to(['index']).'?year='.$year.'&satker_id="+$(this).val(), 
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
    <?= \hscstudio\heart\widgets\Modal::widget() ?>
	<?php \yii\widgets\Pjax::end(); ?>
                      </div>            
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>