<?php

namespace app\modules\SubstituteTeacher\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PlacementSearch represents the model behind the search form about `app\modules\SubstituteTeacher\models\Placement`.
 */
class PlacementSearch extends Placement
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'call_id', 'deleted'], 'integer'],
            [['date', 'base_contract_start_date', 'base_contract_end_date', 'decision_board', 'decision', 'ada', 'comments'], 'safe'],
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
        $query = Placement::find();

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
            'call_id' => $this->call_id,
            'date' => $this->date,
            'base_contract_start_date' => $this->base_contract_start_date,
            'base_contract_end_date' => $this->base_contract_end_date,
            'deleted' => $this->deleted,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'decision_board', $this->decision_board])
            ->andFilterWhere(['like', 'decision', $this->decision])
            ->andFilterWhere(['like', 'comments', $this->comments])
            ->andFilterWhere(['like', 'ada', $this->ada]);

        return $dataProvider;
    }
}
