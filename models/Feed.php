<?php

namespace app\models;

use Yii;

class Feed extends \app\components\ActiveRecord
{
    const FEEDING_CONST = ['one', 'two', 'three', 'four', 'five', 'six'];
    public $tests = 'TASTE SOME TEST';
    public $image = ['/i/orange.png',
        '/i/cherry-pie.png',
        '/i/milk-carton.png',
        '/i/potato-chips.png',
        '/i/sausage.png',
        '/i/meat.png'];
    public $title = 'Первый тест по теме Приставки ПРЕ- и ПРИ-';
    public $time = 5;
    public $percent = 10;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        //return [
        //    [['user_id', 'article_id', 'status'], 'integer'],
        //    [['text'], 'string', 'max' => 255],
        //    [['article_id'], 'exist', 'skipOnError' => true, 'targetClass' => Article::className(), 'targetAttribute' => ['article_id' => 'id']],
        //    [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        //];
    }

    public function getTests()
    {
        return $this->tests;
    }

    public function getFeedingConst($number)
    {
        return $one = self::FEEDING_CONST[$number];
    }


    public function getImageFeeding($i)
    {
        return $this->image[$i];
    }


    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getDate()
    {
        //return Yii::$app->formatter->asDate($this->date);
    }

}