<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Inflector;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\pusdiklat\execution\models\TrainingClassSubjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = 'Student Class #'. $class->class;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Training Activities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Inflector::camel2words($activity->name), 'url' => ['class','id'=>$activity->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="training-class-subject-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
			[
				'header' => '<div style="text-align:center;">Name</div>',
				'vAlign'=>'middle',
				'hAlign'=>'left',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){
					return Html::tag('span',$data->trainingStudent->student->person->name,[
						'class'=>'','data-toggle'=>'tooltip','title'=>''
					]);
				},
			],
			[
				'header' => '<div style="text-align:center;">NIP</div>',
				'vAlign'=>'middle',
				'hAlign'=>'left',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){
					return Html::tag('span',$data->trainingStudent->student->person->nip,[
						'class'=>'label label-info','data-toggle'=>'tooltip','title'=>''
					]);
				},
			],
			[
				'header' => '<div style="text-align:center;">Satker</div>',
				'vAlign'=>'middle',
				'hAlign'=>'left',
				'headerOptions'=>['class'=>'kv-sticky-column'],
				'contentOptions'=>['class'=>'kv-sticky-column'],
				'format'=>'raw',
				'value' => function ($data){
					$student = $data->trainingStudent->student;
					$unit = $student->person->unit->reference->name;
					
					if($student->satker==2){
						if(!empty($student->eselon2)){
							$unit = $student->eselon2;
						}
					}
					else if($student->satker==3){
						if(!empty($student->eselon3)){
							$unit = $student->eselon3;
						}
					}
					else if($student->satker==4){
						if(!empty($student->eselon4)){
							$unit = $student->eselon4;
						}
					}
					return Html::tag('span',$unit,[
						'class'=>'label label-default','data-toggle'=>'tooltip','title'=>''
					]);
				},
			],
            [
                'label' => 'Check',
                'format'=>'raw',
                'vAlign'=>'middle',
                'hAlign'=>'center',
                'vAlign'=>'middle',
                'width' => '25px',
                'value' => function ($model) {
                    $icon='<span class="glyphicon glyphicon-check"></span>';
                    $err = 0;
                    $student = $model->trainingStudent->student;
                    if (!(empty($student->person->name))){
                        $check[] = '<span class="glyphicon glyphicon-check"></span>'.' name';
                    }
                    else{
                        $check[] = '<span class="text-danger glyphicon glyphicon-info-sign"></span>'.' name is empty';
                        $err++;
                    }

                    if (!(empty($student->person->nip))){
                        $check[] = '<span class="glyphicon glyphicon-check"></span>'.' nip';
                    }
                    else{
                        $check[] = '<span class="text-danger glyphicon glyphicon-info-sign"></span>'.' nip is empty';
                        $err++;
                    }

                    if (!(empty($student->person->born))){
                        $check[] = '<span class="glyphicon glyphicon-check"></span>'.' born';
                    }
                    else{
                        $check[] = '<span class="text-danger glyphicon glyphicon-info-sign"></span>'.' born is empty';
                        $err++;
                    }

                    if (( date('Y') - substr($student->person->birthday,0,4))>10){
                        $check[] = '<span class="glyphicon glyphicon-check"></span>'.' birhtday';
                    }
                    else{
                        $check[] = '<span class="text-danger glyphicon glyphicon-info-sign"></span>'.' birhtday is invalid';
                        $err++;
                    }

                    $objectReference = \backend\models\ObjectReference::find()
                        ->where([
                            'object'=>'person',
                            'object_id'=>$model->trainingStudent->student->person_id,
                            'type'=>'rank_class',

                        ])
                        ->one();
                    if (null!=$objectReference){
                        $check[] = '<span class="glyphicon glyphicon-check"></span>'.' rank class';
                    }
                    else{
                        $check[] = '<span class="text-danger glyphicon glyphicon-info-sign"></span>'.' rank class is invalid';
                        $err++;
                    }

                    $student = $model->trainingStudent->student;
                    $unit = $student->person->unit->reference->name;

                    if($student->satker==2){
                        if(!empty($student->eselon2)){
                            $unit = $student->eselon2;
                        }
                    }
                    else if($student->satker==3){
                        if(!empty($student->eselon3)){
                            $unit = $student->eselon3;
                        }
                    }
                    else if($student->satker==4){
                        if(!empty($student->eselon4)){
                            $unit = $student->eselon4;
                        }
                    }

                    if (strlen($unit)>=3){
                        $check[] = '<span class="glyphicon glyphicon-check"></span>'.' unit';
                    }
                    else{
                        $check[] = '<span class="text-danger glyphicon glyphicon-info-sign"></span>'.' unit is invalid';
                        $err++;
                    }

                    $path = '';
                    if(isset(Yii::$app->params['uploadPath'])){
                        $path = Yii::$app->params['uploadPath'].DIRECTORY_SEPARATOR;
                    }
                    else{
                        $path = Yii::getAlias('@file').DIRECTORY_SEPARATOR;
                    }

                    $objectFile = \backend\models\ObjectFile::find()
                        ->where([
                            'object'=>'person',
                            'object_id'=>$model->trainingStudent->student->person_id,
                            'type'=>'photo',

                        ])
                        ->one();
                    if (null!=$objectFile){
                    /*if (file_exists($path.'person'.DIRECTORY_SEPARATOR.$model->trainingStudent->student->person_id.DIRECTORY_SEPARATOR.$objectFile->file->file_name) and strlen($objectFile->file->file_name)>3){*/
                        $check[] = '<span class="glyphicon glyphicon-check"></span>'.' photo';
                    }
                    else{
                        $check[] = '<span class="text-danger glyphicon glyphicon-info-sign"></span>'.' photo is invalid';
                        $err++;
                    }

                    if($err>0) $icon='<span class="text-danger glyphicon glyphicon-info-sign"></span>';

                    $title = implode('<br>',$check);
                    return Html::a($icon,'#',[
                        'data-pjax'=>"0",
                        'data-toggle'=>"tooltip",
                        'data-html'=>"true",
                        'title'=>$title
                    ]);

                }
            ],
            [
                'header'=> 'Certificate',
                'template'=>'{status} {create} {update} {delete}',
                'width' => '120px',
                'class' => 'kartik\grid\ActionColumn',
                'buttons' => [
                    'status' => function ($url, $model) use ($activity,$class) {
                        $certificate = backend\models\TrainingClassStudentCertificate::findOne($model->id);
                        if (null!=$certificate){
                            $icon='<span class="glyphicon glyphicon-check"></span>';
                            $label='label label-success';
                            $title='Get Certificate';
                        }
                        else{
                            $icon='-';
                            $label='';
                            $title='-';
                        }

                        return Html::tag('span', $icon, ['class'=>$label,'title'=>$title,'data-toggle'=>"tooltip",'data-placement'=>"top",'style'=>'cursor:pointer']);
                    },
                    'update' => function ($url, $model) use ($activity,$class) {
                        $certificate = backend\models\TrainingClassStudentCertificate::findOne($model->id);
                        if (null!=$certificate){
                            $icon='<span class="fa fa-fw fa-pencil"></span>';
                            $url2 = ['update-certificate-class-student','id'=>$activity->id,'class_id'=>$class->id,'training_class_student_id'=>$model->id,];
                            return Html::a($icon,$url2,[
                                'data-pjax'=>"0",
                                'class'=>'btn btn-xs btn-default'
                            ]);
                        }
                    },
                    'delete' => function ($url, $model) use ($activity,$class) {
                        $certificate = backend\models\TrainingClassStudentCertificate::findOne($model->id);
                        if (null!=$certificate){
                            $icon='<span class="fa fa-fw fa-trash"></span>';
                            $url2 = ['delete-certificate-class-student','id'=>$activity->id,'class_id'=>$class->id,'training_class_student_id'=>$model->id,];
                            return Html::a($icon,$url2,[
                                'title'=>"Delete",'data-confirm'=>"Are you sure to delete this item?",'data-method'=>"post",
                                'data-pjax'=>"0",
                                'class'=>'btn btn-xs btn-default'
                            ]);
                        }
                    },
                    'create' => function ($url, $model)use ($activity,$class) {
                        $certificate = backend\models\TrainingClassStudentCertificate::findOne($model->id);
                        if (null!=$certificate){
                        }
                        else{
                            $icon='<span class="fa fa-plus-circle fa-fw"></span>';
                            $url2 = ['create-certificate-class-student','id'=>$activity->id,'class_id'=>$class->id,'training_class_student_id'=>$model->id,];
                            return Html::a($icon,$url2,[
                                'data-pjax'=>"0",
                                'class'=>'btn btn-xs btn-default'
                            ]);
                        }
                    },
                ],
            ],
			[
				'class' => 'kartik\grid\ActionColumn',
                'template'=> '{update}',
				'buttons' => [
					'update' => function ($url, $model) use ($activity,$class) {
								$icon='<span class="fa fa-fw fa-pencil"></span>';
                                $url = ['update-class-student','id'=>$activity->id,'class_id'=>$class->id,'training_class_student_id'=>$model->id,];
								return Html::a($icon,
									$url,
									[
										'data-pjax'=>'0',
                                        'class'=>'btn btn-xs btn-info'
									]
								);
							},
				],
			]
        ],
		'panel' => [
			'heading'=>'<h3 class="panel-title"><i class="fa fa-fw fa-globe"></i> '.Html::encode($this->title).'</h3>',
			'before'=>
				Html::a('<i class="fa fa-fw fa-arrow-circle-left"></i> Back ', ['class','id'=>$activity->id], ['class' => 'btn btn-warning']).' ',
			'after'=>Html::a('<i class="fa fa-fw fa-repeat"></i> Reset Grid', Url::to(''), ['class' => 'btn btn-info']),
			'showFooter'=>false
		],
		'responsive'=>true,
		'hover'=>true,
    ]); ?>

