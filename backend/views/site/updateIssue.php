<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Issue */

$controller = $this->context;

$this->title = 'Update Issue: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Issues', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="issue-update panel panel-default">
    
    <div class="panel-heading">        
        <div class="pull-right">
        <?=
 Html::a('<i class="fa fa-fw fa-arrow-left"></i> Back', ['index'], ['class' => 'btn btn-xs btn-primary']) ?>
        </div>
        <h1 class="panel-title"><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="panel-body">
        <?= $this->render('_form_issue', [
            'model' => $model,
        ]) ?>
    </div>
</div> 