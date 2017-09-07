<?php

use yii\db\Migration;

class m170907_190223_add_primary_key_to_scale_feed extends Migration
{
    /* public function safeUp()
     {

     }

     public function safeDown()
     {
         echo "m170907_190223_add_primary_key_to_scale_feed cannot be reverted.\n";

         return false;
   }
 */

     // Use up()/down() to run migration code without a transaction.
     public function up()
     {
         $this->addColumn('scale_feed', 'id', $this->primaryKey());

     }

     public function down()
     {
         $this->dropColumn('scale_feed', 'id');
     }

}
