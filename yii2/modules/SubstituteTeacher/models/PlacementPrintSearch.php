<?php

namespace app\modules\SubstituteTeacher\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\SubstituteTeacher\models\PlacementPrint;

/**
 * PlacementPrintSearch represents the model behind the search form about `app\modules\SubstituteTeacher\models\PlacementPrint`.
 */
class PlacementPrintSearch extends PlacementPrint
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'placement_id', 'placement_teacher_id', 'deleted'], 'integer'],
            [['type', 'filename', 'data', 'deleted_at', 'created_at', 'updated_at'], 'safe'],
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
        $query = PlacementPrint::find();

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
            'placement_teacher_id' => $this->placement_teacher_id,
            'deleted' => $this->deleted,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'filename', $this->filename])
            ->andFilterWhere(['like', 'data', $this->data]);

        return $dataProvider;
    }
}
