<?php

namespace app\modules\finance\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\finance\models\FinanceState;

/**
 * FinanceStateSearch represents the model behind the search form about `app\modules\finance\models\FinanceState`.
 */
class FinanceStateSearch extends FinanceState
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['state_id'], 'integer'],
            [['state_name'], 'safe'],
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
        $query = FinanceState::find();

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
            'state_id' => $this->state_id,
        ]);

        $query->andFilterWhere(['like', 'state_name', $this->state_name]);

        return $dataProvider;
    }
}