<?php

namespace backend\modules\pusdiklat\planning\controllers;

use Yii;
use backend\models\ProgramSubject;
use backend\models\ProgramSubjectSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProgramSubjectController implements the CRUD actions for ProgramSubject model.
 */
class TrainingSubject3Controller extends Controller
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
     * Lists all ProgramSubject models.
     * @return mixed
     */
    public function actionIndex($tb_training_id,$status=1)
    {
		$model = \backend\models\Training::find($tb_training_id)->active()->one();
		if($model!=null){
			$tb_program_id = $model->tb_program_id;
			
			$searchModel = new ProgramSubjectSearch();
			$queryParams = Yii::$app->request->getQueryParams();			
			if($status!='all'){
				$queryParams['ProgramSubjectSearch']=[
					'tb_program_id'=>$tb_program_id,
					'status'=>$status,
				];
			}
			else{
				$queryParams['ProgramSubjectSearch']=[
					'tb_program_id'=>$tb_program_id,					
				];
			}
			$queryParams=yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
			$dataProvider = $searchModel->search($queryParams);
			
			$model1 = \backend\models\Program::findOne($tb_program_id);
			return $this->render('index', [
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
				'tb_training_id' => $tb_training_id,
				'status' => $status,
				'training_name'=>$model->name,
			]);		
		}
		else{
			return $this->redirect(['index']);
		}
    }

    /**
     * Displays a single ProgramSubject model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model=$this->findModel($id);
		return $this->render('view', [
            'model' => $model,
			'training_name'=>$model->name,
        ]);	
    }

    /**
     * Creates a new ProgramSubject model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($tb_program_id)
    {
        $model = new ProgramSubject();

        if ($model->load(Yii::$app->request->post())){
			$model->tb_program_id=$tb_program_id;
			if($model->save()) {
				 Yii::$app->session->setFlash('success', 'Data saved');
				// SAVE HISTORY OF PROGRAM SUBJECT
				$model2 = new \backend\models\ProgramSubjectHistory();
				$model2->attributes = array_merge(
				  $model->attributes,[
					'tb_program_subject_id'=>$model->id,
					'tb_program_id'=>$tb_program_id,
					'revision'=>\backend\models\ProgramHistory::getRevision($tb_program_id),					
				  ]
				);				
				$model2->save();
			}
			else{
				 Yii::$app->session->setFlash('error', 'Unable create there are some error');
			}
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $model1 = \backend\models\Program::findOne($tb_program_id);
            return $this->render('create', [
                'model' => $model,
				'tb_program_id' => $tb_program_id,
				'program_name'=>$model1->name,
            ]);
        }
    }

    /**
     * Updates an existing ProgramSubject model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id,$tb_program_id)
    {
        $model = $this->findModel($id);
        $currentFiles=[];
        
        if ($model->load(Yii::$app->request->post())) {
            $files=[];
			
            if($model->save()){
				$idx=0;
                foreach($files as $file){
					if(isset($paths[$idx])){
						$file->saveAs($paths[$idx]);
					}
					$idx++;
				}
				Yii::$app->session->setFlash('success', 'Data saved');
				
				// SAVE HISTORY OF PROGRAM SUBJECT
				$model2 = \backend\models\ProgramSubjectHistory::find()
								->where([
									'tb_program_subject_id'=>$model->id,
									'tb_program_id'=>$tb_program_id,
								])
								->orderBy(['revision'=>'DESC'])
								->one();
				$model2->attributes = array_merge($model->attributes);				
				$model2->save();
				
                return $this->redirect(['view', 'id' => $model->id, 'tb_program_id' => $tb_program_id]);
            } else {
                // error in saving model
				Yii::$app->session->setFlash('error', 'There are some errors');
            }            
        }
		else{
			$model1 = \backend\models\Program::findOne($tb_program_id);
            return $this->render('update', [
                'model' => $model,
				'tb_program_id' => $tb_program_id,
				'program_name'=>$model1->name,
            ]);
		}
    }

    /**
     * Deletes an existing ProgramSubject model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id,$tb_program_id)
    {
		$model=$this->findModel($id);
		try {
			if($model->delete()){
				// DROP ALL HISTORY OF PROGRAM SUBJECT
				\backend\models\ProgramSubjectHistory::deleteAll([
					'tb_program_subject_id'=>$model->id,
					'tb_program_id' => $tb_program_id,
					'revision' =>  \backend\models\ProgramHistory::getRevision($tb_program_id)
				]);
				Yii::$app->session->setFlash('success', 'Data has deleted');
			}
			else{
				Yii::$app->session->setFlash('warning', 'There are few errors');
			}
		} catch (\yii\base\ErrorException $e) {
			 Yii::$app->session->setFlash('error', 'There are some errors');
		}
        return $this->redirect(['index','tb_program_id'=>$tb_program_id]);
    }

    /**
     * Finds the ProgramSubject model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProgramSubject the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProgramSubject::findOne($id)) !== null) {
            if($model->program->ref_satker_id==Yii::$app->user->identity->employee->ref_satker_id){
				return $model;
			}
			else{
				throw new NotFoundHttpException('The requested page does not exist.');
			}
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	public function actionEditable() {
		$model = new ProgramSubject; // your model can be loaded here
		// Check if there is an Editable ajax request
		if (isset($_POST['hasEditable'])) {
			// read your posted model attributes
			if ($model->load($_POST)) {
				// read or convert your posted information
				$model2 = $this->findModel($_POST['editableKey']);
				$name=key($_POST['ProgramSubject'][$_POST['editableIndex']]);
				$value=$_POST['ProgramSubject'][$_POST['editableIndex']][$name];
				$model2->$name = $value ;
				$model2->save();
				// return JSON encoded output in the below format
				echo \yii\helpers\Json::encode(['output'=>$value, 'message'=>'']);
				// alternatively you can return a validation error
				// echo \yii\helpers\Json::encode(['output'=>'', 'message'=>'Validation error']);
			}
			// else if nothing to do always return an empty JSON encoded output
			else {
				echo \yii\helpers\Json::encode(['output'=>'', 'message'=>'']);
			}
		return;
		}
		// Else return to rendering a normal view
		return $this->render('view', ['model'=>$model]);
	}

	public function actionOpenTbs($filetype='docx'){
		$dataProvider = new ActiveDataProvider([
            'query' => ProgramSubject::find(),
        ]);
		
		try {
			$templates=[
				'docx'=>'ms-word.docx',
				'odt'=>'open-document.odt',
				'xlsx'=>'ms-excel.xlsx'
			];
			// Initalize the TBS instance
			$OpenTBS = new \hscstudio\heart\extensions\OpenTBS; // new instance of TBS
			// Change with Your template kaka
			$template = Yii::getAlias('@hscstudio/heart').'/extensions/opentbs-template/'.$templates[$filetype];
			$OpenTBS->LoadTemplate($template); // Also merge some [onload] automatic fields (depends of the type of document).
			$OpenTBS->VarRef['modelName']= "ProgramSubject";
			$data1[]['col0'] = 'id';			
			$data1[]['col1'] = 'tb_program_id';			
			$data1[]['col2'] = 'type';			
			$data1[]['col3'] = 'name';			
	
			$OpenTBS->MergeBlock('a', $data1);			
			$data2 = [];
			foreach($dataProvider->getModels() as $programsubject){
				$data2[] = [
					'col0'=>$programsubject->id,
					'col1'=>$programsubject->tb_program_id,
					'col2'=>$programsubject->type,
					'col3'=>$programsubject->name,
				];
			}
			$OpenTBS->MergeBlock('b', $data2);
			// Output the result as a file on the server. You can change output file
			$OpenTBS->Show(OPENTBS_DOWNLOAD, 'result.'.$filetype); // Also merges all [onshow] automatic fields.			
			exit;
		} catch (\yii\base\ErrorException $e) {
			 Yii::$app->session->setFlash('error', 'Unable export there are some error');
		}	
		
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);		
	}	
	
	public function actionPhpExcel($filetype='xlsx',$template='yes',$engine='')
    {
		$dataProvider = new ActiveDataProvider([
            'query' => ProgramSubject::find(),
        ]);
		
		try {
			if($template=='yes'){
				// only for filetypr : xls & xlsx
				if(in_array($filetype,['xlsx','xls'])){
					$types=['xls'=>'Excel5','xlsx'=>'Excel2007'];
					$objReader = \PHPExcel_IOFactory::createReader($types[$filetype]);
					$template = Yii::getAlias('@hscstudio/heart').'/extensions/phpexcel-template/ms-excel.'.$filetype;
					$objPHPExcel = $objReader->load($template);
					$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(\PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
					$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_FOLIO);
					$objPHPExcel->getProperties()->setTitle("PHPExcel in Yii2Heart");
					$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue('A1', 'Tabel ProgramSubject');
					$idx=2; // line 2
					foreach($dataProvider->getModels() as $programsubject){
						$objPHPExcel->getActiveSheet()->setCellValue('A'.$idx, $programsubject->id)
													  ->setCellValue('B'.$idx, $programsubject->tb_program_id)
													  ->setCellValue('C'.$idx, $programsubject->type)
													  ->setCellValue('D'.$idx, $programsubject->name);
						$idx++;
					}		
					
					// Redirect output to a client�s web browser
					header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
					header('Content-Disposition: attachment;filename="result.'.$filetype.'"');
					header('Cache-Control: max-age=0');
					$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, $types[$filetype]);
					$objWriter->save('php://output');
					exit;
				}
				else{
					Yii::$app->session->setFlash('error', 'Unfortunately pdf not support, only for excel');
				}
			}
			else{
				if(in_array($filetype,['xlsx','xls'])){
					$types=['xls'=>'Excel5','xlsx'=>'Excel2007'];
					// Create new PHPExcel object
					$objPHPExcel = new \PHPExcel();
					$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(\PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
					$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_FOLIO);
					$objPHPExcel->getProperties()->setTitle("PHPExcel in Yii2Heart");
					$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue('A1', 'Tabel ProgramSubject');
					$idx=2; // line 2
					foreach($dataProvider->getModels() as $programsubject){
						$objPHPExcel->getActiveSheet()->setCellValue('A'.$idx, $programsubject->id)
													  ->setCellValue('B'.$idx, $programsubject->tb_program_id)
													  ->setCellValue('C'.$idx, $programsubject->type)
													  ->setCellValue('D'.$idx, $programsubject->name);
						$idx++;
					}		
									
					// Redirect output to a client�s web browser (Excel2007)
					if($filetype=='xlsx')
					header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
					// Redirect output to a client�s web browser (Excel5)
					if($filetype=='xls')
					header('Content-Type: application/vnd.ms-excel');

					header('Content-Disposition: attachment;filename="result.'.$filetype.'"');
					header('Cache-Control: max-age=0');
					// If you're serving to IE 9, then the following may be needed
					header('Cache-Control: max-age=1');

					// If you're serving to IE over SSL, then the following may be needed
					header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
					header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
					header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
					header ('Pragma: public'); // HTTP/1.0

					$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, $types[$filetype]);
					$objWriter->save('php://output');
					exit;				
				}
				else if(in_array($filetype,['pdf'])){
					if(in_array($engine,['tcpdf','mpdf',''])){
						$types=['xls'=>'Excel5','xlsx'=>'Excel2007'];
						if($engine=='tcpdf' or $engine==''){
							$rendererName = \PHPExcel_Settings::PDF_RENDERER_TCPDF;
							$rendererLibraryPath = Yii::getAlias('@hscstudio/heart').'/libraries/tcpdf';
						}
						else if($engine=='mpdf'){
							$rendererName = \PHPExcel_Settings::PDF_RENDERER_MPDF;
							$rendererLibraryPath = Yii::getAlias('@hscstudio/heart').'/libraries/mpdf';
						}
						// Create new PHPExcel object
						$objPHPExcel = new \PHPExcel();
						
						$objPHPExcel->getProperties()->setTitle("PHPExcel in Yii2Heart");
						$objPHPExcel->setActiveSheetIndex(0)
									->setCellValue('A1', 'Tabel ProgramSubject');
						$idx=2; // line 2
						foreach($dataProvider->getModels() as $programsubject){
							$objPHPExcel->getActiveSheet()->setCellValue('A'.$idx, $programsubject->id)
														  ->setCellValue('B'.$idx, $programsubject->tb_program_id)
														  ->setCellValue('C'.$idx, $programsubject->type)
														  ->setCellValue('D'.$idx, $programsubject->name);
							$idx++;
						}		
						
						if (!\PHPExcel_Settings::setPdfRenderer(
							$rendererName,
							$rendererLibraryPath
						)){
							Yii::$app->session->setFlash('error', 
								'NOTICE: Please set the $rendererName and $rendererLibraryPath values' .
								'<br />' .
								'at the top of this script as appropriate for your directory structure'
							);
						}
						else{
							// Redirect output to a client�s web browser (PDF)
							header('Content-Type: application/pdf');
							header('Content-Disposition: attachment;filename="result.pdf"');
							header('Cache-Control: max-age=0');

							$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
							$objWriter->save('php://output');
							exit;
						}
					}
					else{
						Yii::$app->session->setFlash('error', 'Unfortunately this engine not support');
					}
				}
				else{
					Yii::$app->session->setFlash('error', 'Unfortunately filetype not support, only for excel & pdf');
				}
			}
        } catch (\yii\base\ErrorException $e) {
			 Yii::$app->session->setFlash('error', 'Unable export there are some error');
		}	
		
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);	
    }
	
	public function actionImport(){
		$dataProvider = new ActiveDataProvider([
            'query' => ProgramSubject::find(),
        ]);
		
		/* 
		Please read guide of upload https://github.com/yiisoft/yii2/blob/master/docs/guide/input-file-upload.md
		maybe I do mistake :)
		*/		
		if (!empty($_FILES)) {
			$importFile = \yii\web\UploadedFile::getInstanceByName('importFile');
			if(!empty($importFile)){
				$fileTypes = ['xls','xlsx']; // File extensions allowed
				//$ext = end((explode(".", $importFile->name)));
				$ext=$importFile->extension;
				if(in_array($ext,$fileTypes)){
					$inputFileType = \PHPExcel_IOFactory::identify($importFile->tempName );
					$objReader = \PHPExcel_IOFactory::createReader($inputFileType);
					$objPHPExcel = $objReader->load($importFile->tempName );
					$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
					$baseRow = 2;
					$inserted=0;
					$read_status = false;
					$err=[];
					while(!empty($sheetData[$baseRow]['A'])){
						$read_status = true;
						$abjadX=array();
						//$id=  $sheetData[$baseRow]['A'];
						$tb_program_id=  $sheetData[$baseRow]['B'];
						$type=  $sheetData[$baseRow]['C'];
						$name=  $sheetData[$baseRow]['D'];
						$hours=  $sheetData[$baseRow]['E'];
						$sort=  $sheetData[$baseRow]['F'];
						$test=  $sheetData[$baseRow]['G'];
						$status=  $sheetData[$baseRow]['H'];
						//$created=  $sheetData[$baseRow]['I'];
						//$createdBy=  $sheetData[$baseRow]['J'];
						//$modified=  $sheetData[$baseRow]['K'];
						//$modifiedBy=  $sheetData[$baseRow]['L'];
						//$deleted=  $sheetData[$baseRow]['M'];
						//$deletedBy=  $sheetData[$baseRow]['N'];

						$model2=new ProgramSubject;
						//$model2->id=  $id;
						$model2->tb_program_id=  $tb_program_id;
						$model2->type=  $type;
						$model2->name=  $name;
						$model2->hours=  $hours;
						$model2->sort=  $sort;
						$model2->test=  $test;
						$model2->status=  $status;
						//$model2->created=  $created;
						//$model2->createdBy=  $createdBy;
						//$model2->modified=  $modified;
						//$model2->modifiedBy=  $modifiedBy;
						//$model2->deleted=  $deleted;
						//$model2->deletedBy=  $deletedBy;

						try{
							if($model2->save()){
								$inserted++;
							}
							else{
								foreach ($model2->errors as $error){
									$err[]=($baseRow-1).'. '.implode('|',$error);
								}
							}
						}
						catch (\yii\base\ErrorException $e){
							Yii::$app->session->setFlash('error', "{$e->getMessage()}");
							//$this->refresh();
						} 
						$baseRow++;
					}	
					Yii::$app->session->setFlash('success', ($inserted).' row inserted');
					if(!empty($err)){
						Yii::$app->session->setFlash('warning', 'There are error: <br>'.implode('<br>',$err));
					}
				}
				else{
					Yii::$app->session->setFlash('error', 'Filetype allowed only xls and xlsx');
				}				
			}
			else{
				Yii::$app->session->setFlash('error', 'File import empty!');
			}
		}
		else{
			Yii::$app->session->setFlash('error', 'File import empty!');
		}
		
		return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);					
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
        $tb_program_id = $model->tb_program_id;
		
		$status = ($status==1)?0:1;
		$model->status = $status;
		$model->save();
		
		$searchModel = new ProgramSubjectSearch();
		$queryParams = Yii::$app->request->getQueryParams();			
		if($status!='all'){
			$queryParams['ProgramSubjectSearch']=[
				'tb_program_id'=>$tb_program_id,
				'status'=>$status,
			];
		}
		else{
			$queryParams['ProgramSubjectSearch']=[
				'tb_program_id'=>$tb_program_id,					
			];
		}
		$queryParams=yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
		$dataProvider = $searchModel->search($queryParams);
		
		$model1 = \backend\models\Program::findOne($tb_program_id);
		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'tb_program_id' => $tb_program_id,
			'status' => $status,
			'program_name' => $model1->name,
		]);
		
    }
}
