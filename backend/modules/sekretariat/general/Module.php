<?php

namespace backend\modules\sekretariat\general;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\sekretariat\general\controllers';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
	
	public function getMenuItems(){
		return [
			['icon'=>'fa fa-fw fa-dashboard','label' => 'Dashboard', 'url' => ['/'.$this->uniqueId.'/default']],
			['icon'=>'fa fa-fw fa-suitcase','label' => 'Rapat', 'url' => ['/'.$this->uniqueId.'/activity-meeting-general/index'],'path'=>[
				'/activity-meeting-general/',
			]],
			['icon'=>'fa fa-fw fa-inbox','label' => 'Room', 'url' => ['/'.$this->uniqueId.'/room/index'],'path'=>[
				'/room/',
			]],
			['icon'=>'fa fa-fw fa-ticket','label' => 'Room Request', 'url' => ['/'.$this->uniqueId.'/activity-room/index'],'path'=>[
				'/activity-room/',
			]],
		];
	}
}
