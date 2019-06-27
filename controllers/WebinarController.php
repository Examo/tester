<?php

namespace app\controllers;

use app\models\Attempt;
use app\models\Challenge;
use app\models\Course;
use app\models\Webinar;
use dektrium\user\models\UserSearch;
use Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use app\models\Event;

class WebinarController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $this->layout = 'metronic_sidebar';
        return $this->render('index');
    }

    public function actionWebinar()
    {
        $this->layout = 'metronic_sidebar';
        $webinar = Webinar::findOne(Yii::$app->request->get('id'));
        $events = Event::find()->all();

        $regexp = "/(вебинар в системе)([0-9]*)( )(вебинар по порядку)([0-9]*)( )(занятие)([0-9]*)( )(ссылка)(\S*)( )(описание)([\S\s]*)/ui";
        $match = [];
        $data = [];
        $cleanWebinarChallenges = [];
        foreach ($events as $key => $event) {
            if (preg_match($regexp, $event->title, $match[$key])) {

                if (isset($webinar)) {
                    if ($webinar->id == intval($match[$key][2])) {
                        $data['course_id'] = $event->course_id;
                        $courseName = Course::find()->select('name')->where(['id' => $event->course_id])->one();
                        $data['course_name'] = $courseName->name;
                        $data['webinar_id'] = $match[$key][2];
                        $data['webinar_number'] = $match[$key][5];
                        $data['webinar_exercise_id'] = intval($match[$key][8]);
                        $data['webinar_link'] = $match[$key][11];
                        $data['webinar_description'] = $match[$key][14];
                        $data['webinar_start'] = $event->start;
                        $data['webinar_end'] = $event->end;
                    }
                }
            }
        }

        if (isset($data['course_id'])) {
            $data['isSubscribed'] = false;
            foreach (Course::findSubscribed(Yii::$app->user->id)->all() as $keyEvent => $course) {
                if ($course->id == intval($data['course_id'])) {
                    print 'Есть подписка!';
                    $data['isSubscribed'] = true;
                    break;
                }
            }
        }

        if (isset($data['course_id'])) {
            if (Event::find()->where(['course_id' => $data['course_id']])->andWhere(['title' => 'Начало'])->one()) {
                $event = Event::find()->where(['course_id' => $data['course_id']])->andWhere(['title' => 'Начало'])->one();
                $courseStartTime = Yii::$app->getFormatter()->asTimestamp($event->start);
                $webinarWeekTime = Yii::$app->getFormatter()->asTimestamp($data['webinar_start']);
                $time = Yii::$app->getFormatter()->asTimestamp(time());
                // получаем изменение времени с момента начала курса до текущего момента
                $timeAfterCourseStart = $time - $courseStartTime;
                $timeBeforeWebinarStart = $webinarWeekTime - $courseStartTime;
                $weekTime = 604800;
                $week = ceil($timeAfterCourseStart / $weekTime);
                $data['webinar_week'] = ceil($timeBeforeWebinarStart / $weekTime);
            }

            // \yii\helpers\VarDumper::dump($data['webinar_id'], 10, true);
            // \yii\helpers\VarDumper::dump($data['course_id'], 10, true);

            $challenges = Challenge::find()->where(['course_id' => $data['course_id']])->andWhere(['challenge_type_id' => 3])->andWhere(['week' => $data['webinar_week']])->andWhere(['exercise_number' => $data['webinar_exercise_id']])->all();
            //\yii\helpers\VarDumper::dump($challenges, 10, true);


            foreach ($challenges as $challenge) {
                if (intval($data['webinar_exercise_id']) == $challenge->exercise_number) {
                    $cleanWebinarChallenges['challenge'][$challenge->id] = $challenge;
                    $challengeChecked = Attempt::find()->where(['user_id' => Yii::$app->user->id])->andWhere(['challenge_id' => $challenge->id])->one();
                    if (!$challengeChecked) {
                        $cleanWebinarChallenges['isDone'][$challenge->id] = 0;
                    } else {
                        $cleanWebinarChallenges['isDone'][$challenge->id] = 1;
                    }

                } else {
                    //print 'NEUSPESHEN';
                }
            }
        }

       // \yii\helpers\VarDumper::dump($cleanWebinarChallenges, 10, true);

        //\yii\helpers\VarDumper::dump($webinar->getChallengesStatistic(3), 10, true);
        if (!isset($data)){

        }

        //\yii\helpers\VarDumper::dump($data['course_id'], 10, true);

        if (!empty($webinar)) {
            return $this->render('webinar',
                [
                    'webinar' => $webinar,
                    'data' => $data,
                    'cleanWebinarChallenges' => $cleanWebinarChallenges

                ]);
        } else {
            throw new NotFoundHttpException('Такого вебинара пока ещё не существует!');
        }
    }

}
