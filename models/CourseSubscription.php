<?php
namespace app\models;
use app\models\ar\UserPoints;
use app\models\Challenge;
use Yii;
/**
 * @inheritdoc
 */
class CourseSubscription extends \app\models\ar\CourseSubscription
{
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('user', 'User'),
            'course_id' => Yii::t('course', 'Course'),
        ];
    }
    public function getAllCourses($course_id)
    {
        return CourseSubscription::find()
            ->with('courses')->where(['user_id' => $course_id]);
    }
    public function getCourseStart($course_id)
    {
        $events = Event::find()->where(['course_id' => $course_id])->all();
        $allEvents = [];
        $result = [];
        if ($events != []) {
            $allEvents[$course_id] = $events;
        }
        if (isset($allEvents)) {
            // цикл с разбором всех событий
            foreach ($allEvents as $keyEvent => $event) {
                // цикл с перебором всех событий конкретного курса и выбором события "Начало"
                for ($i = 0; $i < count($event); $i++) {
                    // если у события курса название "Начало", то...
                    if ($event[$i]->title == 'Начало') {
                        // получим модель курса
                        //$course = Course::find()->where(['id' => $event[$i]->course_id])->one();
                        // получим время начала курса
                        $courseStartTime = Yii::$app->getFormatter()->asTimestamp($event[$i]->start);
                        // узнаём текущее время и переводим его в простое число
                        $time = Yii::$app->getFormatter()->asTimestamp(time());
                        // получаем изменение времени с момента начала курса до текущего момента
                        $timeAfterCourseStart = $time - $courseStartTime;
                        // если курс ещё не начался
                        if ($timeAfterCourseStart < 0) {
                            $timeAfterCourseStart /= 60;
                            //print 'Курс ' . $course->name . ' ещё не начался!<br> До начала курса осталось ' . $timeAfterCourseStart . ' секунд.<br>';
                        } // если курс уже начался
                        else {
                            //print 'Курс ' . $course->name . ' уже начался!<br> С момента начала курса прошло ' . $timeAfterCourseStart . ' секунд.<br>';
                            $weekTime = 604800;
                            $week = ceil($timeAfterCourseStart / $weekTime);
                            //print 'Идёт ' . $week . '-я неделя курса<br>';
                            $result['week'] = $week;
                        }
                        $result['courseStartTime'] = date("d. m. Y", $courseStartTime);
                        $result['daysAfterCourseStart'] = floor($timeAfterCourseStart / (60 * 60 * 24));
                        $result['monthsAfterCourseStart'] = floor($timeAfterCourseStart / (60 * 60 * 24 * 30));
                        $cleanMonths = $result['monthsAfterCourseStart'] * 30;
                        $result['daysAfterMonthsAfterCourseStart'] =  $result['daysAfterCourseStart'] - $cleanMonths;
                    }
                    if ($event[$i]->title == 'Конец') {
                        $courseEndTime = Yii::$app->getFormatter()->asTimestamp($event[$i]->start);
                        $result['courseEndTime'] = date("d. m. Y", $courseEndTime);
                        $time = Yii::$app->getFormatter()->asTimestamp(time());
                        $timeBeforeCourseEnd = $courseEndTime - $time;
                        $result['daysBeforeCourseEnd'] = floor($timeBeforeCourseEnd  / (60 * 60 * 24));
                        $result['monthsBeforeCourseEnd'] = floor($timeBeforeCourseEnd / (60 * 60 * 24 * 30));
                        $cleanMonths = $result['monthsBeforeCourseEnd'] * 30;
                        $result['daysAfterMonthsBeforeCourseEnd'] =  $result['daysBeforeCourseEnd'] - $cleanMonths;
                    }
                }
            }
        }
        return $result;
    }
    public function getChallenges($course_id)
    {
        $challenges = Challenge::find()->where(['course_id' => $course_id])->all();
        return count($challenges);
    }
    public function getWebinarsCount($course_id)
    {
        $events = Event::find()->where(['course_id' => $course_id])->all();
        $regexp = "/(вебинар)([0-9]*)/ui";
        $match = [];
        if (isset($events)) {
            foreach ($events as $key => $oneEvent) {
                if (preg_match($regexp, $oneEvent->title, $match[$key])) {
                    preg_match($regexp, $oneEvent->title, $match[$key]);
                }
            }
        }
        $number = 0;
        foreach ($match as $item){
            if (count($item) > 0){
                $number++;
            }
        }
        return $number;
    }
    public function getHomeworksCount($course_id)
    {
        $events = Event::find()->where(['course_id' => $course_id])->all();
        $regexp = "/(домашняя работа)([0-9]*)/ui";
        $match = [];
        if (isset($events)) {
            foreach ($events as $key => $oneEvent) {
                if (preg_match($regexp, $oneEvent->title, $match[$key])) {
                    preg_match($regexp, $oneEvent->title, $match[$key]);
                }
            }
        }
        $number = 0;
        foreach ($match as $item){
            if (count($item) > 0){
                $number++;
            }
        }
        return $number;
    }
    public function getExamsCount($course_id)
    {
        $events = Event::find()->where(['course_id' => $course_id])->all();
        $regexp = "/(экзамен)([0-9]*)/ui";
        $match = [];
        if (isset($events)) {
            foreach ($events as $key => $oneEvent) {
                if (preg_match($regexp, $oneEvent->title, $match[$key])) {
                    preg_match($regexp, $oneEvent->title, $match[$key]);
                }
            }
        }
        $number = 0;
        foreach ($match as $item){
            if (count($item) > 0){
                $number++;
            }
        }
        return $number;
    }
    public function getCourseRating($course_id)
    {
        $courseRating = UserPoints::find()->where(['course_id' => $course_id])->all();
        // избавляемся от тех, у кого 0 points
        foreach ($courseRating as $key => $userData) {
            if ($userData->points == 0 && $userData->user_id != Yii::$app->user->id){
                unset($courseRating[$key]);
            }
        }
        $data = [];
        foreach ($courseRating as $key => $usersData){
            if ($usersData->user_id == Yii::$app->user->id){
                $data[$key]['user_id'] = Yii::$app->user->id;
                $data[$key]['isSelf'] = true;
            } else {
                $data[$key]['user_id'] = $usersData->user_id;
                $data[$key]['isSelf'] = null;
            }
            $data[$key]['element_id'] = $usersData->element_id;
            $data[$key]['points'] = $usersData->points;
        }
        //\yii\helpers\VarDumper::dump($data, 10, true);
        $users = [];
        $allUserPoints = [];
        foreach ($data as $key => $value){
            if (isset($users[$value['user_id']])){
                $allUserPoints[$value['user_id']] = $allUserPoints[$value['user_id']] + $value['points'];
            } else {
                $allUserPoints[$value['user_id']] = $value['points'];
                $users[$value['user_id']] = true;
            }
            //unset($data[$key]);
            //$allUserPoints[$value['user_id']] = $value['user_id'];
            //if ($data[$key]['user_id'] == $value['user_id']) {
            //    print $data[$key]['user_id'] . ' === ' . $value['user_id'] . '<br>';
            //unset($data[$key]);
            //}
        }
        // foreach ($allUserPoints as $key => $row) {
        //      $sortOnPoints[$key] = $row;
        //  }
        //   array_multisort($sortOnPoints, SORT_DESC, $data);
        arsort($allUserPoints);
        // оставляем только 5 пользователей в рейтинге, остальных удаляем
        $numberOfItem = 1;
        $neededUsers = [];
        foreach ($allUserPoints as $key => $value) {
            if ($numberOfItem < 6) {
                $neededUsers[$key] = $numberOfItem;
            }
            if ($numberOfItem >= 6){
                if ($key != Yii::$app->user->id) {
                    unset($allUserPoints[$key]);
                }
                if ($key == Yii::$app->user->id) {
                    $neededUsers[$key] = $numberOfItem;;
                }
            }
            $numberOfItem++;
        }
        //print $numberOfItem;
        //\yii\helpers\VarDumper::dump($neededUsers, 10, true);
        foreach ($data as $userKey => $userData) {
            if ($userData['user_id'] != Yii::$app->user->id && !isset($neededUsers[$userData['user_id']])) {
                unset($data[$userKey]);
            } else {
            }
        }
        foreach ($data as $userKey => $userData){
            foreach ($neededUsers as $userId => $userPosition)
                if ($userData['user_id'] == $userId){
                    $data[$userKey]['position'] = $userPosition;
                }
        }
        foreach ($data as $userKey => $userData){
            $user = User::find()->where(['id' => $userData['user_id']])->one();
            $data[$userKey]['username'] = $user->username;
        }
        //\yii\helpers\VarDumper::dump($data, 10, true);
        $all['rating'] = $allUserPoints;
        $all['data'] = $data;
        return $all;
    }
}