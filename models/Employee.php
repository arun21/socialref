<?php

namespace app\models;

use Yii;
use yii\base\InvalidParamException;
use yii\base\NotSupportedException;
use yii\web\IdentityInterface;

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
 * @property string $profile_pic
 * @property integer $is_registered
 * @property double $created_dt
 * @property double $register_dt
 * @property double $updated_dt
 *
 * @property Job $job
 * @property FbDetail[] $fbDetails
 * @property LinkedinDetail[] $linkedinDetails
 * @property TwitterDetail[] $twitterDetails
 */
class Employee extends \yii\db\ActiveRecord implements IdentityInterface
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
            [['job_id', 'email', 'created_dt'], 'required'],
            [['job_id', 'is_registered'], 'integer'],
            [['invite_token', 'profile_pic'], 'string'],
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
            'job_id' => 'Job ID',
            'email' => 'Email',
            'invite_token' => 'Invite Token',
            'auth_key' => 'Auth Key',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'is_registered' => 'Is Registered',
            'profile_pic' => 'Profile Picture',
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
     * @return \yii\db\ActiveQuery
     */
    public function getFbDetails()
    {
        return $this->hasMany(FbDetail::className(), ['employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLinkedinDetails()
    {
        return $this->hasMany(LinkedinDetail::className(), ['employee_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTwitterDetails()
    {
        return $this->hasMany(TwitterDetail::className(), ['employee_id' => 'id']);
    }


    public static function getEmployeeByInviteToken($invite_token)
    {
        if (empty($invite_token) || !is_string($invite_token)) {
            throw new InvalidParamException('Invite token cannot be blank.');
        }
        $user = Employee::findOne(['invite_token' => $invite_token]);
        if (!$user) {
            throw new InvalidParamException('Wrong invite token.');
        }
        return $user;
    }


    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented');
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return ($this->auth_key == $authKey);
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }



    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return self::findOne(['email' => $username]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }
}
