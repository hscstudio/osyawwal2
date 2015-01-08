<?php

namespace backend\modules\pusdiklat\planning\controllers;

use Yii;
use backend\models\Program;
use backend\models\ProgramHistory;
use backend\modules\pusdiklat\planning\models\ProgramSearch;
use backend\modules\pusdiklat\planning\models\ProgramHistorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\ObjectReference;
use backend\models\ObjectFile;
use backend\models\File;
use backend\models\ObjectPerson;
use backend\models\ProgramSubject;
use backend\models\ProgramSubjectHistory;
use hscstudio\heart\helpers\Heart;
use yii\data\ActiveDataProvider;

/**
 * ProgramController implements the CRUD actions for Program model.
 */
class Program2Controller extends Controller
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
     * Displays a single Program model.
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
     * Displays a single Program model.
     * @param integer $id
     * @return mixed
     */
    public function actionHistory($id)
    {
        $searchModel = new ProgramHistorySearch();
		$queryParams = Yii::$app->request->getQueryParams();
		$queryParams['ProgramHistorySearch']=[
			'id'=>$id,
		];
		/* if($status!='all'){
			$queryParams['ProgramHistorySearch']=[
				'status'=>$status,
			];
		} */
		$queryParams=yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
        $dataProvider = $searchModel->search($queryParams);
		$dataProvider->getSort()->defaultOrder = ['revision'=>SORT_DESC];
		
		return $this->render('history', [
			'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'model' => $this->findModel($id),
			'status' => '',
		]);
			
    }

	/**
     * Displays a single Program model.
     * @param integer $id
     * @return mixed
     */
    public function actionViewHistory($id, $revision)
    {
        if (Yii::$app->request->isAjax)
			return $this->renderAjax('view', [
				'model' => $this->findModelHistory($id,$revision),
			]);		
		else
			return $this->render('view', [
				'model' => $this->findModelHistory($id,$revision),
			]);
    }
	
	public function actionSubjectHistory($id,$revision)
    {
        $model = $this->findModelHistory($id, $revision);
		$renders = [];
		$renders['model'] = $model;
		
		$query = ProgramSubjectHistory::find()
		->where([
			'program_id' => $model->id,
			'program_revision' => $revision
		])
		->orderBy(['sort'=>SORT_ASC,]);	
		$dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);	
		//$dataProvider->getSort()->defaultOrder = ['type'=>SORT_ASC];		
		$renders['dataProvider'] = $dataProvider;
		
		if (Yii::$app->request->isAjax)
			return $this->renderAjax('subjectHistory', $renders);
		else
			return $this->render('subjectHistory', $renders);
    } 
	
	protected function findModelHistory($id, $revision)
    {
		if (($model = ProgramHistory::find()
				->where([
					'id'=>$id,
					'revision'=>$revision
				])
				->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
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
			/* $model->stage = implode(',',$model->stage); */
			if (isset(Yii::$app->request->post()['create_revision'])){
				$model->create_revision = true;
			}
            if($model->save()) {
				Yii::$app->getSession()->setFlash('success', '<i class="fa fa-fw fa-check-circle"></i>Program berhasil diperbarui');
				if (isset(Yii::$app->request->post()['create_revision'])){
					$program_subjects = ProgramSubject::find()
						->where([
							'program_id'=>$model->id,
						])
						->all();
					foreach($program_subjects as $program_subject){
						$program_subject->program_revision = (int)\backend\models\ProgramHistory::getRevision($model->id);
						$program_subject->create_revision = true;
						$program_subject->save();
					}
				}
				return $this->redirect(['view', 'id' => $model->id]);
			}
			else{
				Yii::$app->getSession()->setFlash('error', '<i class="fa fa-fw fa-times-circle"></i> Program gagal diperbarui');
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
    public function actionPic($id)
    {
        $model = $this->findModel($id);
		$renders = [];
		$renders['model'] = $model;
		$object_people_array = [
			//1213020200 CEK KD_UNIT_ORG 1213020200 IN TABLE ORGANISATION IS SUBBIDANG KURIKULUM
			'organisation_1213020200'=>'PIC Program [BIDANG KURIKULUM]'
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
			Yii::$app->getSession()->setFlash('success', '<i class="fa fa-fw fa-check-circle"></i> PIC berhasil diperbarui');
			if (!Yii::$app->request->isAjax) {
				return $this->redirect(['index']);	
			}
			else{
				echo 'Pic telah diperbarui';
			}
        } else {
			if (Yii::$app->request->isAjax)
				return $this->renderAjax('pic', $renders);
            else
				return $this->render('pic', $renders);
        }
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
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	/**
     * Updates an existing Program model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDocument($id,$status='all')
    {
        $model = $this->findModel($id);
		$renders = [];
		$renders['model'] = $model;
		$renders['status'] = $status;
		
		if($status=='all'){
			$query = ObjectFile::find()
				->joinWith('file')
				->where([
					'object'=>'program',
					'object_id' => $model->id,
					'type' => ['kap','gbpp','module'],
				])
				->orderBy(['status'=>SORT_DESC,'file_id'=>SORT_DESC,]);			
        }
		else if(in_array($status,[0,1])){
			$query = ObjectFile::find()
				->joinWith('file')
				->where([
					'object'=>'program',
					'object_id' => $model->id,
					'type' => ['kap','gbpp','module'],
					'status' => $status,
				])
				->orderBy(['status'=>SORT_DESC,'file_id'=>SORT_DESC,]);
		}
		$dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);	
		$dataProvider->getSort()->defaultOrder = ['type'=>SORT_ASC];		
		$renders['dataProvider'] = $dataProvider;
		
		$object_file = new ObjectFile();
		$renders['object_file'] = $object_file;
		$file = new File();	
		$file->scenario = 'filetype-document-compressed';
		$renders['file'] = $file;
		
        if (Yii::$app->request->post()) {
			$object_file->load(Yii::$app->request->post());			
			if($file->load(Yii::$app->request->post())){
				$file->status=0;
				$uploaded_file = \yii\web\UploadedFile::getInstance($file, 'file_name');
				if(null!=$uploaded_file){
					Heart::upload(
						$uploaded_file, 
						'program', 
						$model->id, 
						$file,
						$object_file, 
						$object_file->type
					);
					Yii::$app->getSession()->setFlash('success', '<i class="fa fa-fw fa-check-circle"></i> Dokumen berhasil diunggah');					
				}
				else{
					Yii::$app->getSession()->setFlash('error', '<i class="fa fa-fw fa-times-circle"></i> Dokumen gagal diunggah');
				}				
			}			
        }
		
		if (Yii::$app->request->isAjax)
			return $this->renderAjax('document', $renders);
		else
			return $this->render('document', $renders);
    } 
	
	public function actionDocumentDelete($id,$type,$file_id)
    {
		$model = $this->findModel($id);
		$object_file = ObjectFile::find()
			->where([
				'object'=>'program',
				'object_id'=>$model->id,
				'type'=>$type,
				'file_id'=>$file_id,
			])
			->one();
		
		
		if(null!=$object_file){
			if (Heart::unlink($object_file)) Yii::$app->getSession()->setFlash('success', '<i class="fa fa-fw fa-check-circle"></i> Dokumen berhasil dihapus');
			else Yii::$app->getSession()->setFlash('error', '<i class="fa fa-fw fa-times-circle"></i> Dokumen gagal dihapus');
		}
		else Yii::$app->getSession()->setFlash('error', '<i class="fa fa-fw fa-times-circle"></i> Dokumen gagal dihapus');
        return $this->redirect(['document','id'=>$model->id]);
    }
	
	/**
     * Updates an existing ProgramDocument model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDocumentStatus($id, $type,$file_id, $status)
    {
        $model = $this->findModel($id);
		$object_file = ObjectFile::find()
			->where([
				'object'=>'program',
				'object_id'=>$model->id,
				'type'=>$type,
				'file_id'=>$file_id,
			])
			->one();
		$status = ($status==1)?0:1;
		if(null!=$object_file){
			$object_file->file->status=$status;
			if($object_file->file->save()) Yii::$app->getSession()->setFlash('success', '<i class="fa fa-fw fa-check-circle"></i> Dokumen telah terhapus');
			else Yii::$app->getSession()->setFlash('error', '<i class="fa fa-fw fa-times-circle"></i> Dokumen gagal dihapus');
		}
		else Yii::$app->getSession()->setFlash('error', '<i class="fa fa-fw fa-times-circle"></i> Dokumen gagal dihapus');
        return $this->redirect(['document','id'=>$model->id]);
	}
	
	/**
     * Updates an existing Program model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionSubject($id,$status='all',$action='',$subject_id=0)
    {
        $model = $this->findModel($id);
		$renders = [];
		$renders['model'] = $model;
		$renders['status'] = $status;
		
		if($status=='all'){
			$query = ProgramSubject::find()
				->where([
					'program_id' => $model->id,
				])
				->orderBy(['status'=>SORT_DESC,'sort'=>SORT_ASC,]);			
        }
		else if(in_array($status,[0,1])){
			$query = ProgramSubject::find()
				->where([
					'program_id' => $model->id,
					'status'=>$status,
				])
				->orderBy(['status'=>SORT_DESC,'sort'=>SORT_ASC,]);
		}
		$dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);	
		//$dataProvider->getSort()->defaultOrder = ['type'=>SORT_ASC];		
		$renders['dataProvider'] = $dataProvider;
		
		$current_program_hours=0;
		if($action=='update'){
			$program_subject = ProgramSubject::findOne($subject_id);
			$current_program_hours=$program_subject->hours;
		}
		
		if(!isset($program_subject) or null==$program_subject){
			$program_subject = new ProgramSubject([
				'program_id' => $model->id,
				'program_revision' => (int)\backend\models\ProgramHistory::getRevision($model->id),
			]);			
		}
		$renders['program_subject'] = $program_subject;
		
        if (Yii::$app->request->post()) {
			// CHECKING TOTAL JP
			$ps = ProgramSubject::find()
				->select('sum(hours) as sum_hours')
				->where([
					'program_id' => $model->id,
				])
				->asArray()
				->one();
			$program_subject_hours = 0;
			if(!empty($ps)) $program_subject_hours= $ps['sum_hours'];			
			
			$program_subject->load(Yii::$app->request->post());
			$sum_hours = $program_subject_hours + $program_subject->hours - $current_program_hours;
			if($model->hours<$sum_hours){
				Yii::$app->getSession()->setFlash('error', 'Total jamlat melebihi maksimal jamlat pada program.');
			}
			else{
				if(!empty($program_subject->stage)){
					$program_subject->stage = implode('|',$program_subject->stage);
				}
				if($program_subject->save()){
					Yii::$app->getSession()->setFlash('success', '<i class="fa fa-fw fa-check-circle"></i> Mata pelajaran berhasil disimpan');	
					return $this->redirect(['subject','id'=>$model->id]);					
				}
				else{
					Yii::$app->getSession()->setFlash('error', '<i class="fa fa-fw fa-times-circle"></i> Mata pelajaran gagal disimpan');
				}
			}				
        }
		
		if (Yii::$app->request->isAjax)
			return $this->renderAjax('subject', $renders);
		else
			return $this->render('subject', $renders);
    } 
	
	public function actionSubjectDelete($id,$subject_id)
    {
		$model = $this->findModel($id);
		$program_subject = ProgramSubject::find()
			->where([
				'id'=>$subject_id,
				'program_id'=>$id,
			])
			->one();
		
		
		if(null!=$program_subject){
			if ($program_subject->delete()) Yii::$app->getSession()->setFlash('success', '<i class="fa fa-fw fa-check-circle"></i> Mata pelajaran berhasil dihapus');
			else Yii::$app->getSession()->setFlash('error', '<i class="fa fa-fw fa-times-circle"></i> Mata pelajaran gagal dihapus');
		}
		else Yii::$app->getSession()->setFlash('error', '<i class="fa fa-fw fa-times-circle"></i> Mata pelajaran gagal dihapus');
        return $this->redirect(['subject','id'=>$model->id]);
    }
	
	public function actionSubjectStatus($id,$subject_id,$status)
    {
		$model = $this->findModel($id);
		$program_subject = ProgramSubject::find()
			->where([
				'id'=>$subject_id,
				'program_id'=>$id,
			])
			->one();
		
		
		if(null!=$program_subject){
			$status = ($status==1)?0:1;
			$program_subject->status = $status;
			if ($program_subject->save()) Yii::$app->getSession()->setFlash('success', '<i class="fa fa-fw fa-check-circle"></i> Status diperbarui');
			else Yii::$app->getSession()->setFlash('error', '<i class="fa fa-fw fa-times-circle"></i> Status gagal diperbarui');
		}
		else Yii::$app->getSession()->setFlash('error', '<i class="fa fa-fw fa-times-circle"></i> Status gagal diperbarui');
        return $this->redirect(['subject','id'=>$model->id]);
    }
	
	public function actionDocumentHistory($id,$status='all')
    {
        $model = $this->findModel($id);
		$renders = [];
		$renders['model'] = $model;
		$renders['status'] = $status;
		
		if($status=='all'){
			$query = ObjectFile::find()
				->joinWith('file')
				->where([
					'object'=>'program',
					'object_id' => $model->id,
					'type' => ['kap','gbpp','module'],
				])
				->orderBy(['status'=>SORT_DESC,'file_id'=>SORT_DESC,]);			
        }
		
		$dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);	
		$dataProvider->getSort()->defaultOrder = ['type'=>SORT_ASC];		
		$renders['dataProvider'] = $dataProvider;		
        
		
		if (Yii::$app->request->isAjax)
			return $this->renderAjax('documentHistory', $renders);
		else
			return $this->render('documentHistory', $renders);
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