</div>

<div class="panel panel-default">
	<div class="panel-heading">
	<i class="fa fa-fw fa-file"></i> Document Generator
	</div>
    <div class="panel-body">
        <ul class="nav nav-tabs" role="tablist">
            <li class="active"><a href="#sertifikat" role="tab" data-toggle="tab">Sertifikat</a></li>
            <li><a href="#document" role="tab" data-toggle="tab">Document</a></li>
            <li><a href="#kelulusan" role="tab" data-toggle="tab">Status Kelulusan</a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content" style="border:1px solid #ddd;border-top-width:0">
            <div class="tab-pane fade in active" id="sertifikat">
                <?php
                echo $this->render('_printCertificate', [
                    'activity' => $activity,
                    'class' => $class,
					'max_number' => $max_number,
					'max_seri' => $max_seri,
                ]) ?>
            </div>
            <div class="tab-pane fade" id="document">
                <div class="jumbotron">
                    <h1>Coming soon :)</h1>
                </div>
            </div>
            <div class="tab-pane fade" id="kelulusan">
                <div class="jumbotron">
                <div class="row clearfix">
                <div class="col-md-2">
                    <?php
					echo Html::beginTag('label',['class'=>'control-label']).'Status Kelulusan Peserta'.Html::endTag('label');
					echo Html::a('<i class="fa fa-fw fa-check"></i> Set Status Kelulusan Peserta',
									Url::to(['set-kelulusan-peserta','id'=>$activity->id,'class_id'=>$class->id]),
									[
										'class'=>'btn btn-default btn-xs',
										'title'=>'Set Status Kelulusan Peserta',
										//'modal-size'=>'modal-lg',
										'data-pjax'=>'0',
										'data-toggle'=>"tooltip",
										'data-placement'=>"top",
									]
								);
					?>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
