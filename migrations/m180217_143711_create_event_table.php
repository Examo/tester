<?php

use yii\db\Migration;

/**
 * Handles the creation of table `event`.
 */
class m180217_143711_create_event_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('event', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'color' => $this->string(7),
            'start' => $this->dateTime()->notNull(),
            'end' => $this->dateTime()->notNull(),
            'course_id' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx_event_course', 'event', 'course_id');
        $this->addForeignKey('fk_event_course', 'event', 'course_id', 'course', 'id', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('fk_event_course', 'event');
        $this->dropIndex('idx_event_course', 'event');
        $this->dropTable('event');
    }
}
