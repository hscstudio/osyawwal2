<?php

namespace backend\modules\pusdiklat\evaluation;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\pusdiklat\evaluation\controllers';

    public function init()
    {
        parent::init();
    }
	
	public function getMenuItems(){
		return [
			['icon'=>'fa fa-fw fa-dashboard','label' => 'Dashboard', 'url' => ['/'.$this->uniqueId.'/default']],
			['icon'=>'fa fa-fw fa-check-square-o','label' => '+ Evaluasi Diklat', 'url' => ['#'],'items'=>[
				['icon'=>'fa fa-fw fa-stack-overflow', 'label' => 'Training Activity', 'url' => ['activity/index'],'path'=>[
					'/activity/',
				]],
				['icon'=>'fa fa-fw fa-stack-overflow', 'label' => 'Meeting Activity', 'url' => ['meeting-activity/index'],'path'=>[
					'/meeting-activity/',
				]],
			]],
			['icon'=>'fa fa-fw fa-cloud','label' => '+ PHD', 'url' => ['#'],'items'=>[
				['icon'=>'fa fa-fw fa-stack-overflow', 'label' => 'Training Activity', 'url' => ['activity2/index'],'path'=>[
					'/activity2/',
				]],
				['icon'=>'fa fa-fw fa-stack-overflow', 'label' => 'Meeting Activity', 'url' => ['meeting-activity2/index'],'path'=>[
					'/meeting-activity2/',
				]],
			]],
			['icon'=>'fa fa-fw fa-book','label' => '+ IPK', 'url' => ['#'],'items'=>[
				['icon'=>'fa fa-fw fa-stack-overflow', 'label' => 'Training Activity', 'url' => ['activity3/index'],'path'=>[
					'/activity3/',
				]],
				['icon'=>'fa fa-fw fa-stack-overflow', 'label' => 'Meeting Activity', 'url' => ['meeting-activity3/index'],'path'=>[
					'/meeting-activity3/',
				]],
			]], 
		];
	}
}
