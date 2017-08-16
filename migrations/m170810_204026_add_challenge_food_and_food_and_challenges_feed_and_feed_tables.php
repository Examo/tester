<?php

use yii\db\Migration;

class m170810_204026_add_challenge_food_and_food_and_challenges_feed_and_feed_tables extends Migration
{
    public function safeUp()
    {
        $this->createTable('challenge_food', [
            'id' => $this->primaryKey(),
            'challenge_id' => $this->integer()->notNull(),
            'food_id' => $this->integer()->notNull(),
        ]);

        $this->createTable('food', [
            'id' => $this->primaryKey(),
            'food_name' => $this->text(),
        ]);

        $this->createTable('challenges_feed', [
            'id' => $this->primaryKey(),
            'course_id' => $this->integer()->notNull(),
            'week_id' => $this->integer()->notNull(),
            'challenges' => $this->text(),
        ]);

        $this->createTable('feed', [
            'id' => $this->primaryKey(),
            'course_subscription_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'week_id' => $this->integer()->notNull(),
            'challenges_done' => $this->text(),
        ]);

        $this->createIndex('idx_challenge_food_challenge', 'challenge_food', 'challenge_id');
        $this->addForeignKey('fk_challenge_food_challenge', 'challenge_food', 'challenge_id', 'challenge', 'id', 'CASCADE');

        $this->createIndex('idx_challenge_food_food', 'challenge_food', 'food_id');
        $this->addForeignKey('fk_challenge_food_food', 'challenge_food', 'food_id', 'food', 'id', 'CASCADE');

        $this->createIndex('idx_challenges_feed_course', 'challenges_feed', 'course_id');
        $this->addForeignKey('fk_challenges_feed_course', 'challenges_feed', 'course_id', 'course', 'id','CASCADE');

        $this->createIndex('idx_challenges_feed_challenge', 'challenges_feed', 'week_id');
        $this->addForeignKey('fk_challenges_feed_challenge', 'challenges_feed', 'week_id', 'challenge', 'week','CASCADE');

        $this->createIndex('idx_challenges_feed_user', 'challenges_feed', 'user_id');
        $this->addForeignKey('fk_challenges_feed_user', 'challenges_feed', 'user_id', 'user', 'id','CASCADE');

        $this->createIndex('idx_feed_course_subscription', 'feed', 'course_subscription_id');
        $this->addForeignKey('fk_feed_course_subscription', 'feed', 'course_subscription_id', 'course_subscription', 'course_id','CASCADE');

        $this->createIndex('idx_feed_user', 'feed', 'user_id');
        $this->addForeignKey('fk_feed_user', 'feed', 'user_id', 'user', 'id','CASCADE');

        $this->createIndex('idx_feed_week', 'feed', 'week_id');
        $this->addForeignKey('fk_feed_week', 'feed', 'week_id', 'challenge', 'week','CASCADE');

    }

    public function safeDown()
    {
        $this->dropIndex('idx_challenge_food_challenge', 'challenge_food');
        $this->dropForeignKey('fk_challenge_food_challenge', 'challenge');

        $this->dropIndex('idx_challenge_food_food', 'challenge_food');
        $this->dropForeignKey('fk_challenge_food_food', 'food');

        $this->dropIndex('idx_challenges_feed_course', 'challenges_feed');
        $this->dropForeignKey('fk_challenges_feed_course', 'course');

        $this->dropIndex('idx_challenges_feed_challenge', 'challenges_feed');
        $this->dropForeignKey('fk_challenges_feed_challenge', 'challenge');

        $this->dropIndex('idx_challenges_feed_user', 'challenges_feed');
        $this->dropForeignKey('fk_challenges_feed_user', 'user');

        $this->dropIndex('idx_feed_course_subscription', 'feed');
        $this->dropForeignKey('fk_feed_course_subscription', 'course_subscription');

        $this->dropIndex('idx_feed_user', 'feed');
        $this->dropForeignKey('fk_feed_user', 'user');

        $this->dropIndex('idx_feed_week', 'feed');
        $this->dropForeignKey('fk_feed_week', 'challenge');

        $this->dropTable('challenge_food');
        $this->dropTable('food');
        $this->dropTable('challenges_feed');
        $this->dropTable('feed');

    }

}

/* Insert for 'food' table in next migration
 * $this->insert('food', [
            'id' => 1,
            'food_name' => 'orange',
        ]);
        $this->insert('food', [
            'id' => 2,
            'food_name' => 'apple',
        ]);
        $this->insert('food', [
            'id' => 3,
            'food_name' => 'cherry_pie',
        ]);

        $this->insert('food', [
            'id' => 4,
            'food_name' => 'milk_carton',
        ]);

        $this->insert('food', [
            'id' => 5,
            'food_name' => 'meat',
        ]);

        $this->insert('food', [
            'id' => 6,
            'food_name' => 'hot_dog',
        ]);

        $this->insert('food', [
            'id' => 7,
            'food_name' => 'potato_chips',
        ]);

        $this->insert('food', [
            'id' => 8,
            'food_name' => 'banana',
        ]);

        $this->insert('food', [
            'id' => 9,
            'food_name' => 'donut',
        ]);

        $this->insert('food', [
            'id' => 10,
            'food_name' => 'cookie_cat',
        ]);

        $this->insert('food', [
            'id' => 11,
            'food_name' => 'coffee',
        ]);

        $this->insert('food', [
            'id' => 12,
            'food_name' => 'chocolate',
        ]);

        $this->insert('food', [
            'id' => 13,
            'food_name' => 'pasta',
        ]);

        $this->insert('food', [
            'id' => ,
            'food_name' => '',
        ]);
*/
