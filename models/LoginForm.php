<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user
 *
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = false;

    public $isActive = 1;
    public $isBlocked = 0;
    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
            ['isBlocked', 'checkIsBlocked'],
            ['isActive', 'checkIsActive'],
        ];
    }

    public function attributeLabels()
    {
        return [ 
            'username' => 'Username/Email',
            'password' => 'Password',
            'rememberMe' => 'Remember me',
        ];
    }


    public function checkIsBlocked($attribute, $params)
    {
        if (!$this->hasErrors()) {
            // Here, you can check if the user is blocked based on your application's logic.
            // For example, you can query the database to fetch the user and check if the `is_block` attribute is set.
            $user = $this->_user;
            
            if ($user && $user->is_block) {
                $this->addError('password', 'Your account is blocked.');
            }
        }
    }


    public function checkIsActive($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->_user;

            if ($user && !$user->is_active) {
                $this->addError('password', 'Your account is not active. Please contact support.');
            }
        }
    }
    

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
            if(is_null($this->_user)){
                $this->_user = User::findByEmail($this->username);
            }
        }

        return $this->_user;
    }
}
