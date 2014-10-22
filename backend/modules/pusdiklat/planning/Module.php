<?php

namespace backend\modules\pusdiklat\planning;
use Yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\pusdiklat\planning\controllers';

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
		
		$menus_1=[];
		$menus_2=[];
		$menus_3=[];
		
		if($this->checkAccess([
			'pusdiklat-planning-1'
		])){
			$menus_1 = \mdm\admin\components\MenuHelper::getAssignedMenu(\Yii::$app->user->id,28,$callback,true);
			if(empty($menus_1)){
				$menus_1 = [
					['icon'=>'fa fa-fw fa-list','label' => 'Program', 'url' => ['program/index'],'path'=>[
						'/program/',
					]],
					['icon'=>'fa fa-fw fa-stack-overflow','label' => 'Training Activity', 'url' => ['activity/index'],'path'=>[
						'/activity/',
					]],
					['icon'=>'fa fa-fw fa-stack-overflow','label' => 'Meeting Activity', 'url' => ['meeting-activity/index'],'path'=>[
						'/meeting-activity/',
					]],
				];
			}
			$menus_1 = ['icon'=>'fa fa-code-fork fa-fw','label' => '+ Program', 'url' => ['#'], 'items' => $menus_1 ];
		}
		
		if($this->checkAccess([
			'pusdiklat-planning-2'
		])){
			$menus_2 = \mdm\admin\components\MenuHelper::getAssignedMenu(\Yii::$app->user->id,29,$callback,true);
			if(empty($menus_2)){
				$menus_2 = [
					['icon'=>'fa fa-fw fa-list','label' => 'Program', 'url' => ['program2/index'],'path'=>[
						'/program2/',
					]],
					['icon'=>'fa fa-fw fa-stack-overflow','label' => 'Training Activity', 'url' => ['activity2/index'],'path'=>[
						'/activity2/',
					]],
					['icon'=>'fa fa-fw fa-stack-overflow','label' => 'Meeting Activity', 'url' => ['meeting-activity2/index'],'path'=>[
						'/meeting-activity2/',
					]],
				];
			}
			$menus_2 = ['icon'=>'fa fa-university fa-fw','label' => '+ Kurikulum', 'url' => ['#'], 'items' => $menus_2 ];
		}
		
		if($this->checkAccess([
			'pusdiklat-planning-3'
		])){
			$menus_3 = \mdm\admin\components\MenuHelper::getAssignedMenu(\Yii::$app->user->id,30,$callback,true);
			if(empty($menus_3)){
				$menus_3 = [
					['icon'=>'fa fa-fw fa-list','label' => 'Program', 'url' => ['program3/index'],'path'=>[
						'/program3/',
					]],
					['icon'=>'fa fa-fw fa-stack-overflow','label' => 'Training Activity', 'url' => ['activity3/index'],'path'=>[
						'/activity3/',
					]],
					['icon'=>'fa fa-fw fa-stack-overflow','label' => 'Meeting Activity', 'url' => ['meeting-activity3/index'],'path'=>[
						'/meeting-activity3/',
					]],
					['icon'=>'fa fa-fw fa-stack-exchange', 'label' => 'Trainer', 'url' => ['trainer3/index'],'path'=>[
						'/trainer3/',
					]],
				];
			}
			$menus_3 = ['icon'=>'fa fa-users fa-fw','label' => '+ Tenaga Pengajar', 'url' => ['#'], 'items' => $menus_3 ];
		}
		
		$menus[] = ['icon'=>'fa fa-fw fa-dashboard','label' => 'Dashboard', 'url' => ['/'.$this->uniqueId.'/default']];

		if(!empty($menus_1)) $menus[] = $menus_1;	
		if(!empty($menus_2)) $menus[] = $menus_2;	
		if(!empty($menus_3)) $menus[] = $menus_3;	
		return $menus;
	}
}
