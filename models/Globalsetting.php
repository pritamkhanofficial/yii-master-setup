<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "global_settings".
 *
 * @property int $global_settings_id
 * @property string|null $organization_name
 * @property string|null $organization_code
 * @property string|null $organization_email
 * @property string|null $address
 * @property string|null $phone
 * @property string|null $currency
 * @property string|null $currency_symbol
 * @property string|null $footer_text
 * @property string|null $timezone
 * @property string|null $date_format
 * @property string|null $facebook_url
 * @property string|null $twitter_url
 * @property string|null $linkedin_url
 * @property string|null $youtube_url
 * @property string $created_at
 * @property string $updated_at
 * @property int $created_by
 * @property int $updated_by
 *
 * @property Users $createdBy
 */
class Globalsetting extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'global_settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['address'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by'], 'required'],
            [['created_by', 'updated_by'], 'integer'],
            [['organization_name', 'organization_code', 'footer_text', 'timezone'], 'string', 'max' => 255],
            [['organization_email', 'facebook_url', 'twitter_url', 'linkedin_url', 'youtube_url'], 'string', 'max' => 100],
            [['phone', 'currency', 'currency_symbol', 'date_format'], 'string', 'max' => 50],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['created_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'global_settings_id' => 'Global Settings ID',
            'organization_name' => 'Organization Name',
            'organization_code' => 'Organization Code',
            'organization_email' => 'Organization Email',
            'address' => 'Address',
            'phone' => 'Phone',
            'currency' => 'Currency',
            'currency_symbol' => 'Currency Symbol',
            'footer_text' => 'Footer Text',
            'timezone' => 'Timezone',
            'date_format' => 'Date Format',
            'facebook_url' => 'Facebook Url',
            'twitter_url' => 'Twitter Url',
            'linkedin_url' => 'Linkedin Url',
            'youtube_url' => 'Youtube Url',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(Users::class, ['id' => 'created_by']);
    }
}
