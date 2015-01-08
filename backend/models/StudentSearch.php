<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Student;

/**
 * StudentSearch represents the model behind the search form about `backend\models\Student`.
 */
class StudentSearch extends Student
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['person_id', 'satker', 'status'], 'integer'],
            [['username', 'password_hash', 'auth_key', 'password_reset_token', 'eselon2', 'eselon3', 'eselon4', 'no_sk', 'tmt_sk'], 'safe'],
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
        $query = Student::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'person_id' => $this->person_id,
            'satker' => $this->satker,
            'no_sk' => $this->no_sk,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'eselon2', $this->eselon2])
            ->andFilterWhere(['like', 'eselon3', $this->eselon3])
            ->andFilterWhere(['like', 'eselon4', $this->eselon4])
            ->andFilterWhere(['like', 'tmt_sk', $this->tmt_sk]);

        return $dataProvider;
    }
}
