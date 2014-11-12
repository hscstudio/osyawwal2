<?php

namespace backend\modules\bdk\execution;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\bdk\execution\controllers';

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
		
		if($this->checkAccess([
			'pusdiklat-execution-1'
		])){
			$menus_1 = \mdm\admin\components\MenuHelper::getAssignedMenu(\Yii::$app->user->id,41,$callback,true);
			if(empty($menus_1)){
				$menus_1 = [
					['icon'=>'fa fa-fw fa-stack-overflow', 'label' => 'Training Activity', 'url' => ['activity/index'],'path'=>[
						'/activity/',
					]],
					['icon'=>'fa fa-fw fa-stack-overflow', 'label' => 'Meeting Activity', 'url' => ['meeting-activity/index'],'path'=>[
						'/meeting-activity/',
					]],
					['icon'=>'fa fa-fw fa-user-md', 'label' => 'Student', 'url' => ['student/index'],'path'=>[
						'/student/',
					]],
				];
			}
			$menus_1 = ['icon'=>'fa fa-glass fa-fw','label' => 'Penyelenggaraan I', 'url' => ['#'], 'items' => $menus_1 ];
		}
		
		if($this->checkAccess([
			'pusdiklat-execution-2'
		])){
			$menus_2 = \mdm\admin\components\MenuHelper::getAssignedMenu(\Yii::$app->user->id,42,$callback,true);
			if(empty($menus_2)){
				$menus_2 = [
					['icon'=>'fa fa-fw fa-stack-overflow', 'label' => 'Training Activity', 'url' => ['activity2/index'],'path'=>[
						'/activity2/',
						'/training-class-student-attendance/',
						'/training-schedule-trainer-attendance/'
					]],
					['icon'=>'fa fa-fw fa-stack-overflow', 'label' => 'Meeting Activity', 'url' => ['meeting-activity2/index'],'path'=>[
						'/meeting-activity2/',
					]],
					['icon'=>'fa fa-fw fa-user-md', 'label' => 'Student', 'url' => ['student2/index'],'path'=>[
						'/student2/',
					]],
				];
			}
			$menus_2 = ['icon'=>'fa fa-coffee fa-fw','label' => 'Penyelenggaraan II', 'url' => ['#'], 'items' => $menus_2 ];
		}
		
		$menus[] = ['icon'=>'fa fa-fw fa-dashboard','label' => 'Dashboard', 'url' => ['/'.$this->uniqueId.'/default']];

		if(!empty($menus_1)) $menus[] = $menus_1;	
		if(!empty($menus_2)) $menus[] = $menus_2;		
		return $menus;
	}
}
