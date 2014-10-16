<?php

namespace backend\modules\pusdiklat\general\controllers;

use Yii;
use backend\models\Activity;
use backend\models\Training;
use backend\models\TrainingStudentPlan;
use backend\models\Program;
use backend\models\ProgramHistory;
use backend\models\Reference;
use backend\models\ObjectPerson;
use backend\modules\pusdiklat\general\models\TrainingActivitySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use hscstudio\heart\helpers\Heart;
use yii\helpers\ArrayHelper;
/**
 * ActivityController implements the CRUD actions for Activity model.
 */
class ActivityController extends Controller
{
    public $layout = '@hscstudio/heart/views/layouts/column2';
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Activity models.
     * @return mixed
     */
    public function actionIndex($year='',$status='nocancel')
    {
		if(empty($year)) $year=date('Y');
		$searchModel = new TrainingActivitySearch();
		$queryParams = Yii::$app->request->getQueryParams();
		if($status=='nocancel'){
			if($year=='all'){
				$queryParams['TrainingActivitySearch']=[
					'status'=> [0,1,2],
				];
			}
			else{
				$queryParams['TrainingActivitySearch']=[
					'year' => $year,
					'status'=> [0,1,2],
				];
			}
		}
		else if($status=='all'){
			if($year=='all'){
				$queryParams['TrainingActivitySearch']=[
				];
			}
			else{
				$queryParams['TrainingActivitySearch']=[
					'year' => $year,
				];
			}
		}
		else{
			if($year=='all'){
				$queryParams['TrainingActivitySearch']=[
					'status' => $status,
				];
			}
			else{
				$queryParams['TrainingActivitySearch']=[
					'year' => $year,
					'status' => $status,
				];
			}
		}
		$queryParams=yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
		$dataProvider = $searchModel->search($queryParams);
		$dataProvider->getSort()->defaultOrder = ['start'=>SORT_ASC,'end'=>SORT_ASC];
		
		// GET ALL TRAINING YEAR
		$year_training = yii\helpers\ArrayHelper::map(Activity::find()
			->select(['year'=>'YEAR(start)','start','end'])
			->orderBy(['year'=>'DESC'])
			->groupBy(['year'])
			->currentSatker()
			->asArray()
			->all(), 'year', 'year');
		$year_training['all']='All'	;
		
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'year' => $year,
			'status' => $status,
			'year_training' => $year_training,
        ]);
    }

    /**
     * Displays a single Activity model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    

   
    /**
     * Finds the Activity model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Activity the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Activity::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	public function actionProgramName($id){
		$model = Program::find()->where([
			'id'=>$id
		])
		->currentSatker()
		->active()
		->one();
		return $model->name;
	}
	
	/**
     * Lists all Activity models.
     * @return mixed
     */
    public function actionIndexStudentPlan($year='',$status='nocancel')
    {
        if(empty($year)) $year=date('Y');
		$searchModel = new TrainingActivitySearch();
		$queryParams = Yii::$app->request->getQueryParams();
		if($status=='nocancel'){
			if($year=='all'){
				$queryParams['TrainingActivitySearch']=[
					'status'=> [0,1,2],
				];
			}
			else{
				$queryParams['TrainingActivitySearch']=[
					'year' => $year,
					'status'=> [0,1,2],
				];
			}
		}
		else if($status=='all'){
			if($year=='all'){
				$queryParams['TrainingActivitySearch']=[
				];
			}
			else{
				$queryParams['TrainingActivitySearch']=[
					'year' => $year,
				];
			}
		}
		else{
			if($year=='all'){
				$queryParams['TrainingActivitySearch']=[
					'status' => $status,
				];
			}
			else{
				$queryParams['TrainingActivitySearch']=[
					'year' => $year,
					'status' => $status,
				];
			}
		}
		$queryParams=yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
		$dataProvider = $searchModel->search($queryParams);
		$dataProvider->getSort()->defaultOrder = ['start'=>SORT_ASC,'end'=>SORT_ASC];
		
		// GET ALL TRAINING YEAR
		$year_training = yii\helpers\ArrayHelper::map(Activity::find()
			->select(['year'=>'YEAR(start)','start','end'])
			->orderBy(['year'=>'DESC'])
			->groupBy(['year'])
			->currentSatker()
			->active()
			->asArray()
			->all(), 'year', 'year');
		$year_training['all']='All'	;

        return $this->render('indexStudentPlan', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'year' => $year,
			'status' => $status,
			'year_training' => $year_training,
        ]);
    }
	
	/**
     * Displays a single Activity model.
     * @param integer $id
     * @return mixed
     */
    public function actionViewStudentPlan($id)
    {
		$model = $this->findModel($id);
		$training = Training::findOne(['activity_id'=>$model->id]);
		$trainingStudentPlan = TrainingStudentPlan::findOne(['training_id'=>$model->id]);
		if(null==$trainingStudentPlan){
			$trainingStudentPlan = new TrainingStudentPlan(
				['training_id'=>$model->id]
			);
		}
		$renders=[];
		$renders['model'] = $model;
		$renders['training'] = $training;
		$renders['trainingStudentPlan'] = $trainingStudentPlan;
        if (Yii::$app->request->isAjax)
			return $this->renderAjax('viewStudentPlan', $renders);
		else
			return $this->render('viewStudentPlan', $renders);
    }
	
	/**
     * Updates an existing Program model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionPic($id)
    {
        $model = $this->findModel($id);
		$renders = [];
		$renders['model'] = $model;
		$object_people_array = [
			'organisation_1213010100'=>'PIC TRAINING ACTIVITY [SUBBAG TATA USAHA, PEGAWAI, HUMAS]'
		];
		$renders['object_people_array'] = $object_people_array;
		foreach($object_people_array as $object_person=>$label){
			$object_people[$object_person] = ObjectPerson::find()
				->where([
					'object'=>'activity',
					'object_id' => $id,
					'type' => $object_person, 
				])
				->one();
			if($object_people[$object_person]==null){
				$object_people[$object_person]= new ObjectPerson(
					[
						'object'=>'activity',
						'object_id' => $id,
						'type' => $object_person, 
					]
				);
			}
			$renders[$object_person] = $object_people[$object_person];
		}	
		
        if (Yii::$app->request->post()) {
			foreach($object_people_array as $object_person=>$label){
				$person_id = (int)Yii::$app->request->post('ObjectPerson')[$object_person]['person_id'];
				Heart::objectPerson($object_people[$object_person],$person_id,'activity',$id,$object_person);
			}	
			Yii::$app->getSession()->setFlash('success', 'Pic have updated.');
			if (!Yii::$app->request->isAjax) {
				return $this->redirect(['view', 'id' => $model->id]);	
			}
			else{
				echo 'Pic have updated.';
			}
        } else {
			if (Yii::$app->request->isAjax)
				return $this->renderAjax('pic', $renders);
            else
				return $this->render('pic', $renders);
        }
    }
}
