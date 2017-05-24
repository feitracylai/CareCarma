<?php

use yii\db\Migration;

class m170417_184733_initial extends Migration
{
    public function up()
    {
        $this->createTable('reminder', array(
            'id' => 'pk',
            'module' => 'varchar(100) NOT NULL',
            'object_model' => 'varchar(100) NOT NULL',
            'object_id' => 'int(11) NOT NULL',
            'reminder_time' => 'datetime DEFAULT NULL',
            'before_time' => 'bigint(20) DEFAULT NULL',
            'user_id' => 'int(11) NOT NULL',
            'reminded' => 'int(1) DEFAULT 0',
            'recurrence' => 'varchar(255) DEFAULT NULL',

        ), '');
    }

    public function down()
    {
        echo "m170417_184733_initial cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
