<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * TransportSearch represents the model behind the search form about `app\models\Transport`.
 */
class TransportSearch extends Transport
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'employee', 'type', 'decision_protocol', 'application_protocol', 'from_to', 'days_applied', 'days_out', 'mode', 'deleted'], 'integer'],
            [['decision_protocol_date', 'application_protocol_date', 'application_date', 'accompanying_document', 'start_date', 'end_date', 'reason', 'expense_details', 'extra_reason', 'comment', 'create_ts', 'update_ts'], 'safe'],
            [['ticket_value', 'klm_reimb', 'day_reimb', 'reimbursement', 'mtpy', 'pay_amount', 'night_reimb', 'klm', 'code719', 'code721', 'code722'], 'number'],
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
        $query = Transport::find();

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
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'from_to' => $this->from_to,
            'days_applied' => $this->days_applied,
            'klm' => $this->klm,
            'mode' => $this->mode,
            'ticket_value' => $this->ticket_value,
            'night_reimb' => $this->night_reimb,
            'klm_reimb' => $this->klm_reimb,
            'days_out' => $this->days_out,
            'day_reimb' => $this->day_reimb,
            'reimbursement' => $this->reimbursement,
            'mtpy' => $this->mtpy,
            'pay_amount' => $this->pay_amount,
            'code719' => $this->code719,
            'code721' => $this->code721,
            'code722' => $this->code722,
            'create_ts' => $this->create_ts,
            'update_ts' => $this->update_ts,
            'deleted' => $this->deleted,
        ]);

        $query->andFilterWhere(['like', 'accompanying_document', $this->accompanying_document])
            ->andFilterWhere(['like', 'reason', $this->reason])
            ->andFilterWhere(['like', 'extra_reason', $this->extra_reason])
            ->andFilterWhere(['like', 'expense_details', $this->expense_details])
            ->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}
