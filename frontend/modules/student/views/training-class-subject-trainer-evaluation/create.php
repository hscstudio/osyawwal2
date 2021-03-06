<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\TrainingClassSubjectTrainerEvaluation */

$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;

$this->title = 'Form Training Class Subject Trainer Evaluation # '.\frontend\models\Person::findOne(['id'=>base64_decode(\hscstudio\heart\helpers\Kalkun::HexToAscii($trainer_id))])->name;
$this->params['breadcrumbs'][] = ['label' => 'Training Schedule Trainer ', 'url' => ['./training-schedule-trainer/index','training_id'=>$training_id,'training_student_id'=>$training_student_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="training-class-subject-trainer-evaluation-create  panel panel-default">

    <div class="panel-heading"> 
		<div class="pull-right">
        <?=
 Html::a('<i class="fa fa-fw fa-arrow-left"></i> Back', ['training-schedule-trainer/index','training_id'=>$training_id,'training_student_id'=>$training_student_id], ['class' => 'btn btn-xs btn-primary']) ?>
		</div>
		<h1 class="panel-title"><?= Html::encode($this->title) ?></h1> 
	</div>
	<div class="panel-body">
		<?= $this->render('_form', [
			'model' => $model,
		]) ?>
	</div>
</div>
