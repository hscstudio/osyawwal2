<?php

namespace backend\modules\bdk\general\controllers;

use Yii;
use backend\models\Person;
use backend\models\Employee;
use backend\models\User;
use backend\modules\bdk\general\models\PersonSearch;
use backend\models\ObjectReference;
use backend\models\ObjectFile;
use backend\models\File;
use yii\data\ActiveDataProvider;
use hscstudio\heart\helpers\Heart;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
/**
 * PersonController implements the CRUD actions for Person model.
 */
class PersonController extends Controller
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
     * Lists all Person models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PersonSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Person model.
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
     * Creates a new Person model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Person();
		$renders = [];
		$renders['model'] = $model;
		
		$object_references_array = [
			'unit'=>'Unit','religion'=>'Religion','rank_class'=>'Rank Class','graduate'=>'Graduate'];
		$renders['object_references_array'] = $object_references_array;
		foreach($object_references_array as $object_reference=>$label){
			$object_references[$object_reference]= new ObjectReference();
			$renders[$object_reference] = $object_references[$object_reference];
		}	
		
		$currentFiles=[];
		$object_file_array = [
			'photo'=>'Photo 4x6','sk_cpns'=>'SK CPNS','sk_pangkat'=>'SK Pangkat'];
		$renders['object_file_array'] = $object_file_array;
		foreach($object_file_array as $object_file=>$label){
			$currentFiles[$object_file] = '';
			$object_files[$object_file]= new ObjectFile();
			$files[$object_file] = new File();
			$renders[$object_file] = $object_files[$object_file];
			$renders[$object_file.'_file'] = $files[$object_file];
		}
		
        if ($model->load(Yii::$app->request->post())){ 
			$connection=Yii::$app->getDb();
			$transaction = $connection->beginTransaction();			
			if($model->save()) {
				Yii::$app->getSession()->setFlash('success', 'New data have saved.');
				
				foreach($object_references_array as $object_reference=>$label){
					$reference_id = Yii::$app->request->post('ObjectReference')[$object_reference]['reference_id'];
					Heart::objectReference($object_references[$object_reference],$reference_id,'person',$model->id,$object_reference);
				}
				
				$uploaded_files = [];					
				foreach($object_file_array as $object_file=>$label){
					$uploaded_files[$object_file] = \yii\web\UploadedFile::getInstance($files[$object_file], 'file_name['.$object_file.']');						
					if(null!=$uploaded_files[$object_file]){
						//upload(
							//$instance_file, $object='person', $object_id, $file, $object_file, 
							//$type='photo', $resize=false,$current_file='',$thumb = false){
						Heart::upload(
							$uploaded_files[$object_file], 
							'person', 
							$model->id, 
							$files[$object_file],
							$object_files[$object_file], 
							$object_file, 
							($object_file=='photo')?true:false,
							$currentFiles[$object_file],
							($object_file=='photo')?true:false
						);					
					}
				}
				
				try { 
					$satker_id = (int)Yii::$app->user->identity->employee->satker_id;
					// CREATE USER
					$user = new User();
					$user->username = $model->nid;
					$user->password = $model->nid;
					$user->email = $model->nid.'@abc.def';
					$user->role = 1;				
					$user->status = 0;
					if($user->save()){
						// CREATE EMPLOYEE
						$employee = new Employee();
						$employee->person_id = $model->id;
						$employee->user_id = $user->id;
						$employee->satker_id = $satker_id;
						$employee->save();
					}				
					$transaction->commit();
					return $this->redirect(['view', 'id' => $model->id]);
				} 
				catch (Exception $e) {
					Yii::$app->getSession()->setFlash('error', 'Roolback transaction. Data is not saved');
					return $this->render('create', $renders);
				}
				
			}
			else{
				Yii::$app->getSession()->setFlash('error', 'New data is not saved.');
				return $this->render('create', $renders);
			}
				
            
        } else {
            return $this->render('create', $renders);
        }
    }

    /**
     * Updates an existing Person model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$renders = [];
		$renders['model'] = $model;
		
		$object_references_array = [
			'unit'=>'Unit','religion'=>'Religion','rank_class'=>'Rank Class','graduate'=>'Graduate'];
		$renders['object_references_array'] = $object_references_array;
		foreach($object_references_array as $object_reference=>$label){
			$object_references[$object_reference] = ObjectReference::find()
				->where([
					'object'=>'person',
					'object_id' => $id,
					'type' => $object_reference, 
				])
				->one();
			if($object_references[$object_reference]==null){
				$object_references[$object_reference]= new ObjectReference();
			}
			$renders[$object_reference] = $object_references[$object_reference];
		}	
		
		$currentFiles=[];
		$object_file_array = [
			'photo'=>'Photo 4x6','sk_cpns'=>'SK CPNS','sk_pangkat'=>'SK Pangkat'];
		$renders['object_file_array'] = $object_file_array;
		foreach($object_file_array as $object_file=>$label){
			$currentFiles[$object_file] = '';
			$object_files[$object_file] = ObjectFile::find()
				->where([
					'object'=>($object_file=='photo')?'person':'person',
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
            if($model->save()) {				
				foreach($object_references_array as $object_reference=>$label){
					$reference_id = Yii::$app->request->post('ObjectReference')[$object_reference]['reference_id'];
					Heart::objectReference($object_references[$object_reference],$reference_id,'person',$model->id,$object_reference);
				}		
				
				$uploaded_files = [];					
				foreach($object_file_array as $object_file=>$label){
					$uploaded_files[$object_file] = \yii\web\UploadedFile::getInstance($files[$object_file], 'file_name['.$object_file.']');						
					if(null!=$uploaded_files[$object_file]){
						//upload(
							//$instance_file, $object='person', $object_id, $file, $object_file, 
							//$type='photo', $resize=false,$current_file='',$thumb = false){
						Heart::upload(
							$uploaded_files[$object_file], 
							'person', 
							$model->id, 
							$files[$object_file],
							$object_files[$object_file], 
							$object_file, 
							($object_file=='photo')?true:false,
							$currentFiles[$object_file],
							($object_file=='photo')?true:false
						);					
					}
				}
				return $this->redirect(['view', 'id' => $model->id]);
			}
			else{
				Yii::$app->getSession()->setFlash('error', 'Data is not updated.');
				return $this->render('update', $renders);
			}
			
        } else {
            return $this->render('update', $renders);
        }
    }

    /**
     * Deletes an existing Person model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		$model = $this->findModel($id);
		// delete object_reference
		ObjectReference::deleteAll(
			'object = :object AND object_id = :object_id', 
			[':object' => 'person', ':object_id' => $id]
		);
		// find all object_file
		$object_files = ObjectFile::find()
			->where([
				'object' => 'person',
				'object_id' => $id,
			])
			->all();
		foreach($object_files as $object_file){	
			$files = File::find()
			->where([
				'id' => $object_file->file_id,
			])
			->one();
			if($files!=null){
				$path = '';
				if(isset(Yii::$app->params['uploadPath'])){
					$path = Yii::$app->params['uploadPath'].'/'.$object_file->object.'/'.$object_file->object_id.'/';
				}
				else{
					$path = Yii::getAlias('@file').'/'.$object_file->object.'/'.$object_file->object_id.'/';
				}
				$filename = $files->file_name;
				@mkdir($path, 0755, true);
				@chmod($path, 0755);
				@unlink($path . $filename);
				@unlink($path . 'thumb_'. $filename);
				File::find(
					'id = :id',
					[':id' => $files->id]
				)
				->one()
				->delete();
			}
		}
		ObjectFile::deleteAll(
			'object = :object AND object_id = :object_id', 
			[':object' => 'person', ':object_id' => $id]
		);
		
		if($model->delete()) {			
			Yii::$app->getSession()->setFlash('success', 'Data have deleted.');
		}
		else{
			Yii::$app->getSession()->setFlash('error', 'Data is not deleted.');
		}
        return $this->redirect(['index']);
    }

    /**
     * Finds the Person model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Person the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
		/* special user id<=100 not editable */
		if ($id>100){
			$satker_id = (int)Yii::$app->user->identity->employee->satker_id;
			$employee = Employee::find()
						->where([
							'person_id'=>$id,
							'satker_id'=>$satker_id,
						])
						->count();
			if (($model = Person::findOne($id)) !== null and $employee>0) {
				return $model;
			} else {
				throw new NotFoundHttpException('The requested page does not exist.');
			}
		}
		else{
			throw new NotFoundHttpException('You have not privilege to access this data, contact Administratator.');
		}
    }
	
	public function actionImportEmployee(){	

		$session = Yii::$app->session;
		$session->open();
		
		$start = time();
		if (!empty($_FILES)) {
			$importFile = \yii\web\UploadedFile::getInstanceByName('importFile');			
			if(!empty($importFile)){
				$fileTypes = ['xls','xlsx']; 
				$ext=$importFile->extension;
				if(in_array($ext,$fileTypes)){
					$inputFileType = \PHPExcel_IOFactory::identify($importFile->tempName );
					$objReader = \PHPExcel_IOFactory::createReader($inputFileType);
					$objPHPExcel = $objReader->load($importFile->tempName );
					$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
					/* $template_code = @$sheetData[104]['C'];
					if($template_code!=='syawwal'){
						Yii::$app->session->setFlash('error', 'Invalid template!');
						return $this->redirect([
							'index',
						]);
					} */
					$baseRow = 4;
					$err=[];
					$data = [];	
					$row=0;
					while(!empty($sheetData[$baseRow]['B'])){
						// GET DATA FROM EXCEL
						$nip = str_replace(' ','',trim($sheetData[$baseRow]['B']));
						$name = trim($sheetData[$baseRow]['C']);	
						$jabatan_id = (int)trim($sheetData[$baseRow]['D']);
						$organisation_id = (int)trim($sheetData[$baseRow]['F']);
						
						$person_id = 0;
						$employee_id = 0;
						$user_id = 0;
						$person = Person::find()
									->where(['nip' => $nip])
									->orWhere(['nid' => $nip])
									->one();
						if(!empty($person)){
							$person_id = $person->id;
							$employee = Employee::find()
								->where([ 
									'person_id' => $person_id,
								])
								->one();
								
							if(!empty($employee)){
								$employee_id = $employee->person_id;
								$user = User::find()
									->where([ 
										'id' => $employee->user_id,
									])
									->one();
									
								if(!empty($user)){
									$user_id = $user->id;									
								}
							}
							
							if($user_id==0){
								$user = User::find()
									->where(['username' => $nip])
									->one();
									
								if(!empty($user)){
									$user_id = $user->id;									
								}
							}
						}
						
						$password = Yii::$app->security->generatePasswordHash($nip,4);
						$data[$row]=[
							'row'	=>	$row,
							'nip'	=>	$nip,
							'name'	=>	$name,
							'jabatan_id' =>	$jabatan_id,
							'organisation_id' => $organisation_id,
							'person_id' => $person_id,
							'employee_id'=>	$employee_id,
							'user_id'=>	$user_id,
							'password' => $password,
						];
						$row++;
						$baseRow++;
					}	
					
					// SESSION BUG MAYBE :)
					$data[$row+1]=[];
					$session['data'] = $data;						
					Yii::$app->session->setFlash('success', ($row).' row affected');	
				}
				else{
					Yii::$app->session->setFlash('error', 'Filetype allowed only xls and xlsx');
					return $this->redirect([
						'index'
					]);
				}				
			}
			else{
				Yii::$app->session->setFlash('error', 'File import empty!');
				return $this->redirect([
					'index'
				]);
			}
		}
		
		if (Yii::$app->request->post() and isset(Yii::$app->request->post()['selection'])){ 
			$selections = Yii::$app->request->post()['selection'];
			$names = Yii::$app->request->post()['name'];
			$nips = Yii::$app->request->post()['nip'];
			$jabatan_ids = Yii::$app->request->post()['jabatan_id'];
			$organisation_ids = Yii::$app->request->post()['organisation_id'];
			$user_ids = Yii::$app->request->post()['user_id'];
			$employee_ids = Yii::$app->request->post()['employee_id'];
			$person_ids = Yii::$app->request->post()['person_id'];
			$passwords = Yii::$app->request->post()['password'];
			
			/* $person_values = [];
			$student_values = [];
			$training_student_values = []; */
			$satker_id = (int)Yii::$app->user->identity->employee->satker_id;
			$position = 5; //default pelaksana
			$position_desc = 'Pelaksana'; 
			$organisation = 'BPPK';
			$unit = 132; // default 132 = BPPK
			foreach($selections as $selection){	
				/* if($persons[$selection]==0)
					$person_values[] = [$names[$selection],$nips[$selection],$nips[$selection],1];
				if($students[$selection]==0)
					$student_values[] = [$nips[$selection]]; */	
				$person_id = $person_ids[$selection];
				$name = $names[$selection];
				$nip = $nips[$selection];
				$jabatan_id = $jabatan_ids[$selection];
				$organisation_id = $organisation_ids[$selection];
				$user_id = $user_ids[$selection];
				$employee_id = $employee_ids[$selection];
				$person_id = $person_ids[$selection];
				$password = $passwords[$selection];
				// PROVIDE DATA FROM NIP
				$birthday = '';
				$gender = '';
				$status = 1;				
				if(strlen($nip)==18){
					$birthday = substr($nip,0,4) .'-'. substr($nip,4,2) .'-'. substr($nip,6,2);
					$gender = substr($nip,14,1);
				}
				
				if($person_id==0){
					if($jabatan_id==2){
						// Pejabat
						// Get Kode Eselon
						if($organisation_id>0){
							$org = \backend\models\Organisation::find()
								->where([
									'ID' =>  $organisation_id,
									])
									->one();
							if(!empty($org)){
								$position = $org->KD_ESELON;
								$position_desc = $org->NM_UNIT_ORG; 
							}
						}
					}
								
					$person = new Person([
						'name' => $name,
						'nid' => $nip,
						'nip' => $nip,
						'birthday' => $birthday,
						'gender' => $gender,
						'position'=> $position,
						'position_desc'=> $position_desc,
						'organisation'=> $organisation,
						'status' => 1,
					]);
					if($person->save()){
						$person_id = $person->id;
					}
				}
				
				if($person_id>0){						
					if($user_id==0){
						$user = new User([
							'username'=>$nip,
							'password_hash'=>$password,
							'email'=>$nip.'@abc.def',
							'role'=>1,
							'status' => (\Yii::$app->user->can('admin-bdk'))?1:0,
						]);
						if ($user->save()){
							$user_id = $user->id;
						}
					}
					else{
						if(\Yii::$app->user->can('admin-bdk')){
							$user=User::findOne($user_id);
							$user->status=1;
							$user->save();
						}
					}
					
					if($user_id>0){		
						if($employee_id==0){
							$employee = new Employee([
								'person_id' => $person_id,
								'user_id' => $user_id,	
								'satker_id' => $satker_id,
								'organisation_id' => ($organisation_id>0)?$organisation_id:NULL,
								'chairman' => ($jabatan_id==2)?1:0,
							]);
							
							if($employee->save()){							
								$employee_id = $employee->person_id;
							}
							else {
								die(var_dump($employee->errors));
							}
						}				
					}
				}
				
				if($employee_id>0){
					if(in_array($jabatan_id,[2,3])){
						// 139 WI, 140 PK
						$object_reference = ObjectReference::find()
							->where([
								'object'=>'employee',
								'object_id' => $employee_id,
								'type' => 'fungsional', 
							])
							->one();
						Heart::objectReference($object_reference,($jabatan_id==2)?139:140,'employee',$employee_id,'fungsional');
					}
					$object_reference = ObjectReference::find()
							->where([
								'object'=>'person',
								'object_id' => $employee_id,
								'type' => 'unit', 
							])
							->one();
					Heart::objectReference($object_reference,$unit,'person',$employee_id,'unit');
				}
				
				if($user_id>0 and (\Yii::$app->user->can('admin-bdk'))){	
					if (in_array($jabatan_id,[1,2])){
						if($jabatan_id==2){
							$auths = [
								// PR - upload pegawai tar dulu
								'387'=>'Pusdiklat',
								'388'=>'Bagian Tata Usaha',
								'389'=>'Subbagian Tata Usaha, Kepegawaian, Dan Humas',
								'390'=>'Subbagian Perencanaan Dan Keuangan',
								'391'=>'Subbagian Rumah Tangga Dan Pengelolaan Aset',
								'392'=>'Bidang Perencanaan Dan Pengembangan Diklat',
								'393'=>'Subbidang Program',
								'394'=>'Subbidang Kurikulum',
								'395'=>'Subbidang Tenaga Pengajar',
								'396'=>'Bidang Penyelenggaraan',
								'397'=>'Subbidang Penyelenggaraan I',
								'398'=>'Subbidang Penyelenggaraan II',
								'399'=>'Bidang Evaluasi Dan Pelaporan Kinerja',
								'400'=>'Subbidang Evaluasi Diklat',
								'401'=>'Subbidang Pengolahan Hasil Diklat',
								'402'=>'Subbidang Informasi Dan Pelaporan Kinerja',								
							];
						}
						else{
							$auths = [
								'389'=>'Pelaksana Subbagian Tata Usaha, Kepegawaian, Dan Humas',
								'390'=>'Pelaksana Subbagian Perencanaan Dan Keuangan',
								'391'=>'Pelaksana Subbagian Rumah Tangga Dan Pengelolaan Aset',
								
								'393'=>'Pelaksana Subbidang Program',
								'394'=>'Pelaksana Subbidang Kurikulum',
								'395'=>'Pelaksana Subbidang Tenaga Pengajar',
								
								'397'=>'Pelaksana Subbidang Penyelenggaraan I',
								'398'=>'Pelaksana Subbidang Penyelenggaraan II',
								
								'400'=>'Pelaksana Subbidang Evaluasi Diklat',
								'401'=>'Pelaksana Subbidang Pengolahan Hasil Diklat',
								'402'=>'Pelaksana Subbidang Informasi Dan Pelaporan Kinerja',								
							];
						}
												
						
						if(isset($auths[$organisation_id]) and !empty($auths[$organisation_id])){
							\backend\models\AuthAssignment::deleteAll(
								'user_id='.$user_id
							);
							$authAssignment = new \backend\models\AuthAssignment([
								'user_id' => $user_id,
								'item_name' => $auths[$organisation_id],
							]);
							$authAssignment->save();
						}
					}
				}
			}
			Yii::$app->session->setFlash('success', 'done');	
			unset($session['data']);

		}
		
		if ($session->has('data')) {
			$data = $session['data'];
			$dataProvider = new ArrayDataProvider([
				'allModels' => $data,
				/* 'sort' => [
					'attributes' => ['row', 'nip', 'name', 'unit', 'exists'],
				], */
				'pagination' => [
					'pageSize' => 'all',
				],
			]);
			
			return $this->render('importEmployee', [
				'dataProvider' => $dataProvider,
			]);
		}
		else{
			return $this->redirect([
				'index'
			]);
		}
	}
	
}
