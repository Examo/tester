<?php

namespace app\models;

use Yii;

class Learn extends \app\components\ActiveRecord
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

    public function getFeedingConst($number)
    {
        //return $one = self::FEEDING_CONST[$number];
    }


    public function getImageFeeding($i)
    {
        //return $this->image[$i];
    }


    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getDate()
    {
        //return Yii::$app->formatter->asDate($this->date);
    }

    public function getObjectClass($week)
    {
        $object = [];
        switch ($week['object']){
            case 'schooldeskcat':
                $object['height'] = 317;
                $object['width'] = 350;
                $object['background-size'] = 350;
                break;
            case 'blackboard':
                $object['height'] = 300;
                $object['width'] = 250;
                $object['background-size'] = 250;
                break;
            case 'backpack':
                $object['height'] = 129;
                $object['width'] = 100;
                $object['background-size'] = 100;
                break;
            case 'bell':
                $object['height'] = 34;
                $object['width'] = 20;
                $object['background-size'] = 20;
                break;
            case 'schoolsponge':
                $object['height'] = 34;
                $object['width'] = 50;
                $object['background-size'] = 50;
                break;
            case 'colorchalks':
                $object['height'] = 26;
                $object['width'] = 40;
                $object['background-size'] = 40;
                break;
            case 'calendar':
                $object['height'] = 59;
                $object['width'] = 75;
                $object['background-size'] = 75;
                break;
            case 'book':
                $object['height'] = 59;
                $object['width'] = 75;
                $object['background-size'] = 75;
                break;
            case 'diary':
                $object['height'] = 59;
                $object['width'] = 75;
                $object['background-size'] = 75;
                break;
            case 'copybook':
                $object['height'] = 59;
                $object['width'] = 62;
                $object['background-size'] = 62;
                break;
            case 'stand':
                $object['height'] = 59;
                $object['width'] = 75;
                $object['background-size'] = 75;
                break;
            case 'colorpaper':
                $object['height'] = 65;
                $object['width'] = 55;
                $object['background-size'] = 55;
                break;
            case 'dailybook':
                $object['height'] = 65;
                $object['width'] = 55;
                $object['background-size'] = 55;
                break;
            case 'ruler':
                $object['height'] = 65;
                $object['width'] = 55;
                $object['background-size'] = 55;
                break;
            case 'scissors':
                $object['height'] = 65;
                $object['width'] = 55;
                $object['background-size'] = 55;
                break;
            case 'paperknife':
                $object['height'] = 65;
                $object['width'] = 55;
                $object['background-size'] = 55;
                break;
            case 'pen':
                $object['height'] = 65;
                $object['width'] = 55;
                $object['background-size'] = 55;
                break;
            case 'pencil':
                $object['height'] = 65;
                $object['width'] = 55;
                $object['background-size'] = 55;
                break;
            case 'stapler':
                $object['height'] = 65;
                $object['width'] = 55;
                $object['background-size'] = 55;
                break;
            case 'glue':
                $object['height'] = 65;
                $object['width'] = 55;
                $object['background-size'] = 55;
                break;
            case 'alarmclock':
                $object['height'] = 65;
                $object['width'] = 55;
                $object['background-size'] = 55;
                break;
            case 'scotch':
                $object['height'] = 65;
                $object['width'] = 55;
                $object['background-size'] = 55;
                break;
            case 'colors':
                $object['height'] = 65;
                $object['width'] = 55;
                $object['background-size'] = 55;
                break;
            case 'brushesjar':
                $object['height'] = 65;
                $object['width'] = 55;
                $object['background-size'] = 55;
                break;
            case 'album':
                $object['height'] = 65;
                $object['width'] = 55;
                $object['background-size'] = 55;
                break;
            case 'felttippens':
                $object['height'] = 65;
                $object['width'] = 55;
                $object['background-size'] = 55;
                break;
            case 'plasticine':
                $object['height'] = 65;
                $object['width'] = 55;
                $object['background-size'] = 55;
                break;
            case 'corrector':
                $object['height'] = 65;
                $object['width'] = 55;
                $object['background-size'] = 55;
                break;
            case 'stencil':
                $object['height'] = 65;
                $object['width'] = 55;
                $object['background-size'] = 55;
                break;
            case 'eraser':
                $object['height'] = 65;
                $object['width'] = 55;
                $object['background-size'] = 55;
                break;
            case 'clips':
                $object['height'] = 65;
                $object['width'] = 55;
                $object['background-size'] = 55;
                break;
            case 'sharpener':
                $object['height'] = 65;
                $object['width'] = 55;
                $object['background-size'] = 55;
                break;
            case 'chalk':
                $object['height'] = 65;
                $object['width'] = 55;
                $object['background-size'] = 55;
                break;
            case 'laptop':
                $object['height'] = 65;
                $object['width'] = 55;
                $object['background-size'] = 55;
                break;
        }
        return $object;
    }

    public function getTopLeftStyleNumber($week)
    {
        $span = [];
        switch ($week['object']) {
            case 'schooldeskcat':
                $span['top'] = 140;
                $span['left'] = 430;
                break;
            case 'blackboard':
                $span['top'] = 37;
                $span['left'] = 200;
                break;
            case 'backpack':
                $span['top'] = 340;
                $span['left'] = 360;
                break;
            case 'bell':
                $span['top'] = 180;
                $span['left'] = 300;
                break;
            case 'schoolsponge':
                $span['top'] = 180;
                $span['left'] = 110;
                break;
            case 'colorchalks':
                $span['top'] = 180;
                $span['left'] = 160;
                break;
            case 'calendar':
                $span['top'] = 37;
                $span['left'] = 295;
                break;
            case 'book':
                $span['top'] = 230;
                $span['left'] = 345;
                break;
            case 'diary':
                $span['top'] = 257;
                $span['left'] = 270;
                break;
            case 'copybook':
                $span['top'] = 313;
                $span['left'] = 260;
                break;
            case 'stand':
                $span['top'] = 277;
                $span['left'] = 175;
                break;
            case 'colorpaper':
                $span['top'] = 200;
                $span['left'] = 220;
                break;
            case 'dailybook':
                $span['top'] = 200;
                $span['left'] = 220;
                break;
            case 'ruler':
                $span['top'] = 200;
                $span['left'] = 220;
                break;
            case 'scissors':
                $span['top'] = 200;
                $span['left'] = 220;
                break;
            case 'paperknife':
                $span['top'] = 200;
                $span['left'] = 220;
                break;
            case 'pen':
                $span['top'] = 200;
                $span['left'] = 220;
                break;
            case 'pencil':
                $span['top'] = 200;
                $span['left'] = 220;
                break;
            case 'stapler':
                $span['top'] = 200;
                $span['left'] = 220;
                break;
            case 'glue':
                $span['top'] = 200;
                $span['left'] = 220;
                break;
            case 'alarmclock':
                $span['top'] = 200;
                $span['left'] = 220;
                break;
            case 'scotch':
                $span['top'] = 200;
                $span['left'] = 220;
                break;
            case 'colors':
                $span['top'] = 200;
                $span['left'] = 220;
                break;
            case 'brushesjar':
                $span['top'] = 200;
                $span['left'] = 220;
                break;
            case 'album':
                $span['top'] = 200;
                $span['left'] = 220;
                break;
            case 'felttippens':
                $span['top'] = 200;
                $span['left'] = 220;
                break;
            case 'plasticine':
                $span['top'] = 200;
                $span['left'] = 220;
                break;
            case 'corrector':
                $span['top'] = 200;
                $span['left'] = 220;
                break;
            case 'stencil':
                $span['top'] = 200;
                $span['left'] = 220;
                break;
            case 'eraser':
                $span['top'] = 200;
                $span['left'] = 220;
                break;
            case 'clips':
                $span['top'] = 200;
                $span['left'] = 220;
                break;
            case 'sharpener':
                $span['top'] = 200;
                $span['left'] = 220;
                break;
            case 'chalk':
                $span['top'] = 200;
                $span['left'] = 220;
                break;
            case 'laptop':
                $span['top'] = 200;
                $span['left'] = 220;
                break;
        }
        return $span;
    }

}