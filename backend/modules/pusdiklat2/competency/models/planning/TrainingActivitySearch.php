<?php

namespace backend\modules\pusdiklat2\competency\models\planning;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Activity;

/**
 * ActivitySearch represents the model behind the search form about `backend\models\Activity`.
 */
class TrainingActivitySearch extends Activity
{
    public $year;
	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'satker_id', 'hostel', 'status', 'created_by', 'modified_by'], 'integer'],
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
    public function search($params, $satker_id=null)
    {
		// satker_id dibuat lebih fleksibel, jadi pusdiklat bisa pantau bdk, cukup kasih aja di argumennya
        if ($satker_id == null) $satker_id = (int)Yii::$app->user->identity->employee->satker_id;
		
        $query = Activity::find()
			->joinWith('training',false,'RIGHT JOIN')
			->where([
				'satker_id' => $satker_id,
			]);
			
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'satker_id' => $this->satker_id,
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
