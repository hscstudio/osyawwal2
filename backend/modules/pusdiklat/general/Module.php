<?php

namespace backend\modules\pusdiklat\general;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\pusdiklat\general\controllers';

    public function init()
    {
        parent::init();
    }
	
	public function getMenuItems(){
		return [
			['icon'=>'fa fa-fw fa-dashboard','label' => 'Dashboard', 'url' => ['/'.$this->uniqueId.'/default']],
			['icon'=>'fa fa-fw fa-android', 'label' => 'Administrator', 'url' => '#','items'=>[
				['icon'=>'fa fa-fw fa-stack-overflow', 'label' => 'Assignment', 'url' => ['assignment0/index'],'path'=>[
					'/assignment0/',
				]],
			]],
			['icon'=>'fa fa-fw fa-link', 'label' => '+ Pegawai TU Humas', 'url' => ['#'], 'items'=>[
				['icon'=>'fa fa-fw fa-stack-overflow', 'label' => 'Training Activity', 'url' => ['activity/index'],'path'=>[
					'/activity/',
				]],
				['icon'=>'fa fa-fw fa-stack-overflow', 'label' => 'Meeting Activity', 'url' => ['meeting-activity/index'],'path'=>[
					'/meeting-activity/',
				]],
				['icon'=>'fa fa-fw fa-user','label' => 'Manage Person', 'url' => ['person/index'],'path'=>[
				'/person/',
				]],
				['icon'=>'fa fa-fw fa-user-md','label' => 'Manage Employee', 'url' => ['employee/index'],'path'=>[
					'/employee/',
				]],
				['icon'=>'fa fa-fw fa-key','label' => 'Manage User', 'url' => ['user/index'],'path'=>[
					'/user/',
				]],
			]],
			
			['icon'=>'fa fa-fw fa-link', 'label' => '+ Keuangan', 'url' => ['#'], 'items'=>[
				['icon'=>'fa fa-fw fa-stack-overflow', 'label' => 'Training Activity', 'url' => ['activity2/index'],'path'=>[
					'/activity2/',
				]],
				['icon'=>'fa fa-fw fa-stack-overflow', 'label' => 'Meeting Activity', 'url' => ['meeting-activity2/index'],'path'=>[
					'/meeting-activity2/',
				]],
			]],
			
			['icon'=>'fa fa-fw fa-link', 'label' => '+ Pengelolaan Aset', 'url' => ['#'], 'items'=>[
				['icon'=>'fa fa-fw fa-stack-overflow', 'label' => 'Training Activity', 'url' => ['activity3/index'],'path'=>[
					'/activity3/',
				]],
				['icon'=>'fa fa-fw fa-stack-overflow', 'label' => 'Meeting Activity', 'url' => ['meeting-activity3/index'],'path'=>[
					'/meeting-activity3/',
				]],
				['icon'=>'fa fa-fw fa-stack-overflow', 'label' => 'Room Request', 'url' => ['room-request3/index'],'path'=>[
					'/room-request3/',
				]],
				['icon'=>'fa fa-fw fa-stack-overflow', 'label' => 'Room', 'url' => ['room3/index'],'path'=>[
					'/room3/',
				]],
			]],			
		];
	}
}
