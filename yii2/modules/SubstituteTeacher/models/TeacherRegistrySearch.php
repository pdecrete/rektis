<?php

namespace app\modules\SubstituteTeacher\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * TeacherRegistrySearch represents the model behind the search form about `app\modules\SubstituteTeacher\models\TeacherRegistry`.
 */
class TeacherRegistrySearch extends TeacherRegistry
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'protected_children', 'aei', 'tei', 'epal', 'iek', 'military_service_certificate', 'sign_language', 'braille'], 'integer'],
            // ['specialisation_ids', 'each', 'rule' => ['integer']],
            ['specialisation_ids', 'integer'],
            [['gender', 'surname', 'firstname', 'fathername', 'mothername', 'marital_status', 'mobile_phone', 'home_phone', 'work_phone', 'home_address', 'city', 'postal_code', 'social_security_number', 'tax_identification_number', 'tax_service', 'identity_number', 'bank', 'iban', 'email', 'birthdate', 'birthplace', 'comments', 'created_at', 'updated_at', 'ama', 'efka_facility', 'municipality'], 'safe'],
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
        $query = TeacherRegistry::find()
            ->joinWith('specialisations');

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
            'id' => $this->id,
            'protected_children' => $this->protected_children,
            'birthdate' => $this->birthdate,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'gender', $this->gender])
            ->andFilterWhere(['like', 'surname', $this->surname])
            ->andFilterWhere(['like', 'firstname', $this->firstname])
            ->andFilterWhere(['like', 'fathername', $this->fathername])
            ->andFilterWhere(['like', 'mothername', $this->mothername])
            ->andFilterWhere(['like', 'marital_status', $this->marital_status])
            ->andFilterWhere(['like', 'mobile_phone', $this->mobile_phone])
            ->andFilterWhere(['like', 'home_phone', $this->home_phone])
            ->andFilterWhere(['like', 'work_phone', $this->work_phone])
            ->andFilterWhere(['like', 'home_address', $this->home_address])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'postal_code', $this->postal_code])
            ->andFilterWhere(['like', 'social_security_number', $this->social_security_number])
            ->andFilterWhere(['like', 'tax_identification_number', $this->tax_identification_number])
            ->andFilterWhere(['like', 'tax_service', $this->tax_service])
            ->andFilterWhere(['like', 'identity_number', $this->identity_number])
            ->andFilterWhere(['like', 'bank', $this->bank])
            ->andFilterWhere(['like', 'iban', $this->iban])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'birthplace', $this->birthplace])
            ->andFilterWhere(['like', 'comments', $this->comments])
            ->andFilterWhere(['like', 'ama', $this->email])
            ->andFilterWhere(['like', 'efka_facility', $this->email])
            ->andFilterWhere(['like', 'municipality', $this->email]);

        $query->andFilterWhere(['specialisations.id' => $this->specialisation_ids]);

        return $dataProvider;
    }
}
