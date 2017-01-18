<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\LeaveBalance;

/**
 * LeaveBalanceSearch represents the model behind the search form about `app\models\LeaveBalance`.
 */
class LeaveBalanceSearch extends LeaveBalance
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'employee', 'leave_type', 'days'], 'integer'],
            [['year'], 'safe'],
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
        $query = LeaveBalance::find();

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
            'employee' => $this->employee,
            'leave_type' => $this->leave_type,
            'days' => $this->days,
        ]);

        $query->andFilterWhere(['like', 'year', $this->year]);

        return $dataProvider;
    }
}
