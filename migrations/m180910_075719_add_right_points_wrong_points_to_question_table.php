<?php

use yii\db\Migration;

/**
 * Class m180910_075719_add_right_points_wrong_points_to_question_table
 */
class m180910_075719_add_right_points_wrong_points_to_question_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('question', 'right_points', $this->integer()->notNull());
        $this->addColumn('question', 'wrong_points', $this->integer()->notNull());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('question', 'right_points');
        $this->dropColumn('question', 'wrong_points');
        //echo "m180910_075719_add_right_points_wrong_points_to_question_table cannot be reverted.\n";

        //return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180910_075719_add_right_points_wrong_points_to_question_table cannot be reverted.\n";

        return false;
    }
    */
}
