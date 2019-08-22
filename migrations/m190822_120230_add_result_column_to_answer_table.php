<?php

use yii\db\Migration;

/**
 * Handles adding result to table `answer`.
 */
class m190822_120230_add_result_column_to_answer_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('answer', 'result', $this->text());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('answer', 'result');
    }
}
