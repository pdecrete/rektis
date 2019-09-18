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
    public $from_school;
    public $to_school;
    public $directorate_shortname;
    public $disposalreason_description;
    public $localdirdecision_protocol;
    public $localdirdecision_action;
    public $surname;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['disposal_id', 'teacher_id', 'fromschool_id', 'toschool_id', 'localdirdecision_id'], 'integer'],
            [['teacher_name', 'teacher_surname', 'teacher_registrynumber', 'organic_school', 'from_school', 'to_school', 'code', 'directorate_shortname',
              'disposalreason_description', 'localdirdecision_protocol', 'localdirdecision_action', 'surname'], 'string'],
            [['disposal_startdate', 'disposal_enddate', 'disposal_hours', 'disposal_days'], 'safe'],
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


    public static function getAllDisposalsQuery($archived = 0, $approval_id = -1, $rejected = 0)
    {
        /* TODO cannot be $rejected = 1 AND ($archived = 1 OR $approval_id != -1) */
        $prefix = Yii::$app->db->tablePrefix;
        $dspls = $prefix . 'disposal_disposal';
        $dspls_apprvs = $prefix . 'disposal_disposalapproval';
        $apprvs = $prefix . 'disposal_approval';
        $tchers = $prefix . 'teacher';
        $specs = $prefix . 'specialisation';
        $s_schls = $prefix . 'schoolunit srv_sch';
        $d_schls = $prefix . 'schoolunit dsp_sch';
        $o_schls = $prefix . 'schoolunit orgn_sch';
        $dir_o_schl = $prefix . 'directorate';
        $reasons = $prefix . 'disposal_disposalreason';
        $duties = $prefix . 'disposal_disposalworkobj';
        $users = $prefix . 'user';
        $localdir_decisions = $prefix . 'disposal_localdirdecision';

        $tables_fields_array = [$dspls. ".*", $tchers . ".teacher_id AS TEACHER", $tchers . ".teacher_surname", $tchers . ".teacher_name",
                                $tchers . ".teacher_mothername", $tchers . ".teacher_fathername", $tchers . ".teacher_registrynumber", $specs . ".*" , $dir_o_schl . ".*" ,
                                $reasons . ".disposalreason_id AS REASON" , $reasons . ".disposalreason_description", $duties . ".disposalworkobj_id AS DUTY", $duties . ".disposalworkobj_description",
                                $localdir_decisions . ".localdirdecision_id AS LOCALDIR_DECISION" , $localdir_decisions . ".localdirdecision_protocol", $localdir_decisions . ".localdirdecision_action",
                                "`dsp_sch`.school_name AS to_school, `srv_sch`.school_name AS from_school, `orgn_sch`.school_name AS organic_school", $users . ".id AS USER_ID", 
                                $users . ".surname AS USER_SURNAME", $users . ".name AS USER_NAME"];
        $tables_array = [$dspls, $tchers, $specs, $s_schls, $d_schls, $o_schls, $dir_o_schl, $reasons, $localdir_decisions, $duties, $users];
        if ($archived) {
            $tables_fields_array = array_merge($tables_fields_array, [$dspls_apprvs . ".disposal_id AS DISPOSAL", $dspls_apprvs . ".approval_id",
                                                                      $apprvs . ".approval_id AS APPROVAL", $apprvs . ".deleted AS APPROVALDELETED", $apprvs . ".approval_republished"]);
            $tables_array = array_merge($tables_array, [$dspls_apprvs, $apprvs]);
        }

        $query = (new \yii\db\Query())
                ->select($tables_fields_array)
                ->from($tables_array)
                ->where([$dspls . ".archived" => $archived])
                ->andWhere(
                    $dspls . ".deleted=0 " .
                    " AND " . $dspls . ".disposal_republished IS NULL " .
                    " AND " . $dspls . ".disposal_rejected=" . $rejected .
                    " AND " . $dspls . ".teacher_id=" . $tchers . ".teacher_id" .
                    " AND " . $dspls . ".updated_by=" . $users . ".id" .
                    " AND " . $dspls . ".disposalreason_id=" . $reasons . ".disposalreason_id" .
                    " AND " . $dspls . ".disposalworkobj_id=" . $duties . ".disposalworkobj_id" .
                    " AND " . $dspls . ".localdirdecision_id=" . $localdir_decisions . ".localdirdecision_id" .
                    " AND " . $tchers . ".specialisation_id=" . $specs . ".id" .
                    " AND " . $dspls . ".fromschool_id=srv_sch.school_id" .
                    " AND " . $dspls . ".toschool_id=dsp_sch.school_id" .
                    " AND " . $tchers . ".school_id=orgn_sch.school_id" .
                    " AND " . $localdir_decisions . ".directorate_id=" . $dir_o_schl . ".directorate_id"
                    )->distinct();

        if ($archived) {
            $query->andWhere($apprvs . ".deleted=0")->
                    andWhere($dspls . ".disposal_id=" . $dspls_apprvs . ".disposal_id")->
                    andWhere($apprvs . ".approval_id=" . $dspls_apprvs . ".approval_id")->
                    andWhere($apprvs . ".approval_republished IS NULL");
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
    public function search($params, $archived = 0, $approval_id = -1, $rejected = 0)
    {
        $dspls = Yii::$app->db->tablePrefix . 'disposal_disposal';
        $query = self::getAllDisposalsQuery($archived, $approval_id, $rejected);

        $defaultOrder = ($archived != 0) ? [$dspls . '.updated_at' => SORT_DESC] : ['code' => SORT_ASC];
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [ 'attributes' => ['teacher_surname', 'teacher_name', 'teacher_registrynumber', 'code',
                                          $dspls . '.updated_at', 'to_school', 'from_school', 'organic_school', 'disposal_startdate', 'disposal_enddate',
                                         'directorate_shortname', 'disposal_hours', 'disposal_days', 'disposalreason_description', 'localdirdecision_protocol',
                                         'localdirdecision_action', 'surname'],
                                        'defaultOrder' => $defaultOrder,
                         'enableMultiSort' => true,
                      ],
        ]);

        if ($archived == 1) {
            $dataProvider->pagination->pageSize = 10;
        } else {
            $dataProvider->pagination = false;
        }

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'disposal_hours' => $this->disposal_hours,
            'disposal_days' => $this->disposal_days,
            'teacher_id' => $this->teacher_id,
            //'toschool_id' => $this->toschool_id,
            //'fromschool_id' => $this->fromschool_id
        ]);

        $query->andFilterWhere(['like', 'teacher_name', $this->teacher_name]);
        $query->andFilterWhere(['like', 'teacher_surname', $this->teacher_surname]);
        $query->andFilterWhere(['like', 'teacher_registrynumber', $this->teacher_registrynumber]);
        $query->andFilterWhere(['like', 'code', $this->code]);
        $query->andFilterWhere(['like', 'srv_sch.school_name', $this->from_school]);
        $query->andFilterWhere(['like', 'dsp_sch.school_name', $this->to_school]);
        $query->andFilterWhere(['like', 'orgn_sch.school_name', $this->organic_school]);
        $query->andFilterWhere(['like', 'directorate_shortname', $this->directorate_shortname]);
        $query->andFilterWhere(['like', 'disposalreason_description', $this->disposalreason_description]);
        $query->andFilterWhere(['like', 'localdirdecision_protocol', $this->localdirdecision_protocol]);
        $query->andFilterWhere(['like', 'localdirdecision_action', $this->localdirdecision_action]);
        $query->andFilterWhere(['like', 'surname', $this->surname]);
        $query->andFilterWhere(['>=', 'disposal_startdate', $this->disposal_startdate]);
        $query->andFilterWhere(['<=', 'disposal_enddate', $this->disposal_enddate]);
        //echo $query->createCommand()->rawSql; die();
        return $dataProvider;
    }
}
