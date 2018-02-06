<?php

namespace app\modules\finance\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\finance\models\FinanceInvoicetype;

/**
 * FinanceInvoicetypeSearch represents the model behind the search form about `app\modules\finance\models\FinanceInvoicetype`.
 */
class FinanceInvoicetypeSearch extends FinanceInvoicetype
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['invtype_id'], 'integer'],
            [['invtype_title'], 'safe'],
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
        $query = FinanceInvoicetype::find();

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
            'invtype_id' => $this->invtype_id,
        ]);

        $query->andFilterWhere(['like', 'invtype_title', $this->invtype_title]);

        return $dataProvider;
    }
}
