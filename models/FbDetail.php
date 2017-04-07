<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%fb_detail}}".
 *
 * @property integer $id
 * @property integer $employee_id
 * @property string $fb_id
 * @property string $name
 * @property string $profile_pic
 * @property integer $friend_count
 * @property string $access_token
 * @property double $created_dt
 * @property double $updated_dt
 *
 * @property Employee $employee
 * @property FbPost[] $fbPosts
 */
class FbDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%fb_detail}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['employee_id', 'fb_id'], 'required'],
            [['employee_id', 'friend_count'], 'integer'],
            [['profile_pic', 'access_token'], 'string'],
            [['created_dt', 'updated_dt'], 'number'],
            [['fb_id'], 'string', 'max' => 45],
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
            'fb_id' => 'Fb ID',
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFbPosts()
    {
        return $this->hasMany(FbPost::className(), ['fb_user_id' => 'id']);
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
