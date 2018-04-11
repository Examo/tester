<?php

namespace app\models\ar;

use Yii;

/**
 * This is the model class for table "scale_learn".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $course_id
 * @property integer $week_id
 * @property string $monday
 * @property string $tuesday
 * @property string $wednesday
 * @property string $thursday
 * @property string $friday
 * @property string $saturday
 * @property string $sunday
 *
 * @property Course $course
 * @property User $user
 */
class ScaleLearn extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'scale_learn';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'course_id', 'week_id', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'], 'required'],
            [['user_id', 'course_id', 'week_id'], 'integer'],
            [['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'], 'string'],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => Course::className(), 'targetAttribute' => ['course_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'course_id' => 'Course ID',
            'week_id' => 'Week ID',
            'monday' => 'Monday',
            'tuesday' => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday',
            'sunday' => 'Sunday',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(Course::className(), ['id' => 'course_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getValue($allDays)
    {
        $all = [];
        if ($allDays) {
            foreach ($allDays as $allDaysKeyCourse => $allDaysWeek) {
                print '<br><br><br><br><br><br><br><br><br><br><br><br>';
                print 'Курс: ' . $allDaysKeyCourse . ':<br>';
                print count($allDays[$allDaysKeyCourse]) . ' - неделя курса<br>';
                foreach ($allDays[$allDaysKeyCourse] as $allDaysWeekNumber => $allDaysWeekDays) {
                    //print 'Неделя: ' . $allDaysKeyTwo . '<br>Выполненность: ';
                    //\yii\helpers\VarDumper::dump($allDaysValueTwo, 10, true);
                //    if ($allDaysWeekNumber == count($allDays[$allDaysKeyCourse])) {
                //        print 'Текущая неделя: ' . $allDaysWeekNumber . '<br>';
                //        $number = 0;
                //        foreach ($allDaysWeekDays as $feedDay) {
                //            //print $feedDay;
                //            $number += $feedDay;
                //        }
                //        print $number . '<br>';
                //    } else {
                //        print 'Прошедшая неделя: ' . $allDaysWeekNumber . '<br>';
                //        $number = 0;
                //        foreach ($allDaysWeekDays as $feedDay) {
                //            //print $feedDay;
                //            $number += $feedDay;
                //        }
                //        print $number . '<br>';
                //    }
                    $number = 0;
                    foreach ($allDaysWeekDays as $feedDay) {
                                   //print $feedDay;
                                    $number += $feedDay;
                    }

                    $all[$allDaysKeyCourse][$allDaysWeekNumber] = $number;
                }
                }

        } else {
            $all = [];
        }
        return $all;
    }

}
