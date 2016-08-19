<?php

use yii\db\Migration;

class m160819_150947_address_modify extends Migration
{
    public function up()
    {
        $this->update('profile_field', ['sort_order' => 800], ['id' => 6]);
        $this->update('profile_field', ['sort_order' => 500], ['id' => 7]);
        $this->update('profile_field', ['sort_order' => 600], ['id' => 9]);


        $this->insert('profile_field', [
            'profile_field_category_id' => 1,
            'field_type_class' => 'humhub\modules\user\models\fieldtype\Text',
            'field_type_config' => '{"minLength":null,"maxLength":150,"validator":null,"default":null,"regexp":null,"regexpErrorMessage":null,"fieldTypes":[]}',
            'internal_name' => 'address2',
            'title' => 'Apt/Unit (optional)',
            'sort_order' => '450',
            'editable' => '1',
            'visible' => '1',
            'show_at_registration' => '0',
            'required' => '0',
            'is_system' => '1',
            'care_edit' => '1',
        ]);

        $this->addColumn('profile', 'address2', 'varchar(255) DEFAULT NULL');
    }

    public function down()
    {
        echo "m160819_150947_address_modify cannot be reverted.\n";

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
