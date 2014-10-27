<?php

namespace backend\modules\sekretariat\finance;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\sekretariat\finance\controllers';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
	
	public function getMenuItems(){
		return [
			['icon'=>'fa fa-fw fa-dashboard','label' => 'Dashboard', 'url' => ['/'.$this->uniqueId.'/default']],
			['icon'=>'fa fa-fw fa-tree','label' => 'Referensi', 'url' => '#','items'=>[
			['icon'=>'fa fa-fw fa-stack-overflow','label' => 'Sbu', 'url' => ['/'.$this->uniqueId.'/reference-sbu/index'],'path'=>[
				'/reference-sbu/',
			]],
			]],
			['icon'=>'fa fa-fw fa-stack-overflow','label' => 'Meeting Activity', 'url' => ['/'.$this->uniqueId.'/activity-meeting-general/index'],'path'=>[
				'/activity-meeting-general/',
			]],
		];
	}
}
