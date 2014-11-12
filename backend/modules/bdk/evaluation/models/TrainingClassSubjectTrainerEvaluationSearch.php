<?php

namespace backend\modules\bdk\evaluation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\TrainingClassSubjectTrainerEvaluation;

/**
 * TrainingClassSubjectTrainerEvaluationSearch represents the model behind the search form about `backend\models\TrainingClassSubjectTrainerEvaluation`.
 */
class TrainingClassSubjectTrainerEvaluationSearch extends TrainingClassSubjectTrainerEvaluation
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'training_class_subject_id', 'trainer_id', 'student_id', 'status', 'created_by', 'modified_by'], 'integer'],
            [['value', 'comment', 'created', 'modified'], 'safe'],
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
        $query = TrainingClassSubjectTrainerEvaluation::find()
				->joinWith('trainingClassSubject')
				->joinWith('trainingClassSubject.trainingClass');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'training_class_subject_id' => $this->training_class_subject_id,
            'trainer_id' => $this->trainer_id,
            'student_id' => $this->student_id,
            'status' => $this->status,
            'created' => $this->created,
            'created_by' => $this->created_by,
            'modified' => $this->modified,
            'modified_by' => $this->modified_by,
        ]);

        $query->andFilterWhere(['like', 'value', $this->value])
            ->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}
