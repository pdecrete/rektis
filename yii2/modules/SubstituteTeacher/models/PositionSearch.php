<?php

namespace app\modules\SubstituteTeacher\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\SubstituteTeacher\models\Position;

/**
 * PositionSearch represents the model behind the search form about `app\modules\SubstituteTeacher\models\Position`.
 */
class PositionSearch extends Position
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'operation_id', 'specialisation_id', 'prefecture_id', 'teachers_count', 'hours_count', 'whole_teacher_hours', 'covered_teachers_count', 'covered_hours_count'], 'integer'],
            [['title', 'created_at', 'updated_at'], 'safe'],
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
    public function search($params, $pagesize = null)
    {
        $query = Position::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        if ($pagesize !== null) {
            $dataProvider->pagination->pagesize = $pagesize;
//            $dataProvider->pagination = new Pagination(['pagesize' => 10]);
        }

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'operation_id' => $this->operation_id,
            'specialisation_id' => $this->specialisation_id,
            'prefecture_id' => $this->prefecture_id,
            'teachers_count' => $this->teachers_count,
            'hours_count' => $this->hours_count,
            'whole_teacher_hours' => $this->whole_teacher_hours,
            'covered_teachers_count' => $this->covered_teachers_count,
            'covered_hours_count' => $this->covered_hours_count,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}
