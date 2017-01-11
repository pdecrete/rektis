<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TransportFunds;

/**
 * TransportFundsSearch represents the model behind the search form about `app\models\TransportFunds`.
 */
class TransportFundsSearch extends TransportFunds
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'service'], 'integer'],
            [['amount'], 'number'],
            [['count_flag'], 'boolean'],
            [['name', 'date', 'year', 'ada', 'code', 'kae', 'amount', 'count_flag'], 'safe'],
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
        $query = TransportFunds::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'service' => $this->service,
            'date' => $this->date,
            'amount' => $this->amount,
            'count_flag' => $this->count_flag,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'ada', $this->ada])
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'kae', $this->kae])
			->andFilterWhere(['like', 'year', $this->year]);

        return $dataProvider;
    }
}
