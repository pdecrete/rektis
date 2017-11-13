<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * TransportPrintSearch represents the model behind the search form about `app\models\TransportPrint`.
 */
class TransportPrintSearch extends TransportPrint
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['filename', 'doctype', 'create_ts', 'send_ts', 'to_emails', 'from', 'to', 'sum719', 'sum721', 'sum722', 'sum_mtpy', 'paid', 'total', 'clean', 'asum719', 'asum721', 'asum722', 'asum_mtpy', 'atotal', 'aclean'], 'safe'],
            [['sum719', 'sum721', 'sum722', 'sum_mtpy', 'total', 'clean', 'asum719', 'asum721', 'asum722', 'asum_mtpy', 'atotal', 'aclean'], 'number'],
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
        $query = TransportPrint::find();
        $query->orderby(' create_ts DESC ');
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [ 'pageSize' => 10 ],
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
            'create_ts' => $this->create_ts,
            'send_ts' => $this->send_ts,
            'doctype' => $this->doctype,
            'sum719' => $this->sum719,
            'sum721' => $this->sum721,
            'sum722' => $this->sum722,
            'sum_mtpy' => $this->sum_mtpy,
            'total' => $this->total,
            'clean' => $this->clean,
            'asum719' => $this->asum719,
            'asum721' => $this->asum721,
            'asum722' => $this->asum722,
            'asum_mtpy' => $this->asum_mtpy,
            'atotal' => $this->atotal,
            'aclean' => $this->aclean,
            'from' => $this->from,
            'to' => $this->to,
            'paid' => $this->paid,

        ]);

        $query->andFilterWhere(['like', 'filename', $this->filename])
            ->andFilterWhere(['like', 'to_emails', $this->to_emails]);

        return $dataProvider;
    }
}
