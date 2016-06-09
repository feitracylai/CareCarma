<?php

use yii\db\Migration;

class m160609_203342_delete_useless_column extends Migration
{
    public function up()
    {
        $this->dropColumn('user', 'gcmId');
        $this->dropColumn('device', 'user_id');
        $this->dropColumn('contact', 'AndroidId');
        $this->dropColumn('contact', 'isRead');
        $this->execute('DROP TRIGGER IF EXISTS after_device_update');
        $this->dropTable('device_message');
    }

    public function down()
    {
        echo "m160609_203342_delete_useless_column cannot be reverted.\n";

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
