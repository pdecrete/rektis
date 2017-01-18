<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Leave;

/**
 * LeaveSearch represents the model behind the search form about `app\models\Leave`.
 */
class LeaveSearch extends Leave
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'employee', 'type', 'decision_protocol', 'application_protocol', 'duration'], 'integer'],
            [['decision_protocol_date', 'application_protocol_date', 'application_date', 'accompanying_document', 'start_date', 'end_date', 'extra_reason1', 'extra_reason2', 'extra_reason3', 'reason', 'comment', 'create_ts', 'update_ts'], 'safe'],
            [['type'], 'safe'],
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
        $query = Leave::find();

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
            'employee' => $this->employee,
            'type' => $this->type,
            'decision_protocol' => $this->decision_protocol,
            'decision_protocol_date' => $this->decision_protocol_date,
            'application_protocol' => $this->application_protocol,
            'application_protocol_date' => $this->application_protocol_date,
            'application_date' => $this->application_date,
            'duration' => $this->duration,
            'deleted' => $this->deleted,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'create_ts' => $this->create_ts,
            'update_ts' => $this->update_ts,
        ]);

        $query->andFilterWhere(['like', 'accompanying_document', $this->accompanying_document])
                ->andFilterWhere(['like', 'reason', $this->reason])
                ->andFilterWhere(['like', 'extra_reason1', $this->extra_reason1])
                ->andFilterWhere(['like', 'extra_reason2', $this->extra_reason2])
                ->andFilterWhere(['like', 'extra_reason3', $this->extra_reason3])
                ->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }

}
