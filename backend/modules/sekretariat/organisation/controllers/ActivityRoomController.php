<?php

namespace backend\modules\sekretariat\organisation\controllers;

use Yii;
use backend\models\Activity;
use backend\models\ActivityHistory;
use backend\models\ActivityRoom;
use backend\models\Room;
use backend\models\Meeting;
use backend\models\Reference;
use backend\models\Organisation;
use backend\models\ObjectPerson;
use backend\modules\sekretariat\general\models\ActivityRoomSearch as ActivityRoomSearch;
use backend\modules\sekretariat\general\models\RoomSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use hscstudio\heart\helpers\Heart;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
/**
 * ActivityRoomController implements the CRUD actions for Activity model.
 */
class ActivityRoomController extends Controller
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
    public function actionIndex()
    {
        $searchModel = new ActivityRoomSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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
	
	public function actionRoom($activity_id, $satker_id=0)
    {
		$activity=$this->findModel($activity_id);
		
        $searchModel = new RoomSearch();
		if($satker_id===0) $satker_id = (int)$activity->location;
		if($satker_id<0) $satker_id = (int)Yii::$app->user->identity->employee->satker_id;
		if($satker_id=='all'){
			$queryParams['RoomSearch']=[
				'status'=>1,
			];
		}
		else{
			$queryParams['RoomSearch']=[
				'satker_id'=>$satker_id,
				'status'=>1,
			];
		}	
		$queryParams=yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
        $dataProvider = $searchModel->search($queryParams);

		// GET ALL TRAINING YEAR
		$satkers['all']='All';
		$satkers = yii\helpers\ArrayHelper::map(\backend\models\Reference::find()
			->where(['type'=>'satker'])
			//->orderBy(['eselon'=>'ASC',])
			//->active()
			->asArray()
			->all(), 'id', 'name');
		
		if (Yii::$app->request->isAjax){
			return $this->renderAjax('room', [
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
				'activity_id'=>$activity_id,
				'activity'=>$activity,
				'satker_id'=>$satker_id,
				'satkers'=>$satkers,
			]);
		}
		else{
			return $this->render('room', [
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
				'activity_id'=>$activity_id,
				'activity'=>$activity,
				'satker_id'=>$satker_id,
				'satkers'=>$satkers,
			]);
		}
    }
	
	public function actionSetRoom($activity_id,$room_id,$status)
    {
        $satker_id = (int)Yii::$app->user->identity->employee->satker_id;
		$activity=$this->findModel($activity_id);		
		$meeting = Meeting::findOne($activity->id);
		$room = Room::findOne($room_id);
		/* $status = 0;
		if($room->satker_id == $satker_id) $status = 1; */
		$model = new ActivityRoom([
			'activity_id'=>$meeting->activity_id,
			'room_id'=>$room->id,
			'start'=>$activity->start,
			'end'=>$activity->end,
			'status'=>$status,
		]);
		
        if($model->save()) {
			Yii::$app->session->setFlash('success', 'Data saved');
		}
		else{
			 Yii::$app->session->setFlash('error', 'Unable create there are some error');
		}
		if (Yii::$app->request->isAjax){	
			return ('Room have set');
		}
		else{
			return $this->redirect(['room', 'activity_id' => $activity_id]);
		}
    }
	
	 public function actionUnsetRoom($activity_id,$room_id)
    {
        $model = ActivityRoom::find()->where(
			'activity_id=:activity_id AND room_id=:room_id',[':activity_id'=>$activity_id,':room_id'=>$room_id])->one();
		if($model->delete()) {
			Yii::$app->session->setFlash('success', 'Data saved');
		}
		else{
			 Yii::$app->session->setFlash('error', 'Unable create there are some error');
		}
		if (Yii::$app->request->isAjax){	
			return ('Room have unset');
		}
		else{
			return $this->redirect(['room', 'activity_id' => $activity_id]);
		}
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
            throw new NotFoundHttpException(Yii::t('app','SYSTEM_TEXT_PAGE_NOT_FOUND'));
        }
    }
}
