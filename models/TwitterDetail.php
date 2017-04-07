<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%twitter_detail}}".
 *
 * @property integer $id
 * @property integer $employee_id
 * @property string $twitter_id
 * @property string $name
 * @property string $profile_pic
 * @property string $access_token
 * @property double $created_dt
 * @property double $updated_dt
 *
 * @property Employee $employee
 */
class TwitterDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%twitter_detail}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['employee_id', 'twitter_id'], 'required'],
            [['employee_id'], 'integer'],
            [['profile_pic', 'access_token'], 'string'],
            [['created_dt', 'updated_dt'], 'number'],
            [['twitter_id'], 'string', 'max' => 45],
            [['name'], 'string', 'max' => 250],
            [['employee_id'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['employee_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'employee_id' => 'Employee ID',
            'twitter_id' => 'Twitter ID',
            'name' => 'Name',
            'profile_pic' => 'Profile Pic',
            'access_token' => 'Access Token',
            'created_dt' => 'Created Dt',
            'updated_dt' => 'Updated Dt',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['id' => 'employee_id']);
    }

    public function beforeSave($insert)
    {
        if($this->isNewRecord)  {
            $this->created_dt = time();
            $this->updated_dt = 0;
        }   else    {
            $this->updated_dt = time();
        }
        return parent::beforeSave($insert);
    }
}
