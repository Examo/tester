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
                $object['height'] = 408;
                $object['width'] = 450;
                $object['background-size'] = 450;
                break;
            case 'blackboard':
                $object['height'] = 360;
                $object['width'] = 300;
                $object['background-size'] = 300;
                break;
            case 'backpack':
                $object['height'] = 180;
                $object['width'] = 140;
                $object['background-size'] = 140;
                break;
            case 'bell':
                $object['height'] = 43;
                $object['width'] = 25;
                $object['background-size'] = 25;
                break;
            case 'schoolsponge':
                $object['height'] = 44;
                $object['width'] = 60;
                $object['background-size'] = 60;
                break;
            case 'colorchalks':
                $object['height'] = 26;
                $object['width'] = 40;
                $object['background-size'] = 40;
                break;
            case 'calendar':
                $object['height'] = 75;
                $object['width'] = 95;
                $object['background-size'] = 95;
                break;
            case 'book':
                $object['height'] = 37;
                $object['width'] = 75;
                $object['background-size'] = 75;
                break;
            case 'diary':
                $object['height'] = 39;
                $object['width'] = 75;
                $object['background-size'] = 75;
                break;
            case 'copybook':
                $object['height'] = 40;
                $object['width'] = 62;
                $object['background-size'] = 62;
                break;
            case 'stand':
                $object['height'] = 41;
                $object['width'] = 75;
                $object['background-size'] = 75;
                break;
            case 'colorpaper':
                $object['height'] = 65;
                $object['width'] = 55;
                $object['background-size'] = 55;
                break;
            case 'dailybook':
                $object['height'] = 55;
                $object['width'] = 45;
                $object['background-size'] = 45;
                break;
            case 'ruler':
                $object['height'] = 85;
                $object['width'] = 35;
                $object['background-size'] = 35;
                break;
            case 'scissors':
                $object['height'] = 55;
                $object['width'] = 25;
                $object['background-size'] = 25;
                break;
            case 'paperknife':
                $object['height'] = 65;
                $object['width'] = 20;
                $object['background-size'] = 20;
                break;
            case 'pen':
                $object['height'] = 30;
                $object['width'] = 55;
                $object['background-size'] = 55;
                break;
            case 'pencil':
                $object['height'] = 19;
                $object['width'] = 55;
                $object['background-size'] = 55;
                break;
            case 'stapler':
                $object['height'] = 22;
                $object['width'] = 25;
                $object['background-size'] = 25;
                break;
            case 'glue':
                $object['height'] = 37;
                $object['width'] = 15;
                $object['background-size'] = 15;
                break;
            case 'alarmclock':
                $object['height'] = 69;
                $object['width'] = 50;
                $object['background-size'] = 50;
                break;
            case 'scotch':
                $object['height'] = 47;
                $object['width'] = 25;
                $object['background-size'] = 25;
                break;
            case 'colors':
                $object['height'] = 60;
                $object['width'] = 45;
                $object['background-size'] = 45;
                break;
            case 'brushesjar':
                $object['height'] = 70;
                $object['width'] = 50;
                $object['background-size'] = 50;
                break;
            case 'album':
                $object['height'] = 68;
                $object['width'] = 55;
                $object['background-size'] = 55;
                break;
            case 'felttippens':
                $object['height'] = 55;
                $object['width'] = 35;
                $object['background-size'] = 35;
                break;
            case 'plasticine':
                $object['height'] = 64;
                $object['width'] = 45;
                $object['background-size'] = 45;
                break;
            case 'corrector':
                $object['height'] = 46;
                $object['width'] = 18;
                $object['background-size'] = 18;
                break;
            case 'stencil':
                $object['height'] = 43;
                $object['width'] = 65;
                $object['background-size'] = 65;
                break;
            case 'eraser':
                $object['height'] = 20;
                $object['width'] = 15;
                $object['background-size'] = 15;
                break;
            case 'clips':
                $object['height'] = 32;
                $object['width'] = 25;
                $object['background-size'] = 25;
                break;
            case 'sharpener':
                $object['height'] = 20;
                $object['width'] = 26;
                $object['background-size'] = 26;
                break;
            case 'chalk':
                $object['height'] = 13;
                $object['width'] = 35;
                $object['background-size'] = 35;
                break;
            case 'laptop':
                $object['height'] = 70;
                $object['width'] = 100;
                $object['background-size'] = 100;
                break;
        }
        return $object;
    }

    public function getTopLeftStyleNumber($week)
    {
        $span = [];
        switch ($week['object']) {
            case 'schooldeskcat':
                $span['top'] = 160;
                $span['left'] = 500;
                break;
            case 'blackboard':
                $span['top'] = 37;
                $span['left'] = 230;
                break;
            case 'backpack':
                $span['top'] = 430;
                $span['left'] = 400;
                break;
            case 'bell':
                $span['top'] = 215;
                $span['left'] = 355;
                break;
            case 'schoolsponge':
                $span['top'] = 240;
                $span['left'] = 120;
                break;
            case 'colorchalks':
                $span['top'] = 240;
                $span['left'] = 170;
                break;
            case 'calendar':
                $span['top'] = 37;
                $span['left'] = 350;
                break;
            case 'book':
                $span['top'] = 290;
                $span['left'] = 410;
                break;
            case 'diary':
                $span['top'] = 290;
                $span['left'] = 310;
                break;
            case 'copybook':
                $span['top'] = 340;
                $span['left'] = 390;
                break;
            case 'stand':
                $span['top'] = 330;
                $span['left'] = 200;
                break;
            case 'colorpaper':
                $span['top'] = 240;
                $span['left'] = 230;
                break;
            case 'dailybook':
                $span['top'] = 300;
                $span['left'] = 180;
                break;
            case 'ruler':
                $span['top'] = 240;
                $span['left'] = 260;
                break;
            case 'scissors':
                $span['top'] = 250;
                $span['left'] = 290;
                break;
            case 'paperknife':
                $span['top'] = 280;
                $span['left'] = 240;
                break;
            case 'pen':
                $span['top'] = 340;
                $span['left'] = 480;
                break;
            case 'pencil':
                $span['top'] = 390;
                $span['left'] = 480;
                break;
            case 'stapler':
                $span['top'] = 335;
                $span['left'] = 245;
                break;
            case 'glue':
                $span['top'] = 370;
                $span['left'] = 280;
                break;
            case 'alarmclock':
                $span['top'] = 470;
                $span['left'] = 260;
                break;
            case 'scotch':
                $span['top'] = 430;
                $span['left'] = 310;
                break;
            case 'colors':
                $span['top'] = 500;
                $span['left'] = 520;
                break;
            case 'brushesjar':
                $span['top'] = 530;
                $span['left'] = 380;
                break;
            case 'album':
                $span['top'] = 440;
                $span['left'] = 470;
                break;
            case 'felttippens':
                $span['top'] = 550;
                $span['left'] = 450;
                break;
            case 'plasticine':
                $span['top'] = 470;
                $span['left'] = 390;
                break;
            case 'corrector':
                $span['top'] = 350;
                $span['left'] = 540;
                break;
            case 'stencil':
                $span['top'] = 370;
                $span['left'] = 440;
                break;
            case 'eraser':
                $span['top'] = 320;
                $span['left'] = 220;
                break;
            case 'clips':
                $span['top'] = 360;
                $span['left'] = 250;
                break;
            case 'sharpener':
                $span['top'] = 400;
                $span['left'] = 350;
                break;
            case 'chalk':
                $span['top'] = 245;
                $span['left'] = 320;
                break;
            case 'laptop':
                $span['top'] = 380;
                $span['left'] = 310;
                break;
        }
        return $span;
    }

}