<?php

namespace app\modules\schooltransport\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SchtransportMeetingSearch represents the model behind the search form about `app\modules\schooltransport\models\SchtransportMeeting`.
 */
class SchtransportMeetingSearch extends SchtransportMeeting
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['meeting_id', 'program_id'], 'integer'],
            [['meeting_city', 'meeting_country', 'meeting_startdate', 'meeting_enddate'], 'safe'],
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
        $query = SchtransportMeeting::find();

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
            'meeting_id' => $this->meeting_id,
            'meeting_startdate' => $this->meeting_startdate,
            'meeting_enddate' => $this->meeting_enddate,
            'program_id' => $this->program_id,
        ]);

        $query->andFilterWhere(['like', 'meeting_city', $this->meeting_city])
            ->andFilterWhere(['like', 'meeting_country', $this->meeting_country]);

        return $dataProvider;
    }
}
