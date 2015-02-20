<?php

namespace frontend\modules\student\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\Activity;
use frontend\models\ActivitySearch;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use hscstudio\heart\helpers\Heart;
use yii\data\ArrayDataProvider;

class DefaultController extends Controller
{
	public $layout = '@hscstudio/heart/views/layouts/column2';
	
    public function actionIndex($year=NULL,$satker_id=NULL,$month=NULL)
    {
        $satker = ArrayHelper::map(\frontend\models\Reference::find()
							->select(['id','name'])
							->where(['type'=>'satker'])
							->asArray()->all(), 'id', 'name');
		
		if(empty($year)) $year=date('Y');
		$searchModel = new ActivitySearch();
		$queryParams = Yii::$app->request->getQueryParams();
		//die(var_dump($queryParams));
			
		
		$queryParams=yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
		$dataProvider = $searchModel->search($queryParams,$year,$satker_id);		
		$dataProvider->getSort()->defaultOrder = ['start'=>SORT_ASC,'end'=>SORT_ASC];
		
		$year_training = yii\helpers\ArrayHelper::map(Activity::find()
			->select(['year'=>'YEAR(start)','start','end'])
			->orderBy(['year'=>'DESC'])
			->groupBy(['year'])
			->asArray()
			->all(), 'year', 'year');
		$year_training['all']='All'	; 
		
		return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'year' => $year,
			'year_training' => $year_training,
			'satker' =>$satker,
			'satker_id' =>$satker_id,
        ]);
		//return $this->render('index');
    }	
	
}
