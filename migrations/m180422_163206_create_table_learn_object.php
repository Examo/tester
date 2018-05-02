<?php

use yii\db\Migration;

/**
 * Class m180422_163206_create_table_learn_object
 */
class m180422_163206_create_table_learn_object extends Migration
{
    public function up()
    {
        $this->createTable('learn_object', [
            'id' => $this->primaryKey(),
            'object' => $this->text()->notNull()
        ]);

    }

    public function down()
    {
        $this->dropTable('learn_object');
    }
}
