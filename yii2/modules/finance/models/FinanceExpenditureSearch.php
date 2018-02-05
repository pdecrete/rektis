<?php

namespace app\modules\finance\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\finance\models\FinanceExpenditure;
use app\modules\finance\components\Money;

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
            [['exp_id', 'exp_amount', 'exp_date', 'exp_deleted',  'fpa_value'], 'integer'],
            [['suppl_id', 'exp_lock'], 'safe'],
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
        $suppl = $prefix . 'finance_supplier';
      
        $count_states = "(SELECT COUNT(exp_id) FROM " . $exp_states .
        " WHERE " .$exp_states . ".exp_id = " . $exps . ".exp_id)";
        
        $kae = "(SELECT " . $cred . ".kae_id FROM " . $cred . "," . $wthdr . " 
                 WHERE " . $cred . ".kaecredit_id=" . $wthdr . ".kaecredit_id AND "
                 . $wthdr . ".kaewithdr_id=" . $expwithdr . ".kaewithdr_id)";
        
        $query = (new \yii\db\Query())
                    ->select([$suppl. ".suppl_name", $exps . ".*", $count_states . " AS statescount ", $kae . " AS kae_id "])
                    ->from([$exps, $expwithdr, $suppl])
                    ->where($suppl . ".suppl_id=" . $exps . ".suppl_id AND " . $exps . ".exp_id=" . $expwithdr . ".exp_id AND " . 
                            $expwithdr . ".kaewithdr_id 
                            IN (SELECT " . $wthdr . ".kaewithdr_id
                                FROM " . $wthdr . ", " . $cred . "  
                                WHERE " . $cred . ".year=" . Yii::$app->session["working_year"] . "
                                AND " . $wthdr . ".kaecredit_id=" . $cred . ".kaecredit_id)")->distinct();
                    //->orderBy([$exps . '.suppl_id' => SORT_ASC, $exps . '.exp_id' => SORT_ASC]);
        //echo $query->createCommand()->getRawSql();die();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['attributes' => ['suppl_id', 'fpa_value', 'exp_id', 'statescount',  
                                        'exp_amount', 'exp_date', 'exp_description', 'statescount', 'kae_id'
                                        ],
                'defaultOrder' => ['suppl_id'=>SORT_ASC, 'exp_id'=>SORT_ASC,]
            ],
        ]);

       // echo "<pre>"; echo ($params['FinanceExpenditureSearch']['exp_amount']); echo "</pre>";die();
       // var_dump($params[]);die();
        
        $this->load($params);
       // if(isset($params['FinanceExpenditureSearch']['exp_amount']))
      //      $this->exp_amount = Money::toCents($this->exp_amount);

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
        //    'suppl_id' => $this->suppl_id,
            'fpa_value' => $this->fpa_value,
        ]);

        
        $query->andFilterWhere(['like', 'suppl_name', $this->suppl_id]);

        //if(isset($params['FinanceExpenditureSearch']['exp_amount']))
         //   $this->exp_amount = Money::toCurrency($this->exp_amount);
        
        //if($params['FinanceExpenditureSearch']['exp_amount'] == 0)
        //    $this->exp_amount = null;
            
        return $dataProvider;
    }
}
