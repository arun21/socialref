<?php

namespace app\modules\company\models;

use Yii;
use yii\base\NotSupportedException;

/**
 * This is the model class for table "{{%company}}".
 *
 * @property integer $id
 * @property string $company_name
 * @property string $profile_code
 * @property string $logo_path
 * @property string $email
 * @property string $password
 * @property string $password_reset_token
 * @property string $first_name
 * @property string $last_name
 * @property string $phone_number
 * @property string $website
 * @property string $address
 * @property string $country
 * @property string $city
 * @property string $postal_code
 * @property string $company_status
 * @property double $created_dt
 * @property double $updated_dt
 */
class Company extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    const SCENARIO_LOGIN = 'login';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%company}}';
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
        throw new NotSupportedException('"validateAuthKey" is not implemented');
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        throw new NotSupportedException('"getAuthKey" is not implemented');
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

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }
        return static::findOne([
            'password_reset_token' => $token,
            'company_status' => 'E',
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }
    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }


    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_LOGIN] = array('email', 'password');
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company_name', 'profile_code', 'logo_path', 'email', 'password', 'first_name', 'last_name', 'phone_number', 'website', 'address', 'country', 'city', 'postal_code', 'company_status', 'created_dt', 'updated_dt'], 'required'],
            [['email', 'password'], 'required', 'on' => self::SCENARIO_LOGIN],
            [['company_status'], 'string'],
            [['created_dt', 'updated_dt'], 'number'],
            [['company_name', 'password', 'address'], 'string', 'max' => 100],
            [['profile_code'], 'string', 'max' => 4],
            [['logo_path'], 'string', 'max' => 37],
            [['email', 'website'], 'string', 'max' => 50],
            [['first_name', 'last_name'], 'string', 'max' => 30],
            [['phone_number'], 'string', 'max' => 15],
            [['country', 'city'], 'string', 'max' => 25],
            [['postal_code'], 'string', 'max' => 6],
            [['profile_code'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_name' => 'Company Name',
            'profile_code' => 'Profile Code',
            'logo_path' => 'Logo Path',
            'email' => 'Email',
            'password' => 'Password',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'phone_number' => 'Phone Number',
            'website' => 'Website',
            'address' => 'Address',
            'country' => 'Country',
            'city' => 'City',
            'postal_code' => 'Postal Code',
            'company_status' => 'Company Status',
            'created_dt' => 'Created Dt',
            'updated_dt' => 'Updated Dt',
        ];
    }
}
