<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
?>
<div class="site-error">


    <div class="jumbotron">
        <?php
            echo '<img style="max-width:none!important;" src="'.Yii::$app->homeUrl.'/kuc'.rand(4,5).'.gif">';
        ?>
        <h2><?= nl2br(Html::encode($message)) ?> Hubungi Bagian TIK</h2>
    </div>

</div>
