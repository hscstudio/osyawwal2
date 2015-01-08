<?php
use yii\helpers\Html;
use yii\grid\GridView as Gridview2;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\pusdiklat\planning\models\ActivitySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
$this->title = Yii::t('app', 'BPPK_TEXT_MEETING_ACTIVITY');
$this->params['breadcrumbs'][] = $this->title;
?>
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
				'hAlign'=>'left',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],	
				'format'=>'raw',
				'value' => function ($data){
					return Html::a($data->name,'#',[
						'title'=>$data->description,
						'data-toggle'=>"tooltip",
						'data-placement'=>"top",
						'data-html'=>'true',
					]);
				},
			],            
			[
				'attribute' => 'start',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'width'=>'100px',
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
				'width'=>'100px',
				'format'=>'raw',
				'value' => function ($data){
					return Html::tag('span',date('d M Y',strtotime($data->end)),[
						'class'=>'label label-info',
					]);
				},
			],
			[
				'label' => 'Peserta',
				
				'vAlign'=>'left',
				'hAlign'=>'center',
				'width' => '75px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){
					return Html::tag('span', $data->meeting->attendance_count_plan,
						[
							'class'=>'label label-primary',
							'data-pjax'=>'0',
							'data-toggle'=>'tooltip',
							'title' => 'Rencana jumlah  peserta',
						]); 
				},
            ],
			
			[
				'format' => 'raw',
				'label' => 'PIC',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'70px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function ($data){
					// CEK AUTHORISE ACCESS
					$permit = \Yii::$app->user->can('pusdiklat-planning-3');
					$object_person=\backend\models\ObjectPerson::find()
						->where([
							'object'=>'activity',
							'object_id'=>$data->id,														
							'type'=>'organisation_1213020300' //1213010100 CEK KD_UNIT_ORG 1213010100 IN TABLE ORGANISATION IS SUBBIDANG KURIKULUM
						])
						->one();
					
					if($permit){
						$options = [
									'class'=>'label label-info modal-heart',
									'data-toggle'=>'tooltip',
									'data-pjax'=>'0',
									'data-html'=>'true',
									'title'=>($object_person!=null)?'PIC Sekarang <br> '.$object_person->person->name.'.<br> Klik untuk mengubah PIC':'Klik untuk memilih PIC',
									'modal-title'=>($object_person!=null)?'Ubah PIC':'Pilih PIC',
									'modal-size'=>'modal-md',
								];
						$person_name = ($object_person!=null)?substr($object_person->person->name,0,5).'.':'-';
						return Html::a($person_name,['pic','id'=>$data->id],$options);
					}
					else{
						$options = [
									'class'=>'label label-info',
									'data-toggle'=>'tooltip',
									'data-pjax'=>'0',
									'data-html'=>'true',
									'title'=>($object_person!=null)?'PIC Sekarang<br> '.$object_person->person->name.'':'PIC tidak tersedia',
								];
						$person_name = ($object_person!=null)?substr($object_person->person->name,0,5).'.':'-';
						return Html::tag('span',$person_name,$options);
					}
				}
			],
			
			[
				//'attribute' => 'classCount',
				'format'=>'raw',
				'label'=>'Room',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'100px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'value' => function ($data) {
					$activityRoom = \backend\models\ActivityRoom::find()
								->where('activity_id=:activity_id',
								[
									':activity_id' => $data->id
								]);		
					if($activityRoom->count()==0){ 
						return Html::a('Lihat', ['room','activity_id'=>$data->id], 
							[							
							'class' => 'label label-warning modal-heart',
							'data-pjax'=>0,
							'source'=>'',
							'modal-size'=>'modal-lg',
							'modal-title' => '<i class="fa fa-fw fa-inbox"></i> Lihat Status Ruangan'
							]);
					}		
					else{
						$statuss = [
							'0' => 'Menunggu',
							'1' => 'Proses',
							'2' => 'Disetujui',
							'3' => 'Ditolak',
						];
						
						$ars = $activityRoom->all();
						$rooms = [];
						foreach($ars as $ar){
							$rooms[] = $ar->room->name.'=>'.$statuss[$ar->status];
						}
						$rooms = implode('<br>',$rooms);
						return Html::a($activityRoom->count(), ['room','activity_id'=>$data->id], [
							'class' => 'label label-info ',
							'data-pjax'=>0,
							'source'=>'',
							'modal-size'=>'modal-lg',
							'data-html'=>true,
							'title'=>$rooms,
							'data-toggle'=>'tooltip',
						]);
					}
				}
			],
			
			[
				'attribute' => 'status',
				'filter' => false,
				'label' => 'Status',
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'75px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){
					$status_icons = [
						'0'=>'<span class="glyphicon glyphicon-fire"></span>',
						'1'=>'<span class="glyphicon glyphicon-check"></span>'
					];
					$status_classes = ['0'=>'warning','1'=>'info'];
					$status_title = ['0'=>'Rencana','1'=>'Siap'];
					return Html::tag(
						'span',
						$status_icons[$data->status],
						[
							'class'=>'label label-'.$status_classes[$data->status],
							'data-toggle'=>'tooltip',
							'data-pjax'=>'0',
							'title'=>$status_title[$data->status],
						]
					);
				},
			],
			
			// 'location',
            // 'hostel',
            // 'status',
            // 'created',
            // 'created_by',
            // 'modified',
            // 'modified_by',

            [
				'class' => 'kartik\grid\ActionColumn',
				'template' => '<div class="btn-group">{view} {update} {delete}</div>',
				'width' => '120px',
				'buttons' => [
					'view' => function ($url, $model) {
								$icon='<span class="fa fa-fw fa-eye"></span>';
								return Html::a($icon,$url,[
									'class'=>'btn btn-default btn-xs modal-heart',
									'modal-title' => '<i class="fa fa-fw fa-eye"></i> Informasi '.$model->name,
									'data-pjax'=>'0',
									'modal-size' => 'modal-lg'
								]);
							},
					'update' => function ($url, $model) {
								$icon='<span class="fa fa-fw fa-pencil"></span>';
								return Html::a($icon,$url,[
									'class'=>'btn btn-default btn-xs modal-heart',
									'modal-title' => '<i class="fa fa-fw fa-pencil"></i> Ubah '.$model->name,
									'data-pjax'=>'0',
									'modal-size' => 'modal-lg'
								]);
							},
					'delete' => function ($url, $model) {
								$icon='<span class="fa fa-fw fa-trash-o"></span>';
								return Html::a($icon,$url,[
									'class'=>'btn btn-default btn-xs',
									'data-pjax'=>'0',
									'data-method' => 'post',
									'data-confirm' => 'Yakin ingin menghapus?'
								]);
							},
				],
			],
        ],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> '.Html::encode($this->title).'</h3>',
			'before'=>
				Html::a('<i class="fa fa-fw fa-plus"></i> '.Yii::t('app', 'SYSTEM_BUTTON_CREATE'), ['create'], [
					'class' => 'btn btn-success modal-heart',
					'modal-size' => 'modal-lg',
					'modal-title' => '<i class="fa fa-fw fa-plus-circle"></i>'.Yii::t('app', 'SYSTEM_BUTTON_CREATE'),
					'data-pjax' => '0',
				]).
				'<div class="pull-right" style="margin-right:5px;">'.
				Select2::widget([
					'name' => 'year', 
					'data' => $year_meeting,
					'value' => $year,
					'options' => [
						'placeholder' => 'Tahun ...', 
						'class'=>'form-control', 
						'onchange'=>'
							$.pjax.reload({
								url: "'.\yii\helpers\Url::to(['index']).'?status='.$status.'&year="+$(this).val(), 
								container: "#pjax-gridview", 
								timeout: 1,
							});
						',	
					],
				]).
				'</div>'.
				'<div class="pull-right" style="margin-right:5px;" id="div-select2-status">'.
				Select2::widget([
					'name' => 'status', 
					'data' => ['all'=>'- Semua -','0'=>'Draft','1'=>'Publikasi'],
					'value' => $status,
					'options' => [
						'placeholder' => 'Status ...', 
						'class'=>'form-control', 
						'onchange'=>'
							$.pjax.reload({
								url: "'.\yii\helpers\Url::to(['index']).'?year='.$year.'&status="+$(this).val(), 
								container: "#pjax-gridview", 
								timeout: 1000,
							});
						',	
					],
				]).
				'</div>',
			'after'=>
				Html::a('<i class="fa fa-fw fa-repeat"></i> '.Yii::t('app', 'SYSTEM_BUTTON_RESET_GRID'), Url::to(''), ['class' => 'btn btn-info']),
			'showFooter'=>false
		],
		'responsive'=>true,
		'hover'=>true,
    ]); ?>
	<?= \hscstudio\heart\widgets\Modal::widget() ?>
	<?php $this->registerCss('#div-select2-status .select2-container{width:125px !important;}');  ?>
	<?php \yii\widgets\Pjax::end(); ?>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
	<i class="fa fa-fw fa-refresh"></i> Document Generator
	</div>
    <div class="panel-body">
		<?php
		$form = \yii\bootstrap\ActiveForm::begin([
			'method'=>'get',
			'action'=>['export-meeting','year'=>$year,'status'=>$status],
		]);
		echo Html::submitButton('<i class="fa fa-fw fa-download"></i> Unduh Daftar Rapat', ['class' => 'btn btn-default','style'=>'display:block;']);
		\yii\bootstrap\ActiveForm::end(); 
		?>
	</div>
</div>
