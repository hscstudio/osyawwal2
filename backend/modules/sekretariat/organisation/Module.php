<?php

namespace backend\modules\sekretariat\organisation;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\sekretariat\organisation\controllers';

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
			'sekretariat-organisation-organisasi'
		])){
			$menus_1 = \mdm\admin\components\MenuHelper::getAssignedMenu(\Yii::$app->user->id,175,$callback,true);
			if(empty($menus_1)){
				$menus_1 = [
					['icon'=>'fa fa-fw fa-money','label' => 'Sbu', 'url' => ['/'.$this->uniqueId.'/reference-sbu/index'],'path'=>[
					'/reference-sbu/',]],
					['icon'=>'fa fa-fw fa-suitcase','label' => 'Rapat', 'url' => ['/'.$this->uniqueId.'/activity-meeting-finance/index'],'path'=>[
					'/activity-meeting-finance/',]],
				];
			}
			$menus_1 = ['icon'=>'fa fa-fw fa-file','label' => 'Organisasi', 'url' => ['#'], 'items' => $menus_1 ];
		}
		
		if($this->checkAccess([
			'sekretariat-organisation-tatalaksana'
		])){
			$menus_2 = \mdm\admin\components\MenuHelper::getAssignedMenu(\Yii::$app->user->id,176,$callback,true);
			if(empty($menus_2)){
				$menus_2 = [
					['icon'=>'fa fa-fw fa-suitcase','label' => 'Rapat', 'url' => ['/'.$this->uniqueId.'/activity-meeting-finance2/index'],'path'=>[
				'/activity-meeting-finance/',]],
				];
			}
			$menus_2 = ['icon'=>'fa fa-fw fa-anchor','label' => 'Tatalaksana', 'url' => ['#'], 'items' => $menus_2 ];
		}
		
		if($this->checkAccess([
			'sekretariat-organisation-hukker'
		])){
			$menus_3 = \mdm\admin\components\MenuHelper::getAssignedMenu(\Yii::$app->user->id,177,$callback,true);
			if(empty($menus_3)){
				$menus_3 = [
					['icon'=>'fa fa-fw fa-suitcase','label' => 'Rapat', 'url' => ['/'.$this->uniqueId.'/activity-meeting-finance3/index'],'path'=>[
				'/activity-meeting-finance/',]],
				];
			}
			$menus_3 = ['icon'=>'fa fa-fw fa-bullhorn','label' => 'Hukker', 'url' => ['#'], 'items' => $menus_3 ];
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
			['icon'=>'fa fa-fw fa-list-alt','label' => 'Organisasi', 'url' => '#','items'=>[
				['icon'=>'fa fa-fw fa-suitcase','label' => 'Rapat', 'url' => ['/'.$this->uniqueId.'/activity-meeting-organisation2/index'],'path'=>[
				'/activity-meeting-organisation2/',]],
			]],
			['icon'=>'fa fa-fw fa-list-alt','label' => 'Tata Laksana', 'url' => '#','items'=>[
			['icon'=>'fa fa-fw fa-graduation-cap','label' => 'Graduate', 'url' => ['/'.$this->uniqueId.'/reference-graduate/index'],'path'=>'reference-graduate/'],
			['icon'=>'fa fa-fw fa-sliders','label' => 'Program Code', 'url' => ['/'.$this->uniqueId.'/reference-program-code/index'],'path'=>'reference-program-code/'],
			['icon'=>'fa fa-fw fa-empire', 'label' => 'Religion', 'url' => ['/'.$this->uniqueId.'/reference-religion/index'],'path'=>'reference-religion/'],
			['icon'=>'fa fa-fw fa-trophy', 'label' => 'RankClass', 'url' => ['/'.$this->uniqueId.'/reference-rank-class/index'],'path'=>'reference-rank-class/'],
			['icon'=>'fa fa-fw fa-institution', 'label' => 'Satker', 'url' => ['/'.$this->uniqueId.'/reference-satker/index'],'path'=>'reference-satker/'],
			['icon'=>'fa fa-fw fa-building', 'label' => 'Unit', 'url' => ['/'.$this->uniqueId.'/reference-unit/index'],'path'=>'reference-unit/'],	
			['icon'=>'fa fa-fw fa-tags', 'label' => 'Subject Type', 'url' => ['/'.$this->uniqueId.'/reference-subject-type/index'],'path'=>'reference-subject-type/'],
			['icon'=>'fa fa-fw fa-user-md', 'label' => 'Trainer Type', 'url' => ['/'.$this->uniqueId.'/reference-trainer-type/index'],'path'=>'reference-trainer-type/'],
			['icon'=>'fa fa-fw fa-suitcase','label' => 'Rapat', 'url' => ['/'.$this->uniqueId.'/activity-meeting-organisation/index'],'path'=>[
				'/activity-meeting-organisation/',
			]],
			
			 ]],
			
			['icon'=>'fa fa-fw fa-list-alt','label' => 'Hukker', 'url' => '#','items'=>[
				['icon'=>'fa fa-fw fa-suitcase','label' => 'Rapat', 'url' => ['/'.$this->uniqueId.'/activity-meeting-organisation3/index'],'path'=>[
				'/activity-meeting-organisation3/',]],
			]],
		];
	}*/
}
