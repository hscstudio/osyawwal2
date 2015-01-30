<?php
use yii\helpers\Html;
use hscstudio\heart\widgets\Breadcrumbs;
use kartik\icons\Icon;
use kartik\widgets\AlertBlock; 
// Set default icon fontawesome
Icon::map($this, Icon::FA);
/**
 * @var \yii\web\View $this
 * @var string $content
 */
?>
<?php $this->beginContent('@app/views/layouts/main.php'); ?>

<div class="wrapper">
    <aside class="right-side strech">
        <?php if (Yii::$app->user->isGuest === false) { // fajar - Klo guest, berarti ga login, jd ga usah nampilin sidemenu ?>
        <?php if (false) { // fajar - hilangin header ?>
        <section class="content-header">
            <h1><?= Html::encode($this->title) ?></h1>
        </section>
        <?php } ?>
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?php } ?>
        <section class="content">
			<?php 
			echo AlertBlock::widget([
				'useSessionFlash' => true,
				'type' => AlertBlock::TYPE_ALERT
			]); 
			?>
            <?= $content ?>
        </section>
    </aside>
</div>
<?php $this->endContent();