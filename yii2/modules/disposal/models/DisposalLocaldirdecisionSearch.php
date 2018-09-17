<?php

namespace app\modules\disposal\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\disposal\models\DisposalLocaldirdecision;

/**
 * DisposalLocaldirdecisionSearch represents the model behind the search form about `app\modules\disposal\models\DisposalLocaldirdecision`.
 */
class DisposalLocaldirdecisionSearch extends DisposalLocaldirdecision
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['localdirdecision_id', 'created_by', 'updated_by', 'deleted', 'archived'], 'integer'],
            [['localdirdecision_protocol', 'localdirdecision_subject', 'localdirdecision_action', 'created_at', 'updated_at'], 'safe'],
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
        $query = DisposalLocaldirdecision::find();

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
            'localdirdecision_id' => $this->localdirdecision_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted' => $this->deleted,
            'archived' => $this->archived,
        ]);

        $query->andFilterWhere(['like', 'localdirdecision_protocol', $this->localdirdecision_protocol])
            ->andFilterWhere(['like', 'localdirdecision_subject', $this->localdirdecision_subject])
            ->andFilterWhere(['like', 'localdirdecision_action', $this->localdirdecision_action]);

        return $dataProvider;
    }
}
