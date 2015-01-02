<?php

namespace backend\modules\pusdiklat\evaluation\controllers;

use Yii;
use backend\models\Activity;
use backend\models\TrainingClass;
use backend\models\TrainingClassSubject;
use backend\models\TrainingClassStudent;
use backend\models\TrainingClassStudentCertificate;
use backend\models\TrainingSchedule;
use backend\models\TrainingScheduleTrainer;
use backend\models\Person;
use backend\models\ObjectReference;
use backend\models\Satker;
use backend\models\Room;
use backend\models\Reference;
use backend\modules\pusdiklat\evaluation\models\ActivitySearch;
use backend\modules\pusdiklat\evaluation\models\TrainingClassSubjectSearch;
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
		$objPHPExcel->getActiveSheet()->setCellValue('B3', "             ".$satker );
		$objPHPExcel->getActiveSheet()->setCellValue('A6', $name_training_capital );
		$objPHPExcel->getActiveSheet()->setCellValue('A7', 'TAHUN ANGGARAN '.$year_training );
		$objPHPExcel->getActiveSheet()->setCellValue('D9', $lokasi_diklat );
		$objPHPExcel->getActiveSheet()->setCellValue('D10', $date_exec );
		$objPHPExcel->getActiveSheet()->setCellValue('D11', $count_student );
		
		$objPHPExcel->setActiveSheetIndex(1);
		$objPHPExcel->getActiveSheet()->setCellValue('B3', "             ".$satker );
		$objPHPExcel->getActiveSheet()->setCellValue('A6', $name_training_capital );
		$objPHPExcel->getActiveSheet()->setCellValue('A7', 'TAHUN ANGGARAN '.$year_training );
		$objPHPExcel->getActiveSheet()->setCellValue('D9', $lokasi_diklat );
		$objPHPExcel->getActiveSheet()->setCellValue('D10', $date_exec );
		$objPHPExcel->getActiveSheet()->setCellValue('D11', $count_student );
		$objPHPExcel->getActiveSheet()->setCellValue('D38', "Jakarta,                                 ".date("Y") );
		$objPHPExcel->getActiveSheet()->setCellValue('D42', '' );
		
		$objPHPExcel->setActiveSheetIndex(2);
		$objPHPExcel->getActiveSheet()->setCellValue('B3', "             ".$satker );
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
	
	public function actionLetterAssignmentWord($id,$filetype='docx')
    {
        $ruang = Yii::$app->request->post()['ruang'];
		$ttd = Yii::$app->request->post()['ttd'];
		$tugas = Yii::$app->request->post()['tugas'];
		$satker = Yii::$app->user->identity->employee->satker_id;
		$admin = Yii::$app->request->post()['admin'];
		$admins = implode(",", $admin);
		$start = Yii::$app->request->post()['start'];
		$finish = Yii::$app->request->post()['finish'];
		$days = array('Minggu','Senin','Selasa','Rabu','Kamis','Jum\'at','Sabtu');
		$months = array('Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
		$month = $months[date("m")-1];
		
		/*echo Room::findOne(['id'=>$ruang])->name."<br>";
		echo $tugas."<br>";
		echo Person::findOne(['id'=>$ttd])->name." - ".Person::findOne(['id'=>$ttd])->nip."<br>";
		for($i=0;$i<=count($admin)-1;$i++){
			echo Person::findOne(['id'=>$admin[$i]])->name." - ".Person::findOne(['id'=>$admin[$i]])->nip." - ".$start[Person::findOne(['id'=>$admin[$i]])->id]." - ".$finish[Person::findOne(['id'=>$admin[$i]])->id]."<br>";
			
			//echo $admin[$i]."<br>";
		}*/
		//////////////////////////
		try {
			$templates=[
				'docx'=>'ms-word.docx',
				'odt'=>'open-document.odt',
				'xlsx'=>'ms-excel.xlsx'
			];
			// Initalize the TBS instance
			$OpenTBS = new \hscstudio\heart\extensions\OpenTBS; // new instance of TBS
			// Change with Your template kaka
			$template = Yii::getAlias('@file').'/template/pusdiklat/evaluation/template.surat.tugas.terkait.diklat.docx';
			
			$OpenTBS->LoadTemplate($template); // Also merge some [onload] automatic fields (depends of the type of document).
			$OpenTBS->VarRef['modelName']= "ActivityGenerate";
			$data1[] = [
						'name_execution_long_titlecase' => Person::findOne(['id'=>$ttd])->position_desc,
						'name_execution_head' =>strtoupper(Reference::findOne(['id'=>$satker])->name),
						'address_execution' => Satker::findOne(['reference_id'=>$satker])->address,
						'phone_execution' => Satker::findOne(['reference_id'=>$satker])->phone,
						'fax_execution' => Satker::findOne(['reference_id'=>$satker])->fax,
						'web_execution' => Satker::findOne(['reference_id'=>$satker])->website,
						'assignments' => $tugas,
						'name_training' =>Activity::findOne(['id'=>$id])->name,
						'location_training' =>$ruang,
						'year_training' =>date('Y',strtotime(Activity::findOne(['id'=>$id])->start)),
						'month' =>date('M'),
						'year' =>date('Y'),
						'position_signature' =>'Kepala '.Person::findOne(['id'=>$ttd])->position_desc,
						'name_signature' =>Person::findOne(['id'=>$ttd])->name,
						'nip_signature' =>Person::findOne(['id'=>$ttd])->nip,
						'tembusan' =>'Kepala Bagian Tata Usaha',
						'no_execution'=> Satker::findOne(['reference_id'=>$satker])->letter_number,
					];
	
			$OpenTBS->MergeBlock('onshow', $data1);	
			//$idx=0;
			$data = [];
			$data2 = [];
			for($i=0;$i<=count($admin)-1;$i++){
				$data[] = [
					'no'=>$i+1,
					'name_admin'=>strtoupper(Person::findOne(['id'=>$admin[$i]])->name),
					'nip_admin'=>Person::findOne(['id'=>$admin[$i]])->nip,
					'position_admin'=>Person::findOne(['id'=>$admin[$i]])->position_desc,
					'date'=>$start[Person::findOne(['id'=>$admin[$i]])->id]." - ".$finish[Person::findOne(['id'=>$admin[$i]])->id],					
				];
				$data2[] = [
					'no'=>$i+1,
					'name_admin'=>strtoupper(Person::findOne(['id'=>$admin[$i]])->name),
					//'nip_admin'=>Person::findOne(['id'=>$admin[$i]])->nip,
					//'position_admin'=>Person::findOne(['id'=>$admin[$i]])->position_desc,
					'tanggal'=>'',					
				];
			}
			
			$OpenTBS->MergeBlock('data', $data);
			$OpenTBS->MergeBlock('data2', $data2);
			// Output the result as a file on the server. You can change output file
			$OpenTBS->Show(OPENTBS_DOWNLOAD, 'surat.tugas.terkait.diklat.'.$filetype); // Also merges all [onshow] automatic fields.			
			exit;
		} catch (\yii\base\ErrorException $e) {
			 Yii::$app->session->setFlash('error', 'Unable export there are some error');
		}		
		
    }
	
	public function actionMapelku($id){
		$model = TrainingScheduleTrainer::findOne(['trainer_id'=>$id])->trainingSchedule->trainingClassSubject->programSubject->name;
		
		return $model;
	}
	
	public function actionFormStudentEvaluationExcel($id,$filetype='xlsx')
    {
        $kelas = Yii::$app->request->post()['class'];
		$jenis_form = Yii::$app->request->post()['jenis_form'];
		$tanggal = Yii::$app->request->post()['tanggal'];
		$waktu = Yii::$app->request->post()['waktu'];
		$trainer = Yii::$app->request->post()['trainer'];
		$mapel = Yii::$app->request->post()['mapel'];
		$days = array('Minggu','Senin','Selasa','Rabu','Kamis','Jum\'at','Sabtu');
		$months = array('Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
		/////////EXCELL///////////
		$types=['xls'=>'Excel5','xlsx'=>'Excel2007'];
		$objReader = \PHPExcel_IOFactory::createReader($types[$filetype]);
		$template = Yii::getAlias('@file').DIRECTORY_SEPARATOR.'template'.DIRECTORY_SEPARATOR.'pusdiklat'.
			DIRECTORY_SEPARATOR.'evaluation'.DIRECTORY_SEPARATOR.'template.evaluasi.peserta.'.$filetype;
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
		$objPHPExcel->getActiveSheet()->setCellValue('A1', $jenis_form=0?'NILAI AKTIFITAS PESERTA':'NILAI UJIAN PESERTA');
		$objPHPExcel->getActiveSheet()->setCellValue('A2', $name_training_capital." Kelas ".TrainingClass::findOne(['id'=>$kelas])->class);
		$objPHPExcel->getActiveSheet()->setCellValue('A3', 'TAHUN ANGGARAN '.$year_training );
		////
		//$date = normalizedate($_POST['date'],0);
		$tgl = $days[date("w",strtotime($tanggal))].", ".date("j",strtotime($tanggal))." ".$months[date("n",strtotime($tanggal))-1]." ".date("Y",strtotime($tanggal));
		$objPHPExcel->getActiveSheet()->setCellValue('D5',$tgl )
									  ->setCellValue('D6',$waktu)
									  ->setCellValue('D7',$mapel)
									  ->setCellValue('G12', 'Jakarta,   - '.$months[date("n",strtotime($tanggal))-1].' - '.date("Y",strtotime($tanggal)) )
									  ->setCellValue('G17',"( ".Person::findOne(['id'=>$trainer])->name." )");
		///////////Student////////
		$idx=0;
		$baseRow = 10;
		
		$data_student = TrainingClassStudent::find()
						->where(['training_id'=>$id,'training_class_id'=>$kelas])
						//->asArray()
						->all();
				foreach($data_student as $student){					
					$row = $baseRow + $idx;
					if($idx!==0) $objPHPExcel->getActiveSheet()->insertNewRowBefore($row,1);
					$nip_student=$student->trainingStudent->student->person->nip;
					//198604 30 198604 
					if(substr($nip_student,0,6)==substr($nip_student,8,6)){
						$nip_student="-";
					}
					$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $idx+1)
	                              ->setCellValue('B'.$row, stripslashes($student->trainingStudent->student->person->name))
	                              ->setCellValue('E'.$row, $nip_student.' ')
	                              ->setCellValue('F'.$row, '')
								  ->mergeCells('B'.$row.':D'.$row);
					if(($idx+1)%2==1){
						$objPHPExcel->getActiveSheet()->setCellValue('G'.$row,'=A'.$row);
					}
					else{
						$objPHPExcel->getActiveSheet()->mergeCells('G'.($row-1).':G'.($row))
													->mergeCells('H'.($row-1).':H'.($row))
													->setCellValue('H'.($row-1),'=A'.$row);
					}
					$idx++;
				}
				if(($idx+1)%2<>1){
					$row = $baseRow + $idx;
					$objPHPExcel->getActiveSheet()->insertNewRowBefore($row,1)
												->mergeCells('B'.$row.':D'.$row)
												->mergeCells('G'.($row-1).':G'.($row))
												->mergeCells('H'.($row-1).':H'.($row));
				}
		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="evaluasi.peserta.xlsx"');
		header('Cache-Control: max-age=0');
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, $types[$filetype]);
		$objWriter->save('php://output');
		exit;
		////////////////////////
    }
	
	public function actionTtdnip($id){
		$model = Person::findOne(['id'=>$id])->nip;		
		return $model;
	}
	
	public function actionTrainingDirectEvaluationWord($id,$filetype='docx'){
		$ruang = Yii::$app->request->post()['ruang'];
		$ttd = Yii::$app->request->post()['ttd'];
		$nip = Yii::$app->request->post()['ttdnip'];
		$satker = Yii::$app->user->identity->employee->satker_id;
		
		//echo $ruang.$ttd.$nip;
		
		try {
			$templates=[
				'docx'=>'ms-word.docx',
				'odt'=>'open-document.odt',
				'xlsx'=>'ms-excel.xlsx'
			];
			// Initalize the TBS instance
			$OpenTBS = new \hscstudio\heart\extensions\OpenTBS; // new instance of TBS
			// Change with Your template kaka
			$template = Yii::getAlias('@file').'/template/pusdiklat/evaluation/template.dokumen.evaluasi.tatap.muka.docx';
			
			$OpenTBS->LoadTemplate($template); // Also merge some [onload] automatic fields (depends of the type of document).
			$OpenTBS->VarRef['modelName']= "TrainingDirectEvaluationWord";
			$data[] = [
						'name_execution_titlecase' => Person::findOne(['id'=>$ttd])->position_desc,
						'evaluation_head' => 'Kepala Bidang Penjenjangan Pangkat Dan Peningkatan Kompetensi',
						'name_execution_head' =>strtoupper(Reference::findOne(['id'=>$satker])->name),
						'address_execution' => Satker::findOne(['reference_id'=>$satker])->address,
						'phone_execution' => Satker::findOne(['reference_id'=>$satker])->phone,
						'fax_execution' => Satker::findOne(['reference_id'=>$satker])->fax,
						'web_execution' => Satker::findOne(['reference_id'=>$satker])->website,
						'name_training' =>Activity::findOne(['id'=>$id])->name,
						'place_training' =>$ruang,
						'year_training' =>date('Y',strtotime(Activity::findOne(['id'=>$id])->start)),
						'division_head' => 'Kepala Bidang',
						'general_head' => 'Kapala Bagian Tata Usaha',
						'month' =>date('M'),
						'year' =>date('Y'),
						'date_training' => date('d-M',strtotime(Activity::findOne(['id'=>$id])->start)).' s.d '.date('d-M-Y',strtotime(Activity::findOne(['id'=>$id])->end)),
						'name_signature' =>strtoupper(Person::findOne(['id'=>$ttd])->name),
						'nip_signature' =>$nip,
						'position_signature' => 'Kepala '.Person::findOne(['id'=>$ttd])->position_desc,
						'no_execution'=> Satker::findOne(['reference_id'=>$satker])->letter_number,
						'date'=>'TAHUN ANGGARAN '.date('Y',strtotime(Activity::findOne(['id'=>$id])->start)),
					];
	
			$OpenTBS->MergeBlock('onshow', $data);				
			// Output the result as a file on the server. You can change output file
			$OpenTBS->Show(OPENTBS_DOWNLOAD, 'dokumen.evaluasi.tatap.muka.'.$filetype); // Also merges all [onshow] automatic fields.			
			exit;
		} catch (\yii\base\ErrorException $e) {
			 Yii::$app->session->setFlash('error', 'Unable export there are some error');
		}		
	}
	
	public function actionTrainingHonorTransportExcel($id,$filetype='xlsx')
	{
		
		$pembayaran = Yii::$app->request->post()['pembayaran'];
		$txtsurat = Yii::$app->request->post()['txtsurat'];
		$nosurat = Yii::$app->request->post()['nosurat'];
		$tgl_surat = Yii::$app->request->post()['tgl_surat'];
		$ttd = Yii::$app->request->post()['ttd'];
		$nip = Yii::$app->request->post()['ttdnip'];
		$days = array('Minggu','Senin','Selasa','Rabu','Kamis','Jum\'at','Sabtu');
		$months = array('Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
		$satker = Yii::$app->user->identity->employee->satker_id;
		$admin = Yii::$app->request->post()['admin'];
		$admins = implode(",", $admin);
		$frek = Yii::$app->request->post()['frek'];
		
		/////////
		$types=['xls'=>'Excel5','xlsx'=>'Excel2007'];
		$objReader = \PHPExcel_IOFactory::createReader($types[$filetype]);
		$template = Yii::getAlias('@file').DIRECTORY_SEPARATOR.'template'.DIRECTORY_SEPARATOR.'pusdiklat'.
			DIRECTORY_SEPARATOR.'evaluation'.DIRECTORY_SEPARATOR.'template.honor.transport.diklat.'.$filetype;
		$objPHPExcel = $objReader->load($template);
		$objPHPExcel->getProperties()->setTitle("Honor Transport Diklat");
		$objPHPExcel->setActiveSheetIndex(0);
		
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setCellValue('A4', "".strtoupper(Reference::findOne(['id'=>$satker])->name)."");
		$objPHPExcel->getActiveSheet()->setCellValue('E4', "".$txtsurat."");	
		$objPHPExcel->getActiveSheet()->setCellValue('E3', $pembayaran)
							  ->setCellValue('F5', $nosurat)
							  ->setCellValue('F6', date("j",strtotime($tgl_surat))." ".$months[date("n",strtotime($tgl_surat))-1]." ".date("Y",strtotime($tgl_surat))."")
							  ->setCellValue('K17', "Jakarta, .... ".$months[date("n")-1]." ".date("Y")."")
							  ->setCellValue('K19', "")
							  ->setCellValue('K20', "")
							  ->setCellValue('H19', Person::findOne(['id'=>$ttd])->name)
							  ->setCellValue('H20', $nip)
							  ->setCellValue('D19', "")
							  ->setCellValue('D20', "")
							  ->setCellValue('B19', "")
							  ->setCellValue('B20', "");
		///////////
		$idx=0;
		$baseRow = 12;
		$total_frek=0;
		$total_jumlah_kotor=0;
		$total_pajak=0;
		$total_jumlah_bersih=0;
			for($i=0;$i<=count($admin)-1;$i++){
				$row = $baseRow + $idx;
				if($idx>0) $objPHPExcel->getActiveSheet()->insertNewRowBefore($row,1);
				$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $idx+1);
				$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, strtoupper(Person::findOne(['id'=>$admin[$i]])->name));
				$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, Reference::findOne(['id'=>$satker])->name);
				//$objPHPExcel->getActiveSheet()->setCellValue('D'.$row, ObjectReference::findOne(['object_id'=>$admin[$i],'object'=>'person','type'=>'rank_class'])->reference_id);
				
				$idx++;
			}
		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="honor.transport.diklat.xlsx"');
		header('Cache-Control: max-age=0');
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, $types[$filetype]);
		$objWriter->save('php://output');
		exit;
	}
	
	public function actionFormb($id)
    {
		$skpp_awal = TrainingClassStudentCertificate::find()
							->where(['training_class_student_id'=>
									 TrainingclassStudent::find()
									 ->select('id')
									 ->where(['training_id'=>$id])
									 ])
							->min('number*1');
		
		$skpp_akhir = TrainingClassStudentCertificate::find()
							->where(['training_class_student_id'=>
									 TrainingclassStudent::find()
									 ->select('id')
									 ->where(['training_id'=>$id])
									 ])
							->max('number*1');
        return $this->render('formb', [
            'model' => $this->findModel($id),
			'skpp_awal' => $skpp_awal,
			'skpp_akhir' => $skpp_akhir,
        ]);
    }
	
	public function actionGenerateFormb($id)
    {
        $nomor_formb = Yii::$app->request->post()['nomor_formb'];
		
		$data_training = Training::findOne(['activity_id'=>$id]);
		$data_training->number_forma = $nomor_forma;
		$data_training->update();
		$satker = $data_training->activity->satker_id;
		
		$jml_mp_jamlat = ProgramSubject::find()
						->where(['program_id'=>$data_training->program_id,'type'=>'109']);
						
		$jml_crmh_jamlat = ProgramSubject::find()
						->where(['program_id'=>$data_training->program_id,'type'=>'110']);
		
		$jml_pkl_jamlat = ProgramSubject::find()
						->where(['program_id'=>$data_training->program_id,'type'=>'112']);
		
		$jml_peserta_diklat = TrainingClassStudent::find()
						->where(['training_id'=>$id]);
						
		$jml_kelas_diklat = TrainingClass::find()
						->where(['training_id'=>$id])->count();
		
		$jml_trainer = TrainingScheduleTrainer::find()
						->where(['training_schedule_id'=>
								 TrainingSchedule::find()
								 ->select('id')
								 ->where(['training_class_id'=>
										  Trainingclass::find()
										  ->select('id')
										  ->where(['training_id'=>$id])
										  ])
								 ])->count();
		
		$months = array('Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
		
		try {
			$templates=[
				'docx'=>'ms-word.docx',
				'odt'=>'open-document.odt',
				'xlsx'=>'ms-excel.xlsx'
			];
			// Initalize the TBS instance
			$OpenTBS = new \hscstudio\heart\extensions\OpenTBS; // new instance of TBS
			// Change with Your template kaka
			if($data_training->regular=='1')
			{$template = Yii::getAlias('@file').'/template/pusdiklat/execution/template_formb.docx';}
			else
			{$template = Yii::getAlias('@file').'/template/pusdiklat/execution/template_formb2.docx';}
			
			$OpenTBS->LoadTemplate($template); // Also merge some [onload] automatic fields (depends of the type of document).
			$OpenTBS->VarRef['modelName']= "Generate Form A";
			$data[] = [
						'nama_diklat' => Activity::findOne(['id'=>$id])->name,
						'year_training' => date('Y',strtotime(Activity::findOne(['id'=>$id])->start)),
						'jenis_formb' => $data_training->regular=='1'?"B1":"B2",
						'no_formb'=> $data_training->number_formb."/".Satker::findOne(['reference_id'=>$satker])->letter_number.".3"."/".date('Y',strtotime(Activity::findOne(['id'=>$id])->start)),
						'jenis_diklat'=> $data_training->regular=='1'?"REGULAR":"PARALEL",
						'nama_satker'=> strtoupper($data_training->activity->satker->name),
						'nama_satker_dua'=> $data_training->activity->satker->name,
						'tanggal_penyelenggaraan_diklat'=> date("d",strtotime($data_training->activity->start))." ".$months[date("n",strtotime($data_training->activity->start))-1]." s.d ".date("d",strtotime($data_training->activity->end))." ".$months[date("n",strtotime($data_training->activity->end))-1].' '.date("Y",strtotime($data_training->activity->end)),
						'lokasi_diklat'=> $data_training->activity->location,
						'lama_diklat'=> $data_training->program->days." Hari",
						'diasramakan'=> Activity::findOne(['id'=>$id])->hostel=='1'?"Ya":"Tidak",
						'jml_mp_jamlat'=> $jml_mp_jamlat->count()." Mata Pelajaran / ".$jml_mp_jamlat->sum('hours')." Jamlat",
						'jml_crmh_jamlat'=> $jml_crmh_jamlat->count()." Ceramah / ".$jml_crmh_jamlat->sum('hours')." Jamlat",
						'jml_pkl_jamlat'=> $jml_pkl_jamlat->sum('hours')." Jamlat",
						'jml_kelas'=> $jml_kelas_diklat,
						'skpp_diklat'=> '',
						'jml_pengajar'=> $jml_trainer." Orang",
						'sk_diklat'=> $data_training->execution_sk,
						'rencana_biaya'=> $data_training->cost_plan,
						'sumber_biaya'=> $data_training->cost_source,
						'rekan_kerjasama'=> $data_training->stakeholder,
						'keterangan_lain'=> $data_training->note,
						'city'=> Satker::findOne(['reference_id'=>$satker])->city,
						'tgl_diklat'=> date("d").' '.$months[date("n")-1].' '.date("Y"),
						'nama_kepala_satker'=> Person::findOne(['id'=>Employee::findOne(['satker_id'=>$data_training->activity->satker_id,'organisation_id'=>'387','chairman'=>'1'])->person_id])->name,
						'nip_kepala_satker'=> Person::findOne(['id'=>Employee::findOne(['satker_id'=>$data_training->activity->satker_id,'organisation_id'=>'387','chairman'=>'1'])->person_id])->nip,
						'nama_kepala_bidang'=> Person::findOne(['id'=>Employee::findOne(['satker_id'=>$data_training->activity->satker_id,'organisation_id'=>'399','chairman'=>'1'])->person_id])->name,
						'nip_kepala_bidang'=> Person::findOne(['id'=>Employee::findOne(['satker_id'=>$data_training->activity->satker_id,'organisation_id'=>'399','chairman'=>'1'])->person_id])->nip,
					];
	
			$OpenTBS->MergeBlock('onshow', $data);	
			// Output the result as a file on the server. You can change output file
			$OpenTBS->Show(OPENTBS_DOWNLOAD, 'generate.formb.'.$filetype); // Also merges all [onshow] automatic fields.			
			exit;
		} catch (\yii\base\ErrorException $e) {
			 Yii::$app->session->setFlash('error', 'Unable export there are some error');
		}	
    }
}
