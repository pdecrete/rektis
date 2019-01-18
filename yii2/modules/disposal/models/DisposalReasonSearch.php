<?php

namespace app\modules\disposal\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * DisposalReasonSearch represents the model behind the search form about `app\modules\disposal\models\DisposalReason`.
 */
class DisposalReasonSearch extends DisposalReason
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['disposalreason_id'], 'integer'],
            [['disposalreason_name', 'disposalreason_description'], 'safe'],
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
        $query = DisposalReason::find();

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
            'disposalreason_id' => $this->disposalreason_id,
        ]);

        $query->andFilterWhere(['like', 'disposalreason_name', $this->disposalreason_name])
            ->andFilterWhere(['like', 'disposalreason_description', $this->disposalreason_description]);

        return $dataProvider;
    }
}
