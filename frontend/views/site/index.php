<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\grid\GridView as Gridview2;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\widgets\Select2;

$controller = $this->context;
//$menus = $controller->module->getMenuItems('SIMBPPK');
//$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
$this->title = Yii::t('app', 'Data Diklat BPPK');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">
    <div class="container-fluid">
        <div class="row">            
            <div class="col-md-12">
                <div class="row">                    
                    <div class="col-md-3">                        
                        <div class="row">
                            <div class="col-md-12">
								<div class="jumbotron">
                                    <h2>Selamat Datang</h2>                            
                                    <p class="lead">Di Aplikasi Kediklatan SIMBPPK</p>                            
                                    <p><a class="btn btn-lg btn-success" href="<?php echo Yii::$app->homeUrl."/site/masuk.aspx";?>">Login Peserta</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">                        
                      
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>