<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%fb_post}}".
 *
 * @property integer $id
 * @property integer $fb_user_id
 * @property string $fb_object_id
 * @property string $post_type
 * @property string $story
 * @property string $description
 * @property string $picture
 * @property string $full_picture
 * @property string $fb_link
 * @property string $link
 * @property integer $like_count
 * @property integer $comment_count
 * @property double $post_time
 * @property double $created_dt
 * @property double $updated_dt
 *
 * @property FbDetail $fbUser
 */
class FbPost extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%fb_post}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fb_user_id', 'fb_object_id', 'post_type', 'fb_link'], 'required'],
            [['fb_user_id', 'like_count', 'comment_count'], 'integer'],
            [['story', 'picture', 'full_picture', 'fb_link', 'link'], 'string'],
            [['post_time', 'created_dt', 'updated_dt'], 'number'],
            [['fb_object_id'], 'string', 'max' => 45],
            [['post_type'], 'string', 'max' => 10],
            [['fb_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => FbDetail::className(), 'targetAttribute' => ['fb_user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fb_user_id' => 'Fb User ID',
            'fb_object_id' => 'Fb Object ID',
            'post_type' => 'Post Type',
            'story' => 'Story',
            'picture' => 'Picture',
            'full_picture' => 'Full Picture',
            'fb_link' => 'Fb Link',
            'link' => 'Link',
            'like_count' => 'Like Count',
            'comment_count' => 'Comment Count',
            'post_time' => 'Post Time',
            'created_dt' => 'Created Dt',
            'updated_dt' => 'Updated Dt',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFbUser()
    {
        return $this->hasOne(FbDetail::className(), ['id' => 'fb_user_id']);
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
