<?php

namespace app\modules\schooltransport\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\schooltransport\models\SchtransportProgramcategory;

/**
 * SchtransportProgramcategorySearch represents the model behind the search form about `app\modules\schooltransport\models\SchtransportProgramcategory`.
 */
class SchtransportProgramcategorySearch extends SchtransportProgramcategory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['programcategory_id'], 'integer'],
            [['programcategory_actioncode', 'programcategory_actiontitle', 'programcategory_actionsubcateg'], 'safe'],
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
        $query = SchtransportProgramcategory::find();

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
            'programcategory_id' => $this->programcategory_id,
        ]);

        $query->andFilterWhere(['like', 'programcategory_actioncode', $this->programcategory_actioncode])
            ->andFilterWhere(['like', 'programcategory_actiontitle', $this->programcategory_actiontitle])
            ->andFilterWhere(['like', 'programcategory_actionsubcateg', $this->programcategory_actionsubcateg]);

        return $dataProvider;
    }
}
