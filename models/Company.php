<?php

namespace app\models;

use Yii;

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
 *
 * @property Job[] $jobs
 */
class Company extends \yii\db\ActiveRecord
{
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
    public function rules()
    {
        return [
            [['company_name', 'profile_code', 'logo_path', 'email', 'password', 'first_name', 'last_name', 'phone_number', 'website', 'address', 'country', 'city', 'postal_code', 'company_status', 'created_dt', 'updated_dt'], 'required'],
            [['password_reset_token', 'company_status'], 'string'],
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
            'password_reset_token' => 'Password Reset Token',
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJobs()
    {
        return $this->hasMany(Job::className(), ['company_id' => 'id']);
    }
}
