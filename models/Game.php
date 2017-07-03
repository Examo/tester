<?php

namespace app\models;

use Yii;

class Game extends \app\components\ActiveRecord
{
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
        // return $this->tests;
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