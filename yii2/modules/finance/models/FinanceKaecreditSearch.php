<?php

namespace app\modules\finance\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * FinanceKaecreditSearch represents the model behind the search form about `app\modules\finance\models\FinanceKaecredit`.
 */
class FinanceKaecreditSearch extends FinanceKaecredit
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kaecredit_id', 'year', 'kae_id'], 'integer'],
            [['kaecredit_amount'], 'number'],
            [['kaecredit_date', 'kaecredit_updated'], 'safe'],
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
        $query = FinanceKaecredit::find();

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
            'kaecredit_id' => $this->kaecredit_id,
            'kaecredit_amount' => $this->kaecredit_amount,
            'kaecredit_date' => $this->kaecredit_date,
            'kaecredit_updated' => $this->kaecredit_updated,
            'year' => $this->year,
            'kae_id' => $this->kae_id,
        ]);

        return $dataProvider;
    }
}
