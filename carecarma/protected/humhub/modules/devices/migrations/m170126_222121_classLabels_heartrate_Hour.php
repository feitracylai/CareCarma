<?php

use yii\db\Migration;

class m170126_222121_classLabels_heartrate_Hour extends Migration
{
    public function up()
    {
        $this->createTable('ClassLabelsHourHeart', array(
            'id' => 'pk',
            'time' => 'varchar(255) DEFAULT NULL',
            'heartrateLabel' => 'int(11) DEFAULT 0',
            'hardware_id' => 'varchar(15) DEFAULT NULL',
        ), '');
    }

    public function down()
    {
        echo "m170126_222121_classLabels_heartrate_Hour cannot be reverted.\n";

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
