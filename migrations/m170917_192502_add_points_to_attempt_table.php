<?php

use yii\db\Migration;

class m170917_192502_add_points_to_attempt_table extends Migration
{
    /*public function safeUp()
    {

    }

    public function safeDown()
    {
        echo "m170917_192502_add_points_to_attempt_table cannot be reverted.\n";

        return false;
    }
*/

    public function up()
    {
        $this->addColumn('attempt', 'points', $this->integer()->notNull());

    }

    public function down()
    {
        $this->dropColumn('attempt', 'points');
    }

}
