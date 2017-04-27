<?php

use yii\db\Migration;

class m160819_153521_address2_debug extends Migration
{
    public function up()
    {
        $this->update('profile_field', ['field_type_class' => 'humhub\modules\user\models\fieldtype\Text', 'field_type_config' => '{"minLength":null,"maxLength":150,"validator":null,"default":null,"regexp":null,"regexpErrorMessage":null,"fieldTypes":[]}'], ['id' => 30]);
    }

    public function down()
    {
        echo "m160819_153521_address2_debug cannot be reverted.\n";

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
