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
    public $directorate_shortname;
    public $disposalreason_description;
    public $localdirdecision_protocol;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['disposal_id', 'teacher_id', 'school_id', 'localdirdecision_id'], 'integer'],
            [['teacher_name', 'teacher_surname', 'teacher_registrynumber', 'organic_school', 'disposal_school', 'code', 'directorate_shortname', 'disposalreason_description', 'localdirdecision_protocol'], 'string'],
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


    public static function getAllDisposalsQuery($archived = 0, $approval_id = -1, $republish = 0, $rejected = 0)
    {
        /* TODO cannot be $rejected = 1 AND ($archived = 1 OR $approval_id != -1) */
        $prefix = Yii::$app->db->tablePrefix;
        $dspls = $prefix . 'disposal_disposal';
        $dspls_apprvs = $prefix . 'disposal_disposalapproval';
        $tchers = $prefix . 'teacher';
        $specs = $prefix . 'specialisation';
        $d_schls = $prefix . 'schoolunit dsp_sch';
        $o_schls = $prefix . 'schoolunit orgn_sch';
        $dir_o_schl = $prefix . 'directorate';
        $reasons = $prefix . 'disposal_disposalreason';
        $duties = $prefix . 'disposal_disposalworkobj';
        $localdir_decisions = $prefix . 'disposal_localdirdecision';

        $tables_fields_array = [$dspls. ".*", $tchers . ".*", $specs . ".*" , $dir_o_schl . ".*" , $reasons . ".*" , $duties . ".*",
                                 $localdir_decisions . ".*" , "`dsp_sch`.school_name AS disposal_school, `orgn_sch`.school_name AS organic_school"];
        $tables_array = [$dspls, $tchers, $specs, $d_schls, $o_schls, $dir_o_schl, $reasons, $localdir_decisions, $duties];
        if ($archived) {
            $tables_fields_array = array_merge($tables_fields_array, [$dspls_apprvs . ".*"]);
            $tables_array = array_merge($tables_array, [$dspls_apprvs]);
        }

        $query = (new \yii\db\Query())
                ->select($tables_fields_array)
                ->from($tables_array)
                ->where([$dspls . ".archived" => $archived])
                ->andWhere(
                    $dspls . ".deleted=0 " .
                    " AND " . $dspls . ".disposal_rejected=" . $rejected .
                    " AND " . $dspls . ".teacher_id=" . $tchers . ".teacher_id" .
                    " AND " . $dspls . ".disposalreason_id=" . $reasons . ".disposalreason_id" .
                    " AND " . $dspls . ".disposalworkobj_id=" . $duties . ".disposalworkobj_id" .
                    " AND " . $dspls . ".localdirdecision_id=" . $localdir_decisions . ".localdirdecision_id" .
                    " AND " . $tchers . ".specialisation_id=" . $specs . ".id" .
                    " AND " . $dspls . ".school_id=dsp_sch.school_id" .
                    " AND " . $tchers . ".school_id=orgn_sch.school_id" .
                    " AND orgn_sch.directorate_id=" . $dir_o_schl . ".directorate_id"
                    )->distinct();

        if ($archived) {
            $query->andWhere($dspls . ".disposal_id=" . $dspls_apprvs . ".disposal_id");
        }

        if ($approval_id != -1) {
            $query->andWhere([$dspls_apprvs . ".approval_id" => $approval_id]);
        }

        //echo $query->createCommand()->rawSql; die();
        return $query;
    }


    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $archived = 0, $approval_id = -1, $republish = 0, $rejected = 0)
    {
        $dspls = Yii::$app->db->tablePrefix . 'disposal_disposal';
        $query = self::getAllDisposalsQuery($archived, $approval_id, $republish, $rejected);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [ 'attributes' => ['teacher_surname', 'teacher_name', 'teacher_registrynumber', 'code',
                                          $dspls . '.updated_at', 'disposal_school', 'organic_school', 'disposal_startdate', 'disposal_enddate',
                                         'directorate_shortname', 'disposal_hours', 'disposalreason_description', 'localdirdecision_protocol'],
                                         'defaultOrder' => [$dspls . '.updated_at' => SORT_DESC]
                      ],
            'pagination' => false,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'disposal_hours' => $this->disposal_hours,
            'teacher_id' => $this->teacher_id,
            'school_id' => $this->school_id
        ]);

        $query->andFilterWhere(['like', 'teacher_name', $this->teacher_name]);
        $query->andFilterWhere(['like', 'teacher_surname', $this->teacher_surname]);
        $query->andFilterWhere(['like', 'teacher_registrynumber', $this->teacher_registrynumber]);
        $query->andFilterWhere(['like', 'code', $this->code]);
        $query->andFilterWhere(['like', 'dsp_sch.school_name', $this->disposal_school]);
        $query->andFilterWhere(['like', 'orgn_sch.school_name', $this->organic_school]);
        $query->andFilterWhere(['like', 'directorate_shortname', $this->directorate_shortname]);
        $query->andFilterWhere(['like', 'disposalreason_description', $this->disposalreason_description]);
        $query->andFilterWhere(['like', 'localdirdecision_protocol', $this->localdirdecision_protocol]);
        $query->andFilterWhere(['>=', 'disposal_startdate', $this->disposal_startdate]);
        $query->andFilterWhere(['<=', 'disposal_enddate', $this->disposal_enddate]);
        //echo $query->createCommand()->rawSql; die();
        return $dataProvider;
    }
}
