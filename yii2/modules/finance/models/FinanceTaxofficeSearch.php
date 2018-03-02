<?php

namespace app\modules\finance\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * FinanceTaxofficeSearch represents the model behind the search form about `app\modules\finance\models\FinanceTaxoffice`.
 */
class FinanceTaxofficeSearch extends FinanceTaxoffice
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['taxoffice_id'], 'integer'],
            [['taxoffice_name'], 'safe'],
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
        $query = FinanceTaxoffice::find();

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
            'taxoffice_id' => $this->taxoffice_id,
        ]);

        $query->andFilterWhere(['like', 'taxoffice_name', $this->taxoffice_name]);

        return $dataProvider;
    }
}
