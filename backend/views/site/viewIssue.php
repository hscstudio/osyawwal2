<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
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
			$content .= '<span class="label label-success">OPEN</span> ';
		}
		else{
			$content .= '<span class="label label-danger">CLOSE</span> ';
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
			if($label=='verified') $labelt = '<span class="label label-warning">Status: To be '.$label.'</span>';
			if($label=='critical') $labelt = '<span class="label label-danger">'.$label.'</span>';
			if($label=='bugfix') $labelt = '<span class="label label-primary">'.$label.'</span>';
			if($label=='discussion') $labelt = '<span class="label label-info">'.$label.'</span>';
			if($label=='enhancement') $labelt = '<span class="label label-success">'.$label.'</span>';
		}
		else{
			$labelt = '';//<span class="label label-default">-</span>';
		}
		echo $content.$labelt;
		?>
    </div>
    <div class="panel-body">
		<blockquote>
			<?php echo $model->subject ?>
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
					if($label=='verified') $labelt = '<span class="label label-warning">Status: To be '.$label.'</span>';
					if($label=='critical') $labelt = '<span class="label label-danger">'.$label.'</span>';
					if($label=='bugfix') $labelt = '<span class="label label-primary">'.$label.'</span>';
					if($label=='discussion') $labelt = '<span class="label label-info">'.$label.'</span>';
					if($label=='enhancement') $labelt = '<span class="label label-success">'.$label.'</span>';
				}
				else{
					$labelt = '';//<span class="label label-default">-</span>';
				}
				echo $labelt.' by '.$author.' '.$modelChildren->created.'<hr> ';
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
						?>
					</div>
				</div>
				<?php
			}
		}
		?>
		
		<div class="issue-form">
			<?php $form = ActiveForm::begin(); ?>
			<?= $form->errorSummary($model) ?> <!-- ADDED HERE -->

			<?php
			$model->content	= NULL;
			echo $form->field($model, 'content')->textarea(['rows' => 3]) ;
			?>
			
			<?php
			echo $form->field($model, 'attachment')->widget(FileInput::classname(), [
				'pluginOptions' => [
					'showUpload' => false,
				]
			])->label(); 
			?>
			
			<?php 
			if(!$model->isNewRecord){
				if(\Yii::$app->user->can('BPPK')){
					$data = [
						'verified' => 'verified','critical' => 'critical',
						'bugfix' => 'bugfix','discussion' => 'discussion',
						'enhancement' => 'enhancement'
					];
					echo $form->field($model, 'label')->widget(Select2::classname(), [
						'data' => $data,
						'options' => ['placeholder' => 'Choose label ...'],
						'pluginOptions' => [
						'allowClear' => true
						],
					]); 
				}
			}
			?>
			
			<?php
			if(!$model->isNewRecord){
				if(\Yii::$app->user->can('BPPK') or \Yii::$app->user->id==$model->created_by){
					$data = ['1' => 'Open','0' => 'Close'];
					echo $form->field($model, 'status')->widget(Select2::classname(), [
						'data' => $data,
						'options' => ['placeholder' => 'Choose status ...'],
						'pluginOptions' => [
						'allowClear' => true
						],
					]); 
				}
			}
			?>
			
			<div class="form-group">
				<?= Html::submitButton('Comment', ['class' => 'btn btn-success' ]) ?>
			</div>

			<?php ActiveForm::end(); ?>

		</div> 
    </div>
</div> 