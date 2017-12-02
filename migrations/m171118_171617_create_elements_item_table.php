<?php

use yii\db\Migration;

/**
 * Handles the creation of table `elements_item`.
 */
class m171118_171617_create_elements_item_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('elements_item', [
            'id' => $this->primaryKey(),
            'element_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
        ]);

        $this->createIndex('idx_elements_item_elements', 'elements_item', 'element_id');
        $this->addForeignKey('fk_elements_item_elements', 'elements_item', 'element_id', 'element', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk_elements_item_elements', 'elements_item');
        $this->dropIndex('idx_elements_item_elements', 'elements_item');
        $this->dropTable('elements_item');
    }
}
