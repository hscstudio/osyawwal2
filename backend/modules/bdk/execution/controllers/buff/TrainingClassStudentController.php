<?php

namespace backend\modules\pusdiklat\execution\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use backend\models\TrainingClassStudent;
use backend\models\TrainingClassStudentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TrainingClassStudentController implements the CRUD actions for TrainingClassStudent model.
 */
class TrainingClassStudentController extends Controller
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
     * Lists all TrainingClassStudent models.
     * @return mixed
     */
    public function actionIndex($tb_training_id,$tb_training_class_id=0)
    {
		$trainingClass = \backend\models\TrainingClass::find()
			->where(['tb_training_id'=>$tb_training_id])
			->orderBy('id ASC')
			->all();
		$listTrainingClass = [];	
		foreach($trainingClass as $ts){
			if($tb_training_class_id==0){
				$tb_training_class_id=$ts->id;
			}
			$listTrainingClass[$ts->id]=$ts->class;
		}
		
		if($tb_training_class_id==0){
			Yii::$app->session->setFlash('warning', 'Anda harus membuat kelas terlebih dulu!');
			return $this->redirect([
				'./training-class/index', 'tb_training_id' => $tb_training_id
			]);
		}
		
        $searchModel = new TrainingClassStudentSearch();
		$queryParams = Yii::$app->request->getQueryParams();
		$queryParams['TrainingClassStudentSearch']=[
					'tb_training_class_id' => $tb_training_class_id,
				];
        $queryParams=yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
		$dataProvider = $searchModel->search($queryParams);
		//$dataProvider->getSort()->defaultOrder = ['start'=>SORT_ASC,'finish'=>SORT_ASC];
		
		$training = \backend\models\Training::findOne($tb_training_id);
		
		
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'training'=>$training,
			'listTrainingClass'=>$listTrainingClass,
			'tb_training_id'=>$tb_training_id,
			'tb_training_class_id'=>$tb_training_class_id,
        ]);
    }

    /**
     * Displays a single TrainingClassStudent model.
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
     * Creates a new TrainingClassStudent model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TrainingClassStudent();

        if ($model->load(Yii::$app->request->post())){
			if($model->save()) {
				 Yii::$app->session->setFlash('success', 'Data saved');
			}
			else{
				 Yii::$app->session->setFlash('error', 'Unable create there are some error');
			}
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TrainingClassStudent model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
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
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                // error in saving model
				Yii::$app->session->setFlash('error', 'There are some errors');
            }            
        }
		else{
			//return $this->render(['update', 'id' => $model->id]);
			return $this->render('update', [
                'model' => $model,
            ]);
		}
    }

    /**
     * Deletes an existing TrainingClassStudent model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TrainingClassStudent model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TrainingClassStudent the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TrainingClassStudent::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	public function actionEditable() {
		$model = new TrainingClassStudent; // your model can be loaded here
		// Check if there is an Editable ajax request
		if (isset($_POST['hasEditable'])) {
			// read your posted model attributes
			if ($model->load($_POST)) {
				// read or convert your posted information
				$model2 = $this->findModel($_POST['editableKey']);
				$name=key($_POST['TrainingClassStudent'][$_POST['editableIndex']]);
				$value=$_POST['TrainingClassStudent'][$_POST['editableIndex']][$name];
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
            'query' => TrainingClassStudent::find(),
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
			$OpenTBS->VarRef['modelName']= "TrainingClassStudent";
			$data1[]['col0'] = 'id';			
			$data1[]['col1'] = 'tb_training_class_id';			
			$data1[]['col2'] = 'tb_student_id';			
			$data1[]['col3'] = 'number';			
	
			$OpenTBS->MergeBlock('a', $data1);			
			$data2 = [];
			foreach($dataProvider->getModels() as $trainingclassstudent){
				$data2[] = [
					'col0'=>$trainingclassstudent->id,
					'col1'=>$trainingclassstudent->tb_training_class_id,
					'col2'=>$trainingclassstudent->tb_student_id,
					'col3'=>$trainingclassstudent->number,
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
            'query' => TrainingClassStudent::find(),
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
								->setCellValue('A1', 'Tabel TrainingClassStudent');
					$idx=2; // line 2
					foreach($dataProvider->getModels() as $trainingclassstudent){
						$objPHPExcel->getActiveSheet()->setCellValue('A'.$idx, $trainingclassstudent->id)
													  ->setCellValue('B'.$idx, $trainingclassstudent->tb_training_class_id)
													  ->setCellValue('C'.$idx, $trainingclassstudent->tb_student_id)
													  ->setCellValue('D'.$idx, $trainingclassstudent->number);
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
								->setCellValue('A1', 'Tabel TrainingClassStudent');
					$idx=2; // line 2
					foreach($dataProvider->getModels() as $trainingclassstudent){
						$objPHPExcel->getActiveSheet()->setCellValue('A'.$idx, $trainingclassstudent->id)
													  ->setCellValue('B'.$idx, $trainingclassstudent->tb_training_class_id)
													  ->setCellValue('C'.$idx, $trainingclassstudent->tb_student_id)
													  ->setCellValue('D'.$idx, $trainingclassstudent->number);
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
									->setCellValue('A1', 'Tabel TrainingClassStudent');
						$idx=2; // line 2
						foreach($dataProvider->getModels() as $trainingclassstudent){
							$objPHPExcel->getActiveSheet()->setCellValue('A'.$idx, $trainingclassstudent->id)
														  ->setCellValue('B'.$idx, $trainingclassstudent->tb_training_class_id)
														  ->setCellValue('C'.$idx, $trainingclassstudent->tb_student_id)
														  ->setCellValue('D'.$idx, $trainingclassstudent->number);
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
	
	public function actionImport($tb_training_id,$tb_training_class_id){	
		$dataProvider = new ActiveDataProvider([
            'query' => TrainingClassStudent::find(),
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
					while(!empty($sheetData[$baseRow]['B'])){
						$read_status = true;
						$abjadX=[];
						// GET DATA FROM EXCEL
						$name = trim($sheetData[$baseRow]['B']);
						$nip = str_replace(' ','',trim($sheetData[$baseRow]['C']));
						
						
						$ref_religion_id = '0';
						$ref_graduate_id = '0';
						$ref_rank_class_id = '0';
						$ref_unit_id  = '0';
						// PROVIDE DATA FROM NIP
						$birthDay = '';
						$gender = '';
						$status = 1;
						
						if(strlen($nip)==18){
							$birthDay = substr($nip,0,4) .'-'. substr($nip,4,2) .'-'. substr($nip,6,2);
							$gender = substr($nip,14,1);
						}
						// CHECK STUDENT EXIST
						$tb_student_id = 0;
						$studentExist = \backend\models\Student::find()
							->where([
								'nip'=>$nip,
							])->one();
						
						if(null!=$studentExist){
							$tb_student_id = $studentExist->id;
						}
						else{
							// SAVE STUDENT
							$student=new \backend\models\Student;
							$student->ref_religion_id=$ref_religion_id;
							$student->ref_graduate_id=$ref_graduate_id;
							$student->ref_rank_class_id=$ref_rank_class_id;
							$student->ref_unit_id=$ref_unit_id;
							
							$student->name=$name;
							$student->nip=$nip;
							$student->birthDay=$birthDay;
							$student->gender=$gender;
							$student->status=$status;
							
							$student->setPassword($nip);
							$student->generateAuthKey();
							$student->save();
							$tb_student_id = $student->id; 
						}
						
						$trainingClassStudent = \backend\models\TrainingClassStudent::find()
							->where([
								'tb_training_class_id' =>  $tb_training_class_id,
								'tb_student_id' => $tb_student_id,
							])->one();
						
						if(null!=$trainingClassStudent ){
						
						}
						else{
							// SAVE STUDENT TO TRAINING CLASS STUDENT
							$trainingClassStudent=new \backend\models\TrainingClassStudent;
							$trainingClassStudent->tb_training_class_id =  $tb_training_class_id;
							$trainingClassStudent->tb_training_id =  $tb_training_id;
							$trainingClassStudent->tb_student_id =  $tb_student_id;
							$trainingClassStudent->status=  $status;
							$trainingClassStudent->save();
							$inserted++;
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
		
		return $this->redirect(['index',
            'tb_training_id'=>$tb_training_id,
			'tb_training_class_id'=>$tb_training_class_id,
        ]);					
	}
}
