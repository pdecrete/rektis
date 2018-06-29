<?php

namespace app\modules\SubstituteTeacher\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * TeacherBoardSearch represents the model behind the search form about `app\modules\SubstituteTeacher\models\TeacherBoard`.
 */
class TeacherBoardSearch extends TeacherBoard
{
    public $year; // to filter teachers by year

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'teacher_id', 'specialisation_id', 'board_type', 'order', 'year', 'status'], 'integer'],
            [['points'], 'number'],
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
        $query = TeacherBoard::find()
            ->joinWith('teacher');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['year'] = [
            'label' => Yii::t('substituteteacher', 'Year'),
            'asc' => ['{{%stteacher}}.year' => SORT_ASC],
            'desc' => ['{{%stteacher}}.year' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'teacher_id' => $this->teacher_id,
            'specialisation_id' => $this->specialisation_id,
            'board_type' => $this->board_type,
            'points' => $this->points,
            'order' => $this->order,
            '{{%stteacher_board}}.status' => $this->status,
            '{{%stteacher}}.year' => $this->year,
        ]);

        return $dataProvider;
    }
}
