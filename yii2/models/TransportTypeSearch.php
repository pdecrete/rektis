<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TransportType;

/**
 * TransportTypeSearch represents the model behind the search form about `app\models\TransportType`.
 */
class TransportTypeSearch extends TransportType
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'deleted'], 'integer'],
            [['name', 'description', 'create_ts', 'update_ts', 'templatefilename1', 'templatefilename2', 'templatefilename3'], 'safe'],
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
        $query = TransportType::find();

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
            'create_ts' => $this->create_ts,
            'update_ts' => $this->update_ts,
            'deleted' => $this->deleted,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'templatefilename1', $this->templatefilename1])
            ->andFilterWhere(['like', 'templatefilename2', $this->templatefilename2])
            ->andFilterWhere(['like', 'templatefilename3', $this->templatefilename3]);

        return $dataProvider;
    }
}
