<?php

namespace backend\modules\pusdiklat2\competency\controllers\evaluation;

use Yii;
use backend\models\Activity;
use backend\models\TrainingClass;
use backend\models\TrainingClassSubject;
use backend\models\TrainingClassStudent;
use backend\models\TrainingSchedule;
use backend\models\TrainingScheduleTrainer;
use backend\models\Person;
use backend\models\Reference;
use backend\modules\pusdiklat2\competency\models\evaluation\ActivitySearch;
use backend\modules\pusdiklat2\competency\models\evaluation\TrainingClassSubjectSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\File;
use hscstudio\heart\helpers\Heart;
use yii\data\ActiveDataProvider;

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
	
	public function actionTrainingTrainerList($id)
    {
        return $this->render('trainingTrainerList', [
            'model' => $this->findModel($id),
        ]);
    }
	
	public function actionTrainingTrainerListExcel($id,$status='nocancel',$filetype='xlsx')
    {
		$kelas=$_POST['class'];
		$searchModel = new TrainingClassSubjectSearch();
		$queryParams = Yii::$app->request->getQueryParams();
		if($status!='all'){
			$queryParams['TrainingClassSubjectSearch']=[
				'training_class_id' => $kelas,
			];
		}
		else
		{
			$queryParams['TrainingClassSubjectSearch']=[
				'training_class_id' => $kelas,
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
			DIRECTORY_SEPARATOR.'evaluation'.DIRECTORY_SEPARATOR.'template.data.pengajar.materi.diklat.'.$filetype;
		$objPHPExcel = $objReader->load($template);
		//$objPHPExcel->getProperties()->setTitle("Daftar Program");
		$objPHPExcel->setActiveSheetIndex(0);
		////////////Mulai//////////
		$objPHPExcel->getProperties()->setCreator("Hafid Mukhlasin")
							 ->setLastModifiedBy("Hafid Mukhlasin")
							 ->setTitle("Data Pengajar & Materi Diklat")
							 ->setSubject("-")
							 ->setDescription("-")
							 ->setKeywords("-")
							 ->setCategory("-");
		$idx=0;
		$baseRow = 6;					 
		foreach($dataProvider->getModels() as $data){
			$row = $baseRow + $idx;
			if($idx==0){
				$objPHPExcel->getActiveSheet()->setCellValue('A1', "Data Pengajar & Materi Diklat");
				$objPHPExcel->getActiveSheet()->setCellValue('A2', $data->trainingClass->training->activity->name);
				$objPHPExcel->getActiveSheet()->setCellValue('A3', strtoupper("Tahun Anggaran ".date('Y',strtotime($data->trainingClass->training->activity->start))));
			}
			
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $data->programSubject->name." ")
	                              	      ->setCellValue('C'.$row, $data->programSubject->hours." JP");
			
			$trainer = TrainingScheduleTrainer::find()
													->where(['training_schedule_id'=>
															 TrainingSchedule::find()
															 		->select('id')
															 		->where(['training_class_subject_id'=>$data->id])
																	->groupBy('training_class_subject_id')																	
																	]);						
						
			$trainer_count = $trainer->count();
			if($trainer_count==0)
			{$objPHPExcel->getActiveSheet()->setCellValue('D'.$row,"-");}
			else
			{
				$idx2=0;
				$datatrainer = TrainingScheduleTrainer::find()
						->joinWith('trainingSchedule')
						->where(['training_class_subject_id'=>$data->id])
						->groupBy('training_class_subject_id')
						->asArray()
						->all();
				foreach($datatrainer as $data_trainer){
					if($idx2>0) { 
						$row += 1; 
						$idx++; 
					} 
					
					$objPHPExcel->getActiveSheet()->setCellValue('D'.$row, Person::findOne(['id'=>$data_trainer['trainer_id']])->name." ");
					$objPHPExcel->getActiveSheet()->setCellValue('E'.$row, Reference::findOne(['id'=>$data_trainer['type']])->name." ");
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$row, Person::findOne(['id'=>$data_trainer['trainer_id']])->phone." ");
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$row, Person::findOne(['id'=>$data_trainer['trainer_id']])->email." ");
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$row, Person::findOne(['id'=>$data_trainer['trainer_id']])->organisation." ");
					$objPHPExcel->getActiveSheet()->setCellValue('I'.$row, Person::findOne(['id'=>$data_trainer['trainer_id']])->address." ");
					$objPHPExcel->getActiveSheet()->setCellValue('J'.$row, Person::findOne(['id'=>$data_trainer['trainer_id']])->office_address." - ".Person::findOne(['id'=>$data_trainer['trainer_id']])->office_phone);
					$idx2++;
				}
			}
			$idx++;
		}
	
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);

		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="template.data.pengajar.materi.diklat.xlsx"');
		header('Cache-Control: max-age=0');
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, $types[$filetype]);
		$objWriter->save('php://output');
		exit;
    }
	
	public function actionDailyTrainingMonitoring($id)
    {
        return $this->render('dailyTrainingMonitoring', [
            'model' => $this->findModel($id),
        ]);
    }
	
	public function actionDailyTrainingMonitoringExcel($id,$status='nocancel',$filetype='xlsx')
    {
        $kelas=$_POST['class'];
		//////////////////////////////////////////////////////////////////////////////////////////////////////		
		$types=['xls'=>'Excel5','xlsx'=>'Excel2007'];
		$objReader = \PHPExcel_IOFactory::createReader($types[$filetype]);
		$template = Yii::getAlias('@file').DIRECTORY_SEPARATOR.'template'.DIRECTORY_SEPARATOR.'pusdiklat'.
			DIRECTORY_SEPARATOR.'evaluation'.DIRECTORY_SEPARATOR.'template.rekap.monitoring.harian.'.$filetype;
		$objPHPExcel = $objReader->load($template);
		//$objPHPExcel->getProperties()->setTitle("Daftar Program");
		$objPHPExcel->setActiveSheetIndex(0);
		////////////Mulai//////////////////////////////////////////////////////////////////////////////////////
		$searchModel = new ActivitySearch();
		$queryParams = Yii::$app->request->getQueryParams();
		$queryParams['ActivitySearch']=[
				'id' => $id,
			];
		
		$queryParams=yii\helpers\ArrayHelper::merge(Yii::$app->request->getQueryParams(),$queryParams);
        $dataProvider = $searchModel->search($queryParams);        
		$dataProvider->getSort()->defaultOrder = [
			'status'=>SORT_DESC,		
		];
		$dataProvider->setPagination(false);
		foreach($dataProvider->getModels() as $data){
			$name_training_capital = strtoupper($data->name);
			$satker = strtoupper(Reference::findOne(['id'=>$data->satker_id])->name);
			$year_training = date('Y',strtotime($data->start));
			$lokasi = explode('|',$data->location);
			$lokasi_diklat = strtoupper(Reference::findOne(['id'=>$lokasi[0]])->name);
			$date_exec = $data->start.' s.d '.$data->end;
			$count_student = TrainingClassStudent::find()->where(['training_id'=>$data->id,'training_class_id'=>$kelas])->count();
		}
		///////////////////////////////////////////////////////////////////////////////////////////////////////
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setCellValue('B3', $satker );
		$objPHPExcel->getActiveSheet()->setCellValue('A6', $name_training_capital );
		$objPHPExcel->getActiveSheet()->setCellValue('A7', 'TAHUN ANGGARAN '.$year_training );
		$objPHPExcel->getActiveSheet()->setCellValue('D9', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$lokasi_diklat );
		$objPHPExcel->getActiveSheet()->setCellValue('D10', $date_exec );
		$objPHPExcel->getActiveSheet()->setCellValue('D11', $count_student );
		
		$objPHPExcel->setActiveSheetIndex(1);
		$objPHPExcel->getActiveSheet()->setCellValue('B3', $satker );
		$objPHPExcel->getActiveSheet()->setCellValue('A6', $name_training_capital );
		$objPHPExcel->getActiveSheet()->setCellValue('A7', 'TAHUN ANGGARAN '.$year_training );
		$objPHPExcel->getActiveSheet()->setCellValue('D9', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$lokasi_diklat );
		$objPHPExcel->getActiveSheet()->setCellValue('D10', $date_exec );
		$objPHPExcel->getActiveSheet()->setCellValue('D11', $count_student );
		$objPHPExcel->getActiveSheet()->setCellValue('D38', "Jakarta,                                 ".date("Y") );
		$objPHPExcel->getActiveSheet()->setCellValue('D42', '' );
		
		$objPHPExcel->setActiveSheetIndex(2);
		$objPHPExcel->getActiveSheet()->setCellValue('B3', $satker );
		$objPHPExcel->getActiveSheet()->setCellValue('A6', $name_training_capital );
		$objPHPExcel->getActiveSheet()->setCellValue('A7', 'TAHUN ANGGARAN '.$year_training );
		$objPHPExcel->getActiveSheet()->setCellValue('C24', "Jakarta,                                 ".date("Y") );
		$objPHPExcel->getActiveSheet()->setCellValue('C29', '' );
		
		//$objPHPExcel->getActiveSheet()->setCellValue('B3', '             ' );

		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="rekap_monitoring_diklat_harian.xlsx"');
		header('Cache-Control: max-age=0');
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, $types[$filetype]);
		$objWriter->save('php://output');
		exit;
    }
	
	public function actionTrainerEvaluationEnvelope($id)
    {
        return $this->render('trainerEvaluationEnvelope', [
            'model' => $this->findModel($id),
        ]);
    }
	
	public function actionTrainerEvaluationEnvelopeWord($id,$filetype='docx')
    {
        $kelas=$_POST['class'];
		$dataProvider = new ActiveDataProvider([
            'query' => TrainingScheduleTrainer::find()
												->where(['training_schedule_id'=>
														 TrainingSchedule::find()
														 	->select('id')
															->where(['training_class_id'=>$kelas])
														 ])
												->groupBy('trainer_id'),
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
			$template = Yii::getAlias('@file').'/template/pusdiklat/evaluation/template.amplop.evaluasi.pengajar.docx';
			
			$OpenTBS->LoadTemplate($template); // Also merge some [onload] automatic fields (depends of the type of document).
			$OpenTBS->VarRef['modelName']= "ActivityGenerate";
			$data1[] = [
						'name_training' => Activity::findOne(['id'=>$id])->name,
						'year_training' => date('Y',strtotime(Activity::findOne(['id'=>$id])->start)),
					];
	
			$OpenTBS->MergeBlock('onshow', $data1);	
			$idx=0;
			$data = [];
			foreach($dataProvider->getModels() as $trainer){
				$data[] = [
					'name_trainer'=>strtoupper($trainer->trainer->person->name),
					'wi'=>$trainer->trainer->person->position_desc,
					'address_trainer'=>$trainer->trainer->person->office_address,					
				];
				$idx++;
			}
			$OpenTBS->MergeBlock('data', $data);
			// Output the result as a file on the server. You can change output file
			$OpenTBS->Show(OPENTBS_DOWNLOAD, 'amplop.evaluasi.pengajar.'.$filetype); // Also merges all [onshow] automatic fields.			
			exit;
		} catch (\yii\base\ErrorException $e) {
			 Yii::$app->session->setFlash('error', 'Unable export there are some error');
		}	
    }
	
	public function actionLetterAssignmentWord($id)
    {
        $kelas=$_POST['class'];
		$dataProvider = new ActiveDataProvider([
            'query' => TrainingScheduleTrainer::find()
												->where(['training_schedule_id'=>
														 TrainingSchedule::find()
														 	->select('id')
															->where(['training_class_id'=>$kelas])
														 ])
												->groupBy('trainer_id'),
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
			$template = Yii::getAlias('@file').'/template/pusdiklat/evaluation/template.amplop.evaluasi.pengajar.docx';
			
			$OpenTBS->LoadTemplate($template); // Also merge some [onload] automatic fields (depends of the type of document).
			$OpenTBS->VarRef['modelName']= "ActivityGenerate";
			$data1[] = [
						'name_training' => Activity::findOne(['id'=>$id])->name,
						'year_training' => date('Y',strtotime(Activity::findOne(['id'=>$id])->start)),
					];
	
			$OpenTBS->MergeBlock('onshow', $data1);	
			$idx=0;
			$data = [];
			foreach($dataProvider->getModels() as $trainer){
				$data[] = [
					'name_trainer'=>strtoupper($trainer->trainer->person->name),
					'wi'=>$trainer->trainer->person->position_desc,
					'address_trainer'=>$trainer->trainer->person->office_address,					
				];
				$idx++;
			}
			$OpenTBS->MergeBlock('data', $data);
			// Output the result as a file on the server. You can change output file
			$OpenTBS->Show(OPENTBS_DOWNLOAD, 'amplop.evaluasi.pengajar.'.$filetype); // Also merges all [onshow] automatic fields.			
			exit;
		} catch (\yii\base\ErrorException $e) {
			 Yii::$app->session->setFlash('error', 'Unable export there are some error');
		}	
    }
}
