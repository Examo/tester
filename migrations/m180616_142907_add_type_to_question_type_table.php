<?php

use yii\db\Migration;

/**
 * Class m180616_142907_add_type_to_question_type_table
 */
class m180616_142907_add_type_to_question_type_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert( 'question_type', [
            'id' => 8,
            'name' => '3 Вопроса',
            'sysname' => 'three_question',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->delete('question_type', [
            'name' => '3 Вопроса',
            'sysname' => 'three_question'
        ]);
    }
}
