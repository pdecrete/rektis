<?php

namespace app\modules\finance\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\finance\models\FinanceExpenditure;

/**
 * FinanceExpenditureSearch represents the model behind the search form about `app\modules\finance\models\FinanceExpenditure`.
 */
class FinanceExpenditureSearch extends FinanceExpenditure
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['exp_id', 'exp_amount', 'exp_date', 'exp_deleted', 'suppl_id', 'fpa_value'], 'integer'],
            [['exp_lock'], 'safe'],
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
        $prefix = Yii::$app->db->tablePrefix;
        $exp_states = $prefix . 'finance_expenditurestate';
        $exps = $prefix . 'finance_expenditure';
        $expwithdr = $prefix . 'finance_expendwithdrawal';
        $wthdr = $prefix . "finance_kaewithdrawal";
        $cred = $prefix . "finance_kaecredit";   
        
        /*
            SELECT admapp_finance_expenditure.* 
            FROM `admapp_finance_expenditure`, admapp_finance_expendwithdrawal 
            WHERE admapp_finance_expenditure.exp_id=admapp_finance_expendwithdrawal.exp_id
            AND admapp_finance_expendwithdrawal.kaewithdr_id 
            IN (SELECT admapp_finance_kaewithdrawal.kaewithdr_id 
            FROM admapp_finance_kaewithdrawal, admapp_finance_kaecredit
            WHERE admapp_finance_kaecredit.year = 2019 AND admapp_finance_kaewithdrawal.kaecredit_id = admapp_finance_kaecredit.kaecredit_id)
         */
        
        
        $count_states = "(SELECT COUNT(exp_id) FROM " . $exp_states .
        " WHERE " .$exp_states . ".exp_id = " . $exps . ".exp_id)";
        
        $query = (new \yii\db\Query())
                    ->select([$exps . ".*", $count_states . " AS statescount "])
                    ->from([$exps, $expwithdr])
                    ->where($exps . ".exp_id=" . $expwithdr . ".exp_id AND " . 
                            $expwithdr . ".kaewithdr_id 
                            IN (SELECT " . $wthdr . ".kaewithdr_id
                                FROM " . $wthdr . ", " . $cred . "  
                                WHERE " . $cred . ".year=" . Yii::$app->session["working_year"] . "
                                AND " . $wthdr . ".kaecredit_id=" . $cred . ".kaecredit_id)")->distinct();
        //echo $query->createCommand()->getRawSql();die();

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
            'exp_id' => $this->exp_id,
            'exp_amount' => $this->exp_amount,
            'exp_date' => $this->exp_date,
            'exp_deleted' => $this->exp_deleted,
            'suppl_id' => $this->suppl_id,
            'fpa_value' => $this->fpa_value,
        ]);

        $query->andFilterWhere(['like', 'exp_lock', $this->exp_lock]);

        return $dataProvider;
    }
}
