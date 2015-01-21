<?php

namespace backend\modules\pusdiklat2\competency\controllers\planning;

use Yii;
use backend\models\Program;
use backend\modules\pusdiklat2\competency\models\planning\ProgramSearch;
use backend\modules\pusdiklat2\competency\models\planning\TrainingActivityAllSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\ObjectReference;
use backend\models\ObjectFile;
use backend\models\File;
use backend\models\ObjectPerson;
use hscstudio\heart\helpers\Heart;
/**
 * ProgramController implements the CRUD actions for Program model.
 */
class ProgramController extends Controller
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
     * Lists all Program models.
     * @return mixed
     */
    public function actionIndex($status=1)
    {
        $searchModel = new ProgramSearch();
		$queryParams = Yii::$app->request->getQueryParams();
		if($status!='all'){
			$queryParams['ProgramSearch']=[
				'status'=>$status,
			];
		}
		$queryParams=yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
        $dataProvider = $searchModel->search($queryParams);
		
		
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'status' => $status,
        ]);
    }

	/**
     * Lists all Program models.
     * @return mixed
     */
    public function actionTraining($id)
    {
		$model = $this->findModel($id);
		$renders = [];
		$renders['model'] = $model;
		
		$searchModel = new TrainingActivityAllSearch();
		$queryParams = Yii::$app->request->getQueryParams();
		$queryParams['TrainingActivityAllSearch']=[
			'program_id'=>$id,
		];
		$queryParams=yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
        $dataProvider = $searchModel->search($queryParams);
		$dataProvider->getSort()->defaultOrder = [
			'start'=>SORT_DESC,		
			'end'=>SORT_DESC,
		];
		$renders['searchModel'] = $searchModel;
		$renders['dataProvider'] = $dataProvider;	
        
		if (Yii::$app->request->isAjax)
			return $this->renderAjax('training', $renders);		
		else
			return $this->render('training', $renders);
    }
    /**
     * Displays a single Program model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        if (Yii::$app->request->isAjax)
			return $this->renderAjax('view', [
				'model' => $this->findModel($id),
			]);		
		else
			return $this->render('view', [
				'model' => $this->findModel($id),
			]);
    }

    /**
     * Creates a new Program model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Program();
		$renders = [];
		$renders['model'] = $model;	
		
        if ($model->load(Yii::$app->request->post())){ 
			$model->status = 1;
			$model->satker = 'current';
			/* $model->stage = implode(',',$model->stage); */
			if($model->save()) {
				Yii::$app->getSession()->setFlash('success', '<i class="fa fa-fw fa-check-circle"></i>New data have saved.');
				return $this->redirect(['index']);
			}
			else{
				Yii::$app->getSession()->setFlash('error', '<i class="fa fa-fw fa-times-circle"></i>New data is not saved.');
				return $this->render('create', $renders);
			}            
        } else {
			if (Yii::$app->request->isAjax)
				return $this->renderAjax('create', $renders);
			else 
				return $this->render('create', $renders);
        }
    }

    /**
     * Updates an existing Program model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$renders = [];
		$renders['model'] = $model;
		
        if ($model->load(Yii::$app->request->post())) {
			$model->satker = 'current';
            if($model->save()) {
				Yii::$app->getSession()->setFlash('success', '<i class="fa fa-fw fa-check-circle"></i>Data have updated.');
				
				return $this->redirect(['index']);
			}
			else{
				Yii::$app->getSession()->setFlash('error', '<i class="fa fa-fw fa-times-circle"></i>Data is not updated.');
				return $this->render('update', $renders);
			}
			
        } else {
			if (Yii::$app->request->isAjax)
				return $this->renderAjax('update', $renders);
			else
				return $this->render('update', $renders);
        }
    }

	/**
     * Updates an existing Program model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionValidation($id)
    {
        $model = $this->findModel($id);
		$renders = [];
		$renders['model'] = $model;
		$currentFiles=[];
		$object_file_array = [
			'validation_document'=>'Validation Document'];
		$renders['object_file_array'] = $object_file_array;
		foreach($object_file_array as $object_file=>$label){
			$currentFiles[$object_file] = '';
			$object_files[$object_file] = ObjectFile::find()
				->where([
					'object'=>'program',
					'object_id' => $model->id,
					'type' => $object_file, 
				])
				->one();
			
			if($object_files[$object_file]!=null){
				$files[$object_file] = File::find()
					->where([
						'id'=>$object_files[$object_file]->file_id, 
					])
					->one();
				$currentFiles[$object_file]=$files[$object_file]->file_name;
			}
			else{
				$object_files[$object_file]= new ObjectFile();
				$files[$object_file] = new File();
			}
			$renders[$object_file] = $object_files[$object_file];
			$renders[$object_file.'_file'] = $files[$object_file];
		}
		
        if ($model->load(Yii::$app->request->post())) {
			$model->satker = 'current';
            if($model->save()) {
				Yii::$app->getSession()->setFlash('success', '<i class="fa fa-fw fa-check-circle"></i>Data have updated.');
				$uploaded_files = [];					
				foreach($object_file_array as $object_file=>$label){
					$uploaded_files[$object_file] = \yii\web\UploadedFile::getInstance($files[$object_file], 'file_name['.$object_file.']');						
					if(null!=$uploaded_files[$object_file]){
						Heart::upload(
							$uploaded_files[$object_file], 
							'program', 
							$model->id, 
							$files[$object_file],
							$object_files[$object_file], 
							$object_file, 
							false,
							$currentFiles[$object_file],
							false
						);					
					}
				}
				return $this->redirect(['index']);
			}
			else{
				Yii::$app->getSession()->setFlash('error', '<i class="fa fa-fw fa-times-circle"></i>Data is not updated.');
				return $this->render('validation', $renders);
			}
			
        } else {
            if (Yii::$app->request->isAjax)
				return $this->renderAjax('validation', $renders);
			else
				return $this->render('validation', $renders);
        }
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
			// CEK ID 393 IN TABLE ORGANISATION IS SUBBIDANG PROGRAM
			'organisation_1202020100'=>'PIC PROGRAM [SUBBIDANG PERENCANAAN DAN PENGEMBANGAN]'
		];
		$renders['object_people_array'] = $object_people_array;
		foreach($object_people_array as $object_person=>$label){
			$object_people[$object_person] = ObjectPerson::find()
				->where([
					'object'=>'program',
					'object_id' => $id,
					'type' => $object_person, 
				])
				->one();
			if($object_people[$object_person]==null){
				$object_people[$object_person]= new ObjectPerson([
					'object'=>'program',
					'object_id' => $id,
					'type' => $object_person, 
				]);
			}
			$renders[$object_person] = $object_people[$object_person];
		}	
		
        if (Yii::$app->request->post()) {
			foreach($object_people_array as $object_person=>$label){
				$person_id = (int)Yii::$app->request->post('ObjectPerson')[$object_person]['person_id'];
				Heart::objectPerson($object_people[$object_person],$person_id,'program',$id,$object_person);
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
	
    /**
     * Deletes an existing Program model.
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
     * Finds the Program model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Program the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
		if (($model = Program::find()
				->where([
					'id'=>$id,
				])
				->currentSatker()
				->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app','SYSTEM_TEXT_PAGE_NOT_FOUND'));
        }
    }
	
	/**
     * Updates an existing ProgramDocument model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionStatus($id, $status)
    {
        $model = $this->findModel($id);		
		$status = ($status==1)?0:1;
		$model->status = $status;
		$model->save();	
		return $this->redirect(['index']);		
	}
	
	/**
     * Lists all TrainingClass models.
     * @return mixed
     */
    public function actionExportProgram($status=1,$filetype='xlsx')
    {
		$searchModel = new ProgramSearch();
		$queryParams = Yii::$app->request->getQueryParams();
		if($status!='all'){
			$queryParams['ProgramSearch']=[
				'status'=>$status,
			];
		}
		$queryParams=yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
        $dataProvider = $searchModel->search($queryParams);        
		$dataProvider->getSort()->defaultOrder = [
			'status'=>SORT_DESC,		
		];
		$dataProvider->setPagination(false);
		
		$types=['xls'=>'Excel5','xlsx'=>'Excel2007'];
		$objReader = \PHPExcel_IOFactory::createReader($types[$filetype]);
		$template = Yii::getAlias('@file').DIRECTORY_SEPARATOR.'template'.DIRECTORY_SEPARATOR.'pusdiklat'.
			DIRECTORY_SEPARATOR.'planning'.DIRECTORY_SEPARATOR.'program.list.'.$filetype;
		$objPHPExcel = $objReader->load($template);
		$objPHPExcel->getProperties()->setTitle("Daftar Program");
		$objPHPExcel->setActiveSheetIndex(0);
		$activeSheet = $objPHPExcel->getActiveSheet();
		$activeSheet->setCellValue('A3', strtoupper(\Yii::$app->user->identity->employee->satker->name));
		$idx=7; // line 7
		$categories = ['','1'=>'Dasar','2'=>'Lanjutan','3'=>'Menengah','4'=>'Tinggi'];
		foreach($dataProvider->getModels() as $data){		
			
			$activeSheet->insertNewRowBefore($idx+1,1);
			$activeSheet->setCellValue('A'.$idx, $idx-6)
					    ->setCellValue('B'.$idx, $data->number)
					    ->setCellValue('C'.$idx, $data->name)
					    ->setCellValue('D'.$idx, $data->hours)
					    ->setCellValue('E'.$idx, $data->days)
					    ->setCellValue('F'.$idx, ($data->test==1)?'Ya':'Tidak')
						->setCellValue('G'.$idx, $data->stage)
						->setCellValue('H'.$idx, ($categories[$data->category]))
						->setCellValue('I'.$idx, (($data->validation_status==1)?'Sudah':'Belum').', ',$data->validation_note)
						->setCellValue('J'.$idx, ($data->status==1)?'Publish':'Draft')
						;
			$idx++;
		} 	
		
		// Redirect output to a client’s web browser
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="program.list.'.date('YmdHis').'.'.$filetype.'"');
		header('Cache-Control: max-age=0');
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, $types[$filetype]);
		$objWriter->save('php://output');
		exit;
		/* return $this->redirect(['student', 'id' => $id, 'status'=>$status]);	 */
    }
}
