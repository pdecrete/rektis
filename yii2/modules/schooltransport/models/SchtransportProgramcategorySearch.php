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
            [['programcategory_id', 'programcategory_programparent'], 'integer'],
            [['programcategory_programalias', 'programcategory_programtitle', 'programcategory_programdescription'], 'safe'],
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
            'programcategory_programparent' => $this->programcategory_programparent,
        ]);

        $query->andFilterWhere(['like', 'programcategory_programtitle', $this->programcategory_programtitle])
            ->andFilterWhere(['like', 'programcategory_programdescription', $this->programcategory_programdescription]);

        return $dataProvider;
    }
}
