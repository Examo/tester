<?php

use yii\db\Migration;

class m160915_163646_add_question_type_assoc_table extends Migration
{
    public function up()
    {
        $this->insert('question_type', [
            'name' => 'Таблица ассоциаций',
            'sysname' => 'assoc_table'
        ]);
    }

    public function down()
    {
        $this->delete('question_type', [
            'sysname' => 'assoc_table'
        ]);
    }
}
