<?php

use yii\db\Migration;

/**
 * Class m230507_152822_global_settings
 */
class m230507_152822_global_settings extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('global_settings', [
            'global_settings_id' => "bigint(11) unsigned NOT NULL AUTO_INCREMENT",
            'organization_name' => "varchar(255) DEFAULT NULL",
            'organization_code' => "varchar(255) DEFAULT NULL",
            'organization_email' => "varchar(100) DEFAULT NULL",
            'address' => "text DEFAULT NULL",
            'phone' => "varchar(50) DEFAULT NULL",
            'currency' => "varchar(50) DEFAULT NULL",
            'currency_symbol' => "varchar(50) DEFAULT NULL",
            'footer_text' => "varchar(255) DEFAULT NULL",
            'timezone' => "varchar(255) DEFAULT NULL",
            'date_format' => "varchar(50) DEFAULT NULL",
            'facebook_url' => "varchar(100) DEFAULT NULL",
            'twitter_url' => "varchar(100) DEFAULT NULL",
            'linkedin_url' => "varchar(100) DEFAULT NULL",
            'youtube_url' => "varchar(100) DEFAULT NULL",
            'created_at' => "timestamp NOT NULL DEFAULT current_timestamp()",
            'updated_at' => "timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()",
            'created_by' => "bigint(11) unsigned NOT NULL",
            'updated_by' => "bigint(11) unsigned NOT NULL",
            "PRIMARY KEY (global_settings_id)",
            "CONSTRAINT `fk_global_settings_created_by` FOREIGN KEY(`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE NO ACTION",
            
        ],"ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('global_settings');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230507_152822_global_settings cannot be reverted.\n";

        return false;
    }
    */
}
