<?php

namespace backend\modules\pusdiklat2\test;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\pusdiklat2\test\controllers';

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
		
		//$menus_0=[];
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
			'pusdiklat2-test-planning'
		])){
			$menus_1 = \mdm\admin\components\MenuHelper::getAssignedMenu(\Yii::$app->user->id,137,$callback,true);
			if(empty($menus_1)){
				$menus_1 = [
					['icon'=>'fa fa-fw fa-suitcase', 'label' => 'Meeting Activity', 'url' => ['meeting-activity/index'],'path'=>[
						'/meeting-activity/',
					]],
				];
			}
			$menus_1 = ['icon'=>'fa fa-user-md fa-fw','label' => 'Perencanaan Test', 'url' => ['#'], 'items' => $menus_1 ];
		}
		
		if($this->checkAccess([
			'pusdiklat2-test-execution'
		])){
			$menus_2 = \mdm\admin\components\MenuHelper::getAssignedMenu(\Yii::$app->user->id,138,$callback,true);
			if(empty($menus_2)){
				$menus_2 = [
					['icon'=>'fa fa-fw fa-suitcase', 'label' => 'Meeting Activity', 'url' => ['meeting-activity2/index'],'path'=>[
						'/meeting-activity2/',
					]],
				];
			}
			$menus_2 = ['icon'=>'fa fa-user-md fa-fw','label' => 'Penyelenggaraan Test', 'url' => ['#'], 'items' => $menus_2 ];
		}
		
		if($this->checkAccess([
			'pusdiklat2-test-evaluation'
		])){
			$menus_3 = \mdm\admin\components\MenuHelper::getAssignedMenu(\Yii::$app->user->id,139,$callback,true);
			if(empty($menus_3)){
				$menus_3 = [
					['icon'=>'fa fa-fw fa-suitcase', 'label' => 'Meeting Activity', 'url' => ['meeting-activity3/index'],'path'=>[
						'/meeting-activity3/',
					]],
				];
			}
			$menus_3 = ['icon'=>'fa fa-user-md fa-fw','label' => 'Evaluasi Test', 'url' => ['#'], 'items' => $menus_3 ];
		}
		
		$menus[] = ['icon'=>'fa fa-fw fa-dashboard','label' => 'Dashboard', 'url' => ['/'.$this->uniqueId.'/default']];
		//if(!empty($menus_0)) $menus[] = $menus_0;	
		if(!empty($menus_1)) $menus[] = $menus_1;	
		if(!empty($menus_2)) $menus[] = $menus_2;	
		if(!empty($menus_3)) $menus[] = $menus_3;	
		return $menus;
	}
}
