<?php

use yii\db\Migration;

class m170926_201928_add_step_to_scale_feed extends Migration
{
    /*public function safeUp()
    {

    }

    public function safeDown()
    {
        echo "m170926_201928_add_step_to_scale_feed cannot be reverted.\n";

        return false;
    }
    */

    public function up()
    {
        $this->addColumn('scale_feed', 'step', $this->integer()->notNull());

    }

    public function down()
    {
        $this->dropColumn('scale_feed', 'step');
    }
}
