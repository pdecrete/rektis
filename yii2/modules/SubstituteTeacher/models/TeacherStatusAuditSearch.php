<?php

namespace app\modules\SubstituteTeacher\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\SubstituteTeacher\models\TeacherStatusAudit;

/**
 * TeacherStatusAuditSearch represents the model behind the search form about `app\modules\SubstituteTeacher\models\TeacherStatusAudit`.
 */
class TeacherStatusAuditSearch extends TeacherStatusAudit
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'teacher_id', 'status'], 'integer'],
            [['audit_ts', 'audit', 'data'], 'safe'],
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
        $query = TeacherStatusAudit::find();

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
            'teacher_id' => $this->teacher_id,
            'status' => $this->status,
            'audit_ts' => $this->audit_ts,
        ]);

        $query->andFilterWhere(['like', 'audit', $this->audit])
            ->andFilterWhere(['like', 'data', $this->data]);

        return $dataProvider;
    }
}
