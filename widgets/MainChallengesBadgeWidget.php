<?php
namespace app\widgets;
use app\helpers\CourseStats;
use app\models\ar\ChallengesWeeks;
use app\models\ar\DifficultSubjects;
use app\models\ar\LearnObject;
use app\models\ar\ScaleLearn;
use app\models\Attempt;
use app\models\Challenge;
use app\models\Course;
use app\models\Event;
use app\models\Question;
use Yii;
use yii\base\Widget;

class MainChallengesBadgeWidget extends Widget
{
    /**
     *
     */
    public function init()
    {
        parent::init();

        $challenges = [];
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $currentDay = strtolower(date("l"));
        $currentDayNumber = 0;
        $all = [];
        $allValue = 0;
        $chain = [];
        foreach (Course::findSubscribed(Yii::$app->user->id)->all() as $keyEvent => $course) {
            if (Event::find()->where(['course_id' => $course->id])->andWhere(['title' => 'Начало'])->one()) {

                $courseStats = CourseStats::getCourseStart($course->id);

                $learn = ScaleLearn::find()->where(['course_id' => $course->id])->andWhere(['week_id' => $courseStats['week']])->andWhere(['user_id' => Yii::$app->user->id])->one();

                if ($learn) {

                    for ($o = 1; $o <= $courseStats['week']; $o++) {
                        if (ScaleLearn::find()->where(['course_id' => $course->id])->andWhere(['week_id' => $o])->andWhere(['user_id' => Yii::$app->user->id])->one()) {
                            $scale = ScaleLearn::find()->where(['course_id' => $course->id])->andWhere(['week_id' => $o])->andWhere(['user_id' => Yii::$app->user->id])->one();
                            foreach ($days as $day) {
                                $oneOfDays = json_decode($scale->$day, true);
                                //$allDaysFeed[$keyEvent][$o][] = $oneOfDays['feed'];
                                //$allDaysClean[$keyEvent][$o][] = $oneOfDays['clean'];
                                $allDays[$course->id][$o]['feed'][] = $oneOfDays['feed'];
                                $allDays[$course->id][$o]['clean'][] = $oneOfDays['clean'];
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

                    $test = Event::find()->where(['course_id' => $course->id])->all();
                    //\yii\helpers\VarDumper::dump($test, 10, true);

                    // узнаём текущее время и переводим его в простое число

                    // получаем изменение времени с момента начала курса до текущего момента
                    //$timeAfterCourseStart = $time - $courseStartTime;
                    // если курс ещё не начался

                    $regexp = "/(Общий тест) ([0-9]*)/ui";
                    $weekTime = 604800;
                    $match = [];

                    foreach ($test as $key => $oneTest) {
                        if (preg_match($regexp, $oneTest->title, $match[$course->id][$key])) {
                            $currentWeek = ceil($courseStats['timeAfterCourseStart'] / $weekTime);
                            $testWeekTime = Yii::$app->getFormatter()->asTimestamp($oneTest->start);
                            $tillTestWeekStart = $testWeekTime - $courseStats['courseStartTime'];
                            if ($tillTestWeekStart > 0) {
                                $testWeek = ceil($tillTestWeekStart / $weekTime);
                                $week = ceil($courseStats['timeAfterCourseStart'] / $weekTime);
                                if ($week - $testWeek >= 0) {
                                    $learnObject = LearnObject::find()->where(['id' => $testWeek])->one();
                                    //$chain[$course->id]['currentWeek'] = $currentWeek;
                                    $chain[$course->id][$testWeek]['week'] = $testWeek;
                                    $chain[$course->id][$testWeek]['test'] = $match[$course->id][$key][2];
                                    $chain[$course->id][$testWeek]['object'] = $learnObject->object;
                                    if (Challenge::find()->where(['id' => $chain[$course->id][$testWeek]['test']])->andWhere(['challenge_type_id' => 4])->one()) {
                                        if (Attempt::find()->where(['challenge_id' => $chain[$course->id][$testWeek]['test']])->andWhere(['user_id' => Yii::$app->user->id])->one()) {
                                            //\yii\helpers\VarDumper::dump($ifAttemp, 10, true);
                                            $chain[$course->id][$testWeek]['isAttempt'] = 1;
                                            $allValue = $allValue - 1;
                                        } else {
                                            $chain[$course->id][$testWeek]['isAttempt'] = 0;
                                        }
                                    } else {
                                        $allValue = $allValue - 1;
                                        $chain[$course->id][$testWeek]['isAttempt'] = 2;
                                    }
                                }
                            }
                        } else {
                            unset($match[$course->id][$key]);
                        }
                    }
                   // print '<br><br><br><br><br><br><br><br><br>';
                   // \yii\helpers\VarDumper::dump($chain, 10, true);

                    //\yii\helpers\VarDumper::dump($chain, 10, true);


                    foreach ($days as $key => $day) {
                        if ($currentDay == $day) {
                            $currentDayNumber = $key;
                        }
                    }

                    if (!ChallengesWeeks::find()->where(['course_id' => $course->id])->andWhere(['element_id' => 1])->andWhere(['week_id' => $courseStats['week']])->andWhere(['user_id' => Yii::$app->user->id])->one()){
                        $currentWeeksChallenges = Challenge::find()->where(['course_id' => $course->id])->andWhere(['week' => $week])->andWhere(['element_id' => 1])->all();
                        $allNewChallenges = [];
                        foreach ($currentWeeksChallenges as $newChallenge) {
                            $allNewChallenges[$newChallenge->id] = 0;
                        }
                        if (!$currentWeeksChallenges) {

                        }
                        if ($allNewChallenges == []){
                            $allNewChallenges = [2 => 0];
                        }
                        $challengesWeeks = new ChallengesWeeks();
                        $challengesWeeks->course_id = $course->id;
                        $challengesWeeks->week_id = $week;
                        $challengesWeeks->user_id = Yii::$app->user->id;
                        $challengesWeeks->challenges = json_encode($allNewChallenges);
                        $challengesWeeks->element_id = 1;
                        $challengesWeeks->save();
                    }

                    if (!ChallengesWeeks::find()->where(['course_id' => $course->id])->andWhere(['element_id' => 2])->andWhere(['week_id' => $courseStats['week']])->andWhere(['user_id' => Yii::$app->user->id])->one()){
                        $currentWeeksChallenges = Challenge::find()->where(['course_id' => $course->id])->andWhere(['week' => $week])->andWhere(['element_id' => 1])->all();
                        $allNewChallenges = [];
                        foreach ($currentWeeksChallenges as $newChallenge) {
                            $allNewChallenges[$newChallenge->id] = 0;
                        }
                        if (!$currentWeeksChallenges) {

                        }
                        if ($allNewChallenges == []){
                            $allNewChallenges = [1 => 0];
                        }
                        $challengesWeeks = new ChallengesWeeks();
                        $challengesWeeks->course_id = $course->id;
                        $challengesWeeks->week_id = $week;
                        $challengesWeeks->user_id = Yii::$app->user->id;
                        $challengesWeeks->challenges = json_encode($allNewChallenges);
                        $challengesWeeks->element_id = 2;
                        $challengesWeeks->save();
                    }

                    if (ChallengesWeeks::find()->where(['course_id' => $course->id])->andWhere(['element_id' => 1])->andWhere(['week_id' => $courseStats['week']])->andWhere(['user_id' => Yii::$app->user->id])->one() && ChallengesWeeks::find()->where(['course_id' => $course->id])->andWhere(['element_id' => 2])->andWhere(['week_id' => $courseStats['week']])->andWhere(['user_id' => Yii::$app->user->id])->one()) {
                        $feedChallengesWeeks = ChallengesWeeks::find()->where(['course_id' => $course->id])->andWhere(['element_id' => 1])->andWhere(['week_id' => $courseStats['week']])->andWhere(['user_id' => Yii::$app->user->id])->one();
                        $challengesFeed = json_decode($feedChallengesWeeks->challenges, true);
                        $cleanChallengesWeeks = ChallengesWeeks::find()->where(['course_id' => $course->id])->andWhere(['element_id' => 2])->andWhere(['week_id' => $courseStats['week']])->andWhere(['user_id' => Yii::$app->user->id])->one();
                        $challengesClean = json_decode($cleanChallengesWeeks->challenges, true);

                        $checked = [];
                        foreach ($days as $keyDay => $day) {
                            //print $keyDay;
                            if ($data = json_decode($learn->$day, true)) {
                                $data = json_decode($learn->$day, true);
                               // \yii\helpers\VarDumper::dump($data, 10, true);
                                if ($day == $currentDay && $data['feed'] == 0) {
                                    // print 'Сегодня ' . $day;
                                    $all[$course->id][$day]['feed'] = null;
                                    foreach ($challengesFeed as $number => $value) {
                                        if ($value == 0 && !isset($checked[$number])) {
                                            $all[$course->id][$day]['feed'] = $number;
                                            //unset($challengesFeed[$number]);
                                            $checked[$number] = $number;
                                            break;
                                        }
                                    }
                                }
                                if ($day == $currentDay && $data['clean'] == 0) {
                                    //print 'Сегодня ' . $day;
                                    $all[$course->id][$day]['clean'] = null;
                                    foreach ($challengesClean as $number => $value) {
                                        if ($value == 0 && !isset($checked[$number])) {
                                            $all[$course->id][$day]['clean'] = $number;
                                            //unset($challengesClean[$number]);
                                            $checked[$number] = $number;
                                            break;
                                        }
                                    }
                                }
                                if ($keyDay < $currentDayNumber && $currentDay != 'monday' && $data['feed'] == 0) {

                                    $all[$course->id][$day]['feed'] = null;
                                    foreach ($challengesFeed as $number => $value) {
                                        if ($value == 0 && !isset($checked[$number])) {
                                            $all[$course->id][$day]['feed'] = $number;
                                            //unset($challengesFeed[$number]);
                                            $checked[$number] = $number;
                                            break;
                                        }
                                    }

                                }
                                if ($keyDay < $currentDayNumber && $currentDay != 'monday' && $data['feed'] == 1) {

                                }
                                if ($keyDay < $currentDayNumber && $currentDay != 'monday' && $data['clean'] == 0) {
                                    //$data = json_decode($learn->$day, true);
                                    $all[$course->id][$day]['clean'] = null;
                                    foreach ($challengesClean as $number => $value) {
                                        if ($value == 0 && !isset($checked[$number])) {
                                            $all[$course->id][$day]['clean'] = $number;
                                            //unset($challengesClean[$number]);
                                            $checked[$number] = $number;
                                            break;
                                        }
                                    }

                                }
                                if ($keyDay < $currentDayNumber && $currentDay != 'monday' && $data['clean'] == 1) {

                                }

                            }
                        }
                    }
                }
            }
        }

        //\yii\helpers\VarDumper::dump($all, 10, true);
        //$allValue = 0;
        foreach ($all as $course => $days){
            foreach ($days as $day => $values){
                foreach ($values as $element => $value) {
                    $allValue += 1; //= $value;
                   // \yii\helpers\VarDumper::dump($element, 10, true);
                }
            }
        }

        $allPassedWeeks = [];
        $allElementsValue = 0;
        if (isset($allDays)) {
            foreach ($allDays as $course => $weeks) {
                foreach ($weeks as $week => $elements) {
                    foreach ($elements as $element => $days) {
                        foreach ($days as $day => $value) {
                            if ($value == 0) {
                                // print $week;
                                $allPassedWeeks[$course][$week] = null;
                                $allElementsValue += 1;
                                break;
                            }
                        }
                    }
                }
            }
        }
        $allValue = $allElementsValue / 2 + $allValue;

        if ($allValue == 0){
            $badgeBackgroundColor = 'white';
            $badgeColor = 'grey';
        } else {
            $badgeBackgroundColor = '#26A69A';
            $badgeColor = 'white';
        }

        print '<li class="dropdown dropdown-extended dropdown-notification dropdown-dark" id="challenges">
						<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
						<i class="icon-bell"></i>
						<span class="badge badge-success" style="background-color: ' . $badgeBackgroundColor . '; color: ' . $badgeColor . '">' . $allValue . '</span>
						</a>';
						if ($allValue != 0) {
                        print '
						<ul class="dropdown-menu">
							<li class="external">
								<h3>Пропущено: <span class="bold">' . $allValue . '</span></h3>
								<!--<a href="profile.html">view all</a>-->
							</li>
							
                            <li>
								<ul class="dropdown-menu-list scroller" style="height: 250px;" data-handle-color="white">';
                                $number = 1;
                            if (isset($all)) {
                                foreach ($all as $course => $days) {
                                    foreach ($days as $day => $values) {
                                        foreach ($values as $element => $value) {
                                            $label = 'success';
                                            $icon = 'paw';
                                            if ($element == 'clean') {
                                                $label = 'success';
                                                $icon = 'trash';
                                            }
                                            if ($element == 'feed') {
                                                $label = 'danger';
                                                $icon = 'cutlery';
                                            }
                                            print '
                                                <li>
			    							    <a href="/challenge/start?id=' . $value . '">
			    		    			        <!--<span class="time" title="">Курс?</span>-->
			    		    			        <span class="details">
                                                <span class="label label-sm label-icon label-' . $label . '"> 
			    		                        <i class="fa fa-' . $icon . '"></i>
			    		                        </span>
			    		                        ' . Yii::t('days', $day) . ', ' . Yii::t('element', $element) . '<br><center><strong>Курс</strong></center> "' . Course::find()->where(['id' => $course])->one()->name . '"</span>
			    		                        </a>
			    			                    </li>';
                                            $number++;
                                        }
                                    }
                                }
                            }

                            if ($chain){
                                foreach ($chain as $course => $weeks){
                                    foreach ($weeks as $generalChallenges){
                                        //\yii\helpers\VarDumper::dump($generalChallenges, 10, true);
                                        print '
                                                <li>
			    							    <a href="/challenge/start?id=' . $generalChallenges['test'] . '">
			    		    			        <span class="details">
                                                <span class="label label-sm label-icon label-danger"> 
			    		                        <i class="fa fa-star"></i>
			    		                        </span>
			    		                        Общий тест за ' . $generalChallenges['week'] . '-ю неделю, заполняет в Учёбе "' . Yii::t('learnObjects',  $generalChallenges['object']) . '"<br>"' . Course::find()->where(['id' => $course])->one()->name . '"
			    		                        </a>
			    			                    </li>';
                                        $number++;
                                    }
                                }
                            }
                        }
						  print '</ul>
							</li>
						</ul>
			 </li>';

    }

    public function run(){

        //return $this->food;
    }
}