<?php

namespace app\modules\company\models;

use Yii;

/**
 * This is the model class for table "{{%employee}}".
 *
 * @property integer $id
 * @property integer $job_id
 * @property string $email
 * @property string $invite_token
 * @property string $auth_key
 * @property string $first_name
 * @property string $last_name
 * @property integer $is_registered
 * @property double $created_dt
 * @property double $register_dt
 * @property double $updated_dt
 *
 * @property Job $job
 */
class Employee extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%employee}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['job_id', 'email'], 'required'],
            [['job_id', 'is_registered'], 'integer'],
            [['created_dt', 'register_dt', 'updated_dt'], 'number'],
            [['email'], 'string', 'max' => 100],
            [['first_name', 'last_name'], 'string', 'max' => 50],
            [['job_id'], 'exist', 'skipOnError' => true, 'targetClass' => Job::className(), 'targetAttribute' => ['job_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'job_id' => 'Job',
            'email' => 'Email',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'is_registered' => 'Is Registered',
            'created_dt' => 'Created Dt',
            'register_dt' => 'Register Dt',
            'updated_dt' => 'Updated Dt',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJob()
    {
        return $this->hasOne(Job::className(), ['id' => 'job_id']);
    }

    /**
     * Generates new invite token
     */
    public function generateInviteToken()
    {
        $this->invite_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function beforeSave($insert)
    {
        if($this->isNewRecord)  {
            $this->created_dt = time();
            $this->updated_dt = 0;
            $this->auth_key = md5(time());
        }   else    {
            $this->updated_dt = time();
        }
        return parent::beforeSave($insert);
    }
}
