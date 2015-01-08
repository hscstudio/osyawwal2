<?php

namespace backend\modules\sekretariat\hrd;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\sekretariat\hrd\controllers';

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
		$menus_4=[];
		
		if($this->checkAccess([
			'sekretariat-hrd-general'
		])){
			$menus_1 = \mdm\admin\components\MenuHelper::getAssignedMenu(\Yii::$app->user->id,149,$callback,true);
			if(empty($menus_1)){
				$menus_1 = [
					['icon'=>'fa fa-fw fa-child','label' => 'Manage Person', 'url' => ['person/index'],'path'=>[
					'/person/']],
					['icon'=>'fa fa-fw fa-user', 'label' => 'Manage Employee', 'url' => ['employee/index'],'path'=>[
							'/employee/']],
					['icon'=>'fa fa-fw fa-users','label' => 'Manage User', 'url' => ['user/index'],'path'=>[
						'/user/']],
					['icon'=>'fa fa-fw fa-suitcase','label' => 'Rapat', 'url' => ['/'.$this->uniqueId.'/activity-meeting-hrd/index'],'path'=>[
						'/activity-meeting-hrd/']],
				];
			}
			$menus_1 = ['icon'=>'fa fa-fw fa-file','label' => 'Umum Kepegawaian', 'url' => ['#'], 'items' => $menus_1 ];
		}
		
		if($this->checkAccess([
			'sekretariat-hrd-development'
		])){
			$menus_2 = \mdm\admin\components\MenuHelper::getAssignedMenu(\Yii::$app->user->id,150,$callback,true);
			if(empty($menus_2)){
				$menus_2 = [
					['icon'=>'fa fa-fw fa-stack-overflow','label' => 'Meeting Activity', 'url' => ['activity-meeting-hrd2/index'],'path'=>[
						'/activity-meeting-hrd2/',
					]],
				];
			}
			$menus_2 = ['icon'=>'fa fa-fw fa-anchor','label' => 'Pengembangan Pegawai', 'url' => ['#'], 'items' => $menus_2 ];
		}
		
		if($this->checkAccess([
			'sekretariat-hrd-jafung'
		])){
			$menus_3 = \mdm\admin\components\MenuHelper::getAssignedMenu(\Yii::$app->user->id,151,$callback,true);
			if(empty($menus_3)){
				$menus_3 = [
					['icon'=>'fa fa-fw fa-stack-overflow','label' => 'Meeting Activity', 'url' => ['activity-meeting-hrd3/index'],'path'=>[
						'/activity-meeting-hrd3/',
					]],
				];
			}
			$menus_3 = ['icon'=>'fa fa-fw fa-bullhorn','label' => 'Jabatan Fungsional', 'url' => ['#'], 'items' => $menus_3 ];
		}
		
		if($this->checkAccess([
			'sekretariat-hrd-ki'
		])){
			$menus_4 = \mdm\admin\components\MenuHelper::getAssignedMenu(\Yii::$app->user->id,152,$callback,true);
			if(empty($menus_4)){
				$menus_4 = [
					['icon'=>'fa fa-fw fa-stack-overflow','label' => 'Meeting Activity', 'url' => ['activity-meeting-hrd4/index'],'path'=>[
						'/activity-meeting-hrd4/',
					]],
				];
			}
			$menus_4 = ['icon'=>'fa fa-fw fa-bullhorn','label' => 'Kepatuhan Internal', 'url' => ['#'], 'items' => $menus_4 ];
		}
		
		$menus[] = ['icon'=>'fa fa-fw fa-dashboard','label' => 'Dashboard', 'url' => ['/'.$this->uniqueId.'/default']];

		if(!empty($menus_1)) $menus[] = $menus_1;	
		if(!empty($menus_2)) $menus[] = $menus_2;	
		if(!empty($menus_3)) $menus[] = $menus_3;	
		if(!empty($menus_4)) $menus[] = $menus_4;	
		return $menus;
	}
}