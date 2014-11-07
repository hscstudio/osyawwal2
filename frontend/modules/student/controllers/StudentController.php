<?php

namespace frontend\modules\student\controllers;

use Yii;
use frontend\models\Student;
use frontend\models\Person;
use frontend\models\StudentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


use backend\models\ObjectReference;
use backend\models\ObjectFile;
use backend\models\File;
use hscstudio\heart\helpers\Heart;
use yii\data\ActiveDataProvider;
/**
 * StudentController implements the CRUD actions for Student model.
 */
class StudentController extends Controller
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
    public function actionProfile()
    {
        $id = Yii::$app->user->id;
		$model = $this->findModel($id);
		$model->scenario = 'profile';
		$person = Person::findOne($model->person_id);
		if (null==$person){  
			throw new NotFoundHttpException('The requested page does not exist.');
		}
		
		$renders = [];
		$renders['model'] = $model;
		$renders['person'] = $person;
		$object_references_array = [
			'unit'=>'Unit','religion'=>'Religion','rank_class'=>'Rank Class','graduate'=>'Graduate'];
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
				Yii::$app->getSession()->setFlash('success', 'Data student have updated.');
				$person->load(Yii::$app->request->post());
				//$person->scenario = 'profile';
				if($person->save()) {
					Yii::$app->getSession()->setFlash('success', 'Data student & person have updated.');
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
				return $this->redirect(['view']);
			}
			else{
				Yii::$app->getSession()->setFlash('error', 'Data is not updated.');
				return $this->render('profile', $renders);
			}	
        } else {
            return $this->render('profile', $renders);
        }
    }

	/**
     * Lists all Student models.
     * @return mixed
     */
    public function actionPassword()
    {
        $id = Yii::$app->user->id;
		$model = $this->findModel($id);
		$model->scenario = 'password';
        if ($model->load(Yii::$app->request->post())) {	
			if(Yii::$app->security->validatePassword($model->old_password, Yii::$app->user->identity->password_hash)){
				Yii::$app->getSession()->setFlash('error', 'Old password not match with current password');
			}
			else{
				$model->password = $model->new_password;
				if($model->save()) {
					Yii::$app->getSession()->setFlash('success', 'Password have changed.');				
				}
				else{
					Yii::$app->getSession()->setFlash('error', 'Password is not change.');
				}
			}
			return $this->redirect(['password']);
        } else {
            return $this->render('password', [
                'model' => $model,
            ]);
        }
    }
	
	public function actionView()
    {
        $id = Yii::$app->user->identity->id;
		return $this->render('view', [
            'model' => \frontend\models\Person::findOne(['id'=>$id]),
			//$this->findModel($id),
        ]);
    }
	
	public function actionPrint($status_training_student=NULL,$filetype='docx') {
		$dataProvider = new ActiveDataProvider([
            'query' => \frontend\models\Person::find()->where(['id'=>Yii::$app->user->identity->id]),
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
			$template = Yii::getAlias('@frontend').'/template_ereg/print_ereg.docx';
			$OpenTBS->LoadTemplate($template); // Also merge some [onload] automatic fields (depends of the type of document).
			$OpenTBS->VarRef['modelName']= "Student";
			$data1[]['col0'] = date('M Y');								
	
			$OpenTBS->MergeBlock('a', $data1);			
			$data2 = [];
			foreach($dataProvider->getModels() as $student){
				$tgllahir = explode('-',$student->birthday);
				$data2[] = [
					'col0'=>strtoupper($student->name),
					'col1'=>$student->nip,
					'col2'=>strtoupper($student->born),
					'col3'=>$tgllahir[2].'-'.$tgllahir[1].'-'.$tgllahir[0],
					'col4'=>'KEMENTERIAN KEUANGAN',
					'col5'=> strtoupper(\frontend\models\ObjectReference::findOne(['object'=>'person','object_id'=>$student->id,'type'=>'unit'])->reference->name),
					'col6'=>strtoupper(\frontend\models\ObjectReference::findOne(['object'=>'person','object_id'=>$student->id,'type'=>'rank_class'])->reference->name),
					'col7'=>strtoupper($student->position_desc),
					'col8'=>$status_training_student==2?'MENGULANG':'BARU',
				];
			}
			$OpenTBS->MergeBlock('b', $data2);
			// Output the result as a file on the server. You can change output file
			$OpenTBS->Show(OPENTBS_DOWNLOAD, 'result.'.$filetype); // Also merges all [onshow] automatic fields.			
			exit;
		} catch (\yii\base\ErrorException $e) {
			 Yii::$app->session->setFlash('error', 'Unable export there are some error');
		}	
		
        /*return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);		*/
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
	
	
}
