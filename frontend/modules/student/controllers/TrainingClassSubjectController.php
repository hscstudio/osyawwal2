<?php

namespace frontend\modules\student\controllers;

use Yii;
use frontend\models\TrainingClassSubject;
use frontend\modules\student\models\TrainingClassSubjectSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TrainingClassSubjectController implements the CRUD actions for TrainingClassSubject model.
 */
class TrainingClassSubjectController extends Controller
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
     * Lists all TrainingClassSubject models.
     * @return mixed
     */
    public function actionIndex($training_id=NULL,$training_student_id=NULL)
    {
        $id=base64_decode(\hscstudio\heart\helpers\Kalkun::HexToAscii($training_id));
		$id2 = base64_decode(\hscstudio\heart\helpers\Kalkun::HexToAscii($training_student_id));
		$searchModel = new TrainingClassSubjectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id,$id2);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TrainingClassSubject model.
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
     * Creates a new TrainingClassSubject model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TrainingClassSubject();

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
     * Updates an existing TrainingClassSubject model.
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
     * Deletes an existing TrainingClassSubject model.
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

    /**
     * Finds the TrainingClassSubject model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TrainingClassSubject the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TrainingClassSubject::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
