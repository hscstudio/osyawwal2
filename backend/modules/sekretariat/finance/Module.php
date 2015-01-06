<?php

namespace backend\modules\sekretariat\finance;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\sekretariat\finance\controllers';

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
			'sekretariat-finance-anggaran'
		])){
			$menus_1 = \mdm\admin\components\MenuHelper::getAssignedMenu(\Yii::$app->user->id,168,$callback,true);
			if(empty($menus_1)){
				$menus_1 = [
					['icon'=>'fa fa-fw fa-money','label' => 'Sbu', 'url' => ['/'.$this->uniqueId.'/reference-sbu/index'],'path'=>[
					'/reference-sbu/',]],
					['icon'=>'fa fa-fw fa-suitcase','label' => 'Rapat', 'url' => ['/'.$this->uniqueId.'/activity-meeting-finance/index'],'path'=>[
					'/activity-meeting-finance/',]],
				];
			}
			$menus_1 = ['icon'=>'fa fa-fw fa-file','label' => 'Penyusunan Anggaran', 'url' => ['#'], 'items' => $menus_1 ];
		}
		
		if($this->checkAccess([
			'sekretariat-finance-perbendaharaan'
		])){
			$menus_2 = \mdm\admin\components\MenuHelper::getAssignedMenu(\Yii::$app->user->id,169,$callback,true);
			if(empty($menus_2)){
				$menus_2 = [
					['icon'=>'fa fa-fw fa-suitcase','label' => 'Rapat', 'url' => ['/'.$this->uniqueId.'/activity-meeting-finance2/index'],'path'=>[
				'/activity-meeting-finance/',]],
				];
			}
			$menus_2 = ['icon'=>'fa fa-fw fa-anchor','label' => 'Perbendaharaan', 'url' => ['#'], 'items' => $menus_2 ];
		}
		
		if($this->checkAccess([
			'sekretariat-finance-akuntansi'
		])){
			$menus_3 = \mdm\admin\components\MenuHelper::getAssignedMenu(\Yii::$app->user->id,170,$callback,true);
			if(empty($menus_3)){
				$menus_3 = [
					['icon'=>'fa fa-fw fa-suitcase','label' => 'Rapat', 'url' => ['/'.$this->uniqueId.'/activity-meeting-finance3/index'],'path'=>[
				'/activity-meeting-finance/',]],
				];
			}
			$menus_3 = ['icon'=>'fa fa-fw fa-bullhorn','label' => 'Akuntansi Pelaporan', 'url' => ['#'], 'items' => $menus_3 ];
		}
		
		$menus[] = ['icon'=>'fa fa-fw fa-dashboard','label' => 'Dashboard', 'url' => ['/'.$this->uniqueId.'/default']];

		if(!empty($menus_1)) $menus[] = $menus_1;	
		if(!empty($menus_2)) $menus[] = $menus_2;	
		if(!empty($menus_3)) $menus[] = $menus_3;	
		return $menus;
	}
}
