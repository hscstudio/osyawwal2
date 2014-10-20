<?php

namespace frontend\modules\student\controllers;

use Yii;
use frontend\models\TrainingExecutionEvaluation;
use frontend\modules\student\models\TrainingExecutionEvaluationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TrainingExecutionEvaluationController implements the CRUD actions for TrainingExecutionEvaluation model.
 */
class TrainingExecutionEvaluationController extends Controller
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
     * Lists all TrainingExecutionEvaluation models.
     * @return mixed
     */
    public function actionIndex($training_id)
    {
        /*$searchModel = new TrainingExecutionEvaluationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);*/
		$training_student_id = \frontend\models\TrainingStudent::findOne(['student_id' => Yii::$app->user->identity->id,'training_id'=>base64_decode(\hscstudio\heart\helpers\Kalkun::HexToAscii($training_id))])->id;
		$training_class_student_id =\frontend\models\TrainingClassStudent::findOne(['training_id'=>base64_decode(\hscstudio\heart\helpers\Kalkun::HexToAscii($training_id)),'training_student_id'=>$training_student_id])->id;
		
		if (($model = TrainingExecutionEvaluation::findOne(['training_class_student_id'=>$training_class_student_id])) !== null) 
		{
				return $this->redirect(['view',
						'training_id' => $training_id,		
				]);
		}
		else
		{		return $this->redirect(['create',
						'training_id' => $training_id,		
				]);
		}
    }

    /**
     * Displays a single TrainingExecutionEvaluation model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($training_id)
    {
        /*return $this->render('view', [
            'model' => $this->findModel($id),
        ]);*/
		$id = base64_decode(\hscstudio\heart\helpers\Kalkun::HexToAscii($training_id));
		$training_student_id = \frontend\models\TrainingStudent::findOne(['student_id' => Yii::$app->user->identity->id,'training_id'=>$id])->id;
			
		$training_class_student_id = \frontend\models\TrainingClassStudent::findOne(['training_id'=>$id,'training_student_id'=>$training_student_id])->id;
		
		return $this->render('view', [
            'model' => $this->findModel($training_class_student_id),
			'training_id' => base64_decode(\hscstudio\heart\helpers\Kalkun::HexToAscii($training_id)),
        ]);
    }

    /**
     * Creates a new TrainingExecutionEvaluation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($training_id)
    {
        $id = base64_decode(\hscstudio\heart\helpers\Kalkun::HexToAscii($training_id));
		$model = new TrainingExecutionEvaluation();

        /*if ($model->load(Yii::$app->request->post())){ 
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
        }*/
		if ($model->load(Yii::$app->request->post())){
			
			for($x=1;$x<=33;$x++)
			{
				$model->value[$x];
			}
			$model->value=implode("|",$model->value);
			
			$training_student_id = \frontend\models\TrainingStudent::findOne(['student_id' => Yii::$app->user->identity->id,'training_id'=>$id])->id;
			
			$model->training_class_student_id = \frontend\models\TrainingClassStudent::findOne(['training_id'=>$id,'training_student_id'=>$training_student_id])->id;
			
			$model->status=1;
			if($model->save()) {
				 Yii::$app->session->setFlash('success', 'Data saved');
			}
			else{
				 Yii::$app->session->setFlash('error', 'Unable create there are some error');
			}
            return $this->redirect(['view', 'training_id' => $training_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
				'training_id' => $training_id,
            ]);
        }
    }

    /**
     * Updates an existing TrainingExecutionEvaluation model.
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
     * Deletes an existing TrainingExecutionEvaluation model.
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
     * Finds the TrainingExecutionEvaluation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TrainingExecutionEvaluation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TrainingExecutionEvaluation::findOne(['training_class_student_id'=>$id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
