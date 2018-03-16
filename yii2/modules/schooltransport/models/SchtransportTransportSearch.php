<?php

namespace app\modules\schooltransport\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\schooltransport\models\SchtransportTransport;

/**
 * SchtransportTransportSearch represents the model behind the search form about `app\modules\schooltransport\models\SchtransportTransport`.
 */
class SchtransportTransportSearch extends SchtransportTransport
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['transport_id', 'meeting_id', 'school_id'], 'integer'],
            [['transport_submissiondate', 'transport_startdate', 'transport_enddate', 'transport_teachers', 'transport_students'], 'safe'],
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
        $query = SchtransportTransport::find();

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
            'transport_id' => $this->transport_id,
            'transport_submissiondate' => $this->transport_submissiondate,
            'transport_startdate' => $this->transport_startdate,
            'transport_enddate' => $this->transport_enddate,
            'meeting_id' => $this->meeting_id,
            'school_id' => $this->school_id,
        ]);

        $query->andFilterWhere(['like', 'transport_teachers', $this->transport_teachers])
            ->andFilterWhere(['like', 'transport_students', $this->transport_students]);

        return $dataProvider;
    }
}
