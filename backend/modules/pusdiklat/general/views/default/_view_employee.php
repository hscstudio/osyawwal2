<?php
use yii\helpers\Html;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use kartik\datecontrol\DateControl;
use kartik\widgets\SwitchInput;
use backend\models\Reference;
use kartik\widgets\FileInput;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\Person */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="person-form">


<ul class="nav nav-tabs" role="tablist" id="tab_wizard">
    <li class="active"><a href="#personal_information" role="tab" data-toggle="tab">Personal</a></li>
    <li class=""><a href="#contact_information" role="tab" data-toggle="tab">Contact</a></li>
    <li class=""><a href="#employee_information" role="tab" data-toggle="tab">Employee</a></li>
    <li class=""><a href="#office_information" role="tab" data-toggle="tab">Office</a></li>
    <li class=""><a href="#education_information" role="tab" data-toggle="tab">Education</a></li>
    <li class=""><a href="#photo_document" role="tab" data-toggle="tab">Photo & Document</a></li>
</ul>
<div class="tab-content" style="border: 1px solid #ddd; border-top-color: transparent; padding:10px; background-color: #fff;">

    <div class="tab-pane fade-in active" id="personal_information">
        <h3>Informasi Pribadi</h3>
        <table class="table table-striped table-condensed">
            <tr>
                <td style="width:20%">Nama Lengkap</td>
                <td>
                    <?= $model->front_title; ?>
                    <?= $model->name; ?>
                    <?= $model->back_title; ?>
                </td>
            </tr>
            <tr>
                <td>Panggilan</td>
                <td>
                    <?= $model->nickname; ?>
                </td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td>
                    <?= ($model->gender==1)?'Pria':'Wanita'; ?>
                </td>
            </tr>
            <tr>
                <td>Tempat, Tanggal Lahir</td>
                <td>
                    <?= $model->born; ?>
                    <?= $model->birthday; ?>
                </td>
            </tr>
            <tr>
                <td>Status Perkawinan</td>
                <td>
                    <?= ($model->married==1)?'Menikah':'Tidak Menikah'; ?>
                </td>
            </tr>
            <tr>
                <td>Gol. Darah</td>
                <td>
                    <?= $model->blood; ?>
                </td>
            </tr>
            <tr>
                <td>NIP</td>
                <td>
                    <?= $model->nip; ?>
                </td>
            </tr>
            <tr>
                <td>No Identitas</td>
                <td>
                    <?= $model->nid; ?>
                </td>
            </tr>
        </table>

        <a class="btn btn-default" onclick="$('#tab_wizard a[href=#contact_information]').tab('show')">
            Next
            <i class="fa fa-fw fa-arrow-circle-o-right"></i>
        </a>
    </div>
    <div class="tab-pane fade" id="contact_information">

        <h3>Informasi Kontak</h3>

        <table class="table table-striped table-condensed">
            <tr>
                <td style="width:20%">Telp</td>
                <td>
                    <?= $model->phone; ?>
                </td>
            </tr>
            <tr>
                <td>Email</td>
                <td>
                    <?= $model->email; ?>
                </td>
            </tr>
            <tr>
                <td>Website Blog</td>
                <td>
                    <?= $model->homepage; ?>
                </td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>
                    <?= $model->address; ?>
                </td>
            </tr>
            <tr>
                <td>Akun Bank</td>
                <td>
                    <?= $model->bank_account; ?>
                </td>
            </tr>
        </table>

        <a class="btn btn-default" onclick="$('#tab_wizard a[href=#personal_information]').tab('show')">
            Previous
            <i class="fa fa-fw fa-arrow-circle-o-left"></i>
        </a>
        <a class="btn btn-default" onclick="$('#tab_wizard a[href=#employee_information]').tab('show')">
            Next
            <i class="fa fa-fw fa-arrow-circle-o-right"></i>
        </a>

    </div>
    <div class="tab-pane fade" id="employee_information">

        <h3>Informasi Kepegawaian (PNS Only)</h3>

        <table class="table table-striped table-condensed">
            <tr>
                <td style="width:20%">NIP</td>
                <td>
                    <?= $model->nip; ?>
                </td>
            </tr>
            <tr>
                <td>Golongan</td>
                <td>
                    <?php
                    $object_reference=\backend\models\ObjectReference::find()
                        ->where([
                            'object'=>'person',
                            'object_id'=>$model->id,
                            'type'=>'rank_class',
                        ])
                        ->one();
                    if(null!=$object_reference)
                        if(null!=$object_reference->reference)
                            echo $object_reference->reference->name;
                    ?>
                </td>
            </tr>
        </table>

        <a class="btn btn-default" onclick="$('#tab_wizard a[href=#contact_information]').tab('show')">
            Previous
            <i class="fa fa-fw fa-arrow-circle-o-left"></i>
        </a>
        <a class="btn btn-default" onclick="$('#tab_wizard a[href=#office_information]').tab('show')">
            Next
            <i class="fa fa-fw fa-arrow-circle-o-right"></i>
        </a>

    </div>
    <div class="tab-pane fade" id="office_information">

        <h3>Informasi Kantor</h3>

        <table class="table table-striped table-condensed">
            <tr>
                <td style="width:20%">Jabatan</td>
                <td>
                    <?php
                    $data = ['5' => 'Pelaksana / Others','4' => 'Eselon 4','3' => 'Eselon 3','2' => 'Eselon 2','1' => 'Eselon 1'];
                    if(isset($data[$model->position])) echo $data[$model->position].' ';
                    echo $model->position_desc;
                    ?>
                </td>
            </tr>
            <tr>
                <td>Golongan</td>
                <td>
                    <?php
                    $object_reference=\backend\models\ObjectReference::find()
                        ->where([
                            'object'=>'person',
                            'object_id'=>$model->id,
                            'type'=>'unit',
                        ])
                        ->one();
                    if(null!=$object_reference)
                        if(null!=$object_reference->reference)
                            echo $object_reference->reference->name;
                    ?>
                </td>
            </tr>
            <tr>
                <td>Organisation</td>
                <td>
                    <?= $model->organisation; ?>
                </td>
            </tr>
            <tr>
                <td>Office Phone</td>
                <td>
                    <?= $model->office_phone; ?>
                </td>
            </tr>
            <tr>
                <td>Office Fax</td>
                <td>
                    <?= $model->office_fax; ?>
                </td>
            </tr>
            <tr>
                <td>Office Email</td>
                <td>
                    <?= $model->office_email; ?>
                </td>
            </tr>
            <tr>
                <td>Office Addrees</td>
                <td>
                    <?= $model->office_address; ?>
                </td>
            </tr>
        </table>

        <a class="btn btn-default" onclick="$('#tab_wizard a[href=#employee_information]').tab('show')">
            Previous
            <i class="fa fa-fw fa-arrow-circle-o-left"></i>
        </a>
        <a class="btn btn-default" onclick="$('#tab_wizard a[href=#education_information]').tab('show')">
            Next
            <i class="fa fa-fw fa-arrow-circle-o-right"></i>
        </a>

    </div>
    <div class="tab-pane fade" id="education_information">

        <h3>Informasi Pendidikan & Pengalaman</h3>

        <table class="table table-striped table-condensed">
            <tr>
                <td style="width:20%">Pendidikan</td>
                <td>
                    <?php
                    $object_reference=\backend\models\ObjectReference::find()
                        ->where([
                            'object'=>'person',
                            'object_id'=>$model->id,
                            'type'=>'graduate',
                        ])
                        ->one();
                    if(null!=$object_reference)
                        if(null!=$object_reference->reference)
                            echo $object_reference->reference->name;
                    echo $model->graduate_desc;
                    ?>
                </td>
            </tr>

        </table>

        <a class="btn btn-default" onclick="$('#tab_wizard a[href=#office_information]').tab('show')">
            Previous
            <i class="fa fa-fw fa-arrow-circle-o-left"></i>
        </a>
        <a class="btn btn-default" onclick="$('#tab_wizard a[href=#photo_document]').tab('show')">
            Next
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
                        </tr>
                    <?php
                    }
                }
            }
            ?>
        </table>

        <div class="clearfix"></div>

        <a class="btn btn-default" onclick="$('#tab_wizard a[href=#education_information]').tab('show')">
            Previous
            <i class="fa fa-fw fa-arrow-circle-o-left"></i>
        </a>
    </div>
</div>
<div class="clearfix"><hr></div>

</div>
