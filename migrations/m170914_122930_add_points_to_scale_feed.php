<?php

use yii\db\Migration;

class m170914_122930_add_points_to_scale_feed extends Migration
{
    /* public function safeUp()
     {

     }

     public function safeDown()
     {
         echo "m170914_122930_add_points_to_scale_feed cannot be reverted.\n";

         return false;
   }*/


     // Use up()/down() to run migration code without a transaction.
     public function up()
     {
         $this->addColumn('scale_feed', 'points', $this->integer()->notNull());

     }

    public function down()
    {
        $this->dropColumn('scale_feed', 'points');
    }

}
