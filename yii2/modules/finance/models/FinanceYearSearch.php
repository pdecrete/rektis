<?php

namespace app\modules\finance\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\finance\models\FinanceYear;

/**
 * FinanceYearSearch represents the model behind the search form about `app\modules\finance\models\FinanceYear`.
 */
class FinanceYearSearch extends FinanceYear
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['year', 'year_credit', 'year_iscurrent', 'year_lock'], 'integer'],
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
        $query = FinanceYear::find();

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
            'year' => $this->year,
            'year_credit' => $this->year_credit,
            'year_iscurrent' => $this->year_iscurrent,
            'year_lock' => $this->year_lock,
        ]);

        return $dataProvider;
    }
}
