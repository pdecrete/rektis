<?php

namespace app\modules\schooltransport\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\schooltransport\models\SchtransportProgram;

/**
 * SchtransportProgramSearch represents the model behind the search form about `app\modules\schooltransport\models\SchtransportProgram`.
 */
class SchtransportProgramSearch extends SchtransportProgram
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['program_id', 'programcategory_id'], 'integer'],
            [['program_title', 'program_code'], 'safe'],
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
        $query = SchtransportProgram::find();

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
            'program_id' => $this->program_id,
            'programcategory_id' => $this->programcategory_id,
        ]);

        $query->andFilterWhere(['like', 'program_title', $this->program_title])
            ->andFilterWhere(['like', 'program_code', $this->program_code]);

        return $dataProvider;
    }
}
