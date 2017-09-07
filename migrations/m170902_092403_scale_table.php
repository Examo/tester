<?php

use yii\db\Migration;

class m170902_092403_scale_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('scale_feed', [
            'user_id' => $this->integer()->notNull(),
            'last_time' => $this->dateTime()->notNull()
        ]);

        $this->createIndex('idx_scale_feed_user', 'scale_feed', 'user_id');
        $this->addForeignKey('fk_scale_feed_user', 'scale_feed', 'user_id', 'user', 'id', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropIndex('idx_scale_feed_user', 'scale_feed');
        $this->dropForeignKey('fk_scale_feed_user', 'user');

        $this->dropTable('scale_feed');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170902_092403_scale_table cannot be reverted.\n";

        return false;
    }
    */
}
