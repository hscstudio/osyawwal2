<?php

namespace frontend\modules\student\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\TrainingClassSubject;

/**
 * TrainingClassSubjectSearch represents the model behind the search form about `frontend\models\TrainingClassSubject`.
 */
class TrainingClassSubjectSearch extends TrainingClassSubject
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'training_class_id', 'program_subject_id', 'status', 'created_by', 'modified_by'], 'integer'],
            [['created', 'modified'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params,$training_id,$training_student_id)
    {
        $query = TrainingClassSubject::find()
				->leftjoin('training_class_student','training_class_student.training_class_id=training_class_subject.training_class_id')
				->where(['training_id'=>$training_id,'training_student_id'=>$training_student_id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'training_class_id' => $this->training_class_id,
            'program_subject_id' => $this->program_subject_id,
            'status' => $this->status,
            'created' => $this->created,
            'created_by' => $this->created_by,
            'modified' => $this->modified,
            'modified_by' => $this->modified_by,
        ]);

        return $dataProvider;
    }
}
