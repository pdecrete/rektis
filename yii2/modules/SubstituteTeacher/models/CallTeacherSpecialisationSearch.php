<?php

namespace app\modules\SubstituteTeacher\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * CallTeacherSpecialisationSearch represents the model behind the search form about `app\modules\SubstituteTeacher\models\CallTeacherSpecialisation`.
 */
class CallTeacherSpecialisationSearch extends CallTeacherSpecialisation
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'call_id', 'specialisation_id', 'teachers'], 'integer'],
            [['teachers_called'], 'safe'],
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
        $query = CallTeacherSpecialisation::find();

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
            'specialisation_id' => $this->specialisation_id,
            'teachers' => $this->teachers,
        ]);

        $query->andFilterWhere(['like', 'teachers_called', $this->teachers_called]);

        return $dataProvider;
    }
}
