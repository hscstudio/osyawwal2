<?php

namespace backend\modules\pusdiklat2\competency\controllers\evaluation;

use Yii;
use backend\models\Activity;
use backend\modules\pusdiklat2\competency\models\evaluation\ActivitySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ActivityGenerateController implements the CRUD actions for Activity model.
 */
class ActivityGenerateController extends Controller
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
        $searchModel = new ActivitySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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
	
	public function actionLetterAssignment($id)
    {
        return $this->render('letterAssignment', [
            'model' => $this->findModel($id),
        ]);
    }
	
	public function actionAppraisalForm($id)
    {
        return $this->render('appraisalForm', [
            'model' => $this->findModel($id),
        ]);
    }
	
	public function actionEvaluationDocument($id)
    {
        return $this->render('evaluationDocument', [
            'model' => $this->findModel($id),
        ]);
    }
	
	public function actionHonorTransport($id)
    {
        return $this->render('honorTransport', [
            'model' => $this->findModel($id),
        ]);
    }
}
