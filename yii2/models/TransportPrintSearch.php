<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TransportPrint;

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
            [['id', 'transport'], 'integer'],
            [['filename', 'create_ts', 'send_ts', 'to_emails'], 'safe'],
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
            'transport' => $this->transport,
            'create_ts' => $this->create_ts,
            'send_ts' => $this->send_ts,
        ]);

        $query->andFilterWhere(['like', 'filename', $this->filename])
            ->andFilterWhere(['like', 'to_emails', $this->to_emails]);

        return $dataProvider;
    }
}
