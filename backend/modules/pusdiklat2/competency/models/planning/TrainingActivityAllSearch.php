<?php

namespace backend\modules\pusdiklat2\competency\models\planning;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Activity;

/**
 * ActivitySearch represents the model behind the search form about `backend\models\Activity`.
 */
class TrainingActivityAllSearch extends Activity
{
    public $year, $program_id;
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['program_id','id', 'satker_id', 'hostel', 'status', 'created_by', 'modified_by'], 'integer'],
            [['name', 'description', 'start', 'end', 'location', 'created', 'modified'], 'safe'],
			[['year'], 'safe'],
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
		
        $query = Activity::find()
			->joinWith('training',false,'RIGHT JOIN');
			
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
			'program_id' => $this->program_id,
            'start' => $this->start,
            'end' => $this->end,
            'hostel' => $this->hostel,
            'status' => $this->status,
            'created' => $this->created,
            'created_by' => $this->created_by,
            'modified' => $this->modified,
            'modified_by' => $this->modified_by,
			'YEAR(start)' => $this->year,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'location', $this->location]);

        return $dataProvider;
    }
}
