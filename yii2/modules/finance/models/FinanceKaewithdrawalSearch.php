<?php

namespace app\modules\finance\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * FinanceKaewithdrawalSearch represents the model behind the search form about `app\modules\finance\models\FinanceKaewithdrawal`.
 */
class FinanceKaewithdrawalSearch extends FinanceKaewithdrawal
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kaewithdr_id', 'kaewithdr_amount', 'kaecredit_id'], 'integer'],
            [['kaewithdr_decision', 'kaewithdr_date'], 'safe'],
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
        $wthdr = $prefix . "finance_kaewithdrawal";
        $prc = $prefix . "finance_kaecreditpercentage";
        $cred = $prefix . "finance_kaecredit";
        $kae = $prefix . "finance_kae";

        $sum_percents = "(SELECT SUM(kaeperc_percentage) FROM " . $prc .
                        " WHERE " . $prc . ".kaecredit_id = " . $cred . ".kaecredit_id)";

        $query = (new \yii\db\Query())
            ->select([$wthdr . ".*", $cred . ".kaecredit_amount",
            //$cred . ".kaecredit_date", $cred . ".kaecredit_updated",$cred . ".year",
            $sum_percents . " AS percentages ",
            $kae . ".*", ])
            ->from([$wthdr, $cred, $kae])
            ->where($cred . '.year=' . Yii::$app->session["working_year"] . " AND " . $wthdr . '.kaecredit_id=' . $cred . '.kaecredit_id AND ' . $cred . '.kae_id=' . $kae . '.kae_id');

        $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'sort' => ['attributes' => ['kae_id', 'kae_title',
                    'kaecredit_amount', 'percentages', 'kaewithdr_amount', 'kaewithdr_decision', 'kaewithdr_date'],
                    'defaultOrder' => ['kae_id'=>SORT_ASC, 'kaewithdr_date' => SORT_ASC]
                ],
            ]);
        //var_dump($dataProvider); die();

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'kaewithdr_id' => $this->kaewithdr_id,
            'kaewithdr_amount' => $this->kaewithdr_amount,
            'kaewithdr_date' => $this->kaewithdr_date,
            'kaecredit_id' => $this->kaecredit_id,
        ]);

        $query->andFilterWhere(['like', 'kaewithdr_decision', $this->kaewithdr_decision]);

        return $dataProvider;
    }
}
