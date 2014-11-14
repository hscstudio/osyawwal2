<?php

namespace backend\modules\pusdiklat2\competency;
use Yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\pusdiklat2\competency\controllers';

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
			'pusdiklat2-competency'
		])){
			$menus_1 = \mdm\admin\components\MenuHelper::getAssignedMenu(\Yii::$app->user->id,62,$callback,true);
			if(empty($menus_1)){
				$menus_1 = [
					['icon'=>'fa fa-fw fa-list','label' => 'Program', 'url' => ['planning/program/index'],'path'=>[
						'/planning/program/',
					]],
					['icon'=>'fa fa-fw fa-stack-overflow','label' => 'Training Activity', 'url' => ['planning/activity/index'],'path'=>[
						'/planning/activity/',
					]],
					['icon'=>'fa fa-fw fa-stack-overflow','label' => 'Meeting Activity', 'url' => ['planning/meeting-activity/index'],'path'=>[
						'/planning/meeting-activity/',
					]],
				];
			}
			$menus_1 = ['icon'=>'fa fa-qrcode fa-fw','label' => 'Program', 'url' => ['#'], 'items' => $menus_1 ];
		}
		
		if($this->checkAccess([
			'pusdiklat2-competency'
		])){
			$menus_2 = \mdm\admin\components\MenuHelper::getAssignedMenu(\Yii::$app->user->id,63,$callback,true);
			if(empty($menus_2)){
				$menus_2 = [
					['icon'=>'fa fa-fw fa-list','label' => 'Program', 'url' => ['planning/program2/index'],'path'=>[
						'/planning/program2/',
					]],
					['icon'=>'fa fa-fw fa-stack-overflow','label' => 'Training Activity', 'url' => ['planning/activity2/index'],'path'=>[
						'/planning/activity2/',
					]],
					/*['icon'=>'fa fa-fw fa-stack-overflow','label' => 'Meeting Activity', 'url' => ['planning/meeting-activity2/index'],'path'=>[
						'/planning/meeting-activity2/',
					]],*/
				];
			}
			$menus_2 = ['icon'=>'fa fa-list fa-fw','label' => 'Kurikulum', 'url' => ['#'], 'items' => $menus_2 ];
		}
		
		if($this->checkAccess([
			'pusdiklat2-competency'
		])){
			$menus_3 = \mdm\admin\components\MenuHelper::getAssignedMenu(\Yii::$app->user->id,64,$callback,true);
			if(empty($menus_3)){
				$menus_3 = [
					['icon'=>'fa fa-fw fa-list','label' => 'Program', 'url' => ['planning/program3/index'],'path'=>[
						'/planning/program3/',
					]],
					['icon'=>'fa fa-fw fa-stack-overflow','label' => 'Training Activity', 'url' => ['planning/activity3/index'],'path'=>[
						'/planning/activity3/',
					]],
					/*['icon'=>'fa fa-fw fa-stack-overflow','label' => 'Meeting Activity', 'url' => ['planning/meeting-activity3/index'],'path'=>[
						'/planning/meeting-activity3/',
					]],*/
					['icon'=>'fa fa-fw fa-stack-exchange', 'label' => 'Trainer', 'url' => ['planning/trainer3/index'],'path'=>[
						'/planning/trainer3/',
					]],
				];
			}
			$menus_3 = ['icon'=>'fa fa-users fa-fw','label' => 'Tenaga Pengajar', 'url' => ['#'], 'items' => $menus_3 ];
		}
		if($this->checkAccess([
			'pusdiklat2-competency'
		])){
			$menus_4 = \mdm\admin\components\MenuHelper::getAssignedMenu(\Yii::$app->user->id,65,$callback,true);
			if(empty($menus_4)){
				$menus_4 = [
					['icon'=>'fa fa-fw fa-stack-overflow', 'label' => 'Training Activity', 'url' => ['execution/activity/index'],'path'=>[
						'/execution/activity/',
					]],
					['icon'=>'fa fa-fw fa-stack-overflow', 'label' => 'Meeting Activity', 'url' => ['execution/meeting-activity/index'],'path'=>[
						'/execution/meeting-activity/',
					]],
					['icon'=>'fa fa-fw fa-user-md', 'label' => 'Student', 'url' => ['execution/student/index'],'path'=>[
						'/execution/student/',
					]],
				];
			}
			$menus_4 = ['icon'=>'fa fa-glass fa-fw','label' => 'Penyelenggaraan I', 'url' => ['#'], 'items' => $menus_4 ];
		}
		
		if($this->checkAccess([
			'pusdiklat2-competency'
		])){
			$menus_5 = \mdm\admin\components\MenuHelper::getAssignedMenu(\Yii::$app->user->id,66,$callback,true);
			if(empty($menus_5)){
				$menus_5 = [
					['icon'=>'fa fa-fw fa-stack-overflow', 'label' => 'Training Activity', 'url' => ['execution/activity2/index'],'path'=>[
						'/execution/activity2/',
						'/execution/training-class-student-attendance/',
						'/execution/training-schedule-trainer-attendance/'
					]],
					/*['icon'=>'fa fa-fw fa-stack-overflow', 'label' => 'Meeting Activity', 'url' => ['execution/meeting-activity2/index'],'path'=>[
						'/execution/meeting-activity2/',
					]],*/
					['icon'=>'fa fa-fw fa-user-md', 'label' => 'Student', 'url' => ['execution/student2/index'],'path'=>[
						'/execution/student2/',
					]],
				];
			}
			$menus_5 = ['icon'=>'fa fa-coffee fa-fw','label' => 'Penyelenggaraan II', 'url' => ['#'], 'items' => $menus_5 ];
		}
		
		if($this->checkAccess([
			'pusdiklat2-competency'
		])){
			$menus_6 = \mdm\admin\components\MenuHelper::getAssignedMenu(\Yii::$app->user->id,67,$callback,true);
			if(empty($menus_6)){
				$menus_6 = [
					['icon'=>'fa fa-fw fa-book', 'label' => 'Training Activity', 'url' => ['evaluation/activity/index'],'path'=>[
						'/evaluation/activity/',
					]],
					['icon'=>'fa fa-fw fa-briefcase', 'label' => 'Meeting Activity', 'url' => ['evaluation/meeting-activity/index'],'path'=>[
						'/evaluation/meeting-activity/',
					]],
				];
			}
			$menus_6 = ['icon'=>'fa fa-check-square-o fa-fw','label' => 'Evaluasi Diklat', 'url' => ['#'], 'items' => $menus_6 ];
		}
		
		if($this->checkAccess([
			'pusdiklat2-competency'
		])){
			$menus_7 = \mdm\admin\components\MenuHelper::getAssignedMenu(\Yii::$app->user->id,68,$callback,true);
			if(empty($menus_7)){
				$menus_7 = [
					['icon'=>'fa fa-fw fa-book', 'label' => 'Training Activity', 'url' => ['evaluation/activity2/index'],'path'=>[
						'/evaluation/activity2/',
					]],
					/*['icon'=>'fa fa-fw fa-briefcase', 'label' => 'Meeting Activity', 'url' => ['evaluation/meeting-activity2/index'],'path'=>[
						'/evaluation/meeting-activity2/',
					]],*/
				];
			}
			$menus_7 = ['icon'=>'fa fa-cloud fa-fw','label' => 'PHD', 'url' => ['#'], 'items' => $menus_7 ];
		}
		
		if($this->checkAccess([
			'pusdiklat2-competency'
		])){
			$menus_8 = \mdm\admin\components\MenuHelper::getAssignedMenu(\Yii::$app->user->id,69,$callback,true);
			if(empty($menus_8)){
				$menus_8 = [
					['icon'=>'fa fa-fw fa-book', 'label' => 'Training Activity', 'url' => ['evaluation/activity3/index'],'path'=>[
						'/evaluation/activity3/',
					]],
					/*['icon'=>'fa fa-fw fa-briefcase', 'label' => 'Meeting Activity', 'url' => ['evaluation/meeting-activity3/index'],'path'=>[
						'/evaluation/meeting-activity3/',
					]],*/
				];
			}
			$menus_8 = ['icon'=>'fa fa-book fa-fw','label' => 'IPK', 'url' => ['#'], 'items' => $menus_8 ];
		}
		$menus[] = ['icon'=>'fa fa-fw fa-dashboard','label' => 'Dashboard', 'url' => ['/'.$this->uniqueId.'/default']];

		if(!empty($menus_1)) $menus[] = $menus_1;	
		if(!empty($menus_2)) $menus[] = $menus_2;	
		if(!empty($menus_3)) $menus[] = $menus_3;	
		if(!empty($menus_4)) $menus[] = $menus_4;
		if(!empty($menus_5)) $menus[] = $menus_5;
		if(!empty($menus_6)) $menus[] = $menus_6;	
		if(!empty($menus_7)) $menus[] = $menus_7;
		if(!empty($menus_8)) $menus[] = $menus_8;
		return $menus;
	}
}
