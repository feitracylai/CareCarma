<?php

use yii\db\Migration;

class m160613_154806_alter_phone_column extends Migration
{
    public function up()
    {
        
        $this->alterColumn('contact', 'device_phone', 'varchar(255) NOT NULL');
        $this->alterColumn('contact', 'home_phone', 'varchar(255) NOT NULL');
        $this->alterColumn('contact', 'work_phone', 'varchar(255) NOT NULL');
    }

    public function down()
    {
        echo "m160613_154806_alter_phone_column cannot be reverted.\n";

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
