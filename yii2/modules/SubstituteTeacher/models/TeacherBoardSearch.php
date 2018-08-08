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
    public $operation_id_search;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'teacher_id', 'specialisation_id', 'board_type', 'order', 'year', 'status', 'operation_id_search'], 'integer'],
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
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'operation_id_search' => Yii::t('substituteteacher', 'Operation'),
        ]);
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
            ->with('teacherRegistry')
            ->joinWith('teacher');

        $this->load($params);
        // add conditions that should always apply here

        if (!empty($this->operation_id_search)) {
            // TODO perhaps convert this to an exists subquery
            $query = $query->joinWith([
                'teacher', 
                'placementTeachers',
                'placementTeachers.placementPositions',
                'placementTeachers.placementPositions.position',
                'placementTeachers.placementPositions.position.operation',
            ]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['year'] = [
            'label' => Yii::t('substituteteacher', 'Year'),
            'asc' => ['{{%stteacher}}.year' => SORT_ASC],
            'desc' => ['{{%stteacher}}.year' => SORT_DESC],
        ];

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'teacher_id' => $this->teacher_id,
            '{{%stteacher_board}}.specialisation_id' => $this->specialisation_id,
            'board_type' => $this->board_type,
            'points' => $this->points,
            'order' => $this->order,
            '{{%stteacher_board}}.status' => $this->status,
            '{{%stteacher}}.year' => $this->year,
            '{{%stoperation}}.id' => $this->operation_id_search,
        ]);

        return $dataProvider;
    }
}
