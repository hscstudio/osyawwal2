<?php
use yii\helpers\Html;
use yii\grid\GridView as Gridview2;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\widgets\Select2;

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
$this->title = Yii::t('app', 'BPPK_TEXT_TRAINING_ACTIVITIES');
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
				'label' => Yii::t('app', 'BPPK_TEXT_NAME'),
				'vAlign'=>'middle',
				'hAlign'=>'left',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],	
				'format'=>'raw',
				'value' => function ($data){
					return Html::a($data->name,'#',[
						'title'=>$data->description.'<hr>'.$data->training->note,
						'data-toggle'=>"tooltip",
						'data-placement'=>"top",
						'data-html'=>'true',
					]);
				},
			],            
			[
				'attribute' => 'start',
				'label' => Yii::t('app', 'BPPK_TEXT_START'),
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
				'label' => Yii::t('app', 'BPPK_TEXT_END'),
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
				'label' => Yii::t('app', 'BPPK_TEXT_STUDENT'),
				'vAlign'=>'left',
				'hAlign'=>'center',
				'width' => '75px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){
					return Html::a($data->training->student_count_plan,['index-student-plan'],
						[
							'class'=>'label label-primary',
							'data-pjax'=>'0',
							'data-toggle'=>'tooltip',
							'title' => Yii::t('app', 'BPPK_TEXT_TOOLTIP_CLICK_SPREAD'),
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
					$permit = \Yii::$app->user->can('pusdiklat-planning-1');
					$object_person=\backend\models\ObjectPerson::find()
						->where([
							'object'=>'activity',
							'object_id'=>$data->id,
							'type'=>'organisation_1213020100', // CEK KD_UNIT_ORG 1213020100 IN TABLE ORGANISATION IS SUBBIDANG PROGRAM
						])
						->one();
					
					if($permit){
						$options = [
									'class'=>'label label-info modal-heart',
									'data-toggle'=>'tooltip',
									'data-pjax'=>'0',
									'data-html'=>'true',
									'title'=>($object_person!=null)?'PIC Diklat <br> '.$object_person->person->name.'.<br> Klik untuk mengubah PIC':'Klik untuk memilih PIC',
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
									'title'=>($object_person!=null)?'PIC Diklat <br> '.$object_person->person->name.'':'PIC tidak tersedia',
								];
						$person_name = ($object_person!=null)?substr($object_person->person->name,0,5).'.':'-';
						return Html::tag('span',$person_name,$options);
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
						'1'=>'<span class="glyphicon glyphicon-refresh"></span>',
						'2'=>'<span class="glyphicon glyphicon-check"></span>',
						'3'=>'<span class="glyphicon glyphicon-remove"></span>'
					];
					$status_classes = ['0'=>'warning','1'=>'info','2'=>'success','3'=>'danger'];
					$status_title = ['0'=>'Rencana','1'=>'Siap','2'=>'Berjalan','3'=>'Batal'];
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
			[
				'filter' => false,
				'label' => Yii::t('app', 'BPPK_TEXT_APPROVED_STATUS'),
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'75px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data) use ($satker_id) {
					$status_icons = [
						'0'=>'<i class="fa fa-fw fa-times-circle"></i>',
						'1'=>'<i class="fa fa-fw fa-check-circle"></i>',
					];
					$status_classes = ['0'=>'warning','1'=>'success'];
					$status_title = ['0'=> Yii::t('app', 'BPPK_TEXT_WAITING_APPROVAL'),'1'=> Yii::t('app', 'BPPK_TEXT_APPROVED')];

					if ($data->training->approved_status === null) {
						return Html::a(
							$status_icons[0],
							['togel-approve-diklat', 'training_id' => $data->training->activity_id],
							[
								'class'=>'label label-'.$status_classes[0],
								'data-toggle'=>'tooltip',
								'data-pjax'=>'0',
								'title'=>$status_title[0],
							]
						);
					}
					else {
						return Html::a(
							$status_icons[$data->training->approved_status],
							['togel-approve-diklat', 'training_id' => $data->training->activity_id, 'satker_id' => $satker_id],
							[
								'class'=>'label label-'.$status_classes[$data->training->approved_status],
								'data-toggle'=>'tooltip',
								'data-pjax'=>'0',
								'title'=>$status_title[$data->training->approved_status],
							]
						);
					}
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
				'width'=>'150px',
				'template' => '<div class="btn-group">{history} {view} {update} {delete}</div>',
				'buttons' => [
					'history'=> function ($url, $model) {
						$icon='<span class="fa fa-fw fa-h-square"></span>';
						return Html::a($icon,'#',[
							'class'=>'btn btn-default btn-xs',
							'data-pjax'=>'0',
							'onclick' => 'alert("Sedang dalam pengerjaan")',
						]);
					},
					'view' => function ($url, $model) {
						$icon='<span class="fa fa-fw fa-eye"></span>';
						return Html::a($icon,$url,[
							'class'=>'btn btn-default btn-xs modal-heart',
							'data-pjax'=>'0',
							'modal-size' => 'modal-lg',
							'modal-title' => '<i class="fa fa-fw fa-eye"></i> Informasi '.$model->name
						]);
					},
					'update' => function ($url, $model) {
						$icon='<span class="fa fa-fw fa-pencil"></span>';
						return Html::a($icon,$url,[
							'class'=>'btn btn-default btn-xs',
							'data-pjax'=>'0',
						]);
					},
					'delete' => function ($url, $model) {
						$icon='<span class="fa fa-fw fa-trash-o"></span>';
						if (!in_array($model->status,[2])){
							return Html::a($icon,$url,[
								'class'=>'btn btn-default btn-xs',
								'data-pjax'=>'0',
								'data-confirm'=>'Yakin ingin menghapus!',
								'data-method' => 'post',
							]);
						}
					},
				]
			],
			
			/* if($model->status==2){
				$edited = 2; // permit with warning
			}
				
			$countTrainingSubject = \backend\models\TrainingClassSubject::find()
				->where([
					'training_class_id'=>\backend\models\TrainingClass::find()
						->where([
							'training_id' => $model->id
						])
						->column(),		
				])
				->count();
			if($countTrainingSubject>0){
				$edited = 3; // refused
			} */
		],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-book"></i> '.Html::encode($this->title).'</h3>',
			'before'=>
				Html::a('<i class="fa fa-fw fa-plus-circle"></i> '.Yii::t('app', 'SYSTEM_BUTTON_CREATE'), ['create'], [
					'class' => 'btn btn-success modal-heart',
					'data-pjax' => '0',
					'modal-title' => '<i class="fa fa-fw fa-plus-circle"></i> '.Yii::t('app', 'SYSTEM_BUTTON_CREATE'),
					'modal-size' => 'modal-lg'
				]).
				'<div class="pull-right" style="margin-right:5px;" id="div-select2-satker">'.
				Select2::widget([
					'name' => 'satker_id', 
					'data' => $satker,
					'value' => $satker_id,
					'options' => [
						'width'=> '200px',
						'placeholder' => 'Satker ...', 
						'class'=>'form-control',
						'onchange'=>'
							$.pjax.reload({
								url: "'.\yii\helpers\Url::to(['index']).'?status='.$status.'&satker_id="+$(this).val(), 
								container: "#pjax-gridview", 
								timeout: 1,
							});
						',	
					],
				]).
				'</div>'.
				'<div class="pull-right" style="margin-right:5px;">'.
				Select2::widget([
					'name' => 'year', 
					'data' => $year_training,
					'value' => $year,
					'options' => [
						'placeholder' => Yii::t('app', 'BPPK_TEXT_YEAR'), 
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
				'<div class="pull-right" style="margin-right:5px;">'.
				Select2::widget([
					'name' => 'status', 
					'data' => [
						'nocancel'=>Yii::t('app','BPPK_TEXT_SHOW_ALL_EXCEPT_CANCEL'),
						'all'=>Yii::t('app','BPPK_TEXT_SHOW_ALL'),
						'0'=>Yii::t('app','BPPK_TEXT_PLAN'),
						'1'=>Yii::t('app','BPPK_TEXT_READY'),
						'2'=>Yii::t('app','BPPK_TEXT_EXECUTE'),
						'3'=>Yii::t('app','BPPK_TEXT_CANCEL')
					],
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
				Html::a('<i class="fa fa-fw fa-tasks"></i> '.Yii::t('app', 'BPPK_BUTTON_STUDENT_PLAN'), ['index-student-plan'], ['class' => 'btn btn-default','data-pjax'=>'0']).' '.
				Html::a('<i class="fa fa-fw fa-repeat"></i> '.Yii::t('app', 'SYSTEM_BUTTON_RESET_GRID'), Url::to(''), ['class' => 'btn btn-info']),
			'showFooter'=>false
		],
		'responsive'=>true,
		'hover'=>true,
    ]); ?>
	<?= \hscstudio\heart\widgets\Modal::widget() ?>
	<?php /* $this->registerCss('.select2-container{width:125px !important;}'); */ ?>
	<?php \yii\widgets\Pjax::end(); ?>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
	<i class="fa fa-fw fa-refresh"></i> Dokumen Generator
	</div>
    <div class="panel-body">
		<?php
		$form = \yii\bootstrap\ActiveForm::begin([
			'method'=>'get',
			'action'=>['export-training','year'=>$year,'status'=>$status],
		]);
		echo Html::submitButton('<i class="fa fa-fw fa-download"></i> '.Yii::t('app', 'SYSTEM_TEXT_DOWNLOAD').' Kalender Diklat', ['class' => 'btn btn-default','style'=>'display:block;']);
		\yii\bootstrap\ActiveForm::end(); 
		?>
	</div>
</div>