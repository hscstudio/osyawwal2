<?php

namespace backend\modules\pusdiklat2\scholarship;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\pusdiklat2\scholarship\controllers';

    public function init()
    {
        parent::init();
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
		
		$menus_0=[];
		$menus_1=[];
		$menus_2=[];
		$menus_3=[];
		
		/*if($this->checkAccess([
			'admin-pusdiklat2'
		])){
			$menus_0 = \mdm\admin\components\MenuHelper::getAssignedMenu(\Yii::$app->user->id,70,$callback,true);
			if(empty($menus_0)){
				$menus_0 = [
					['icon'=>'fa fa-fw fa-plug', 'label' => 'Assignment', 'url' => ['assignment0/index'],'path'=>[
						'/assignment0/',
					]],
				];
			}
			$menus_0 = ['icon'=>'fa fa-android fa-fw','label' => 'Administrator', 'url' => ['#'], 'items' => $menus_0 ];
		}*/
		
		if($this->checkAccess([
			'pusdiklat2-scholarship'
		])){
			$menus_1 = \mdm\admin\components\MenuHelper::getAssignedMenu(\Yii::$app->user->id,71,$callback,true);
			if(empty($menus_1)){
				$menus_1 = [
					['icon'=>'fa fa-fw fa-suitcase', 'label' => 'Meeting Activity', 'url' => ['meeting-activity/index'],'path'=>[
						'/meeting-activity/',
					]],
				];
			}
			$menus_1 = ['icon'=>'fa fa-user-md fa-fw','label' => 'Scholarship', 'url' => ['#'], 'items' => $menus_1 ];
		}
		
		$menus[] = ['icon'=>'fa fa-fw fa-dashboard','label' => 'Dashboard', 'url' => ['/'.$this->uniqueId.'/default']];
		//if(!empty($menus_0)) $menus[] = $menus_0;	
		if(!empty($menus_1)) $menus[] = $menus_1;		
		return $menus;
	}
}
