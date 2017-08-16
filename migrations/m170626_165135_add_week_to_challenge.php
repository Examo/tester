<?php

use yii\db\Migration;
use yii\db\Schema;

class m170626_165135_add_week_to_challenge extends Migration
{
    public function up()
    {
        $this->addColumn(
            'challenge',
            'week',
            $this->integer()->notNull()
        );
    }

    public function down()
    {
        echo "m170626_165135_add_week_to_challenge cannot be reverted.\n";

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
