<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%job}}".
 *
 * @property integer $id
 * @property integer $company_id
 * @property string $job_title
 * @property double $created_dt
 * @property double $updated_dt
 *
 * @property Employee[] $employees
 * @property Company $company
 */
class Job extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%job}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company_id', 'job_title', 'created_dt', 'updated_dt'], 'required'],
            [['company_id'], 'integer'],
            [['created_dt', 'updated_dt'], 'number'],
            [['job_title'], 'string', 'max' => 50],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::className(), 'targetAttribute' => ['company_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_id' => 'Company ID',
            'job_title' => 'Job Title',
            'created_dt' => 'Created Dt',
            'updated_dt' => 'Updated Dt',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployees()
    {
        return $this->hasMany(Employee::className(), ['job_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }
}
