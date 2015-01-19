<?php
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use backend\models\TrainingClassStudent;
use backend\models\Reference;
use backend\models\Organisation;
use backend\models\Person;
use backend\models\Employee;
use hscstudio\heart\widgets\Box;

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
$this->title = Yii::t('app', 'Generate {modelClass}', [
    'modelClass' => 'Form A',]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'BPPK_TEXT_TRAINING_ACTIVITIES'), 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Form A '. Inflector::camel2words($model->activity->name);
?>
<div class="panel panel-default">
    <div class="panel-heading"> 
        <div class="pull-right">
            <?= (Yii::$app->request->isAjax)?'':Html::a('<i class="fa fa-fw fa-arrow-left"></i> '.Yii::t('app', 'SYSTEM_BUTTON_BACK'), ['index'], ['class' => 'btn btn-xs btn-primary']) ?>
        </div>
        <h1 class="panel-title"><i class="fa fa-fw fa-ellipsis-h"></i>Navigasi</h1> 
    </div>

    <div class="row clearfix">
        <div class="col-md-3">
        <?php
        Box::begin([
            'type'=>'small', // ,small, solid, tiles
            'bgColor'=>'red', // , aqua, green, yellow, red, blue, purple, teal, maroon, navy, light-blue
            'bodyOptions' => [],
            'icon' => 'glyphicon glyphicon-eye-open',
            'link' => ['property','id'=>$model->activity->id],
            'footerOptions' => [
                'class' => 'dashboard-hide',
            ],
            'footer' => 'Buka <i class="fa fa-arrow-circle-right"></i>',
        ]);
        ?>
        <h3>Informasi</h3>
        <p>Informasi Diklat</p>
        <?php
        Box::end();
        ?>
        </div>
        
        <div class="col-md-2">
        <?php
        Box::begin([
            'type'=>'small', // ,small, solid, tiles
            'bgColor'=>'aqua', // , aqua, green, yellow, red, blue, purple, teal, maroon, navy, light-blue
            'bodyOptions' => [],
            'icon' => 'fa fa-fw fa-inbox',
            'link' => ['room','id'=>$model->activity->id],
            'footerOptions' => [
                'class' => 'dashboard-hide',
            ],
            'footer' => 'Buka <i class="fa fa-arrow-circle-right"></i>',
        ]);
        ?>
        <h3>Ruangan</h3>
        <p>Pesan Ruangan</p>
        <?php
        Box::end();
        ?>
        </div>
        
        <div class="col-md-2">
        <?php
        Box::begin([
            'type'=>'small', // ,small, solid, tiles
            'bgColor'=>'green', // , aqua, green, yellow, red, blue, purple, teal, maroon, navy, light-blue
            'bodyOptions' => [],
            'icon' => 'fa fa-fw fa-users',
            'link' => ['student','id'=>$model->activity->id],
            'footerOptions' => [
                'class' => 'dashboard-hide',
            ],
            'footer' => 'Buka <i class="fa fa-arrow-circle-right"></i>',
        ]);
        ?>
        <h3>Peserta</h3>
        <p>Input Data Peserta</p>
        <?php
        Box::end();
        ?>
        </div>
        
        <div class="col-md-2">
        <?php
        Box::begin([
            'type'=>'small', // ,small, solid, tiles
            'bgColor'=>'yellow', // , aqua, green, yellow, red, blue, purple, teal, maroon, navy, light-blue
            'bodyOptions' => [],
            'icon' => 'glyphicon glyphicon-home',
            'link' => ['class','id'=>$model->activity->id],
            'footerOptions' => [
                'class' => 'dashboard-hide',
            ],
            'footer' => 'Buka <i class="fa fa-arrow-circle-right"></i>',
        ]);
        ?>
        <h3>Kelas</h3>
        <p>Kelola kelas</p>
        <?php
        Box::end();
        ?>
        </div>
        <div class="col-md-3 margin-top-small">
        <?php
        Box::begin([
            'type'=>'small', // ,small, solid, tiles
            'bgColor'=>'teal', // , aqua, green, yellow, red, blue, purple, teal, maroon, navy, light-blue
            'bodyOptions' => [],
            'icon' => 'fa fa-fw fa-check',
            'link' => ['forma','id'=>$model->activity->id],
            'footerOptions' => [
                'class' => 'dashboard-hide',
            ],
            'footer' => 'Buka <i class="fa fa-arrow-circle-right"></i>',
        ]);
        ?>
        <h3>Form A</h3>
        <p>Anda Disini</p>
        <?php
        Box::end();
        ?>
        </div>
        
    </div>
