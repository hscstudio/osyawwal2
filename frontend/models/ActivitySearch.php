<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Activity;

/**
 * ActivitySearch represents the model behind the search form about `frontend\models\Activity`.
 */
class ActivitySearch extends Activity
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
	 
    public function search($params,$year=NULL,$satker_id=NULL)
    {
       if($year=='all'){
				if(!empty($satker_id))
				{
					$queryParams=[
						'status'=> [0,1,2],
						'satker_id'=>$satker_id,
					];
				}
				else
				{
					$queryParams=[
						'status'=> [0,1,2],
					];
				}
			}
			else{
				if(!empty($satker_id))
				{
					$queryParams=[
						'YEAR(start)' => $year,
						'status'=> [0,1,2],
						'satker_id'=>$satker_id,
					];
				}
				else
				{
					
					$queryParams=[
						'YEAR(start)' => $year,
						'status'=> [0,1,2],
					];
					
				}
			}
		$query = Activity::find()
				->joinWith('training',false,'RIGHT JOIN')
				->where($queryParams);
				
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
		
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'satker_id' => $this->satker_id,
			'name' =>$this->name,
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
            ->andFilterWhere(['like', 'start', $this->start])
            ->andFilterWhere(['like', 'end', $this->end]);
		
        return $dataProvider;
    }
}
