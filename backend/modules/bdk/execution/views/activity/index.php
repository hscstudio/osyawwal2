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
							'title' => 'Click to view student spread plan',
						]);
				},
            ],
			[
				'header' => Html::tag('span','MP',[
					'data-toggle'=>'tooltip',
					'title'=>'Mata Pelajaran',
				]),
				'vAlign'=>'middle',
				'hAlign'=>'center',
				'width'=>'70px',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){
					$mp = \backend\models\ProgramSubjectHistory::find()
						->where([
							'program_id' => $data->training->program_id,
							'program_revision' => $data->training->program_revision,
							'status' => 1,
						])
						->count();
					$icon='<span class="fa fa-fw fa-clipboard"></span> - '.$mp;
					return Html::a(
						$icon,
						['subject','id'=>$data->id],
						[
							'class'=>'btn btn-default btn-xs',
							'data-toggle'=>'tooltip',
							'data-pjax'=>'0',
							'title'=>'Mata Pelajaran',
						]
					);
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
					$permit = \Yii::$app->user->can('Subbidang Penyelenggaraan I');
					$object_person=\backend\models\ObjectPerson::find()
						->where([
							'object'=>'activity',
							'object_id'=>$data->id,														
							'type'=>'organisation_1213030100' //1213030100 CEK KD_UNIT_ORG 1213030100 IN TABLE ORGANISATION IS SUBBIDANG PENYEL I
						])
						->one();
					if($permit){
						$options = [
									'class'=>'label label-info modal-heart',
									'data-toggle'=>'tooltip',
									'data-pjax'=>'0',
									'data-html'=>'true',
									'title'=>($object_person!=null)?'CURRENT PIC TRAINING <br> '.$object_person->person->name.'.<br> CLICK HERE TO CHANGE PIC':'CLICK HERE TO SET PIC',
									'modal-title'=>($object_person!=null)?'CHANGE PIC':'SET PIC',
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
									'title'=>($object_person!=null)?'CURRENT PIC TRAINING <br> '.$object_person->person->name.'':'PIC IS UNAVAILABLE',
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
				'width'=>'55px',
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
					$status_title = ['0'=>'Plan','1'=>'Ready','2'=>'Execution','3'=>'Cancel'];					
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
				'value' => function ($data){
					$status_icons = [
						'0'=>'<i class="fa fa-fw fa-times-circle"></i>',
						'1'=>'<i class="fa fa-fw fa-check-circle"></i>',
					];
					$status_classes = ['0'=>'warning','1'=>'success'];
					$status_title = ['0'=> Yii::t('app', 'BPPK_TEXT_WAITING_APPROVAL'),'1'=> Yii::t('app', 'BPPK_TEXT_APPROVED')];

					if ($data->training->approved_status === null) {
						return Html::tag(
							'span',
							$status_icons[0],
							[
								'class'=>'label label-'.$status_classes[0],
								'data-toggle'=>'tooltip',
								'data-pjax'=>'0',
								'title'=>$status_title[0],
							]
						);
					}
					else {
						return Html::tag(
							'span',
							$status_icons[$data->training->approved_status],
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
				'width' => '200px',
				'template' => '<div class="btn-group">{update}</div> <div class="btn-group">{property}{room}{student}{class}{forma}</div>',
				'buttons' => [
					'dashboard' => function ($url, $model) {
								if (in_array($model->status,[2]) and in_array($model->training->approved_status, [1])){
									$icon='<span class="fa fa-fw fa-dashboard"></span>';
									return Html::a($icon,$url,[
										'class'=>'btn btn-default btn-xs',
										'data-pjax'=>'0',
									]);
								}
							},
					'update' => function ($url, $model) {
								if (in_array($model->status,[1,2]) and in_array($model->training->approved_status, [1])) {
									$icon='<span class="fa fa-fw fa-pencil"></span>';
									return Html::a($icon,$url,[
										'class'=>'btn btn-default btn-xs modal-heart',
										'data-pjax'=>'0',
										'modal-title' => '<i class="fa fa-fw fa-pencil-square"></i> Ubah Diklat',
										'modal-size' => 'modal-lg',
										'data-toggle' => 'tooltip',
										'data-title' => 'Ubah Diklat',
										'data-container' => 'body'
									]);
								}
							},
					'property' => function ($url, $model) {
								if (in_array($model->status,[2]) and in_array($model->training->approved_status, [1])) {
									$icon='<span class="fa fa-fw fa-eye"></span>';
									return Html::a($icon,$url,[
										'class'=>'btn btn-default btn-xs',
										'data-pjax'=>'0',
										'data-toggle' => 'tooltip',
										'data-title' => Yii::t('app', 'BPPK_TEXT_TOOLTIP_PROPERTY'),
										'data-container' => 'body'
									]);
								}
							},
					'room' => function ($url, $model) {
								if (in_array($model->status,[2]) and in_array($model->training->approved_status, [1])) {
									$icon='<span class="fa fa-fw fa-home"></span>';
									return Html::a($icon,$url,[
										'class'=>'btn btn-default btn-xs',
										'data-pjax'=>'0',
										'data-toggle' => 'tooltip',
										'data-title' => Yii::t('app', 'BPPK_TEXT_TOOLTIP_ROOM'),
										'data-container' => 'body'
									]);
								}
							},
					'student' => function ($url, $model) {
								if (in_array($model->status,[2]) and in_array($model->training->approved_status, [1])) {
									$icon='<span class="fa fa-fw fa-users"></span>';
									return Html::a($icon,$url,[
										'class'=>'btn btn-default btn-xs',
										'data-pjax'=>'0',
										'data-toggle' => 'tooltip',
										'data-title' => Yii::t('app', 'BPPK_TEXT_TOOLTIP_STUDENT'),
										'data-container' => 'body'
									]);
								}
							},
					'class' => function ($url, $model) {
								if (in_array($model->status,[2]) and in_array($model->training->approved_status, [1])) {
									$icon='<span class="fa fa-fw fa-inbox"></span>';
									return Html::a($icon,$url,[
										'class'=>'btn btn-default btn-xs',
										'data-pjax'=>'0',
										'data-toggle' => 'tooltip',
										'data-title' => Yii::t('app', 'BPPK_TEXT_TOOLTIP_CLASS'),
										'data-container' => 'body'
									]);
								}
							},
					'forma' => function ($url, $model) {
								if (in_array($model->status,[2]) and in_array($model->training->approved_status, [1])) {
									$icon='<span class="fa fa-fw fa-book"></span>';
									return Html::a($icon,$url,[
										'class'=>'btn btn-default btn-xs',
										'data-pjax'=>'0',
										'data-toggle' => 'tooltip',
										'data-title' => Yii::t('app', 'BPPK_TEXT_TOOLTIP_FORMA'),
										'data-container' => 'body'
									]);
								}
							},
				],		
			],
        ],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> '.Html::encode($this->title).'</h3>',
			'before'=>
				Html::a('<i class="fa fa-fw fa-plus-circle"></i> '.Yii::t('app', 'SYSTEM_BUTTON_CREATE'), ['create'], [
					'class' => 'btn btn-success modal-heart',
					'data-pjax' => '0',
					'modal-title' => '<i class="fa fa-fw fa-plus-circle"></i> '.Yii::t('app', 'SYSTEM_BUTTON_CREATE'),
					'modal-size' => 'modal-lg'
				]).
				'<div class="pull-right" style="margin-right:5px;">'.
				Select2::widget([
					'name' => 'year', 
					'data' => $year_training,
					'value' => $year,
					'options' => [
						'placeholder' => 'Year ...', 
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
					'data' => ['nocancel'=>'All -Cancel','all'=>'All','0'=>'Plan','1'=>'Ready','2'=>'Execute','3'=>'Cancel'],
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
				Html::a('<i class="fa fa-fw fa-link"></i> '.Yii::t('app', 'BPPK_BUTTON_STUDENT_PLAN'), ['index-student-plan'], ['class' => 'btn btn-default','data-pjax'=>'0']).' '.
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
	<i class="fa fa-fw fa-refresh"></i> Document Generator
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