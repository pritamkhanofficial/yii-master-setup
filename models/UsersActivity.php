<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users_activity".
 *
 * @property int $id
 * @property int $user_id
 * @property string $ip
 * @property string $login
 * @property string $loout
 * @property string $agent
 * @property string $created_at
 *
 * @property Users $user
 */
class UsersActivity extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users_activity';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['user_id','ip','login', 'logout', 'agent', 'created_at','updated_at'], 'safe'],
            [['ip', 'agent'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'ip' => 'Ip',
            'login' => 'Login',
            'logout' => 'Logout',
            'agent' => 'Agent',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }
}
