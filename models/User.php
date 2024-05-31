<?php

namespace app\models;
use yii\db\ActiveRecord;
use yii\base\NotSupportedException;
use yii\helpers\Security;
class User extends ActiveRecord implements \yii\web\IdentityInterface
{
    public $activity_id;
    /* public $id;
    public $username;
    public $password;
    public $authKey;
    public $accessToken;

    private static $users = [
        '100' => [
            'id' => '100',
            'username' => 'admin',
            'password' => 'admin',
            'authKey' => 'test100key',
            'accessToken' => '100-token',
        ],
        '101' => [
            'id' => '101',
            'username' => 'demo',
            'password' => 'demo',
            'authKey' => 'test101key',
            'accessToken' => '101-token',
        ],
    ]; */

    /* public function rules()
    {
        return [
            [['activity_id'], 'safe'],
        ];
    } */

    public static function tableName() { return 'users'; }
    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(["id" => $id]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($user_token, $type = null)
    {
        return static::findOne(["user_token" => $user_token]);
    }

    public function setPassword($password)
    {
        return \Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(["username" => $username]);
    }
    /**
     * Finds user by Register Email
     *
     * @param string $username
     * @return static|null
     */
    public static function findByEmail($username)
    {
        return static::findOne(["email" => $username]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->user_id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->user_token;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($user_token)
    {
        return $this->user_token === $user_token;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return \Yii::$app->security->validatePassword($password, $this->password);
    }
}
