<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Issue */

$controller = $this->context;

$this->title = 'Create Issue';
$this->params['breadcrumbs'][] = ['label' => 'Issues', 'url' => ['issue']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="issue-create  panel panel-default">

    <div class="panel-heading"> 
        <div class="pull-right">
        <?=
 Html::a('<i class="fa fa-fw fa-arrow-left"></i> Back', ['issue'], ['class' => 'btn btn-xs btn-primary']) ?>
        </div>
        <h1 class="panel-title"><?= Html::encode($this->title) ?></h1> 
    </div>
    <div class="panel-body">
        <?= $this->render('_form_issue', [
            'model' => $model,
        ]) ?>
    </div>
</div> 