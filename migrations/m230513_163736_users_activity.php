<?php

use yii\db\Migration;

/**
 * Class m230513_163736_users_activity
 */
class m230513_163736_users_activity extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('users_activity', [
            'id' => "bigint(30) unsigned NOT NULL AUTO_INCREMENT",
            'user_id' => "bigint(30) unsigned NOT NULL",
            'ip' => "varchar(255) NOT NULL DEFAULT '::1'",
            'login' => "timestamp NOT NULL DEFAULT current_timestamp()",
            'logout' => "timestamp NOT NULL DEFAULT current_timestamp()",
            'agent' => "varchar(255) NOT NULL DEFAULT 'Agent Not Found'",
            'created_at' => "timestamp NOT NULL DEFAULT current_timestamp()",
            'updated_at' => "timestamp NOT NULL DEFAULT current_timestamp()",
            "PRIMARY KEY (id)",
            "CONSTRAINT `fk_users_user_id` FOREIGN KEY(`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION",
            
        ],"ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('users_activity');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230513_163736_users_activity cannot be reverted.\n";

        return false;
    }
    */
}
