<?php

namespace backend\modules\pusdiklat2\general;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\pusdiklat2\general\controllers';

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
		
		if($this->checkAccess([
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
		}
		
		if($this->checkAccess([
			'pusdiklat2-general-1'
		])){
			$menus_1 = \mdm\admin\components\MenuHelper::getAssignedMenu(\Yii::$app->user->id,71,$callback,true);
			if(empty($menus_1)){
				$menus_1 = [
					['icon'=>'fa fa-fw fa-book', 'label' => 'Training Activity', 'url' => ['activity/index'],'path'=>[
						'/activity/',
					]],
					['icon'=>'fa fa-fw fa-suitcase', 'label' => 'Meeting Activity', 'url' => ['meeting-activity/index'],'path'=>[
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
				];
			}
			$menus_1 = ['icon'=>'fa fa-user-md fa-fw','label' => 'Pegawai TU Humas', 'url' => ['#'], 'items' => $menus_1 ];
		}
		
		if($this->checkAccess([
			'pusdiklat2-general-2'
		])){
			$menus_2 = \mdm\admin\components\MenuHelper::getAssignedMenu(\Yii::$app->user->id,72,$callback,true);
			if(empty($menus_2)){
				$menus_2 = [
					['icon'=>'fa fa-fw fa-book', 'label' => 'Training Activity', 'url' => ['activity2/index'],'path'=>[
						'/activity2/',
					]],
					['icon'=>'fa fa-fw fa-briefcase', 'label' => 'Meeting Activity', 'url' => ['meeting-activity2/index'],'path'=>[
						'/meeting-activity2/',
					]],
				];
			}
			$menus_2 = ['icon'=>'fa fa-money fa-fw','label' => 'Keuangan', 'url' => ['#'], 'items' => $menus_2 ];
		}
		
		if($this->checkAccess([
			'pusdiklat2-general-3'
		])){
			$menus_3 = \mdm\admin\components\MenuHelper::getAssignedMenu(\Yii::$app->user->id,73,$callback,true);
			if(empty($menus_3)){
				$menus_3 = [
					['icon'=>'fa fa-fw fa-book', 'label' => 'Training Activity', 'url' => ['activity3/index'],'path'=>[
						'/activity3/',
					]],
					['icon'=>'fa fa-fw fa-briefcase', 'label' => 'Meeting Activity', 'url' => ['meeting-activity3/index'],'path'=>[
						'/meeting-activity3/',
					]],
					['icon'=>'fa fa-fw fa-ticket', 'label' => 'Room Request', 'url' => ['room-request3/index'],'path'=>[
						'/room-request3/',
					]],
					['icon'=>'fa fa-fw fa-inbox', 'label' => 'Room', 'url' => ['room3/index'],'path'=>[
						'/room3/',
					]],
				];
			}
			$menus_3 = ['icon'=>'fa fa-archive fa-fw','label' => 'Pengelolaan Aset', 'url' => ['#'], 'items' => $menus_3 ];
		}
		
		$menus[] = ['icon'=>'fa fa-fw fa-dashboard','label' => 'Dashboard', 'url' => ['/'.$this->uniqueId.'/default']];
		if(!empty($menus_0)) $menus[] = $menus_0;	
		if(!empty($menus_1)) $menus[] = $menus_1;	
		if(!empty($menus_2)) $menus[] = $menus_2;	
		if(!empty($menus_3)) $menus[] = $menus_3;	
		return $menus;
	}
}
