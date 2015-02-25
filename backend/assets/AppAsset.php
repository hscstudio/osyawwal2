<?php

namespace backend\assets;

use Yii;
use yii\base\Event;
use yii\web\Response;
use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
    ];
    public $js = [
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}

/* Titip hook. Hapus header expires tiap kali ngirim repon. Pusintek ga suka persisten cookie */
Event::on(Response::className(), Response::EVENT_AFTER_PREPARE, function ($event) {
    header_remove('Expires');
});