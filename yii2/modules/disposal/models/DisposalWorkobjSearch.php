<?php

namespace app\modules\disposal\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * DisposalWorkobjSearch represents the model behind the search form about `app\modules\disposal\models\DisposalWorkobj`.
 */
class DisposalWorkobjSearch extends DisposalWorkobj
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['disposalworkobj_id'], 'integer'],
            [['disposalworkobj_name', 'disposalworkobj_description'], 'safe'],
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
        $query = DisposalWorkobj::find();

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
            'disposalworkobj_id' => $this->disposalworkobj_id,
        ]);

        $query->andFilterWhere(['like', 'disposalworkobj_name', $this->disposalworkobj_name])
            ->andFilterWhere(['like', 'disposalworkobj_description', $this->disposalworkobj_description]);

        return $dataProvider;
    }
}
