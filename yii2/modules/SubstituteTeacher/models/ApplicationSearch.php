<?php

namespace app\modules\SubstituteTeacher\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ApplicationSearch represents the model behind the search form about `app\modules\SubstituteTeacher\models\Application`.
 */
class ApplicationSearch extends Application
{
    protected $default_filter = [
        'ApplicationSearch' => [
            'deleted' => Application::APPLICATION_NOT_DELETED
        ]
    ];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'call_id', 'teacher_board_id', 'agreed_terms_ts', 'state', 'state_ts', 'deleted'], 'integer'],
            [['reference', 'created_at', 'updated_at'], 'safe'],
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
        $query = Application::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        // default filters
        $params = array_merge($this->default_filter, $params);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'call_id' => $this->call_id,
            'teacher_board_id' => $this->teacher_board_id,
            'agreed_terms_ts' => $this->agreed_terms_ts,
            'state' => $this->state,
            'state_ts' => $this->state_ts,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted' => $this->deleted,
        ]);

        $query->andFilterWhere(['like', 'reference', $this->reference]);

        return $dataProvider;
    }
}
