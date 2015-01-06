<?php

namespace backend\modules\sekretariat\it;
use Yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\sekretariat\it\controllers';

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
			'sekretariat-it-si'
		])){
			$menus_1 = \mdm\admin\components\MenuHelper::getAssignedMenu(\Yii::$app->user->id,143,$callback,true);
			if(empty($menus_1)){
				$menus_1 = [
					['icon'=>'fa fa-fw fa-stack-overflow','label' => 'Meeting Activity', 'url' => ['activity-meeting-it/index'],'path'=>[
						'/activity-meeting-it/',
					]],
				];
			}
			$menus_1 = ['icon'=>'fa fa-fw fa-file','label' => 'Sistem Informasi', 'url' => ['#'], 'items' => $menus_1 ];
		}
		
		if($this->checkAccess([
			'sekretariat-it-duktek'
		])){
			$menus_2 = \mdm\admin\components\MenuHelper::getAssignedMenu(\Yii::$app->user->id,145,$callback,true);
			if(empty($menus_2)){
				$menus_2 = [
					['icon'=>'fa fa-fw fa-stack-overflow','label' => 'Meeting Activity', 'url' => ['activity-meeting-duktek/index'],'path'=>[
						'/activity-meeting-duktek/',
					]],
				];
			}
			$menus_2 = ['icon'=>'fa fa-fw fa-anchor','label' => 'Dukungan Teknis', 'url' => ['#'], 'items' => $menus_2 ];
		}
		
		if($this->checkAccess([
			'sekretariat-it-komlik'
		])){
			$menus_3 = \mdm\admin\components\MenuHelper::getAssignedMenu(\Yii::$app->user->id,147,$callback,true);
			if(empty($menus_3)){
				$menus_3 = [
					['icon'=>'fa fa-fw fa-stack-overflow','label' => 'Meeting Activity', 'url' => ['activity-meeting-komlik/index'],'path'=>[
						'/activity-meeting-komlik/',
					]],
				];
			}
			$menus_3 = ['icon'=>'fa fa-fw fa-bullhorn','label' => 'Komunikasi Publik', 'url' => ['#'], 'items' => $menus_3 ];
		}
		
		$menus[] = ['icon'=>'fa fa-fw fa-dashboard','label' => 'Dashboard', 'url' => ['/'.$this->uniqueId.'/default']];

		if(!empty($menus_1)) $menus[] = $menus_1;	
		if(!empty($menus_2)) $menus[] = $menus_2;	
		if(!empty($menus_3)) $menus[] = $menus_3;	
		return $menus;
	}
}