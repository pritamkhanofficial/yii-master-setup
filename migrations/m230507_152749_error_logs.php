<?php

use yii\db\Migration;

/**
 * Class m230507_152749_error_logs
 */
class m230507_152749_error_logs extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('error_logs', [
            'log_id' => "bigint(30) unsigned NOT NULL AUTO_INCREMENT",
            'log_token' => "varchar(500) NOT NULL",
            'agent' => "varchar(255) NOT NULL DEFAULT 'Agent Not Found'",
            'url' => "varchar(255) NOT NULL DEFAULT 'URL Not Found'",
            'controller' => "varchar(50) DEFAULT NULL",
            'action' => "varchar(50) DEFAULT NULL",
            'device_ip' => "varchar(255) NOT NULL",
            'log_message' => "text DEFAULT NULL",
            'created_at' => "timestamp NOT NULL DEFAULT current_timestamp()",
            'created_by' => "bigint(30) unsigned NOT NULL",
            "PRIMARY KEY (log_id)",
            "CONSTRAINT `fk_users_created_by` FOREIGN KEY(`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION",
            
        ],"ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('error_logs');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230507_152749_logs cannot be reverted.\n";

        return false;
    }
    */
}
