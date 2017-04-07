<?php

namespace app\modules\admin\models;

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
class Company extends \yii\db\ActiveRecord
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    public $input_password;
    public $repeat_password;
    public $logo_upload;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%company}}';
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = array_merge($scenarios['default'], array('input_password', 'repeat_password'));

        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['company_name', 'profile_code', 'email', 'first_name', 'last_name', 'phone_number', 'website', 'address', 'country', 'city', 'postal_code', 'company_status'
            ], 'required'],
            [['input_password', 'repeat_password', 'logo_upload'], 'required', 'on' => self::SCENARIO_CREATE],
            ['input_password', 'string', 'min' => 6],
            ['repeat_password', 'compare', 'compareAttribute'=>'input_password', 'message'=>"Passwords don't match" ],
            [['company_status'], 'string'],
            [['created_dt', 'updated_dt'], 'number'],
            [['company_name', 'address'], 'string', 'max' => 100],
            [['profile_code'], 'string', 'max' => 4],
            [['logo_path'], 'string', 'max' => 37],
            [['email', 'password', 'website'], 'string', 'max' => 50],
            [['first_name', 'last_name'], 'string', 'max' => 30],
            [['phone_number'], 'string', 'max' => 15],
            [['country', 'city'], 'string', 'max' => 25],
            [['postal_code'], 'string', 'max' => 6],
            [['profile_code'], 'unique'],
            [['website'], 'url'],
            [['email'], 'email'],
            [['logo_upload'], 'file', 'extensions' => 'png, jpg','checkExtensionByMimeType'=>false],
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
            'logo_path' => 'Company Logo',
            'logo_upload' => 'Company Logo',
            'email' => 'Email',
            'password' => 'Password',
            'input_password' => 'Password',
            'repeat_password' => 'Repeat Password',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'phone_number' => 'Phone Number',
            'website' => 'Website',
            'address' => 'Address',
            'country' => 'Country',
            'city' => 'City',
            'postal_code' => 'Postal Code',
            'company_status' => 'Company Status',
            'created_dt' => 'Created Date',
            'updated_dt' => 'Updated Date',
        ];
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
