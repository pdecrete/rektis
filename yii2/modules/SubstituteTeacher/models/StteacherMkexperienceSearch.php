<?php

namespace app\modules\SubstituteTeacher\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\SubstituteTeacher\models\StteacherMkexperience;

/**
 * StteacherMkexperienceSearch represents the model behind the search form about `app\modules\SubstituteTeacher\models\StteacherMkexperience`.
 */
class StteacherMkexperienceSearch extends StteacherMkexperience
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'teacher_id', 'exp_years', 'exp_months', 'exp_days', 'exp_sectortype', 'exp_mkvalid'], 'integer'],
            [['exp_startdate', 'exp_enddate', 'exp_sectorname', 'exp_info'], 'safe'],
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
        $query = StteacherMkexperience::find();
        
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
            'exp_startdate' => $this->exp_startdate,
            'exp_enddate' => $this->exp_enddate,
            'exp_years' => $this->exp_years,
            'exp_months' => $this->exp_months,
            'exp_days' => $this->exp_days,
            'exp_mkvalid' => $this->exp_mkvalid,
            'exp_sectortype' => $this->exp_sectortype,
        ]);

        //$query->andFilterWhere(['like', 'exp_sectorname', $this->exp_sectorname])
        //    ->andFilterWhere(['like', 'exp_sectortype_label', $this->exp_sectortype_label])
        //    ->andFilterWhere(['like', 'exp_info', $this->exp_info]);
        $query->orderBy(['id'=>SORT_ASC]);//,'exp_mkvalid'=>SORT_DESC, 'exp_startdate'=>SORT_DESC]);
        //$query->groupBy('teacher_id');

        return $dataProvider;
    }
}
