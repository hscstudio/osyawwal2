<?php

namespace backend\modules\pusdiklat\evaluation\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\TrainingExecutionEvaluation;

/**
 * TrainingExecutionEvaluationSearch represents the model behind the search form about `backend\models\TrainingExecutionEvaluation`.
 */
class TrainingExecutionEvaluationSearch extends TrainingExecutionEvaluation
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'training_class_student_id', 'overall', 'status', 'created_by', 'modified_by'], 'integer'],
            [['value', 'text1', 'text2', 'text3', 'text4', 'text5', 'comment', 'created', 'modified'], 'safe'],
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
    public function search($params,$id=NULL)
    {
        $query = TrainingExecutionEvaluation::find()
				->where(['id'=>$id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'training_class_student_id' => $this->training_class_student_id,
            'overall' => $this->overall,
            'status' => $this->status,
            'created' => $this->created,
            'created_by' => $this->created_by,
            'modified' => $this->modified,
            'modified_by' => $this->modified_by,
        ]);

        $query->andFilterWhere(['like', 'value', $this->value])
            ->andFilterWhere(['like', 'text1', $this->text1])
            ->andFilterWhere(['like', 'text2', $this->text2])
            ->andFilterWhere(['like', 'text3', $this->text3])
            ->andFilterWhere(['like', 'text4', $this->text4])
            ->andFilterWhere(['like', 'text5', $this->text5])
            ->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}
