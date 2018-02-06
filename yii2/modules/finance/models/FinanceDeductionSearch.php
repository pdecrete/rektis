<?php

namespace app\modules\finance\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\finance\models\FinanceDeduction;

/**
 * FinanceDeductionSearch represents the model behind the search form about `app\modules\finance\models\FinanceDeduction`.
 */
class FinanceDeductionSearch extends FinanceDeduction
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['deduct_id', 'deduct_percentage', 'deduct_downlimit', 'deduct_uplimit', 'deduct_obsolete'], 'integer'],
            [['deduct_name', 'deduct_description', 'deduct_date'], 'safe'],
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
        $query = FinanceDeduction::find();

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
            'deduct_id' => $this->deduct_id,
            'deduct_date' => $this->deduct_date,
            'deduct_percentage' => $this->deduct_percentage,
            'deduct_downlimit' => $this->deduct_downlimit,
            'deduct_uplimit' => $this->deduct_uplimit,
            'deduct_obsolete' => $this->deduct_obsolete,
        ]);

        $query->andFilterWhere(['like', 'deduct_name', $this->deduct_name])
            ->andFilterWhere(['like', 'deduct_description', $this->deduct_description]);

        return $dataProvider;
    }
}
