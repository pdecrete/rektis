<?php

namespace app\modules\SubstituteTeacher\models;

use yii\base\Model;

use yii\data\ActiveDataProvider;
use Yii;
use Datetime;
/**
 * TeacherSearch represents the model behind the search form about `app\modules\SubstituteTeacher\models\Teacher`.
 */
class TeacherSearchMK extends Teacher
{
    public $specialisation_id;
    public $status;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'registry_id', 'year', 'public_experience', 'smeae_keddy_experience', 'disabled_children', 'specialisation_id'], 'integer'],
            [['disability_percentage'], 'integer', 'min' => 0, 'max' => 100],
            [['mk_yearsper'], 'integer', 'min' => 2, 'max' => 3],
            [['three_children', 'many_children'], 'boolean'],
            ['mk_changedate', 'date',  'format' => 'php:Y-m-d'],   
            //[['specialisation_id','status'], 'safe']
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
        $query = Teacher::find();

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

        if (!empty($this->specialisation_id)) {
            $query = $query->joinWith([
                'registry.teacherRegistrySpecialisations'
            ]);
        }
//        if (!empty($this->status)) {
//            $query = $query->joinWith(['boards']);
//        }        
       //     $query = $query->joinWith(['Placement']);
            //$query = $query->joinWith(['placementposition']);            
            
        // grid filtering conditions
        $query->andFilterWhere([
            self::tableName().'.id' => $this->id,
            //'id' => $this->id,
            self::tableName() . '.registry_id' => $this->registry_id,
            'year' => $this->year,
//            'public_experience' => $this->public_experience, 
//            'smeae_keddy_experience' => $this->smeae_keddy_experience,
//            'disabled_children' => $this->disabled_children,
//            'disability_percentage' => $this->disability_percentage,
//            'three_children' => $this->three_children, 
//            'many_children' => $this->many_children,
            
            TeacherRegistrySpecialisation::tableName() . '.specialisation_id' => $this->specialisation_id,
            
           // TeacherBoard::tableName() . '.status' => $this->status    
        ]);
        
        $query->andWhere(['>', 'LENGTH(sector)', 0]);
        $query->andFilterCompare('mk_changedate', $this->mk_changedate,'<=');
        
        
        $query->orderBy(['sector' => SORT_ASC, 'mk_changedate' => SORT_ASC, 'registry_id' => SORT_ASC]);
        //$query->orderBy(['registry_id' => SORT_ASC]);
        
        return $dataProvider;
    }
}
