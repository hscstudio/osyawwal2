<?php

namespace backend\modules\bdk\general;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\bdk\general\controllers';

    public function init()
    {
        parent::init();
    }
	
	public function getMenuItems(){
		return [
			['icon'=>'fa fa-fw fa-dashboard','label' => 'Dashboard', 'url' => ['/'.$this->uniqueId.'/default']],
			/* ['icon'=>'fa fa-fw fa-link', 'label' => 'Keuangan', 'url' => ['#'], 'items'=>[
			]], */
			['icon'=>'fa fa-fw fa-link', 'label' => '+ Pegawai TU Humas', 'url' => ['#'], 'items'=>[
				['icon'=>'fa fa-fw fa-user','label' => 'Manage Person', 'url' => ['person2/index'],'path'=>[
				'/person2/',
				]],
				['icon'=>'fa fa-fw fa-user-md','label' => 'Manage Employee', 'url' => ['employee2/index'],'path'=>[
					'/employee2/',
				]],
				['icon'=>'fa fa-fw fa-key','label' => 'Manage User', 'url' => ['user2/index'],'path'=>[
					'/user2/',
				]],
			]],
			['icon'=>'fa fa-fw fa-link', 'label' => '+ Pengelolaan Aset', 'url' => ['#'], 'items'=>[
				['icon'=>'fa fa-fw fa-stack-overflow', 'label' => 'Meeting Activity', 'url' => ['meeting-activity3/index'],'path'=>[
					'/meeting-activity3/',
				]],
				['icon'=>'fa fa-fw fa-stack-overflow', 'label' => 'Room Request', 'url' => ['room-request3/index'],'path'=>[
					'/room-request3/',
				]],
				['icon'=>'fa fa-fw fa-stack-overflow', 'label' => 'Room', 'url' => ['room3/index'],'path'=>[
					'/room3/',
				]],
			]]
		];
	}
}
