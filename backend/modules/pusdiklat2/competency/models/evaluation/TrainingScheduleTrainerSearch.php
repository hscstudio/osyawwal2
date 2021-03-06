<?php

namespace backend\modules\pusdiklat2\competency\models\evaluation;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\TrainingScheduleTrainer;

/**
 * TrainingScheduleTrainerSearch represents the model behind the search form about `backend\models\TrainingScheduleTrainer`.
 */
class TrainingScheduleTrainerSearch extends TrainingScheduleTrainer
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'training_schedule_id', 'type', 'trainer_id', 'cost', 'status', 'created_by', 'modified_by'], 'integer'],
            [['hours'], 'number'],
            [['reason', 'created', 'modified'], 'safe'],
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
        $query = TrainingScheduleTrainer::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'training_schedule_id' => $this->training_schedule_id,
            'type' => $this->type,
            'trainer_id' => $this->trainer_id,
            'hours' => $this->hours,
            'cost' => $this->cost,
            'status' => $this->status,
            'created' => $this->created,
            'created_by' => $this->created_by,
            'modified' => $this->modified,
            'modified_by' => $this->modified_by,
        ]);

        $query->andFilterWhere(['like', 'reason', $this->reason]);

        return $dataProvider;
    }
}
