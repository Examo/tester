<?php

use yii\db\Migration;
use yii\db\Schema;

class m170626_160418_add_date_to_course extends Migration
{
    public function up()
    {
        $this->addColumn(
            'course',
            'start_time',
            Schema::TYPE_TIMESTAMP . ' NOT NULL'
        );
    }

    public function down()
    {
        echo "m170626_160418_add_date_to_course cannot be reverted.\n";

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
