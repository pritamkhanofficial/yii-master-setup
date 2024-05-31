<?php

use yii\db\Migration;

/**
 * Class m230507_151557_users
 */
class m230507_151557_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('users', [
            'id' => "bigint(30) unsigned NOT NULL AUTO_INCREMENT",
            'user_id' => "bigint(30) unsigned NOT NULL",
            'role_id' => "int(11) unsigned NOT NULL",
            'user_token' => "varchar(500) NOT NULL",
            'username' => "varchar(100) NOT NULL",
            'email' => "varchar(100) DEFAULT NULL",
            'password' => "varchar(250) NOT NULL",
            'full_name' => "varchar(100) NOT NULL",
            'phone' => "varchar(20) DEFAULT NULL",
            'profile_pic' => "varchar(100) DEFAULT 'default.png'",
            'last_ip' => "varchar(255) DEFAULT NULL",
            'last_login' => "datetime DEFAULT NULL",
            'last_logout' => "datetime DEFAULT NULL",
            'is_online' => "tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 for offline 1 for online'",
            'is_block' => "tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 for unblocked 1 for blocked'",
            'generate_token' => " varchar(500) DEFAULT NULL",
            'generate_on' => "datetime DEFAULT NULL",
            'created_at' => "timestamp NOT NULL DEFAULT current_timestamp()",
            'updated_at' => "timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()",
            'created_by' => "bigint(30) unsigned NOT NULL",
            'updated_by' => "bigint(30) unsigned NOT NULL",
            'is_active' => "tinyint(1) NOT NULL DEFAULT 1 COMMENT '0 for no 1 for yes'",
            "PRIMARY KEY (id)",
            "INDEX (role_id)",
            "CONSTRAINT `fk_roles_role_id` FOREIGN KEY(`role_id`) REFERENCES `roles` (`role_id`) ON DELETE RESTRICT ON UPDATE RESTRICT",
            "CONSTRAINT `username_unique` UNIQUE(`username`)",
            "CONSTRAINT `phone_unique` UNIQUE(`phone`)",
            "CONSTRAINT `email_unique` UNIQUE(`email`)"
            
        ],"ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");

        // data sedding to table
        date_default_timezone_set('Asia/Calcutta');
        $date = new DateTime();
        $this->insert('users',[
            'user_id'=>1,
            'role_id'=>1,
            'user_token'=>'token_'.Yii::$app->security->generateRandomString(32),
            'username'=>'admin',
            'email'=>'admin@gmail.com',
            'password'=>Yii::$app->security->generatePasswordHash('123'),
            'full_name'=>'Admin',
            'phone'=>'1234567890',
            'created_at'=>$date->format('Y-m-d H:i:s'),
            'updated_at'=>$date->format('Y-m-d H:i:s'),
            'created_by'=>1,
            'updated_by'=>1,
            'is_active'=>1
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('users');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230507_151557_users cannot be reverted.\n";

        return false;
    }
    */
}
