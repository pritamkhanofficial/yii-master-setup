<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property int $user_id
 * @property int $role_id
 * @property string $user_token
 * @property string $username
 * @property string|null $email
 * @property string $password
 * @property string $full_name
 * @property string|null $phone
 * @property string|null $profile_pic
 * @property string|null $last_ip
 * @property string|null $last_login
 * @property string|null $last_logout
 * @property int $is_online 0 for offline 1 for online
 * @property int $is_block 0 for unblocked 1 for blocked
 * @property string|null $generate_token
 * @property string|null $generate_on
 * @property string $created_at
 * @property string $updated_at
 * @property int $created_by
 * @property int $updated_by
 * @property int $is_active 0 for no 1 for yes
 *
 * @property GlobalSettings[] $globalSettings
 * @property Logs[] $logs
 * @property Roles $role
 */
class Users extends \yii\db\ActiveRecord
{
    public $newPassword;
    public $confirmPassword;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['confirmPassword','newPassword'], 'required','on' => 'changepassword'],
            [['role_id', 'user_token', 'username', 'email', 'full_name', 'created_by', 'updated_by'], 'required'],
            ['email','email'],
            [['role_id', 'is_online', 'is_block', 'created_by', 'updated_by', 'is_active'], 'integer'],

            [['user_id','role_id','user_token','username','email','password','full_name','phone','profile_pic','last_ip','last_login','last_logout','is_online','is_block','generate_token','generate_on','created_at','updated_at','created_by','updated_by','is_active'], 'safe'],

            [['user_token', 'generate_token'], 'string', 'max' => 500],
            [['email', 'username', 'phone'], 'unique'],
            [['username', 'email', 'full_name', 'profile_pic'], 'string', 'max' => 100],
            [['password'], 'string', 'max' => 250],
            [['phone'], 'string', 'max' => 20],
            [['last_ip'], 'string', 'max' => 255],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Roles::className(), 'targetAttribute' => ['role_id' => 'role_id']],
            ['newPassword', 'string', 'min' => 6],
            ['confirmPassword', 'compare', 'compareAttribute'=>'newPassword'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'role_id' => 'Role ID',
            'user_token' => 'User Token',
            'username' => 'Username',
            'email' => 'Email',
            'password' => 'Password',
            'full_name' => 'Full Name',
            'phone' => 'Phone',
            'profile_pic' => 'Profile Pic',
            'last_ip' => 'Last Ip',
            'last_login' => 'Last Login',
            'last_logout' => 'Last Logout',
            'is_online' => 'Is Online',
            'is_block' => 'Is Block',
            'generate_token' => 'Generate Token',
            'generate_on' => 'Generate On',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'is_active' => 'Is Active',
            'newPassword' => 'New Password',
            'confirmPassword' => 'Confirm Password',
        ];
    }

    /**
     * Gets query for [[GlobalSettings]].
     *
     * @return \yii\db\ActiveQuery
     */
    /* public function getGlobalSettings()
    {
        return $this->hasMany(GlobalSettings::className(), ['created_by' => 'user_id']);
    } */

    /**
     * Gets query for [[Logs]].
     *
     * @return \yii\db\ActiveQuery
     */
    /* public function getLogs()
    {
        return $this->hasMany(Logs::className(), ['created_by' => 'user_id']);
    } */

    /**
     * Gets query for [[Role]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(Roles::className(), ['role_id' => 'role_id']);
    }


    public function datatable($limit, $offset, $search)
    {
        $where_clasue = " WHERE roles.is_delete=0";

        $sql = "SELECT COUNT(users.id) AS total
        FROM users
        LEFT JOIN roles ON roles.role_id = users.role_id ". $where_clasue;
        $totalRecord = Yii::$app->db->createCommand($sql)->queryScalar();

        if (!empty($search)) {
            $where_clasue .= " AND (
                users.username LIKE :search
                OR
                users.full_name LIKE :search
                OR
                users.email LIKE :search
                OR
                roles.display_name LIKE :search
            )";
        }
        $sql = "SELECT COUNT(users.id) AS total
        FROM users
        LEFT JOIN roles ON roles.role_id = users.role_id ". $where_clasue;
        $recordsFiltered = Yii::$app->db->createCommand($sql)
                           ->bindValue(':search','%'.$search.'%')
                           ->queryScalar();

        $sql = "SELECT users.id, users.user_id, users.username, users.full_name, users.email, users.phone, users.created_at, roles.display_name AS role
        FROM users
        LEFT JOIN roles ON roles.role_id = users.role_id
        $where_clasue
        ORDER BY users.created_at DESC
        LIMIT $limit
        OFFSET $offset";
        $data =  Yii::$app->db->createCommand($sql)
                 ->bindValue(':search','%'.$search.'%')
                 ->queryAll();
        return [
            'recordsTotal' => $totalRecord,
            'recordsFiltered' => $recordsFiltered,
            'data' => (object)$data
        ];

    }
}
