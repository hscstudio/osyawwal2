<?php

namespace backend\modules\pusdiklat\general\controllers;

use Yii;
use backend\models\User;
use backend\modules\pusdiklat\general\models\UserSearch;
use backend\models\Employee;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    public $layout = '@hscstudio/heart/views/layouts/column2';
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
					'block' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('view', [
                'model' => $this->findModel($id),
            ]);
        }
        else {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
			if(!empty($model->new_password)){
				$model->password = $model->new_password;
			}
			
            if($model->save()) {
				Yii::$app->getSession()->setFlash('success', '<i class="fa fa-fw fa-check-circle"></i> Data berhasil diperbarui');
			}
			else{
				Yii::$app->getSession()->setFlash('error', '<i class="fa fa-fw fa-times-circle"></i> Data gagal disimpan');
			}
			return $this->redirect(['index']);
        } else {
            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('update', [
                    'model' => $model,
                ]);
            }
            else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		$model = $this->findModel($id);
		ObjectPerson::find([
			'object'=>'person',
			'object_id'=>$id,
			'type' => '-',
		])
		->one()
		->delete();
        if($model->delete()) {          
            Yii::$app->getSession()->setFlash('success', '<i class="fa fa-fw fa-check-circle"></i> Individu berhasil dihapus');
        }
        else{
            Yii::$app->getSession()->setFlash('error', '<i class="fa fa-fw fa-times-circle"></i> Data tidak terhapus');
        }
        return $this->redirect(['index']);
    }

	/**
     * Block an existing User model.
     * If block is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBlock($id)
    {
		Yii::$app->getSession()->setFlash('error', 'You have not privilege, contact Administrator.');
		/* $model = $this->findModel($id);
		$model->status = 0;
        if($model->save()) {
			Yii::$app->getSession()->setFlash('success', 'User have blocked.');
		}
		else{
			Yii::$app->getSession()->setFlash('error', 'User is not blocked.');
		} */
        return $this->redirect(['index']);
    }
	
	/**
     * Unblock an existing User model.
     * If unblock is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUnblock($id)
    {
		Yii::$app->getSession()->setFlash('error', 'You have not privilege, contact Administrator.');
		// $model = $this->findModel($id);
		// $model->status = 1;
        // if($model->save()) {
			// Yii::$app->getSession()->setFlash('success', 'User have unblocked.');
		// }
		// else{
			// Yii::$app->getSession()->setFlash('error', 'User is not unblocked.');
		// }
        return $this->redirect(['index']);
    }
	
    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        /* special user id<=100 not editable */
		if ($id>100){
			if (($model = User::findOne($id)) !== null) {
				return $model;
			} else {
				throw new NotFoundHttpException(Yii::t('app','SYSTEM_TEXT_PAGE_NOT_FOUND'));
			}
		}
		else{
			throw new NotFoundHttpException('You have not privilege to access this data, contact Administratator.');
		}
    }
}
