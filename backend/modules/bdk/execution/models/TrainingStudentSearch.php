<?php

namespace backend\modules\bdk\execution\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\TrainingStudent;

/**
 * TrainingStudentSearch represents the model behind the search form about `backend\models\TrainingStudent`.
 */
class TrainingStudentSearch extends TrainingStudent
{
	public $name, $nip;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'training_id', 'student_id', 'status', 'created_by', 'modified_by'], 'integer'],
            [['created', 'modified'], 'safe'],
			[['name', 'nip'], 'safe'],
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
    public function search($params)
    {
        $query = TrainingStudent::find()
			->joinWith('student')
			->joinWith('student.person')
			->orderBy('name ASC')
			;

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
		
        $query->andFilterWhere([
            'id' => $this->id,
            'training_id' => $this->training_id,
            'student_id' => $this->student_id,
            'training_student.status' => $this->status,
            'created' => $this->created,
            'created_by' => $this->created_by,
            'modified' => $this->modified,
            'modified_by' => $this->modified_by,
        ]);
		
		$query->andFilterWhere(['like', 'name', $this->name])
			  ->andFilterWhere(['like', 'nip', $this->nip]);
			
        return $dataProvider;
    }
}
