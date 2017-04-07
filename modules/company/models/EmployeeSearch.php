<?php

namespace app\modules\company\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\company\models\Employee;

/**
 * EmployeeSearch represents the model behind the search form about `app\modules\company\models\Employee`.
 */
class EmployeeSearch extends Employee
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'job_id', 'is_registered'], 'integer'],
            [['email', 'first_name', 'last_name'], 'safe'],
            [['created_dt', 'register_dt', 'updated_dt'], 'number'],
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
        $query->joinWith(['job']);

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'job_id' => $this->job_id,
            Job::tableName().'.company_id' => Yii::$app->user->identity->getId(),
            'is_registered' => $this->is_registered,
            'created_dt' => $this->created_dt,
            'register_dt' => $this->register_dt,
            'updated_dt' => $this->updated_dt,
        ]);

        $query->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name]);

        return $dataProvider;
    }
}
