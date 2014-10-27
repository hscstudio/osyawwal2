<?php

namespace backend\modules\sekretariat\it;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\sekretariat\it\controllers';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
	
	public function getMenuItems(){
		return [
			['icon'=>'fa fa-fw fa-dashboard','label' => 'Dashboard', 'url' => ['/'.$this->uniqueId.'/default']],
			['icon'=>'fa fa-fw fa-stack-overflow','label' => 'Meeting Activity', 'url' => ['/'.$this->uniqueId.'/activity-meeting-general/index'],'path'=>[
				'/activity-meeting-general/',
			]],
		];
	}
}
