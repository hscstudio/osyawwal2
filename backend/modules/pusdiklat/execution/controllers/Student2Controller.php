<?php

namespace backend\modules\pusdiklat\execution\controllers;

use Yii;
use backend\models\Student;
use backend\modules\pusdiklat\execution\models\StudentSearch;
use backend\models\Person;
use backend\modules\pusdiklat\execution\models\PersonSearch;
use backend\models\ObjectReference;
use backend\models\ObjectFile;
use backend\models\File;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use hscstudio\heart\helpers\Heart;

/**
 * StudentController implements the CRUD actions for Student model.
 */
class Student2Controller extends Controller
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
     * Lists all Student models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StudentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Student model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
    	if (Yii::$app->request->isAjax) {
	        return $this->renderAjax('view', [
	            'model' => $this->findModel($id),
	        ]);
	    } else {
	        return $this->render('view', [
	            'model' => $this->findModel($id),
	        ]);
	    }
    }

   /**
     * Creates a new Trainer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateX($person_id)
    {
		$person = Person::findOne($person_id);
		if (null==$person){  
			throw new NotFoundHttpException('The requested page does not exist.');
		}
		
        $model = new Student([
			'person_id'=>$person_id,
		]);

        if ($model->load(Yii::$app->request->post())){ 
			if(strlen($person->nip)>3){
				$model->username = $person->nip;
				$model->password = $person->nip;				
				if($model->save()) {
					Yii::$app->getSession()->setFlash('success', '<i class="fa fa-fw fa-check-circle"></i> Data baru berhasil disimpan');
				}
				else{
					Yii::$app->getSession()->setFlash('error', '<i class="fa fa-fw fa-times-circle"></i> Data baru gagal disimpan');
				}
			}
			else{
				Yii::$app->getSession()->setFlash('error', 'NIP tidak boleh kosong.');
			}
            return $this->redirect(['person']);
        } else {
			if (Yii::$app->request->isAjax){
				return $this->renderAjax('create', [
					'model' => $model,
				]);
			}
			else{
				return $this->render('create', [
					'model' => $model,
				]);
			}
        }
    }

    /**
     * Updates an existing Student model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdateX($id)
    {
        $model = $this->findModel($id);
		$person = Person::findOne($model->person_id);
		if (null==$person){  
			throw new NotFoundHttpException('The requested page does not exist.');
		}
		$renders = [];
		$renders['model'] = $model;
		$renders['person'] = $person;
		$object_references_array = [
			'unit'=>'Unit','religion'=>Yii::t('app', 'BPPK_TEXT_RELIGION'),'rank_class'=>Yii::t('app', 'BPPK_TEXT_RANK_CLASS'),'graduate'=>Yii::t('app', 'BPPK_TEXT_GRADUATE')];
		$renders['object_references_array'] = $object_references_array;
		foreach($object_references_array as $object_reference=>$label){
			$object_references[$object_reference] = ObjectReference::find()
				->where([
					'object'=>'person',
					'object_id' => $person->id,
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
					'object_id' => $person->id,
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
				Yii::$app->getSession()->setFlash('success', '<i class="fa fa-fw fa-check-circle"></i> Data peserta berhasil diperbarui');
				$person->load(Yii::$app->request->post());
				
				//$person->scenario = 'profile';
				if($person->save()) {
					Yii::$app->getSession()->setFlash('success', '<i class="fa fa-fw fa-check-circle"></i> Data peserta berhasil diperbarui');
					foreach($object_references_array as $object_reference=>$label){
						$reference_id = Yii::$app->request->post('ObjectReference')[$object_reference]['reference_id'];
						Heart::objectReference($object_references[$object_reference],$reference_id,'person',$person->id,$object_reference);
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
								$person->id, 
								$files[$object_file],
								$object_files[$object_file], 
								$object_file, 
								($object_file=='photo')?true:false,
								$currentFiles[$object_file],
								($object_file=='photo')?true:false
							);					
						}
					}					
				}
				return $this->redirect(['index']);
			}
			else{
				Yii::$app->getSession()->setFlash('error', '<i class="fa fa-fw fa-times-circle"></i> Data gagal diperbarui');
			}
			return $this->redirect(['index']);
        } else {
        	if (Yii::$app->request->isAjax) {
            	return $this->renderAjax('update', $renders);
            } else {
            	return $this->render('update', $renders);
            }
        }
    }

    /**
     * Deletes an existing Student model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeleteX($id)
    {
		if($this->findModel($id)->delete()) {
			Yii::$app->getSession()->setFlash('success', '<i class="fa fa-fw fa-check-circle"></i>Data berhasil dihapus');
		}
		else{
			Yii::$app->getSession()->setFlash('error', '<i class="fa fa-fw fa-times-circle"></i>Data gagal dihapus');
		}
        return $this->redirect(['index']);
    }

    /**
     * Finds the Student model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Student the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Student::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	/**
     * Lists all Person models.
     * @return mixed
     */
    public function actionPerson()
    {		
        $searchModel = new PersonSearch();
		
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('person', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	
	/**
     * Creates a new Person model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreatePerson()
    {
        $model = new Person();
		$student = new Student();
		$renders = [];
		$renders['model'] = $model;
		$renders['student'] = $student;
		
		$object_references_array = [
			'religion'=>Yii::t('app', 'BPPK_TEXT_RELIGION'),'rank_class'=>Yii::t('app', 'BPPK_TEXT_RANK_CLASS'),'graduate'=>Yii::t('app', 'BPPK_TEXT_GRADUATE')];
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
			if($object_file=='photo'){
				$files[$object_file]->scenario = 'filetype-image';
			}
			else{
				$files[$object_file]->scenario = 'filetype-document';
			}
			$renders[$object_file] = $object_files[$object_file];
			$renders[$object_file.'_file'] = $files[$object_file];
		}
		
        if ($model->load(Yii::$app->request->post())){ 
			$model->status = 1;
			if($model->save()) {
				$student->person_id = $model->id;
				$student->save();
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
				
				return $this->redirect(['view-person', 'id' => $model->id]);
			}
			else{
				Yii::$app->getSession()->setFlash('error', 'New data is not saved.');
				return $this->render('create_person', $renders);
			}
            
        } else {
            return $this->render('create_person', $renders);
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
        $model = $this->findModelPerson($id);
		$student = $this->findModel($id);
		$renders = [];
		$renders['model'] = $model;
		$renders['student'] = $student;
		$object_references_array = [
			'religion'=>Yii::t('app', 'BPPK_TEXT_RELIGION'),'rank_class'=>Yii::t('app', 'BPPK_TEXT_RANK_CLASS'),'graduate'=>Yii::t('app', 'BPPK_TEXT_GRADUATE')];
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
				Yii::$app->getSession()->setFlash('success', 'Data Person telah diupdate.');
				if ($student->load(Yii::$app->request->post())) {
					if($student->save()){
						Yii::$app->getSession()->setFlash('success', 'Data Pengajar telah diupdate.');
					}
				}
				 
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
				return $this->redirect(['view-person', 'id' => $model->id]);
			}
			else{
				Yii::$app->getSession()->setFlash('error', 'Data is not updated.');
				return $this->render('update_person', $renders);
			}
			
        } else {
            return $this->render('update_person', $renders);
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
		$model = $this->findModelPerson($id);
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
     * Displays a single Person model.
     * @param integer $id
     * @return mixed
     */
    public function actionViewPerson($id)
    {
        return $this->render('view_person', [
            'model' => $this->findModelPerson($id),
        ]);
    }
	
	/**
     * Finds the Person model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Person the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelPerson($id)
    {
        if (($model = Person::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
