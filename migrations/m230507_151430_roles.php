<?php

use yii\db\Migration;

/**
 * Class m230507_151430_roles
 */
class m230507_151430_roles extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('roles', [
            'role_id' => "int(11) unsigned NOT NULL AUTO_INCREMENT",
            'name' => "varchar(100) NOT NULL",
            'display_name' => "varchar(100) NOT NULL",
            'created_at' => "timestamp NOT NULL DEFAULT current_timestamp()",
            'updated_at' => "timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()",
            'created_by' => "int(30) unsigned NOT NULL",
            'updated_by' => "int(30) unsigned NOT NULL",
            'is_delete' => "tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 for no 1 for yes'",
            'is_active' => "tinyint(1) NOT NULL DEFAULT 1 COMMENT '0 for no 1 for yes'",
            "PRIMARY KEY (role_id)",
            
        ],"ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
        date_default_timezone_set('Asia/Calcutta');
        $date = new DateTime();
        $this->insert('roles',[
            'name'=>'admin',
            'display_name'=>'Admin',
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
        $this->dropTable('roles');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230507_151430_roles cannot be reverted.\n";

        return false;
    }
    */
}
