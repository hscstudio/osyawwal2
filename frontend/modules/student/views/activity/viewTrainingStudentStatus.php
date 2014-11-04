<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use yii\bootstrap\Modal;
use kartik\widgets\FileInput;

/* @var $this yii\web\View */
/* @var $model frontend\models\Student */

$this->title = $model->training->activity->name;
$this->params['breadcrumbs'][] = ['label' => 'Students', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
$data = \frontend\models\Person::find()->where(['id'=>Yii::$app->user->identity->id])->One();
$tgllahir = explode('-',$data->birthday);
?>
<?php
    Modal::begin([
		'header'=>'PRINT FORM EREGISTERASI',
		//'data' => $model,
		'toggleButton' => ['label'=>'PRINT', 'class'=>'fa fa-fw fa-search'],
	]);
?>
<table align="center" cellspacing="1" cellpadding="2" style="font-family:'Times New Roman', Times, serif" width="100%">
	<tr>
    	<td width="8%" rowspan="4"><img src="<?php echo Yii::getAlias('@web');?>/DepkeuLogo.jpg" width="55" height="60" />
   	  <td width="1%"></td><td width="91%">KEMENTERIAN KEUANGAN REPUBLIK INDONESIA</td>
    </tr>
    <tr>
    	<td></td><td>BADAN PENDIDIKAN DAN PELATIHAN KEUANGAN</td>
    </tr>
    <tr>
    	<td></td><td></td>
    </tr>
    <tr>
    	<td></td><td></td>
    </tr>
</table>
<hr />
<table align="center" style="font-family:'Times New Roman', Times, serif" width="100%">
	<tr>
    	<td width="33%">NAMA</td><td width="2%">:</td><td width="65%"><?php echo strtoupper($data->name);?></td>
    </tr>
    <tr>
    	<td>NIP</td><td>:</td><td><?php echo $data->nip;?></td>
    </tr>
    <tr>
    	<td>TEMPAT / TANGGAL LAHIR </td><td>:</td><td><?php echo strtoupper($data->born);?> / <?php echo $tgllahir[2].'-'.$tgllahir[1].'-'.$tgllahir[0];?></td>
    </tr>
    <tr>
    	<td>KEMENTERIAN</td><td>:</td><td>KEMENTERIAN KEUANGAN</td>
    </tr>
    <tr>
    	<td>UNIT ORGANISASI</td><td>:</td><td><?php echo strtoupper(\frontend\models\ObjectReference::findOne(['object'=>'person','object_id'=>$data->id,'type'=>'unit'])->reference->name);?></td>
    </tr>
    <tr>
    	<td>PANGKAT / GOLONGAN</td><td>:</td><td><?php echo strtoupper(\frontend\models\ObjectReference::findOne(['object'=>'person','object_id'=>$data->id,'type'=>'rank_class'])->reference->name);?></td>
    </tr>
    <tr>
    	<td>JABATAN</td><td>:</td><td><?php echo strtoupper($data->position_desc);?></td>
    </tr>
    <tr>
    	<td>STATUS PESERTA</td><td>:</td><td><?php echo $data->status==2?'BARU':'MENGULANG';?></td>
    </tr>
    
</table>
<table style="font-family:'Times New Roman', Times, serif" width="100%">
	<tr>
    	<td >&nbsp;</td><td ></td><td></td>
    </tr>
    <tr>
    	<td colspan="3" style="font-size:12px">Dengan ini saya menyatakan bahwa data yang saya inputkan adalah benar dan dapat dipertanggungjawabkan.</td>
    </tr>
    <tr>
    	<td >&nbsp;</td><td ></td><td></td>
    </tr>
    <tr>
    	<td>&nbsp;</td><td></td><td>..........,<?php echo date('M Y');?></td>
    </tr>
    <tr>
    	<td>PETUGAS REGISTERASI</td><td>&nbsp;</td><td>HORMAT SAYA</td>
    </tr>
    <tr>
    	<td>&nbsp;</td><td></td><td></td>
    </tr>
    <tr>
    	<td >&nbsp;</td><td ></td><td></td>
    </tr>
    <tr>
    	<td>.......................................</td><td></td><td>.......................</td>
    </tr>
</table>
<div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="window.location='<?php echo \yii\helpers\Url::to('../student/print.aspx');?>'">Print</button>
               
</div>
<?php		
	Modal::end();
?>
<div class="student-view">
    <?= DetailView::widget([
        'model' => $model,
		'mode'=>DetailView::MODE_VIEW,
		'panel'=>[
			'heading'=>'<i class="fa fa-fw fa-globe"></i> '.'Students',
			'type'=>DetailView::TYPE_DEFAULT,
		],
		
		'buttons1'=> Html::a('<i class="fa fa-fw fa-arrow-left"></i> BACK',['index'],

						['class'=>'btn btn-xs btn-primary',
						 'title'=>'Back to Index',
						])
					
		,
        'attributes' => [
            [
				'attribute'=> 'training_id',
				'value'=>$model->training->activity->name,
			],
			[
				'attribute'=> 'student_id',
				'value'=>$model->student->person->name,
			],
	
			[
				'attribute'=> 'status',
				'value'=>$model->status==1?'Baru':'Mengulang',
			],
        ],
    ]) ?>
</div>