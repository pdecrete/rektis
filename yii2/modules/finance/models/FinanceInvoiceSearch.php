<?php

namespace app\modules\finance\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * FinanceInvoiceSearch represents the model behind the search form about `app\modules\finance\models\FinanceInvoice`.
 */
class FinanceInvoiceSearch extends FinanceInvoice
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['inv_id', 'inv_deleted', 'suppl_id', 'exp_id', 'invtype_id'], 'integer'],
            [['inv_number', 'inv_order', 'inv_date'], 'safe'],
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
        //$query = FinanceInvoice::find();

        $prefix = Yii::$app->db->tablePrefix;
        $exp_states = $prefix . 'finance_expenditurestate';
        $exps = $prefix . 'finance_expenditure';
        $expwithdr = $prefix . 'finance_expendwithdrawal';
        $wthdr = $prefix . "finance_kaewithdrawal";
        $cred = $prefix . "finance_kaecredit";
        $invs = $prefix . "finance_invoice";

        $count_states = "(SELECT COUNT(exp_id) FROM " . $exp_states .
        " WHERE " .$exp_states . ".exp_id = " . $exps . ".exp_id)";

        $queryExpenditures = (new \yii\db\Query())
                    ->select([$exps . ".exp_id"])
                    ->from([$exps, $expwithdr])
                    ->where($exps . ".exp_id=" . $expwithdr . ".exp_id AND " .
                        $expwithdr . ".kaewithdr_id
                                        IN (SELECT " . $wthdr . ".kaewithdr_id
                                            FROM " . $wthdr . ", " . $cred . "
                                            WHERE " . $cred . ".year=" . Yii::$app->session["working_year"] . "
                                            AND " . $wthdr . ".kaecredit_id=" . $cred . ".kaecredit_id)")->distinct();
        $sqlQueryExpenditures = $queryExpenditures->createCommand()->getRawSql();
        $query = (new \yii\db\Query())
                    ->select([$invs . ".*"])
                    ->from([$invs])
                    ->where($invs . ".exp_id IN (" . $sqlQueryExpenditures . ")");

        //echo $query->createCommand()->getRawSql();die();

        $dataProvider = new ActiveDataProvider([
                                'query' => $query,
                                'sort' => ['attributes' => ['inv_number', 'inv_date', 'inv_order'],
                                'defaultOrder' => ['inv_date' => SORT_DESC,]]
                                ]);

        $this->load($params);
        //echo "<pre>"; print_r($params); echo "</pre>"; die();
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'inv_id' => $this->inv_id,
            'inv_date' => $this->inv_date,
            'inv_deleted' => $this->inv_deleted,
            'suppl_id' => $this->suppl_id,
            'exp_id' => $this->exp_id,
            'invtype_id' => $this->invtype_id,
        ]);

        $query->andFilterWhere(['like', 'inv_number', $this->inv_number])
            ->andFilterWhere(['like', 'inv_order', $this->inv_order]);

        return $dataProvider;
    }
}
