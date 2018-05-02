<?php
namespace app\controllers;
use app\models\ar\LearnObject;
use app\models\ar\ScaleLearn;
use app\models\Course;
use app\models\Learn;
use app\models\search\CourseSearch;
use app\models\Event;
use Yii;
use yii\web\Controller;

class LearnController extends Controller
{
    public $layout = 'metronic_sidebar';

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            // Authorized users only
            if ( \Yii::$app->user->isGuest ) {
                $this->redirect( ['user/login'] );
                return false;
            }

            return true;
        }

        return false;
    }

    public function actionIndex() // основной экшн
    {
        $learning = new Learn();

        $challenges = [];

        foreach (Course::findSubscribed(Yii::$app->user->id)->all() as $course) {
            $challenges = array_merge($challenges, $course->getNewChallenges(Yii::$app->user->id)->all());
        }
        $searchModel = new CourseSearch();
        $dataProvider = $searchModel->searchSubscribed(
            Yii::$app->user->id,
            Yii::$app->request->queryParams
        );

        $backgroundColor = 'grey';
        $heightScaleValue = 0;
        $allDaysFeed = [];
        $allDaysClean = [];
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $currentDay = strtolower(date("l"));

        foreach (Course::findSubscribed(Yii::$app->user->id)->all() as $keyCourse => $course) {
            $events = Event::find()->where(['course_id' => $course->id])->all();
            $allEvents[$course->id] = $events;
        }

        if (isset($allEvents)) {

            // цикл с разбором всех событий
            foreach ($allEvents as $keyEvent => $event) {

                // цикл с перебором всех событий конкретного курса и выбором события "Начало"
                for ($i = 0; $i < count($event); $i++) {
                    // если у события курса название "Начало", то...
                    if ($event[$i]->title == 'Начало') {
                        // получим модель курса
                        $course = Course::find()->where(['id' => $event[$i]->course_id])->one();
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

                            for ($o = 1; $o <= $week; $o++){
                                if (ScaleLearn::find()->where(['course_id' => $course->id])->andWhere(['week_id' => $o])->andWhere(['user_id' => Yii::$app->user->id])->one()) {
                                    $scale = ScaleLearn::find()->where(['course_id' => $course->id])->andWhere(['week_id' => $o])->andWhere(['user_id' => Yii::$app->user->id])->one();
                                    foreach ($days as $day){
                                        $oneOfDays = json_decode($scale->$day, true);
                                        $allDaysFeed[$keyEvent][$o][] = $oneOfDays['feed'];
                                        $allDaysClean[$keyEvent][$o][] = $oneOfDays['clean'];
                                        $allDays[$keyEvent][$o]['feed'][] = $oneOfDays['feed'];
                                        $allDays[$keyEvent][$o]['clean'][] = $oneOfDays['clean'];
                                    }
                                } else {
                                    $learn = new ScaleLearn();
                                    $learn->user_id = Yii::$app->user->id;
                                    $learn->course_id = $course->id;
                                    $learn->week_id = $o;
                                    foreach ($days as $key => $day) {
                                        $learn->$day = json_encode(['feed' => 0, 'clean' => 0]);
                                    }
                                    $learn->save();
                                }

                            }
                        }
                    }
                }
            }

            $test = Event::find()->where(['course_id' => 1])->all();
            //\yii\helpers\VarDumper::dump($test, 10, true);

            $regexp = "/(тест)([0-9]*)/ui";
            $match = [];
            foreach ($test as $key => $oneTest){
                if(preg_match($regexp, $oneTest->title, $match[$key])) {

                } else {
                    unset($match[$key]);
                }
            }
            //\yii\helpers\VarDumper::dump($match, 10, true);
            foreach ($match as $key => $oneMatch){
               // print $oneMatch[2];
            }

        }

        //   $scale = ScaleLearn::find()->where(['user_id' => Yii::$app->user->id])->one();
        //   $feed = $scale->getValue($allDaysFeed);
        //   $clean = $scale->getValue($allDaysClean);
        //   foreach ($feed as $courseNumber => $weeks){
        //       foreach ($weeks as $weekNumber => $weekValue) {
        //           print 'Курс: ' . $courseNumber . '<br>Неделя: ' . $weekNumber . '<br>Заполненность недели: ' . $weekValue . '<br>';
        //       }
        //   }

        $result = [];
        $weekResult = 0;
        foreach ($allDaysFeed as $courseFeed => $weekFeed){
            foreach ($allDaysClean as $courseClean => $weekClean) {
                if ($courseFeed == $courseClean) {
                    //print $courseClean;
                    foreach ($weekFeed as $keyFeed => $valueFeed){
                        foreach ($weekClean as $keyClean => $valueClean){
                            if ($keyFeed == $keyClean){
                                //$result += $valueFeed + $valueClean;
                                //print $keyClean;
                                $weekResult = 0;
                                foreach ($valueFeed as $lastKeyFeed => $lastValueFeed) {
                                    foreach ($valueClean as $lastKeyClean => $lastValueClean) {
                                        if ($lastKeyFeed == $lastKeyClean){
                                            //print $lastValueFeed;
                                            //print $lastValueClean;
                                            $weekResult += $lastValueFeed + $lastValueClean;
                                            // print '<br>';
                                        }
                                    }
                                }
                            }
                        }
                        $result[$courseFeed][$keyFeed] = $weekResult;
                    }
                }
            }
        }

        // print '<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
        // \yii\helpers\VarDumper::dump($allDays, 10, true);

        $dayName = [0 => 'monday', 1 => 'tuesday', 2 => 'wednesday', 3 => 'thursday', 4 => 'friday', 5 => 'saturday', 6 => 'sunday'];

        $mustDo = [];
        foreach ($allDays as $course => $weeks) {
            foreach ($weeks as $weekKey => $weekArray) {
                //print count($weeks);
                foreach ($weekArray as $element => $week) {
                    foreach ($week as $day => $value) {
                        if ($value == 0 && $weekKey != count($weeks)) {
                            //print 'Получить общий тест ' . $element . ' за неделю №' . $weekKey . '<br>';
                            // набивать массив и выводить в конце
                            $mustDo[$course][$weekKey] = true;
                            break;
                        }
                        if ($value == 0 && $weekKey == count($weeks)) {
                            //print 'Не заполнен ' . $dayName[$day] . ' ' . $element . ' элемент<br>';
                            //print 'в неделе '. $weekKey . '<br>';
                        }
                        if ($value != 0 && $weekKey == count($weeks)) {
                            //print 'Заполнен ' . $dayName[$day] . ' ' . $element . ' элемент<br>';
                            //print 'в неделе ' . $weekKey . '<br>';
                        }

                    }
                }
            }
        }
        //\yii\helpers\VarDumper::dump($mustDo, 10, true);
        //    foreach ($allDaysFeed as $course => $week) {
        //        foreach ($week as $weekKey => $weekArray) {
        //            foreach ($weekArray as $day => $value) {
        //                if ($value == 0) {
        //                    print $dayName[$day] . '<br>';
        //                    print 'в неделе '.$weekKey . '<br>';
        //                }
        //            }
        //            //\yii\helpers\VarDumper::dump($week, 10, true);
        //        }
        //    }

        $lastResult = [];
        $lastWeeks = [];
        //$allWeeks = [];
        foreach ($result as $course => $week) {
            foreach ($week as $weekKey => $day) {
                if (isset($lastResult[$weekKey])) {
                    $lastResult[$weekKey] += $day;
                } else {
                    $lastResult[$weekKey] = $day;
                }
                //    echo '<a href="/learn" id="learn-widget">' .
                //        '<div class="bar-wrapper-learn"><p>' . $weekKey .'</p><p style="font-size:7px"><strong>'  . '<br>'  . '%</strong></p>' .
                //        '<div class="learning-progress-bar-block" style=" background-color:' . $backgroundColor .'">' .
                //        '<div class="learning-progress-bar-fill" style="height:' . $heightScaleValue . '%; width:100%;"></div>' .
                //        '</div>' .
                //        '</div></a>';
                //$allWeeks[$weekKey] =
            }

            $lastWeeks[$course]['week'] = count($week);
            $lastWeeks[$course]['result'] = $week[count($week)];
        }



        $lastValue = 0;
        foreach ($lastWeeks as $course => $lastWeeksValue){
            $lastValue += $lastWeeksValue['result'];
        }

        $heightScaleValue = 100 - $lastValue;

        if ($lastValue <= 10) {
            $backgroundColor = 'red';
        } elseif ($lastValue < 100 && $lastValue > 10) {
            $backgroundColor = 'green';
        } elseif ($lastValue >= 100) {
            $backgroundColor = 'blue';
        }


        //echo '<a href="/learn">' . '<div><p style="margin: -5px">Учёба</p></div></a>';

        //   foreach ($lastResult as $weekKey => $value){
        //       $heightScaleValue = 100 - $value;
        //       echo '<a href="/learn" id="learn-widget">' .
        //           '<div class="bar-wrapper-learn"><p>' .'</p><p style="font-size:7px"><strong>' . $weekKey . '<br>' . $value . '%</strong></p>' .
        //           '<div class="learning-progress-bar-block" style=" background-color:' . $backgroundColor .'">' .
        //           '<div class="learning-progress-bar-fill" style="height:' . $heightScaleValue . '%; width:100%;"></div>' .
        //       //    '</div>' .
        //           '</div></a>';

        //   }

        //print '<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>';

        $all = [];
        foreach ($lastResult as $weekKey => $value){
            if (LearnObject::find()->where(['id' => $weekKey])->one()) {
                $learn = LearnObject::find()->where(['id' => $weekKey])->one();
               // \yii\helpers\VarDumper::dump($learn->object, 10, true);
              //  print '<strong>' . $weekKey . '<br>' . $value . '%</strong>';
              //  print '<br>';
                $value = $value * 25;
                $heightScaleValue = 100 - $value;

                $all[$weekKey]['week'] = $weekKey;
                $all[$weekKey]['object'] = $learn->object;
                $all[$weekKey]['value'] = $value;
                $all[$weekKey]['heightScaleValue'] = $heightScaleValue;

            //    echo '<div class="bar-wrapper"><p>' .'</p><p ><strong>' . $weekKey . '<br>' . $value . '%</strong></p>' .
            //        '<div class="learning-progress-bar-object-block" style="background-image: url(/i/' . $learn->object .'.png); background-repeat: no-repeat">' .
            //        '<div class="learning-progress-bar-object-fill" style="background-image: url(/i/' . $learn->object .'negative.png); background-repeat: no-repeat; height:' . $heightScaleValue . '%; width:100%;"></div>' .
            //        '</div>' .
            //        '</div></a>';

            }
        }
        //\yii\helpers\VarDumper::dump($all, 10, true);
        foreach ($lastResult as $weekKey => $value){
            if ($weekKey == $lastResult[count($weekKey)]) {
                //print '<strong>' . $weekKey . '<br>' . $value . '%</strong>';
                //print '<br>';
                $value = $value * 25;
                $heightScaleValue = 100 - $value;

            //    echo '<div class="bar-wrapper"><p>' .'</p><p ><strong>' . $weekKey . '<br>' . $value . '%</strong></p>' .
            //        '<div class="learning-progress-bar-object-block" style="background-image: url(/i/calendar.png); background-repeat: no-repeat">' .
            //        '<div class="learning-progress-bar-object-fill" style="background-image: url(/i/calendarnegative.png); background-repeat: no-repeat; height:' . $heightScaleValue . '%; width:100%;"></div>' .
            //        '</div>' .
            //        '</div></a>';

            }
        }
        //\yii\helpers\VarDumper::dump($lastResult);
        // массив, в котором все недели по всем курсам
        // каждая неделя содержит количество выполненных тестов в процентах
        // каждая прошедшая неделя содержит ссылку на общий тест - и если выполнил, и если не выполнил

        //   //\yii\helpers\VarDumper::dump($lastWeeks, 10, true);
        //   foreach ($lastWeeks as $course => $lastWeek){
        //       for ($i = 1; $i < $lastWeek['week']; $i++){
        //           if (LearnObject::find()->where(['id' => $i])->one()) {
        //               $learn = LearnObject::find()->where(['id' => $i])->one();
        //               \yii\helpers\VarDumper::dump($learn->object, 10, true);
        //               print $course;
        //               print $i . '<br>';
        //           }
        //       }
        //   }

        // таблица learn_object: id, object
        // связь недель с объектами в шкале и далее в контроллере Учёбы

        //$learn = LearnObject::find()->where(['id' => 1])->one();
        //\yii\helpers\VarDumper::dump($learn->object, 10, true);

        return $this->render('index', [
            'learning' => $learning,
            'challenges' => $challenges,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'all' => $all
        ]);
    }
}