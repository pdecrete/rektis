<?php

namespace app\modules\finance\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\finance\models\FinanceKaecreditpercentage;

/**
 * FinanceKaecreditpercentageSearch represents the model behind the search form about `app\modules\finance\models\FinanceKaecreditpercentage`.
 */
class FinanceKaecreditpercentageSearch extends FinanceKaecreditpercentage
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kaeperc_id', 'kaecredit_id'], 'integer'],
            [['kaeperc_percentage'], 'number'],
            [['kaeperc_date', 'kaeperc_decision'], 'safe'],
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
        $prc = $prefix . "finance_kaecreditpercentage";
        $cred = $prefix . "finance_kaecredit";
        $kae = $prefix . "finance_kae";
            
        $query = (new \yii\db\Query())
                    ->select([  $prc . ".*", $cred . ".kaecredit_amount", 
                                $cred . ".kaecredit_date", $cred . ".kaecredit_updated", 
                                $cred . ".year", $kae . ".*"])
                    ->from([$prc, $cred, $kae])
                    ->where($prc . '.kaecredit_id=' . $cred . '.kaecredit_id AND ' . $cred . '.kae_id=' . $kae . '.kae_id');
        
        //echo "<pre>"; var_dump($query->all()); echo "</pre>"; die();
        //$query = FinanceKaecreditpercentage::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['attributes' => ['kae_id', 'kae_title', 
                        'kaecredit_amount', 'kaeperc_percentage', 'kaeperc_date', 'kaeperc_decision'],
                        'defaultOrder' => ['kae_id'=>SORT_ASC]
                        ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'kaeperc_id' => $this->kaeperc_id,
            'kaeperc_percentage' => $this->kaeperc_percentage,
            'kaeperc_date' => $this->kaeperc_date,
            'kaecredit_id' => $this->kaecredit_id,
        ]);

        $query->andFilterWhere(['like', 'kaeperc_decision', $this->kaeperc_decision]);
        return $dataProvider;
    }
}
