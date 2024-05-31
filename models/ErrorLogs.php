<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "error_logs".
 *
 * @property int $log_id
 * @property string $log_token
 * @property string $agent
 * @property string $url
 * @property string|null $controller
 * @property string|null $action
 * @property string $device_ip
 * @property string|null $log_message
 * @property string $created_at
 * @property int $created_by
 *
 * @property Users $createdBy
 */
class ErrorLogs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'error_logs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['log_token', 'device_ip'], 'required'],
            [['log_message'], 'string'],
            [['log_token','device_ip','agent','url','controller','action','log_message','created_at'], 'safe'],
            [['created_by'], 'integer'],
            [['log_token'], 'string', 'max' => 500],
            [['agent', 'url', 'device_ip'], 'string', 'max' => 255],
            [['controller', 'action'], 'string', 'max' => 50],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['created_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'log_id' => 'Log ID',
            'log_token' => 'Log Token',
            'agent' => 'Agent',
            'url' => 'Url',
            'controller' => 'Controller',
            'action' => 'Action',
            'device_ip' => 'Device Ip',
            'log_message' => 'Log Message',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
        ];
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(Users::className(), ['id' => 'created_by']);
    }
}
