<?php

use yii\db\Migration;

class m170123_225453_classLabels_steps_Hour extends Migration
{
    public function up()
    {
        $this->createTable('ClassLabelsHourSteps', array(
            'id' => 'pk',
            'time' => 'varchar(255) DEFAULT NULL',
            'stepsLabel' => 'int(11) DEFAULT 0',
            'nullData' => 'int(11) DEFAULT 0',
            'hardware_id' => 'varchar(15) DEFAULT NULL',
            'updated_at' => 'datetime DEFAULT NOW()',
            'seen' => 'int(11) DEFAULT 0'
        ), '');
    }

    public function down()
    {
        echo "m170123_225453_classLabels_steps_Hour cannot be reverted.\n";

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
