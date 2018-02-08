<?php

namespace app\modules\SubstituteTeacher\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * CallPositionSearch represents the model behind the search form about `app\modules\SubstituteTeacher\models\CallPosition`.
 */
class CallPositionSearch extends CallPosition
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'call_id', 'position_id', 'teachers_count', 'hours_count'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
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
        $query = CallPosition::find();

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
            'position_id' => $this->position_id,
            'teachers_count' => $this->teachers_count,
            'hours_count' => $this->hours_count,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }
}
