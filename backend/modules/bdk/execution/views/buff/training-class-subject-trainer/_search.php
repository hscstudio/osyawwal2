<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\TrainingClassSubjectTrainerSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="training-class-subject-trainer-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'tb_training_class_subject_id') ?>

    <?= $form->field($model, 'tb_trainer_id') ?>

    <?= $form->field($model, 'ref_trainer_type') ?>

    <?= $form->field($model, 'cost') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created') ?>

    <?php // echo $form->field($model, 'createdBy') ?>

    <?php // echo $form->field($model, 'modified') ?>

    <?php // echo $form->field($model, 'modifiedBy') ?>

    <?php // echo $form->field($model, 'deleted') ?>

    <?php // echo $form->field($model, 'deletedBy') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
