<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model backend\models\Issue */

$controller = $this->context;
$this->title = 'Topic: '.$model->subject.' #'.$model->id;
$this->params['breadcrumbs'][] = ['label' => 'Issues', 'url' => ['issue']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="issue-view  panel panel-default">

   <div class="panel-heading"> 
        <div class="pull-right">
        <?=
 Html::a('<i class="fa fa-fw fa-arrow-left"></i> Back', ['issue'], ['class' => 'btn btn-xs btn-primary']) ?>
        </div>
		<?php
		$content = '<strong>'.$this->title.'</strong><br>';
		$content .= '<small>';
		if($model->status==1){
			$content .= '<span class="label label-success">STATUS: OPEN</span> ';
		}
		else{
			$content .= '<span class="label label-danger">STATUS: CLOSE</span> ';
		}
		$user = \backend\models\User::findOne($model->created_by);						
		if(!empty($user)){
			$content .= ' '.$user->employee->person->name.' ';
		}		
		$content .= 'open this issue at '.$model->created.' ';
		
		$content .= '</small>';
		$label = $model->getLastLabel($model->id);
		$labelt = '';
		if(!empty($label)){
			if($label=='verified') $labelt = '<span class="label label-warning">Label: To be '.$label.'</span>';
			if($label=='critical') $labelt = '<span class="label label-danger">Label: '.$label.'</span>';
			if($label=='bugfix') $labelt = '<span class="label label-primary">Label: '.$label.'</span>';
			if($label=='discussion') $labelt = '<span class="label label-info">Label: '.$label.'</span>';
			if($label=='enhancement') $labelt = '<span class="label label-success">Label: '.$label.'</span>';
		}
		else{
			$labelt = '';//<span class="label label-default">-</span>';
		}
		echo $content.$labelt;
		?>
    </div>
    <div class="panel-body">	
		<blockquote>
			<?php 
			echo $model->content;
			if(!empty($model->attachment) and strlen(($model->attachment))>3){
				echo '<br>';
				echo Html::a('<i class="fa fa-fw fa-download"></i>  download attachment',Url::to(['/file/download','file'=>'issue/'.$model->id.'/'.$model->attachment]));
			}
			?>
		</blockquote>
		<?php
		foreach($modelChildrens as $modelChildren){
			$user = \backend\models\User::findOne($modelChildren->created_by);						
			$author = '';
			if(!empty($user)){
				$author = ' '.$user->employee->person->name.' ';
			}		
			if($modelChildren->subject=='label'){
				$label = $modelChildren->label;
				$labelt = '';
				if(!empty($label)){
					if($label=='verified') $labelt = '<span class="label label-warning">Label: To be '.$label.'</span>';
					if($label=='critical') $labelt = '<span class="label label-danger">Label: '.$label.'</span>';
					if($label=='bugfix') $labelt = '<span class="label label-primary">Label: '.$label.'</span>';
					if($label=='discussion') $labelt = '<span class="label label-info">Label: '.$label.'</span>';
					if($label=='enhancement') $labelt = '<span class="label label-success">Label: '.$label.'</span>';
				}
				else{
					$labelt = '';//<span class="label label-default">-</span>';
				}
				echo $labelt.' by '.$author.' '.$modelChildren->created.'<hr> ';
			}
			else if($modelChildren->subject=='status'){
				$status = $modelChildren->status;
				$statust = '';
				if(isset($status)){
					if($status==1) $statust = '<span class="label label-success">Status: open</span>';
					else $statust = '<span class="label label-danger">Status: close</span>';
				}
				else{
					$statust = '';//<span class="label label-default">-</span>';
				}
				echo $statust.' by '.$author.' '.$modelChildren->created.'<hr> ';
			}
			else{
				?>
				<div class="panel panel-default">
					<div class="panel-heading"> 
						<?php
						$content = $author;						
						$content .= ' commented at '.$modelChildren->created.' ';
						echo $content;
						?>
					</div>
					<div class="panel-body">
						<?php
						echo $modelChildren->content;
						echo '<br>';
						if(!empty($modelChildren->attachment) and strlen(($modelChildren->attachment))>3){
							echo Html::a('<i class="fa fa-fw fa-download"></i> download attachment',Url::to(['/file/download','file'=>'issue/'.$modelChildren->id.'/'.$modelChildren->attachment]));
						}
						?>
					</div>
				</div>
				<?php
			}
		}
		?>
		
		<div class="panel panel-default">
			<div class="panel-heading"> 
				<strong>Write</strong>
			</div>
			<div class="panel-body">
				<?php $form = ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data']]); ?>
				<?= $form->errorSummary($modelNew) ?> <!-- ADDED HERE -->

				<?php
				echo $form->field($modelNew, 'content')->textarea(['rows' => 3]) ;
				?>
				
				<?php
				echo $form->field($modelNew, 'attachment')->widget(FileInput::classname(), [
					'pluginOptions' => [
						'showUpload' => false,
					]
				])->label(); 
				?>
				
				<?php 
				if(\Yii::$app->user->can('BPPK')){
					$data = [
						'verified' => 'verified','critical' => 'critical',
						'bugfix' => 'bugfix','discussion' => 'discussion',
						'enhancement' => 'enhancement'
					];
					echo $form->field($modelNew, 'label')->widget(Select2::classname(), [
						'data' => $data,
						'options' => [
							'placeholder' => 'Choose label ...',
							'onchange' => "$('#issue-content').val($(this).val())",
						],
						'pluginOptions' => [
						'allowClear' => true
						],
					]); 
				}
				?>
				
				<?php
				if(\Yii::$app->user->can('BPPK') or \Yii::$app->user->id==$model->created_by){
					$data = ['1' => 'Open','2' => 'Close'];
					echo $form->field($modelNew, 'status')->widget(Select2::classname(), [
						'data' => $data,
						'options' => [
							'placeholder' => 'Choose status ...',
							'onchange' => "$('#issue-content').val($(this).val())",
						],
						'pluginOptions' => [
						'allowClear' => true
						],
					]); 
				}
				?>
				
				<div class="form-group">
					<?= Html::submitButton('Comment', ['class' => 'btn btn-success' ]) ?>
				</div>

				<?php ActiveForm::end(); ?>
			</div>
		</div> 
    </div>
</div> 