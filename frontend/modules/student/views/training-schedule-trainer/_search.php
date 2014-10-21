<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\modules\student\models\TrainingScheduleTrainerSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="training-schedule-trainer-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'training_schedule_id') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'trainer_id') ?>

    <?= $form->field($model, 'hours') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
