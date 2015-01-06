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
	
	public function checkAccess($roles=[]){
		foreach ($roles as $role){
			if(\Yii::$app->user->can($role)){
				return true;
			}
		}					
	}
	
	public function getMenuItems(){
		$callback = function($menuX){
			$data = eval($menuX['data']);
			return [
				'label' => $menuX['name'], 
				'url' => [$menuX['route']],
				'icon'=> isset($data['icon'])?$data['icon']:'fa fa-link fa-fw',
				'path'=> isset($data['path'])?$data['path']:'',
				'items' => $menuX['children'],
			];
		};
		
		$menus_1=[];
		$menus_2=[];
		$menus_3=[];
		
		if($this->checkAccess([
			'sekretariat-general-tu'
		])){
			$menus_1 = \mdm\admin\components\MenuHelper::getAssignedMenu(\Yii::$app->user->id,160,$callback,true);
			if(empty($menus_1)){
				$menus_1 = [
					['icon'=>'fa fa-fw fa-stack-overflow','label' => 'Meeting Activity', 'url' => ['activity-meeting-general2/index'],'path'=>[
						'/activity-meeting-general2/',
					]],
				];
			}
			$menus_1 = ['icon'=>'fa fa-fw fa-file','label' => 'Tata Usaha', 'url' => ['#'], 'items' => $menus_1 ];
		}
		
		if($this->checkAccess([
			'sekretariat-general-asset'
		])){
			$menus_2 = \mdm\admin\components\MenuHelper::getAssignedMenu(\Yii::$app->user->id,161,$callback,true);
			if(empty($menus_2)){
				$menus_2 = [
					['icon'=>'fa fa-fw fa-stack-overflow','label' => 'Meeting Activity', 'url' => ['activity-meeting-general3/index'],'path'=>[
						'/activity-meeting-general3/',
					]],
				];
			}
			$menus_2 = ['icon'=>'fa fa-fw fa-anchor','label' => 'Pengelolaan Asset', 'url' => ['#'], 'items' => $menus_2 ];
		}
		
		if($this->checkAccess([
			'sekretariat-general-rumah-tangga'
		])){
			$menus_3 = \mdm\admin\components\MenuHelper::getAssignedMenu(\Yii::$app->user->id,162,$callback,true);
			if(empty($menus_3)){
				$menus_3 = [
					['icon'=>'fa fa-fw fa-stack-overflow','label' => 'Meeting Activity', 'url' => ['activity-meeting-general/index'],'path'=>[
						'/activity-meeting-general/',
					]],
					['icon'=>'fa fa-fw fa-inbox','label' => 'Room', 'url' => ['/'.$this->uniqueId.'/room/index'],'path'=>[
						'/room/',]],
					['icon'=>'fa fa-fw fa-ticket','label' => 'Room Request', 'url' => ['/'.$this->uniqueId.'/activity-room/index'],'path'=>[
						'/activity-room/',]],	
				];
			}
			$menus_3 = ['icon'=>'fa fa-fw fa-bullhorn','label' => 'Rumah Tangga', 'url' => ['#'], 'items' => $menus_3 ];
		}
		
		$menus[] = ['icon'=>'fa fa-fw fa-dashboard','label' => 'Dashboard', 'url' => ['/'.$this->uniqueId.'/default']];

		if(!empty($menus_1)) $menus[] = $menus_1;	
		if(!empty($menus_2)) $menus[] = $menus_2;	
		if(!empty($menus_3)) $menus[] = $menus_3;	
		return $menus;
	}
	/*public function getMenuItems(){
		return [
			['icon'=>'fa fa-fw fa-dashboard','label' => 'Dashboard', 'url' => ['/'.$this->uniqueId.'/default']],
			['icon'=>'fa fa-fw fa-list-alt','label' => 'Tata Usaha', 'url' => '#','items'=>[																			
				['icon'=>'fa fa-fw fa-suitcase','label' => 'Rapat', 'url' => ['/'.$this->uniqueId.'/activity-meeting-general2/index'],'path'=>[
					'/activity-meeting-general2/',]],
			]],
			['icon'=>'fa fa-fw fa-list-alt','label' => 'Pengelolaan Aset', 'url' => '#','items'=>[
			['icon'=>'fa fa-fw fa-suitcase','label' => 'Rapat', 'url' => ['/'.$this->uniqueId.'/activity-meeting-general3/index'],'path'=>[
				'/activity-meeting-general3/',]],
			]],
			['icon'=>'fa fa-fw fa-list-alt','label' => 'Rumah Tangga', 'url' => '#','items'=>[
				['icon'=>'fa fa-fw fa-inbox','label' => 'Room', 'url' => ['/'.$this->uniqueId.'/room/index'],'path'=>[
						'/room/',]],
					['icon'=>'fa fa-fw fa-ticket','label' => 'Room Request', 'url' => ['/'.$this->uniqueId.'/activity-room/index'],'path'=>[
						'/activity-room/',]],																					  
				['icon'=>'fa fa-fw fa-suitcase','label' => 'Rapat', 'url' => ['/'.$this->uniqueId.'/activity-meeting-general/index'],'path'=>[
					'/activity-meeting-general/',]],
			]],			
		];
	}*/
}
