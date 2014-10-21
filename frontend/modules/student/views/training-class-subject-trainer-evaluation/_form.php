<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\TrainingClassSubjectTrainerEvaluation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="training-class-subject-trainer-evaluation-form">

    <?php $form = ActiveForm::begin(); ?>
	<?= $form->errorSummary($model) ?> <!-- ADDED HERE -->
    
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
	
	echo "<table class='table table-condensed table-striped'>";
	foreach($group_arr as $idx=>$group){
		echo "<tr>";
		echo "<th colspan='2'>".$group."</th>";
		echo "<th colspan='5'>";
		if($idx==0) echo "Skala Penilaian";
		echo "</th>";
		echo "</tr>";
		foreach($item_arr[$idx] as $num=>$item){
			echo "<tr>";
			if($num==1) echo "<td style='width:10px;'>".$num."</td>"; else echo "<td>".$num."</td>";
			if($num==1) echo "<td style=';'>".$item."</td>"; else echo "<td>".$item."</td>";
			if($num==1) echo "<td style='width:60%;'>"; else echo "<td>";
			echo $form->field($model, 'value['.$num.']')->radioList(
																				  [1 => '1.Tidak baik',
																				   2 => '2.Kurang Baik',
																				   3 => '3.Cukup',
																				   4 => '4.Baik',
																				   5 => '5.Sangat Baik'],
																				  ['inline'=>true]);
			echo "</td>";
			echo "</tr>";
		}
	}	
	echo "<tr>";
	echo "<th colspan='7' style='text-align:left;'>IV. KESAN & SARAN</th>";
	echo "</tr>";
	echo "<tr>";
	echo "<td colspan='20'>'".$form->field($model, 'comment')->textInput(['maxlength' => 3000])->label('Komentar')."'";
	echo "</td>";
	echo "</tr>";
	echo "</table>";
?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
