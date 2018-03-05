<?php

namespace app\modules\finance\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * FinanceSupplierSearch represents the model behind the search form about `app\modules\finance\models\FinanceSupplier`.
 */
class FinanceSupplierSearch extends FinanceSupplier
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['suppl_id', 'suppl_vat', 'suppl_phone', 'suppl_fax', 'taxoffice_id'], 'integer'],
            [['suppl_name', 'suppl_address', 'suppl_iban', 'suppl_employerid'], 'safe'],
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
        $query = FinanceSupplier::find();

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
            'suppl_id' => $this->suppl_id,
            'suppl_vat' => $this->suppl_vat,
            'suppl_phone' => $this->suppl_phone,
            'suppl_fax' => $this->suppl_fax,
            'taxoffice_id' => $this->taxoffice_id,
        ]);

        $query->andFilterWhere(['like', 'suppl_name', $this->suppl_name])
            ->andFilterWhere(['like', 'suppl_address', $this->suppl_address])
            ->andFilterWhere(['like', 'suppl_iban', $this->suppl_iban])
            ->andFilterWhere(['like', 'suppl_employerid', $this->suppl_employerid]);

        return $dataProvider;
    }
}
