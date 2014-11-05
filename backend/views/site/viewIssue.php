<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Issue */

$controller = $this->context;
$this->title = 'Update #'.$model->id;
$this->params['breadcrumbs'][] = ['label' => 'Issues', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="issue-view  panel panel-default">

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
                    'id',
            'parent_id',
            'subject',
            'content:ntext',
            'attachment',
            'label',
            'status',
            'created',
            'created_by',
            'modified',
            'modified_by',
                ],
            ]) ?>
    </div>
</div> 