<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\Inflector;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use backend\models\Reference;
use kartik\widgets\SwitchInput;
use kartik\datecontrol\DateControl;
use kartik\widgets\FileInput;


/* @var $this yii\web\View */
/* @var $person backend\models\TrainingClassStudentCertificate */

$this->title = 'Perbarui Data Peserta';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'BPPK_TEXT_TRAINING_ACTIVITIES'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Kelas '.Inflector::camel2words($activity->name), 'url' => ['class','id'=>$activity->id]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'BPPK_TEXT_CLASS').' '.$class->class, 'url' => ['class-student','id'=>$activity->id,'class_id'=>$class->id]];
$this->params['breadcrumbs'][] = $this->title;
$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

echo \kartik\widgets\AlertBlock::widget([
    'useSessionFlash' => true,
    'type' => \kartik\widgets\AlertBlock::TYPE_ALERT
]);
?>
<div class="training-class-student">
    <div class="panel panel-default">
    <div class="panel-heading">
        <div class="pull-right">
            <?= Html::a('<i class="fa fa-arrow-left"></i> '.Yii::t('app', 'SYSTEM_BUTTON_BACK'),['class-student','id'=>$activity->id,'class_id'=>$class->id],
                ['class'=>'btn btn-xs btn-primary']) ?>
        </div>
        <i class="fa fa-fw fa-globe"></i>
        Pengaturan Data Peserta
    </div>
    <div class="panel-body">
    <?php $form = ActiveForm::begin([
        'options'=>['enctype'=>'multipart/form-data']
    ]); ?>

    <?= $form->errorSummary($person) ?> <!-- ADDED HERE -->

    <ul class="nav nav-tabs" role="tablist" id="tab_wizard">
        <li class="active"><a href="#personal_information" role="tab" data-toggle="tab">Pribadi</a></li>
        <li class=""><a href="#contact_information" role="tab" data-toggle="tab">Kontak</a></li>
        <li class=""><a href="#employee_information" role="tab" data-toggle="tab">Kepegawaian</a></li>
        <li class=""><a href="#office_information" role="tab" data-toggle="tab">Kantor</a></li>
        <li class=""><a href="#education_information" role="tab" data-toggle="tab">Pendidikan</a></li>
        <li class=""><a href="#photo_document" role="tab" data-toggle="tab">Foto & Dokumen</a></li>
        <li class=""><a href="#student" role="tab" data-toggle="tab">Peserta</a></li>
    </ul>
    <div class="tab-content" style="border: 1px solid #ddd; border-top-color: transparent; padding:10px; background-color: #fff;">
    <?php
    foreach($object_references_array as $object_reference=>$label){;
        $data = ArrayHelper::map(Reference::find()
                ->select(['id', 'name'])
                ->where(['type'=>$object_reference])
                ->asArray()
                ->all()
            , 'id', 'name');

        $field[$object_reference] = $form->field(${$object_reference}, '['.$object_reference.']reference_id')->widget(Select2::classname(), [
            'data' => $data,
            'options' => ['placeholder' => 'Pilih '.$label.' ...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ])->label($label);
    }
    ?>
    <div class="tab-pane fade-in active" id="personal_information">
        <h3>Informasi Pribadi</h3>
        <div class="row clearfix">
            <div class="col-md-3">
                <?= $form->field($person, 'front_title')->textInput(['maxlength' => 25]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($person, 'name')->textInput(['maxlength' => 100]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($person, 'back_title')->textInput(['maxlength' => 25]) ?>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-3">
                <?= $form->field($person, 'nickname')->textInput(['maxlength' => 25]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($person, 'gender')->widget(SwitchInput::classname(), [
                    'pluginOptions' => [
                        'onText' => 'Male',
                        'offText' => 'Female',
                    ]
                ]) ?>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-3">
                <?= $form->field($person, 'born')->textInput(['maxlength' => 50]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($person, 'birthday')->widget(DateControl::classname(), [
                    'type' => DateControl::FORMAT_DATE,
                ]); ?>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-3">
                <?= $form->field($person, 'married')->widget(SwitchInput::classname(), [
                    'pluginOptions' => [
                        'onText' => 'Ya',
                        'offText' => 'Tidak',
                    ]
                ]) ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($person, 'blood')->textInput(['maxlength' => 25]) ?>
            </div>
            <div class="col-md-3">
                <?= $field['religion'] ?>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-4">
                <?= $form->field($person, 'nid')->textInput(['maxlength' => 100]) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($person, 'npwp')->textInput(['maxlength' => 100]) ?>
            </div>
        </div>

        <a class="btn btn-default" onclick="$('#tab_wizard a[href=#contact_information]').tab('show')">
            Berikutnya
            <i class="fa fa-fw fa-arrow-circle-o-right"></i>
        </a>
    </div>
    <div class="tab-pane fade" id="contact_information">

        <h3>Informasi Kontak</h3>

        <div class="row clearfix">
            <div class="col-md-4">
                <?= $form->field($person, 'phone')->textInput(['maxlength' => 50]) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($person, 'email')->textInput(['maxlength' => 100]) ?>
            </div>
        </div>

        <?= $form->field($person, 'homepage')->textInput(['maxlength' => 255]) ?>

        <?= $form->field($person, 'address')->textInput(['maxlength' => 255]) ?>

        <?= $form->field($person, 'bank_account')->textInput(['maxlength' => 255]) ?>

        <a class="btn btn-default" onclick="$('#tab_wizard a[href=#personal_information]').tab('show')">
            Sebelumnya
            <i class="fa fa-fw fa-arrow-circle-o-left"></i>
        </a>
        <a class="btn btn-default" onclick="$('#tab_wizard a[href=#employee_information]').tab('show')">
            Berikutnya
            <i class="fa fa-fw fa-arrow-circle-o-right"></i>
        </a>

    </div>
    <div class="tab-pane fade" id="employee_information">

        <h3>Informasi Kepegawaian (PNS Only)</h3>

        <div class="row clearfix">
            <div class="col-md-3">
                <?= $form->field($person, 'nip')->textInput(['maxlength' => 25])->label('NIP') ?>
            </div>
        </div>

        <?= $field['rank_class'] ?>

        <a class="btn btn-default" onclick="$('#tab_wizard a[href=#contact_information]').tab('show')">
            Sebelumnya
            <i class="fa fa-fw fa-arrow-circle-o-left"></i>
        </a>
        <a class="btn btn-default" onclick="$('#tab_wizard a[href=#office_information]').tab('show')">
            Berikutnya
            <i class="fa fa-fw fa-arrow-circle-o-right"></i>
        </a>

    </div>
    <div class="tab-pane fade" id="office_information">

        <h3>Informasi Kantor</h3>

        <div class="row clearfix">
            <div class="col-md-4">
                <?php
                $data = ['5' => 'Pelaksana / Others','4' => 'Eselon 4','3' => 'Eselon 3','2' => 'Eselon 2','1' => 'Eselon 1'];
                echo $form->field($person, 'position')->widget(Select2::classname(), [
                    'data' => $data,
                    'options' => ['placeholder' => 'Pilih Jabatan ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
            </div>
            <div class="col-md-8">
                <?= $form->field($person, 'position_desc')->textInput(['maxlength' => 255]) ?>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-4">
                <?= $field['unit'] ?>
            </div>
            <div class="col-md-8">
                <?= $form->field($person, 'organisation')->textInput(['maxlength' => 255]) ?>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-md-4">
                <?= $form->field($person, 'office_phone')->textInput(['maxlength' => 50]) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($person, 'office_fax')->textInput(['maxlength' => 50]) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($person, 'office_email')->textInput(['maxlength' => 100]) ?>
            </div>
        </div>

        <?= $form->field($person, 'office_address')->textInput(['maxlength' => 255]) ?>

        <a class="btn btn-default" onclick="$('#tab_wizard a[href=#employee_information]').tab('show')">
            Sebelumnya
            <i class="fa fa-fw fa-arrow-circle-o-left"></i>
        </a>
        <a class="btn btn-default" onclick="$('#tab_wizard a[href=#education_information]').tab('show')">
            Berikutnya
            <i class="fa fa-fw fa-arrow-circle-o-right"></i>
        </a>

    </div>
    <div class="tab-pane fade" id="education_information">

        <h3>Informasi Pendidikan & Pengalaman</h3>

        <div class="row clearfix">
            <div class="col-md-4">
                <?= $field['graduate'] ?>
            </div>
            <div class="col-md-8">
                <?= $form->field($person, 'graduate_desc')->textInput(['maxlength' => 255]) ?>
            </div>
        </div>

        <a class="btn btn-default" onclick="$('#tab_wizard a[href=#office_information]').tab('show')">
            Sebelumnya
            <i class="fa fa-fw fa-arrow-circle-o-left"></i>
        </a>
        <a class="btn btn-default" onclick="$('#tab_wizard a[href=#photo_document]').tab('show')">
            Berikutnya
            <i class="fa fa-fw fa-arrow-circle-o-right"></i>
        </a>

    </div>
    <div class="tab-pane fade" id="photo_document">
        <h3>Foto dan Dokumen</h3>

        <table class="table">
            <?php
            foreach($object_file_array as $object_file=>$label){
                if($object_file=='photo'){
                    if(null!=${$object_file.'_file'}){
                        ?>
                        <tr>
                            <td>
                                <div class="file-preview-thumbnails">
                                    <div class="file-preview-frame">
                                        <img src="<?= Url::to(['/file/download','file'=>${$object_file}->object.'/'.${$object_file}->object_id.'/thumb_'.${$object_file.'_file'}->file_name]) ?>" class="file-preview-image">
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php
                                echo $form->field(${$object_file.'_file'}, 'file_name['.$object_file.']')->widget(FileInput::classname(), [
                                    'pluginOptions' => [
                                        'previewFileType' => 'any',
                                        'showUpload' => false,
                                    ]
                                ])->label($label);
                                ?>
                            </td>
                        </tr>
                    <?php
                    }
                }
                else{
                    if(null!=${$object_file.'_file'}){
                        ?>
                        <tr>
                            <td>
                                <?php
                                echo Html::a(
                                    ${$object_file.'_file'}->file_name,
                                    Url::to(['/file/download','file'=>${$object_file}->object.'/'.${$object_file}->object_id.'/'.${$object_file.'_file'}->file_name]),
                                    [
                                        'class'=>'badge',
                                    ]
                                );
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $form->field(${$object_file.'_file'}, 'file_name['.$object_file.']')->widget(FileInput::classname(), [
                                    'pluginOptions' => [
                                        'previewFileType' => 'any',
                                        'showUpload' => false,
                                    ]
                                ])->label($label);
                                ?>
                            </td>
                        </tr>
                    <?php
                    }
                }
            }
            ?>
        </table>

        <div class="clearfix"></div>

        <a class="btn btn-default" onclick="$('#tab_wizard a[href=#education_information]').tab('show')">
            Sebelumnya
            <i class="fa fa-fw fa-arrow-circle-o-left"></i>
        </a>
        <a class="btn btn-default" onclick="$('#tab_wizard a[href=#student]').tab('show')">
            Berikutnya
            <i class="fa fa-fw fa-arrow-circle-o-right"></i>
        </a>

    </div>
    <div class="tab-pane fade" id="student">

        <h3>Informasi Peserta</h3>

        <?= $form->field($student, 'username')->textInput(['maxlength' => 25]) ?>

        <?= $form->field($student, 'new_password')->passwordInput(['maxlength' => 60]) ?>

        <?= $form->field($student, 'eselon2')->textInput(['maxlength' => 255]) ?>

        <?= $form->field($student, 'eselon3')->textInput(['maxlength' => 255]) ?>

        <?= $form->field($student, 'eselon4')->textInput(['maxlength' => 255]) ?>

        <div class="row clearfix">
            <div class="col-md-3">
                <?php
                $data = ['2' => 'Eselon 2','3' => 'Eselon 3','4' => 'Eselon 4'];
                echo $form->field($student, 'satker')->widget(Select2::classname(), [
                    'data' => $data,
                    'options' => ['placeholder' => 'Choose satker ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-md-4">
                <?= $form->field($student, 'no_sk')->textInput() ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($student, 'tmt_sk')->textInput() ?>
            </div>
        </div>

        <a class="btn btn-default" onclick="$('#tab_wizard a[href=#photo_document]').tab('show')">
            Sebelumnya
            <i class="fa fa-fw fa-arrow-circle-o-left"></i>
        </a>
        <?= Html::submitButton($person->isNewRecord ? Yii::t('app', 'SYSTEM_BUTTON_CREATE') : Yii::t('app', 'SYSTEM_BUTTON_UPDATE'), ['class' => $person->isNewRecord ? 'btn btn-success' : 'btn btn-primary','onclick'=>'if($("#agreement_status").prop("checked")==false){ alert("Anda harus menyetujui Pakta Integritas!"); return false; }']) ?>
    </div>
    </div>
    <div class="clearfix"><hr></div>

    <?= $form->field($person, 'status')->widget(SwitchInput::classname(), [
        'pluginOptions' => [
            'onText' => 'Aktif',
            'offText' => 'Blokir',
        ]
    ]) ?>

    <?php ActiveForm::end(); ?>

    <?php $this->registerCss('label{display:block !important;}'); ?>
    </div>
    </div>
</div>
