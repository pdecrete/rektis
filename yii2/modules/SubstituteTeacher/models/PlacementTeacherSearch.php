<?php

namespace app\modules\SubstituteTeacher\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\SubstituteTeacher\models\PlacementTeacher;

/**
 * PlacementTeacherSearch represents the model behind the search form about `app\modules\SubstituteTeacher\models\PlacementTeacher`.
 */
class PlacementTeacherSearch extends PlacementTeacher
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'placement_id', 'teacher_board_id', 'cancelled', 'altered', 'dismissed'], 'integer'],
            [['comments', 'altered_at', 'dismissed_at', 'dismissed_ada', 'cancelled_ada', 'contract_start_date', 'contract_end_date', 'service_start_date', 'service_end_date'], 'safe'],
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
        $query = PlacementTeacher::find()
            ->with(['summaryPrints', 'contractPrints']);

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
            'placement_id' => $this->placement_id,
            'teacher_board_id' => $this->teacher_board_id,
            'altered' => $this->altered,
            'altered_at' => $this->altered_at,
            'dismissed' => $this->dismissed,
            'dismissed_at' => $this->dismissed_at,
            'cancelled' => $this->cancelled,
            'cancelled_at' => $this->cancelled_at,
            'contract_start_date' => $this->contract_start_date,
            'contract_end_date' => $this->contract_end_date,
            'service_start_date' => $this->service_start_date,
            'service_end_date' => $this->service_end_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'comments', $this->comments])
            ->andFilterWhere(['like', 'dismissed_ada', $this->dismissed_ada])
            ->andFilterWhere(['like', 'cancelled_ada', $this->cancelled_ada]);

        return $dataProvider;
    }
}
