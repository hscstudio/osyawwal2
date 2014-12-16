<?php

namespace backend\modules\sekretariat\finance\controllers;

use yii\web\Controller;
use backend\models\ObjectReference;
use backend\models\ObjectFile;
use backend\models\File;

class DefaultController extends Controller
{
    public $layout = '@hscstudio/heart/views/layouts/column2';
	public function actionIndex()
    {
        return $this->render('index');
    }
	
	public function actionViewEmployee($id)
    {
        $model = \backend\models\Person::findOne($id);
        $employee = $model->employee;
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

        return $this->renderAjax('_view_employee', $renders);
    }
}
