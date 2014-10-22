<?php

namespace frontend\modules\student\controllers;

use Yii;
use frontend\models\TrainingClassSubjectTrainerEvaluation;
use frontend\modules\student\models\TrainingClassSubjectTrainerEvaluationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TrainingClassSubjectTrainerEvaluationController implements the CRUD actions for TrainingClassSubjectTrainerEvaluation model.
 */
class TrainingClassSubjectTrainerEvaluationController extends Controller
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
     * Lists all TrainingClassSubjectTrainerEvaluation models.
     * @return mixed
     */
    public function actionIndex($training_class_subject_id=NULL,$trainer_id=NULL)
    {
        /*$searchModel = new TrainingClassSubjectTrainerEvaluationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);*/
		//$id = TrainingClassSubjectTrainerEvaluation::findOne(['training_class_subject_id'=>base64_decode(\hscstudio\heart\helpers\Kalkun::HexToAscii($training_class_subject_id)),'trainer_id'=>base64_decode(\hscstudio\heart\helpers\Kalkun::HexToAscii($trainer_id)),'student_id'=>Yii::$app->user->identity->id])->id;
		if(($model=TrainingClassSubjectTrainerEvaluation::findOne(['training_class_subject_id'=>base64_decode(\hscstudio\heart\helpers\Kalkun::HexToAscii($training_class_subject_id)),'trainer_id'=>base64_decode(\hscstudio\heart\helpers\Kalkun::HexToAscii($trainer_id)),'student_id'=>Yii::$app->user->identity->id]))!=null)
		{
			return $this->redirect(['view',
						'training_class_subject_id' => $training_class_subject_id,
						'trainer_id' => $trainer_id,		
				]);
		}
		else
		{
			return $this->redirect(['create',
						'training_class_subject_id' => $training_class_subject_id,
						'trainer_id' => $trainer_id,
				]);	
		}
    }

    /**
     * Displays a single TrainingClassSubjectTrainerEvaluation model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($training_class_subject_id=NULL,$trainer_id=NULL)
    {
        return $this->render('view', [
            'model' => TrainingClassSubjectTrainerEvaluation::findOne(['training_class_subject_id'=>base64_decode(\hscstudio\heart\helpers\Kalkun::HexToAscii($training_class_subject_id)),'trainer_id'=>base64_decode(\hscstudio\heart\helpers\Kalkun::HexToAscii($trainer_id)),'student_id'=>Yii::$app->user->identity->id]),
			'training_class_subject_id' => $training_class_subject_id,
			'trainer_id' => $trainer_id,
        ]);
    }

    /**
     * Creates a new TrainingClassSubjectTrainerEvaluation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($training_class_subject_id=NULL,$trainer_id=NULL)
    {
        $id1 = base64_decode(\hscstudio\heart\helpers\Kalkun::HexToAscii($training_class_subject_id));
		$id2 = base64_decode(\hscstudio\heart\helpers\Kalkun::HexToAscii($trainer_id));
		$model = new TrainingClassSubjectTrainerEvaluation();

        if ($model->load(Yii::$app->request->post())){ 
			$model->training_class_subject_id = $id1;
			$model->trainer_id = $id2;
			$model->student_id = Yii::$app->user->identity->id;
			
			for($x=1;$x<=12;$x++)
			{
				$model->value[$x];
			}
			$model->value=implode("|",$model->value);
			$model->status=1;
			
			if($model->save()) {
				Yii::$app->getSession()->setFlash('success', 'New data have saved.');
			}
			else{
				Yii::$app->getSession()->setFlash('error', 'New data is not saved.');
			}
            return $this->redirect(['view', 'training_class_subject_id' => $training_class_subject_id,'trainer_id' => $trainer_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
				//'training_id' => base64_decode(\hscstudio\heart\helpers\Kalkun::HexToAscii($tb_training_id)),
				'training_class_subject_id' => $training_class_subject_id,
				'trainer_id' => $trainer_id,
            ]);
        }
    }

    /**
     * Updates an existing TrainingClassSubjectTrainerEvaluation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
       /* $model = $this->findModel($id);

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
        }*/
		return $this->redirect(['index']);
    }

    /**
     * Deletes an existing TrainingClassSubjectTrainerEvaluation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		/*if($this->findModel($id)->delete()) {
			Yii::$app->getSession()->setFlash('success', 'Data have deleted.');
		}
		else{
			Yii::$app->getSession()->setFlash('error', 'Data is not deleted.');
		}*/
        return $this->redirect(['index']);
    }

    /**
     * Finds the TrainingClassSubjectTrainerEvaluation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TrainingClassSubjectTrainerEvaluation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TrainingClassSubjectTrainerEvaluation::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
