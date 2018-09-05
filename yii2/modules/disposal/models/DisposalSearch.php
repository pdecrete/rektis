<?php

namespace app\modules\disposal\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * DisposalSearch represents the model behind the search form about `app\modules\disposal\models\Disposal`.
 */
class DisposalSearch extends Disposal
{
    public $teacher_name;
    public $teacher_surname;
    public $teacher_registrynumber;
    public $code;
    public $organic_school;
    public $disposal_school;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['disposal_id', 'teacher_id', 'school_id'], 'integer'],
            [['teacher_name', 'teacher_surname', 'teacher_registrynumber', 'organic_school', 'disposal_school', 'code'], 'string'],
            [['disposal_startdate', 'disposal_enddate', 'disposal_hours'], 'safe'],
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
    public function search($params, $archived = 0)
    {
        $prefix = Yii::$app->db->tablePrefix;
        $dspls = $prefix . 'disposal_disposal';
        $tchers = $prefix . 'teacher';
        $specs = $prefix . 'specialisation';
        $d_schls = $prefix . 'schoolunit dsp_sch';
        $o_schls = $prefix . 'schoolunit orgn_sch';
                
        $query = (new \yii\db\Query())
                    ->select([$dspls. ".*", $tchers . ".*", $specs . ".*, `dsp_sch`.school_name AS disposal_school, `orgn_sch`.school_name AS organic_school"])
                    ->from([$dspls, $tchers, $specs, $d_schls, $o_schls])
                    ->where($dspls . ".deleted=0 " .
                        " AND " . $dspls . ".archived=" . $archived .
                        " AND " . $dspls . ".teacher_id=" . $tchers . ".teacher_id" .
                        " AND " . $tchers . ".specialisation_id=" . $specs . ".id" .
                        " AND " . $dspls . ".school_id=dsp_sch.school_id" .
                        " AND " . $tchers . ".school_id=orgn_sch.school_id"
                        );

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [ 'attributes' => ['teacher_surname', 'teacher_name', 'teacher_registrynumber', 'code',
                                         'updated_at', 'disposal_school', 'organic_school', 
                                         'disposal_startdate', 'disposal_enddate', 'disposal_hours'],
                        'defaultOrder' => ['updated_at' => SORT_DESC]
                      ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'disposal_startdate' => $this->disposal_startdate,
            'disposal_enddate' => $this->disposal_enddate,
            'disposal_hours' => $this->disposal_hours,
            'teacher_id' => $this->teacher_id,
            'school_id' => $this->school_id,
        ]);
        
        $query->andFilterWhere(['like', 'teacher_name', $this->teacher_name]);
        $query->andFilterWhere(['like', 'teacher_surname', $this->teacher_surname]);
        $query->andFilterWhere(['like', 'teacher_registrynumber', $this->teacher_registrynumber]);
        $query->andFilterWhere(['like', 'code', $this->code]);        
        $query->andFilterWhere(['like', 'dsp_sch.school_name', $this->disposal_school]);
        $query->andFilterWhere(['like', 'orgn_sch.school_name', $this->organic_school]);
        
        return $dataProvider;
    }
}
