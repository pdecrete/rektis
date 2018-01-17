<?php

namespace app\modules\finance\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\finance\models\FinanceInvoice;

/**
 * FinanceInvoiceSearch represents the model behind the search form about `app\modules\finance\models\FinanceInvoice`.
 */
class FinanceInvoiceSearch extends FinanceInvoice
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['inv_id', 'inv_date', 'inv_deleted', 'suppl_id', 'exp_id', 'invtype_id'], 'integer'],
            [['inv_number', 'inv_order'], 'safe'],
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
        $query = FinanceInvoice::find();

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
            'inv_id' => $this->inv_id,
            'inv_date' => $this->inv_date,
            'inv_deleted' => $this->inv_deleted,
            'suppl_id' => $this->suppl_id,
            'exp_id' => $this->exp_id,
            'invtype_id' => $this->invtype_id,
        ]);

        $query->andFilterWhere(['like', 'inv_number', $this->inv_number])
            ->andFilterWhere(['like', 'inv_order', $this->inv_order]);

        return $dataProvider;
    }
}
