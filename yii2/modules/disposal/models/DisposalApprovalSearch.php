<?php

namespace app\modules\disposal\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\disposal\models\DisposalApproval;

/**
 * DisposalApprovalSearch represents the model behind the search form about `app\modules\disposal\models\DisposalApproval`.
 */
class DisposalApprovalSearch extends DisposalApproval
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['approval_id', 'created_by', 'updated_by'], 'integer'],
            [['approval_regionaldirectprotocol', 'approval_regionaldirectprotocoldate', 'approval_localdirectprotocol', 'approval_localdirectdecisionsubject', 
              'approval_notes', 'approval_file', 'approval_signedfile', 'created_at', 'updated_at'], 'safe'],
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
        $query = DisposalApproval::find()->where(['deleted' => 0]);

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
            'approval_id' => $this->approval_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'approval_regionaldirectprotocol', $this->approval_regionaldirectprotocol])
            ->andFilterWhere(['like', 'approval_localdirectprotocol', $this->approval_localdirectprotocol])
            ->andFilterWhere(['like', 'approval_notes', $this->approval_notes])
            ->andFilterWhere(['like', 'approval_file', $this->approval_file])
            ->andFilterWhere(['like', 'approval_signedfile', $this->approval_signedfile]);

        return $dataProvider;
    }
}
