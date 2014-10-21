<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\TrainingClassSubjectTrainerEvaluation */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = 'Update #'.$model->id;
$this->params['breadcrumbs'][] = ['label' => 'Training Class Subject Trainer Evaluations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="training-class-subject-trainer-evaluation-view  panel panel-default">

   <div class="panel-heading"> 
		<div class="pull-right">
        <?=
 Html::a('<i class="fa fa-fw fa-arrow-left"></i> Back', ['index'], ['class' => 'btn btn-xs btn-primary']) ?>
		</div>
		<h1 class="panel-title"><?= Html::encode($this->title) ?></h1> 
	</div>
	<div class="panel-body">

		<!--
		<p>
			<?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
			<?= Html::a('Delete', ['delete', 'id' => $model->id], [
				'class' => 'btn btn-danger',
				'data' => [
					'confirm' => 'Are you sure you want to delete this item?',
					'method' => 'post',
				],
			]) ?>
		</p>
		-->
			<?= DetailView::widget([
				'model' => $model,
				'attributes' => [
		           /* 'id',
            'training_class_subject_id',
            'trainer_id',
            'student_id',
            'value',
            'comment',
            'status',
            'created',
            'created_by',
            'modified',
            'modified_by',*/
				],
			]) ?>
            <?php
	$group_arr=array(
					0=>"I. Sikap Widyaiswara",
					1=>"II. Teknik Presentasi dan Komunikasi",
					2=>"III. Kompetensi Widyaiswara"
				);

	$item_arr[0]=array(
				1=>"Kedisiplinan Kehadiran",
				2=>"Sikap dan Perilaku",
				3=>"Pemberian motivasi kepada peserta",
				4=>"Penampilan",
			);

	$item_arr[1]=array(
				5=>"Nada dan Suara",
				6=>"Sistematika Penyajian",
				7=>"Metode mengajar",
				8=>"Penguasaan sarana diklat",
			);

	$item_arr[2]=array(
				9=>"Kemampuan menyajikan materi",
				10=>"Cara menjawab pertanyaan",
				11=>"Kesesuaian materi dengan SAP dan GBPP",
				12=>"Update terhadap pengetahuan terbaru",
			);
	
	echo "<table class='table table-condensed table-striped table-bordered'>";
	$model->value=explode("|",$model->value);
	$no=0;
	foreach($group_arr as $idx=>$group){
		echo "<tr>";
		echo "<th colspan='2'>".$group."</th>";
		echo "<th>";
		if($idx==0) echo "Skala Penilaian";
		echo "</th>";
		echo "</tr>";
		foreach($item_arr[$idx] as $num=>$item){
			echo "<tr>";
			if($num==1) echo "<td style='width:10px;'>".$num."</td>"; else echo "<td>".$num."</td>";
			if($num==1) echo "<td style=';'>".$item."</td>"; else echo "<td>".$item."</td>";
			if($num==1) echo "<td style='width:60%;'>"; else echo "<td>";
			if($model->value[$no]==1) echo "Tidak baik";
			if($model->value[$no]==2) echo "Kurang baik";
			if($model->value[$no]==3) echo "Cukup";
			if($model->value[$no]==4) echo "Baik";
			if($model->value[$no]==5) echo "Sangat baik";
			echo "</td></tr>";
			$no++;
		}
	}
	echo "<tr>";
	echo "<th colspan='3' style='text-align:left;'>IV. KESAN & SARAN</th>";
	echo "</tr>";	
	echo "<tr>";
	echo "<td colspan='3'>".$model->comment."</textarea>";
	echo "<div class='message'></div>";
	echo "</td>";
	echo "</tr>";	
	echo "</table>";
    ?>
	</div>
</div>
