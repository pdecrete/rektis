<?php

namespace app\modules\schooltransport\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SchtransportTransportSearch represents the model behind the search form about `app\modules\schooltransport\models\SchtransportTransport`.
 */
class SchtransportTransportSearch extends SchtransportTransport
{
    public $meeting_country;
    public $meeting_city;
    public $school_name;
    public $meeting_startdate;
    public $meeting_enddate;
    public $programcategory_programtitle;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [[['transport_id', 'meeting_id', 'school_id'], 'integer'],
                [['transport_submissiondate', 'transport_startdate', 'transport_enddate', 'transport_creationdate', 'transport_teachers',
                'transport_students', 'transport_localdirectorate_protocol', 'transport_pde_protocol', 'transport_remarks',
                'transport_datesentapproval', 'transport_dateprotocolcompleted', 'transport_approvalfile',
                'transport_signedapprovalfile', 'meeting_city' ,'meeting_country', 'meeting_startdate',
                'meeting_enddate', 'programcategory_programtitle', 'school_name'], 'safe']];
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
    public function search($params, $archived = 0)
    {
        $query = parent::getAllTransportsQuery(true, $archived);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [ 'attributes' => ['school_name', 'meeting_country', 'meeting_city', 'transport_startdate', 'transport_enddate', 'transport_creationdate',
                        'meeting_startdate', 'meeting_enddate', 'programcategory_programtitle', 'statescount'],
                        'defaultOrder' => ['transport_creationdate'=>SORT_DESC]]
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
            'transport_startdate' => $this->transport_startdate,
            'transport_enddate' => $this->transport_enddate,
            'meeting_startdate' => $this->meeting_startdate,
            //'meeting_enddate' => $this->meeting_enddate
        ]);

        $query->andFilterWhere(['like', 'meeting_country', $this->meeting_country])
                ->andFilterWhere(['like', 'meeting_city', $this->meeting_city])
                ->andFilterWhere(['like', 'school_name', $this->school_name])
                ->andFilterWhere(['like', 'programcategory_programtitle', $this->programcategory_programtitle]);

        return $dataProvider;
    }
}
