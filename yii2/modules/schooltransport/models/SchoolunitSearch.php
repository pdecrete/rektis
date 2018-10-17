<?php

namespace app\modules\schooltransport\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SchoolunitSearch represents the model behind the search form about `app\modules\schooltransport\models\Schoolunit`.
 */
class SchoolunitSearch extends Schoolunit
{
    public $directorate_name;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['school_id', 'directorate_id'], 'integer'],
            [['school_name', 'directorate_name'], 'safe'],
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
        $tblprefix = Yii::$app->db->tablePrefix;
        $query = (new \yii\db\Query())
                    ->select($tblprefix . 'schoolunit.*,' . $tblprefix . 'directorate.*')
                    ->from($tblprefix . 'schoolunit,' . $tblprefix . 'directorate')
                    ->where($tblprefix . 'schoolunit.school_state=1') /*Show only the ACTIVE schools*/
                    ->andWhere($tblprefix . 'schoolunit.directorate_id =' . $tblprefix . 'directorate.directorate_id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'school_name', $this->school_name]);
        $query->andFilterWhere(['like', 'directorate_name', $this->directorate_name]);

        return $dataProvider;
    }
}
