<?php

namespace app\modules\finance\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\finance\models\KAE;

/**
 * KAESearch represents the model behind the search form about `app\modules\finance\models\KAE`.
 */
class KAESearch extends KAE
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kae_id'], 'integer'],
            [['kae_title', 'kae_description'], 'safe'],
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
        $query = KAE::find();

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
            'kae_id' => $this->kae_id,
        ]);

        $query->andFilterWhere(['like', 'kae_title', $this->kae_title])
            ->andFilterWhere(['like', 'kae_description', $this->kae_description]);

        return $dataProvider;
    }
}