</div>
<div class="activity-update panel panel-default">
	
    <div class="panel-heading">
		<h1 class="panel-title"><i class="fa fa-fw fa-gear"></i>Pengaturan</h1>
	</div>
	<div class="panel-body">
		<div class="letter-assignment-form">
			<?php
				$form = ActiveForm::begin([
					'options'=>[
						'id'=>'myform',
						'onsubmit'=>'',
					],
					'action'=>[
						'generate-forma','id'=>$model->activity_id
					], 
				]);
			?>
            
            <?= $form->errorSummary($model) ?> <!-- ADDED HERE -->
            <div class="row clearfix">
                <div class="col-md-12">
                <?php
				echo Html::beginTag('label',['class'=>'control-label']).'Nomor Form A'.Html::endTag('label');
				echo Html::input('text','nomor_forma',$model->number_forma,['class'=>'form-control','id'=>'nomor_forma']);
				?>
                </div>               
            </div>
            <div class="row clearfix">
                <div class="col-md-12">
                <?php
				$jml_peserta = TrainingClassStudent::find()
							->where(['training_id'=>$model->activity_id])
							->count();
				echo Html::beginTag('label',['class'=>'control-label']).'Jumlah Peserta Diklat'.Html::endTag('label');
				echo Html::input('text','jml_peserta',$jml_peserta,['class'=>'form-control','disabled'=>true,'id'=>'nomor_forma']);
				?>
                </div>               
            </div>
            <div class="row clearfix">
                <div class="col-md-6">
                <?php
				echo Html::beginTag('label',['class'=>'control-label']).'NPP Diklat Awal'.Html::endTag('label');
                echo Html::input('text','npp_awal',$model->number."-".str_pad($npp_awal,4,'0',STR_PAD_LEFT),['class'=>'form-control','disabled'=>true,'id'=>'npp_awal']);
				?>
                </div> 
                <div class="col-md-6">
                <?php
				echo Html::beginTag('label',['class'=>'control-label']).'NPP Diklat Akhir'.Html::endTag('label');
				echo Html::input('text','npp_akhir',$model->number."-".str_pad($npp_akhir,4,'0',STR_PAD_LEFT),['class'=>'form-control','disabled'=>true,'id'=>'npp_akhir']);
				?>
                </div>               
            </div>
            <div class="row clearfix">
                <div class="col-md-6">
                <?php
				echo Html::beginTag('label',['class'=>'control-label']).'Jabatan Penandatangan I'.Html::endTag('label');
				echo Html::input('text','jabatan_ttd_satu','Kepala '.Reference::findOne(['id'=>Yii::$app->user->identity->employee->satker_id])->name,['class'=>'form-control','id'=>'jabatan_ttd_satu']);
				?>
                </div>
                <div class="col-md-6">
                <?php
				echo Html::beginTag('label',['class'=>'control-label']).'Jabatan Penandatangan II'.Html::endTag('label');
				echo Html::input('text','jabatan_ttd_dua','Kepala Bidang Penyelenggaraan',['class'=>'form-control','id'=>'jabatan_ttd_dua']);
				?>
                </div>
            </div>  
            <div class="row clearfix">
                <div class="col-md-6">
                <?php
				echo Html::beginTag('label',['class'=>'control-label']).'Nama Penandatangan I'.Html::endTag('label');
				echo Html::input('text','nama_ttd_satu',Person::findOne(['id'=>Employee::findOne(['satker_id'=>Yii::$app->user->identity->employee->satker_id,'organisation_id'=>'387','chairman'=>'1'])->person_id])->name,['class'=>'form-control','id'=>'ttd_satu']);
				?>
                </div>
                <div class="col-md-6">
                <?php
				echo Html::beginTag('label',['class'=>'control-label']).'Nama Penandatangan II'.Html::endTag('label');
				echo Html::input('text','nama_ttd_dua',Person::findOne([
                    'id'=>Employee::findOne([
                        'satker_id'=>Yii::$app->user->identity->employee->satker_id,
                        'organisation_id'=>'396',
                        'chairman'=>'1'
                    ])->person_id
                ])->name,['class'=>'form-control','id'=>'ttd_dua']);
				?>
                </div>
            </div>    
            <div class="row clearfix">
                <div class="col-md-6">
                <?php
				echo Html::beginTag('label',['class'=>'control-label']).'NIP Penandatangan I'.Html::endTag('label');
				echo Html::input('text','nip_ttd_satu',Person::findOne(['id'=>Employee::findOne(['satker_id'=>Yii::$app->user->identity->employee->satker_id,'organisation_id'=>'387','chairman'=>'1'])->person_id])->nip,['class'=>'form-control','id'=>'nip_ttd_satu']);
				?>
                </div>
                <div class="col-md-6">
                <?php
				echo Html::beginTag('label',['class'=>'control-label']).'NIP Penandatangan II'.Html::endTag('label');
				echo Html::input('text','nip_ttd_dua',Person::findOne(['id'=>Employee::findOne(['satker_id'=>Yii::$app->user->identity->employee->satker_id,'organisation_id'=>'396','chairman'=>'1'])->person_id])->nip,['class'=>'form-control','id'=>'nip_ttd_dua']);
				?>
                </div>
            </div>     
                 
            <div class="clearfix"><hr></div> 
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? '<i class="fa fa-fw fa-file-o"></i>'.Yii::t('app', 'BPPK_BUTTON_GENERATE_FORM_A') : '<i class="fa fa-fw fa-file-o"></i>'.Yii::t('app', 'BPPK_BUTTON_GENERATE_FORM_A'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        
            <?php ActiveForm::end(); ?>
            
            <?php $this->registerCss('label{display:block !important;}'); ?>
        </div>
	</div>
</div>


