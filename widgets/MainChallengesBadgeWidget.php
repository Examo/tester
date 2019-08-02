<?php
namespace app\widgets;
use app\models\ar\ChallengesWeeks;
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
    public function init()
    {
        parent::init();

        $challenges = [];
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $currentDay = strtolower(date("l"));
        $currentDayNumber = 0;
        $all = [];
        $allValue = 0;
        foreach (Course::findSubscribed(Yii::$app->user->id)->all() as $keyEvent => $course) {
            if (Event::find()->where(['course_id' => $course->id])->andWhere(['title' => 'Начало'])->one()) {
                $event = Event::find()->where(['course_id' => $course->id])->andWhere(['title' => 'Начало'])->one();
                //\yii\helpers\VarDumper::dump($events, 10, true);
                $courseStartTime = Yii::$app->getFormatter()->asTimestamp($event->start);
                $time = Yii::$app->getFormatter()->asTimestamp(time());
                // получаем изменение времени с момента начала курса до текущего момента
                $timeAfterCourseStart = $time - $courseStartTime;
                $weekTime = 604800;
                $week = ceil($timeAfterCourseStart / $weekTime);
                //\yii\helpers\VarDumper::dump($week, 10, true);
                $learn = ScaleLearn::find()->where(['course_id' => $course->id])->andWhere(['week_id' => $week])->andWhere(['user_id' => Yii::$app->user->id])->one();
                //\yii\helpers\VarDumper::dump($learn, 10, true);

                if ($learn) {

                    for ($o = 1; $o <= $week; $o++) {
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
                    $timeAfterCourseStart = $time - $courseStartTime;
                    // если курс ещё не начался

                    $regexp = "/(тест)([0-9]*)/ui";
                    $weekTime = 604800;
                    $match = [];
                    foreach ($test as $key => $oneTest) {
                        if (preg_match($regexp, $oneTest->title, $match[$course->id][$key])) {
                            $currentWeek = ceil($timeAfterCourseStart / $weekTime);
                            $testWeekTime = Yii::$app->getFormatter()->asTimestamp($oneTest->start);
                            $tillTestWeekStart = $testWeekTime - $courseStartTime;
                            if ($tillTestWeekStart > 0) {
                                $testWeek = ceil($tillTestWeekStart / $weekTime);
                                $week = ceil($timeAfterCourseStart / $weekTime);
                                if ($week - $testWeek > 0) {
                                    $learnObject = LearnObject::find()->where(['id' => $testWeek])->one();
                                    //$chain[$course->id]['currentWeek'] = $currentWeek;
                                    $chain[$course->id][$key]['week'] = $testWeek;
                                    $chain[$course->id][$key]['test'] = $match[$course->id][$key][2];
                                    $chain[$course->id][$key]['object'] = $learnObject->object;
                                    if (Attempt::find()->where(['challenge_id' => $testWeek])->andWhere(['user_id' => Yii::$app->user->id])->one()) {
                                        //\yii\helpers\VarDumper::dump($ifAttemp, 10, true);
                                        $chain[$course->id][$key]['isAttempt'] = true;
                                        $allValue = $allValue - 1;
                                    } else {
                                        $chain[$course->id][$key]['isAttempt'] = null;
                                    }
                                }
                            }
                        } else {
                            unset($match[$course->id][$key]);
                        }
                    }


                    foreach ($days as $key => $day) {
                        if ($currentDay == $day) {
                            $currentDayNumber = $key;
                        }
                    }

                    if (ChallengesWeeks::find()->where(['course_id' => $course->id])->andWhere(['element_id' => 1])->andWhere(['week_id' => $week])->andWhere(['user_id' => Yii::$app->user->id])->one() && ChallengesWeeks::find()->where(['course_id' => $course->id])->andWhere(['element_id' => 2])->andWhere(['week_id' => $week])->andWhere(['user_id' => Yii::$app->user->id])->one()) {
                        $feedChallengesWeeks = ChallengesWeeks::find()->where(['course_id' => $course->id])->andWhere(['element_id' => 1])->andWhere(['week_id' => $week])->andWhere(['user_id' => Yii::$app->user->id])->one();
                        $challengesFeed = json_decode($feedChallengesWeeks->challenges, true);
                        $cleanChallengesWeeks = ChallengesWeeks::find()->where(['course_id' => $course->id])->andWhere(['element_id' => 2])->andWhere(['week_id' => $week])->andWhere(['user_id' => Yii::$app->user->id])->one();
                        $challengesClean = json_decode($cleanChallengesWeeks->challenges, true);
                        //\yii\helpers\VarDumper::dump($challengesClean, 10, true);
                        //\yii\helpers\VarDumper::dump($challengesFeed, 10, true);

                        $checked = [];
                        foreach ($days as $keyDay => $day) {
                            //print $keyDay;
                            if ($data = json_decode($learn->$day, true)) {
                                $data = json_decode($learn->$day, true);

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
        if (isset($all)) {
            print '<ul class="nav navbar-nav pull-right">
					<li class="separator hide">
					</li>
					<!-- BEGIN NOTIFICATION DROPDOWN -->
					<!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
					<li class="dropdown dropdown-extended dropdown-notification dropdown-dark" id="header_notification_bar">
						<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" aria-expanded="false">
						<i class="icon-bell"></i>
						<span class="badge badge-success" style="background-color: ' . $badgeBackgroundColor . '; color: ' . $badgeColor . '">' . $allValue . '</span>
						</a>';


        if ($allValue != 0) {
            print                '<ul class="dropdown-menu">
							<li class="external">
								<h3>Пропущено: <span class="bold">' . $allValue . '</span></h3>
								<!--<a href="extra_profile.html">view all</a>-->
							</li>
							<li>
								<div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 250px;"><ul class="dropdown-menu-list scroller" style="height: 250px; overflow: hidden; width: auto;" data-handle-color="#637283" data-initialized="1">';


            $number = 1;
            foreach ($all as $course => $days) {
                foreach ($days as $day => $values) {
                    foreach ($values as $element => $value) {

                        echo '<li>
										<a href="/challenge/start?id=' . $value . '">
										<span class="time">Курс ' . $course . '</span>
										<span class="details">
										<span class="label label-sm label-icon"> ' . $number . '
										<!--<i class="fa fa-plus"></i>-->
										</span>
										' . Yii::t('days', $day) . ', ' . Yii::t('element', $element) . '</span>
										</a>
									</li>';
                        $number++;
                    }
                }
            }

            if (isset($chain)) {
                foreach ($chain as $course => $weeks) {
                    foreach ($weeks as $week) {
                        //print $week['test'];
                        $challengeWeek = Challenge::find()->select(['week'])->where(['id' => $week['test']])->one();
                        if (Attempt::find()->where(['challenge_id' => $week['test']])->andWhere(['user_id' => Yii::$app->user->id])->one()) {
                            //$ifAttemp = Attempt::find()->where(['challenge_id' => $week['test']])->andWhere(['user_id' => Yii::$app->user->id])->one();
                        } else {
                            echo '<li>
										<a href="/challenge/start?id=' . $week['test'] . '">
										<span class="time">Курс ' . $course . '</span>
										<span class="details">
										<span class="label label-sm label-icon">' . $number . '
										<!--<i class="fa fa-plus"> </i>-->
										</span>
										Неделя ' . $week['week'] . ', общий тест ' . $week['test'] . '</span>
										</a>
									</li>';
                            $number++;
                        }
                    }
                }
            }


            echo '<!--<li>
										<a href="javascript:;">
										<span class="time">3 mins</span>
										<span class="details">
										<span class="label label-sm label-icon label-danger">
										<i class="fa fa-bolt"></i>
										</span>
										Server #12 overloaded. </span>
										</a>
									</li>
									<li>
										<a href="javascript:;">
										<span class="time">10 mins</span>
										<span class="details">
										<span class="label label-sm label-icon label-warning">
										<i class="fa fa-bell-o"></i>
										</span>
										Server #2 not responding. </span>
										</a>
									</li>
									<li>
										<a href="javascript:;">
										<span class="time">14 hrs</span>
										<span class="details">
										<span class="label label-sm label-icon label-info">
										<i class="fa fa-bullhorn"></i>
										</span>
										Application error. </span>
										</a>
									</li>
									<li>
										<a href="javascript:;">
										<span class="time">2 days</span>
										<span class="details">
										<span class="label label-sm label-icon label-danger">
										<i class="fa fa-bolt"></i>
										</span>
										Database overloaded 68%. </span>
										</a>
									</li>
									<li>
										<a href="javascript:;">
										<span class="time">3 days</span>
										<span class="details">
										<span class="label label-sm label-icon label-danger">
										<i class="fa fa-bolt"></i>
										</span>
										A user IP blocked. </span>
										</a>
									</li>
									<li>
										<a href="javascript:;">
										<span class="time">4 days</span>
										<span class="details">
										<span class="label label-sm label-icon label-warning">
										<i class="fa fa-bell-o"></i>
										</span>
										Storage Server #4 not responding dfdfdfd. </span>
										</a>
									</li>
									<li>
										<a href="javascript:;">
										<span class="time">5 days</span>
										<span class="details">
										<span class="label label-sm label-icon label-info">
										<i class="fa fa-bullhorn"></i>
										</span>
										System Error. </span>
										</a>
									</li>
									<li>
										<a href="javascript:;">
										<span class="time">9 days</span>
										<span class="details">
										<span class="label label-sm label-icon label-danger">
										<i class="fa fa-bolt"></i>
										</span>
										Storage server failed. </span>
										</a>
									</li>-->
								</ul>';
        }


		print						'<div class="slimScrollBar" style="background: rgb(99, 114, 131); width: 7px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 121.359px;"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(234, 234, 234); opacity: 0.2; z-index: 90; right: 1px;"></div>
							</li>
						</ul>
					</li>
					';
        }

    }


    public function run(){

        //return $this->food;
    }
}