<?php

use yii\db\Migration;

class m171118_193007_add_column_elements_item_id_to_table_element extends Migration
{
    public function safeUp()
    {
        $this->addColumn('challenge', 'elements_item_id', $this->integer());

        // creates index for column `elements_item_id`
        $this->createIndex(
            'idx-challenge-elements_item_id',
            'challenge',
            'elements_item_id'
        );

        // add foreign key for table `elements_item`
        $this->addForeignKey(
            'fk-challenge-elements_item_id',
            'challenge',
            'elements_item_id',
            'elements_item',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {

        $this->dropForeignKey(
            'fk-challenge-elements_item_id',
            'challenge'
        );

        $this->dropIndex(
            'idx-challenge-elements_item_id',
            'challenge'
        );

        $this->dropColumn('challenge', 'elements_item_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171118_193007_add_column_elements_item_id_to_table_element cannot be reverted.\n";

        return false;
    }
    */
}
