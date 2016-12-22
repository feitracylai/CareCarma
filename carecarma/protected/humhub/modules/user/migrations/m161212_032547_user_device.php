<?php

use yii\db\Migration;

class m161212_032547_user_device extends Migration
{
    public function up()
    {
        $this->addColumn('device', 'user_id', 'int(11) DEFAULT 0');
    }

    public function down()
    {
        echo "m161212_032547_user_device cannot be reverted.\n";

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
