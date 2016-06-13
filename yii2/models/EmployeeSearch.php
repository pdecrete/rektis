<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Employee;

/**
 * EmployeeSearch represents the model behind the search form about `app\models\Employee`.
 */
class EmployeeSearch extends Employee
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['id',  'position', 'pay_scale', 'master_degree', 'doctorate_degree', 'work_experience'], 'integer'],
            [['status', 'specialisation', 'service_organic', 'service_serve', 'name', 'surname', 'fathersname', 'mothersname', 'tax_identification_number', 'email', 'telephone', 'address', 'identity_number', 'social_security_number', 'identification_number', 'appointment_fek', 'appointment_date', 'rank', 'rank_date', 'pay_scale_date', 'service_adoption', 'service_adoption_date', 'comments', 'create_ts', 'update_ts','position','pay_scale','mobile','deleted'], 'safe'],
            [['social_security_number'], 'integer'],
            [['social_security_number'], 'string', 'length' => 11],
            [['identification_number'], 'integer'],
            [['identification_number'], 'string', 'length' => 6]
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
        $query = Employee::find();
        // join with serviceServe as sServe to avoid joining with the same table twice
        $query->joinWith(['status0', 'specialisation0', 'serviceOrganic', 'serviceServe as sServe', 'position0']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                 'pageSize' => 20,
             ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            //$query->joinWith(['employee_status']);

            return $dataProvider;
        }

        $query->andFilterWhere([
            //'id' => $this->id,
            //'status' => $this->status,
            //'specialisation' => $this->specialisation,
            //'appointment_date' => $this->appointment_date,
            //'service_organic' => $this->service_organic,
            //'service_serve' => $this->service_serve,
            //'position' => $this->position,
            //'rank_date' => $this->rank_date,
            'identity_number' => $this->identity_number,
            'pay_scale' => $this->pay_scale,
            'social_security_number' => $this->social_security_number,
            //'pay_scale_date' => $this->pay_scale_date,
            //'service_adoption_date' => $this->service_adoption_date,
            //'master_degree' => $this->master_degree,
            //'doctorate_degree' => $this->doctorate_degree,
            //'work_experience' => $this->work_experience,
            //'create_ts' => $this->create_ts,
            //'update_ts' => $this->update_ts,
            
            // if unset, set to 0 to display only active employees
            'deleted' => $this->deleted ? 1 : 0
        ]);

        // with help from: http://www.yiiframework.com/wiki/653/displaying-sorting-and-filtering-model-relations-on-a-gridview/
        $query->andFilterWhere(['like', 'admapp_employee_status.name', $this->status])
            ->andFilterWhere(['like', 'identification_number', $this->identification_number])
            ->andFilterWhere(['like', 'tax_identification_number', $this->tax_identification_number])
            ->andFilterWhere(['like', 'admapp_employee.name', $this->name])
            ->andFilterWhere(['like', 'surname', $this->surname])
            ->andFilterWhere(['like', 'admapp_specialisation.code', $this->specialisation])
            ->andFilterWhere(['like', 'admapp_service.name', $this->service_organic])
            ->andFilterWhere(['like', 'sServe.name', $this->service_serve])
            ->andFilterWhere(['like', 'rank', $this->rank])
            ->andFilterWhere(['like', 'admapp_position.name', $this->position]);
            //->andFilterWhere(['like', 'fathersname', $this->fathersname])
            //->andFilterWhere(['like', 'mothersname', $this->mothersname])

            //->andFilterWhere(['like', 'email', $this->email])
            //->andFilterWhere(['like', 'telephone', $this->telephone])
            //->andFilterWhere(['like', 'address', $this->address])
            //->andFilterWhere(['like', 'identity_number', $this->identity_number])
            //->andFilterWhere(['like', 'social_security_number', $this->social_security_number])

            //->andFilterWhere(['like', 'appointment_fek', $this->appointment_fek])
            //->andFilterWhere(['like', 'rank', $this->rank])
            //->andFilterWhere(['like', 'service_adoption', $this->service_adoption])
            //->andFilterWhere(['like', 'comments', $this->comments]);

        return $dataProvider;
    }
}
