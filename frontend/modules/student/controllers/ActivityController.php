<?php

namespace frontend\modules\student\controllers;

use Yii;
use frontend\models\Activity;
use frontend\modules\student\models\ActivitySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

use backend\models\Program;
use frontend\models\ProgramSubject;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use backend\models\ObjectFile;
use frontend\models\TrainingClass;
use backend\modules\pusdiklat\execution\models\TrainingClassSearch;
use frontend\models\TrainingStudent;
use frontend\modules\student\models\TrainingStudentSearch;


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
    public function actionIndex($year=NULL,$satker_id=NULL,$status='nocancel')
    {
		$satker = ArrayHelper::map(\frontend\models\Reference::find()->select(['id','name'])->where(['type'=>'satker'])->asArray()->all(), 'id', 'name');
		//if(empty($year)) 
		$year=date('Y');
		$searchModel = new ActivitySearch();
		$queryParams = Yii::$app->request->getQueryParams();
		if($status=='nocancel'){
			if($year=='all'){
				if(!empty($satker_id))
				{
					$queryParams['ActivitySearch']=[
						'status'=> [0,1,2],
						'satker_id'=>$satker_id,
					];
				}
			}
			else{
				if(!empty($satker_id))
				{
					$queryParams['ActivitySearch']=[
						'year' => $year,
						'status'=> [0,1,2],
						'satker_id'=>$satker_id,
					];
				}
			}
		}
		/*if(!empty($satker_id))
		{$id_satker=$satker_id;}
		else
		{$id_satker=0;}*/
		
		$queryParams=yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
		$dataProvider = $searchModel->search($queryParams);
		$dataProvider->getSort()->defaultOrder = ['start'=>SORT_ASC,'end'=>SORT_ASC];
		
		// GET ALL TRAINING YEAR
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
			'status' => $status,
			'year_training' => $year_training,
			'satker' =>$satker,
			'satker_id' =>$satker_id,
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
     * Creates a new Activity model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Activity();

        if ($model->load(Yii::$app->request->post())){ 
			if($model->save()) {
				Yii::$app->getSession()->setFlash('success', 'New data have saved.');
			}
			else{
				Yii::$app->getSession()->setFlash('error', 'New data is not saved.');
			}
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Activity model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if($model->save()) {
				Yii::$app->getSession()->setFlash('success', 'Data have updated.');
			}
			else{
				Yii::$app->getSession()->setFlash('error', 'Data is not updated.');
			}
			return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Activity model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		if($this->findModel($id)->delete()) {
			Yii::$app->getSession()->setFlash('success', 'Data have deleted.');
		}
		else{
			Yii::$app->getSession()->setFlash('error', 'Data is not deleted.');
		}
        return $this->redirect(['index']);
    }
	
	public function actionDashboard($training_id,$training_student_id)
    {
		$id = base64_decode(\hscstudio\heart\helpers\Kalkun::HexToAscii($training_id));
		$model = $this->findModel($id);
		
        return $this->render('dashboard', [
            'model' => $model,
			'training_student_id' => $training_student_id,
        ]);
    }
	
	public function actionProperty($training_id,$training_student_id)
    {
        $id = base64_decode(\hscstudio\heart\helpers\Kalkun::HexToAscii($training_id));
		$model = $this->findModel($id);
		$program = Program::findOne($model->training->program_id);
		$subject = new ActiveDataProvider([
            'query' => ProgramSubject::find()
						->where([
							'program_id' => $model->training->program_id,
							'status'=>1,
						])
						->orderBy(['sort'=>SORT_ASC,]),
        ]);
		$document = new ActiveDataProvider([
            'query' => ObjectFile::find()
						->joinWith('file')
						->where([
							'object'=>'program',
							'object_id' => $model->training->program_id,
							'file.status'=>1,
							'type' => ['kap','gbpp','module'],
						])
						->orderBy(['type'=>SORT_ASC,]),
        ]);
		return $this->render('property', [
            'model' =>$model,
			'program' =>$program,
			'subject' =>$subject,
			'document' =>$document,
			'training_student_id' => $training_student_id,
        ]);
    }
	
	 /**
     * Lists all TrainingClass models.
     * @return mixed
     */
    public function actionClass($training_id,$training_student_id)
    {
        $id = base64_decode(\hscstudio\heart\helpers\Kalkun::HexToAscii($training_id));
		$model = $this->findModel($id);
		$searchModel = new TrainingClassSearch([
			'training_id' => $id,
		]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('class', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'model' => $model,
			'training_student_id' => $training_student_id,
        ]);
    }
	
	public function actionStudent($training_id,$training_student_id)
    {
        $id = base64_decode(\hscstudio\heart\helpers\Kalkun::HexToAscii($training_id));
		$model = $this->findModel($id);
		$searchModel = new TrainingStudentSearch([
			'training_id' => $id,
		]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('student', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'model' => $model,
			'training_student_id' => $training_student_id,
        ]);
    }
	
	public function actionTrainingExecutionEvaluation($training_id,$training_student_id)
    {
        $id = base64_decode(\hscstudio\heart\helpers\Kalkun::HexToAscii($training_id));
		$model = $this->findModel($id);
		$searchModel = new TrainingStudentSearch([
			'training_id' => $id,
		]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('trainingEvaluation', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'model' => $model,
			'training_student_id' => $training_student_id,
        ]);
    }
	
	public function actionTrainingClassSubjectTrainerEvaluation($training_id)
    {
        $id = base64_decode(\hscstudio\heart\helpers\Kalkun::HexToAscii($training_id));
		$model = $this->findModel($id);
		$searchModel = new TrainingStudentSearch([
			'training_id' => $id,
		]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('student', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'model' => $model,
        ]);
    }
	
	public function actionClassStudent($id, $class_id)
    {
        $activity = $this->findModel($id); // Activity
		$class = $this->findModelClass($class_id); // Class		
			
		$searchModel = new TrainingClassStudentSearch();
		$queryParams['TrainingClassStudentSearch']=[				
			'training_class_id' =>$class_id,
			'training_class_student.status'=>1,
		];
		$queryParams=yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
		$dataProvider = $searchModel->search($queryParams); 
		/* $dataProvider->getSort()->defaultOrder = ['name'=>SORT_ASC]; */
		
		$subquery = TrainingClassStudent::find()
			->select('training_student_id')
			->where(['training_id' => $id]);
		 
		// fetch orders that are placed by customers who are older than 30  
		$trainingStudentCount = TrainingStudent::find()
			->where(['status'=>1])
			->andWhere([
				'not in', 'id', $subquery
			])
			->count();
		
		if (Yii::$app->request->post()){ 
			$student = Yii::$app->request->post()['student'];
			$baseon = 0;
			if(isset(Yii::$app->request->post()['baseon'])) $baseon = Yii::$app->request->post()['baseon'];
			if($student>$trainingStudentCount){
				Yii::$app->session->setFlash('error', '<i class="fa fa-fw fa-times-circle"></i>Your request more than stock!');
			}
			else if($baseon==0 or count($baseon)==0){
				Yii::$app->session->setFlash('error', '<i class="fa fa-fw fa-times-circle"></i>Select base on random!');
			}
			else{				
				$baseon = implode(',',$baseon);
				// select name, gender from person group by name, gender order by rand();
				$trainingStudents = TrainingStudent::find()
					->joinWith('student')
					->joinWith('student.person')
					->joinWith('student.person.unit')
					->where(['training_student.status'=>'1'])
					->andWhere([
						'not in', 'training_student.id', $subquery
					])
					->groupBy($baseon)
					->orderBy('rand()')
					->limit($student)
					->asArray()
					->all();
				foreach ($trainingStudents as $trainingStudent){
					$trainingClassStudent = new TrainingClassStudent([
						'training_id'=>$id,
						'training_class_id'=>$class_id,
						'training_student_id'=>$trainingStudent['id'],
						'status'=>1
					]);
					$trainingClassStudent->save();
				}
				Yii::$app->session->setFlash('success', $student.' student added!');
				
				$subquery = TrainingClassStudent::find()
					->select('training_student_id')
					->where(['training_id' => $id]);
				 
				// fetch orders that are placed by customers who are older than 30  
				$trainingStudentCount = TrainingStudent::find()
					->where(['status'=>'1'])
					->andWhere([
						'not in', 'id', $subquery
					])
					->count();
			}
		}
		
        return $this->render('classStudent', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'activity' => $activity, 
			'class' => $class, 
			'trainingStudentCount' => $trainingStudentCount
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
	
	public function actionTrainingStatus($id)
    {
        $model = TrainingStudent::findOne(['status'=>$id]);
		$renders = [];
		$renders['model'] = $model;
		$object_people_array = [
			// CEK ID 1213010300 IN TABLE ORGANISATION
			'organisation_1201050000'=>'PIC Meeting'
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
