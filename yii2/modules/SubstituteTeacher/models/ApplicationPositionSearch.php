<?php

namespace app\modules\SubstituteTeacher\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\SubstituteTeacher\models\ApplicationPosition;

/**
 * ApplicationPositionSearch represents the model behind the search form about `app\modules\SubstituteTeacher\models\ApplicationPosition`.
 */
class ApplicationPositionSearch extends ApplicationPosition
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'application_id', 'call_position_id', 'order', 'updated', 'deleted'], 'integer'],
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
        $query = ApplicationPosition::find();

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
            'application_id' => $this->application_id,
            'call_position_id' => $this->call_position_id,
            'order' => $this->order,
            'updated' => $this->updated,
            'deleted' => $this->deleted,
        ]);

        return $dataProvider;
    }
}
