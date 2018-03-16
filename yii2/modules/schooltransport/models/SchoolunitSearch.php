<?php

namespace app\modules\schooltransport\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\schooltransport\models\Schoolunit;

/**
 * SchoolunitSearch represents the model behind the search form about `app\modules\schooltransport\models\Schoolunit`.
 */
class SchoolunitSearch extends Schoolunit
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['school_id', 'directorate_id'], 'integer'],
            [['school_name'], 'safe'],
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
        $query = Schoolunit::find();

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
            'school_id' => $this->school_id,
            'directorate_id' => $this->directorate_id,
        ]);

        $query->andFilterWhere(['like', 'school_name', $this->school_name]);

        return $dataProvider;
    }
}
