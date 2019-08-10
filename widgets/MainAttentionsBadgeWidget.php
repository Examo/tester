<?php
namespace app\widgets;
use app\models\ar\ChallengesWeeks;
use app\models\ar\LearnObject;
use app\models\ar\ScaleClean;
use app\models\ar\ScaleFeed;
use app\models\ar\ScaleLearn;
use app\models\ar\UserPoints;
use app\models\Attempt;
use app\models\Challenge;
use app\models\ChallengeHasQuestion;
use app\models\Course;
use app\models\Event;
use app\models\Question;
use Yii;
use yii\base\Widget;

class MainAttentionsBadgeWidget extends Widget
{
    public function init()
    {
        parent::init();

        $feedPoints = [];
        $cleanPoints = [];
        $feedMessage = null;
        $cleanMessage = null;
        $number = 0;
        $pointsMessage = [];
        $badgeBackgroundColor = 'white';
        $badgeColor = 'grey';

        //foreach (Course::findSubscribed(Yii::$app->user->id)->one() as $key => $course) {
        if (Course::findSubscribed(Yii::$app->user->id)->one() && ScaleLearn::find()->where(['course_id' => Course::findSubscribed(Yii::$app->user->id)->one()->id])->andWhere(['user_id' => Yii::$app->user->id])->one()) {
            $course = Course::findSubscribed(Yii::$app->user->id)->one();
            if (ScaleFeed::find()->where(['user_id' => Yii::$app->user->id])->one()) {
                //\yii\helpers\VarDumper::dump(ScaleFeed::find()->where(['user_id' => Yii::$app->user->id])->one(), 10, true);
                //$scaleFeed = ScaleFeed::find()->where(['user_id' => Yii::$app->user->id])->one();
                if (Attempt::find()->innerJoinWith('challenge')->where(['challenge.element_id' => 1])->andWhere(['attempt.user_id' => Yii::$app->user->id])->orderBy(['attempt.id' => SORT_DESC])->limit(1)->one()) {
                    $lastFeedAttempt = Attempt::find()->innerJoinWith('challenge')->where(['challenge.element_id' => 1])->andWhere(['attempt.user_id' => Yii::$app->user->id])->orderBy(['attempt.id' => SORT_DESC])->limit(1)->one();
                    $lastFeedChallengeFinishTime = Yii::$app->getFormatter()->asTimestamp($lastFeedAttempt->finish_time);
                } else {
                    $lastFeedChallengeFinishTime = Yii::$app->getFormatter()->asTimestamp(time());
                }

                // узнаём текущее время и переводим его в простое число
                $time = Yii::$app->getFormatter()->asTimestamp(time());
                // получаем изменение времени с момента окончания теста до текущего момента
                $timeAfterLastFeedChallengeTest = $time - $lastFeedChallengeFinishTime;
                // округляем изменение времени до 100 и отнимаем 1, чтобы получить то значение, которое нужно отнимать для изменения шкалы с течением времени
                $roundTime = ceil($timeAfterLastFeedChallengeTest / 60) - 1;
                //\yii\helpers\VarDumper::dump($roundTime, 10, true);

                if ($roundTime >= 60 && $roundTime < 60 * 3) {
                    $feedMessage = 'Прошло больше часа, я кушать хочу!';
                    //Yii::$app->session->setFlash('successFeed', "Прошло больше часа, я кушать хочу!");
                }
                if ($roundTime >= 60 * 3 && $roundTime < 60 * 12) {
                    $feedMessage = 'Прошло больше трёх часов, я очень хочу есть!';
                    //Yii::$app->session->setFlash('successFeed', "Прошло больше трёх часов, я очень хочу есть!");
                }
                if ($roundTime >= 60 * 12 && $roundTime < 60 * 24) {
                    $feedMessage = 'Прошёл уже день, как я не ела!';
                    //Yii::$app->session->setFlash('successFeed', "Прошёл уже день, как я не ела!");
                }
                if ($roundTime >= 60 * 24 && $roundTime < 60 * 24 * 2) {
                    $feedMessage = 'Прошло уже почти два дня, как я не ела! Ну очень хочется есть!';
                    //Yii::$app->session->setFlash('successFeed', "Прошёл уже день, как я не ела!");
                }
                if ($roundTime >= 60 * 24 * 2 && $roundTime < 60 * 24 * 3) {
                    $feedMessage = 'Прошло уже почти целых три дня, как я не ела!';
                    //Yii::$app->session->setFlash('successFeed', "Прошёл уже целых три дня, как я не ела!");
                }
                if ($roundTime >= 60 * 24 * 3 && $roundTime < 60 * 24 * 7) {
                    $feedMessage = 'Прошло уже больше трёх дней, как я не ела!';
                    //Yii::$app->session->setFlash('successFeed', "Прошло уже больше трёх дней, как я не ела!");
                }
                if ($roundTime >= 60 * 24 * 7) {
                    $feedMessage = 'Прошла уже неделя, как я не ела! Прощай!';
                    //Yii::$app->session->setFlash('successFeed', "Прошла уже неделя, как я не ела! Прощай!");
                }
            } else {
                $scale = new ScaleFeed();
                $scale->user_id = Yii::$app->user->id;
                $scale->last_time = date("Y-m-d H:i:s");
                $scale->points = 0;
                $scale->step = 0;
                $scale->save();
            }

            if (ScaleClean::find()->where(['user_id' => Yii::$app->user->id])->one()) {
                //\yii\helpers\VarDumper::dump(ScaleClean::find()->where(['user_id' => Yii::$app->user->id])->one(), 10, true);
                //$ScaleClean = ScaleClean::find()->where(['user_id' => Yii::$app->user->id])->one();

                if (Attempt::find()->innerJoinWith('challenge')->where(['challenge.element_id' => 2])->andWhere(['attempt.user_id' => Yii::$app->user->id])->orderBy(['attempt.id' => SORT_DESC])->limit(1)->one()) {
                    $lastCleanAttempt = Attempt::find()->innerJoinWith('challenge')->where(['challenge.element_id' => 2])->andWhere(['attempt.user_id' => Yii::$app->user->id])->orderBy(['attempt.id' => SORT_DESC])->limit(1)->one();
                    $lastCleanChallengeFinishTime = Yii::$app->getFormatter()->asTimestamp($lastCleanAttempt->finish_time);
                } else {
                    $lastCleanChallengeFinishTime = Yii::$app->getFormatter()->asTimestamp(time());
                }

                // значение шкалы минус время после теста в единицах минутах равно часу, то

                // узнаём текущее время и переводим его в простое число
                $time = Yii::$app->getFormatter()->asTimestamp(time());
                // получаем изменение времени с момента окончания теста до текущего момента
                $timeAfterLastFeedChallengeTest = $time - $lastCleanChallengeFinishTime;
                // округляем изменение времени до 100 и отнимаем 1, чтобы получить то значение, которое нужно отнимать для изменения шкалы с течением времени
                $roundTime = ceil($timeAfterLastFeedChallengeTest / 100) - 1;
                //\yii\helpers\VarDumper::dump($roundTime, 10, true);

                if ($roundTime >= 60 && $roundTime < 60 * 3) {
                    $cleanMessage = 'Прошло больше часа, я делать уборку хочу!';
                    //Yii::$app->session->setFlash('successClean', "Прошло больше часа, я делать уборку хочу!");
                }
                if ($roundTime >= 60 * 3 && $roundTime < 60 * 12) {
                    $cleanMessage = 'Прошло больше трёх часов, я очень хочу сделать уборку!';
                    //Yii::$app->session->setFlash('successClean', "Прошло больше трёх часов, я очень хочу сделать уборку!");
                }
                if ($roundTime >= 60 * 12 && $roundTime < 60 * 24) {
                    $cleanMessage = 'Прошёл уже день, как я не делала уборку!';
                    //Yii::$app->session->setFlash('successClean', "Прошёл уже день, как я не делала уборку!");
                }
                if ($roundTime >= 60 * 24 && $roundTime < 60 * 24 * 2) {
                    $cleanMessage = 'Прошло уже больше дня, как я не делала уборку! Ну очень хочется сделать уборку!';
                    //Yii::$app->session->setFlash('successClean', "Прошло уже больше дня, как я не делала уборку!");
                }
                if ($roundTime >= 60 * 24 * 2 && $roundTime < 60 * 24 * 3) {
                    $cleanMessage = 'Прошло уже больше двух дней, как я не делала уборку!';
                    //Yii::$app->session->setFlash('successClean', "Прошло уже больше двух дней, как я не делала уборку!");
                }
                if ($roundTime >= 60 * 24 * 3 && $roundTime < 60 * 24 * 7) {
                    $cleanMessage = 'Прошло уже больше трёх дней, как я не делала уборку!';
                    //Yii::$app->session->setFlash('successClean', "Прошло уже больше трёх дней, как я не делала уборку!");
                }
                if ($roundTime >= 60 * 24 * 7) {
                    $cleanMessage = 'Прошла уже неделя, как я не делала уборку! До свидания!';
                    //Yii::$app->session->setFlash('successClean', "Прошла уже неделя, как я не делала уборку! До свидания!");
                }
            } else {
                $scale = new ScaleClean();
                $scale->user_id = Yii::$app->user->id;
                $scale->last_time = date("Y-m-d H:i:s");
                $scale->points = 0;
                $scale->step = 0;
                $scale->save();
            }
        } else {
            //print 'Нет подписки ни на один курс!';
        }

        if (Course::findSubscribed(Yii::$app->user->id)->one() && ScaleLearn::find()->where(['course_id' => Course::findSubscribed(Yii::$app->user->id)->one()->id])->andWhere(['user_id' => Yii::$app->user->id])->one()) {
            foreach (Course::findSubscribed(Yii::$app->user->id)->all() as $key => $course) {
                if (UserPoints::find()->where(['user_id' => Yii::$app->user->id])->andWhere(['course_id' => $course->id])->andWhere(['element_id' => 1])->one()) {
                    $points = UserPoints::find()->where(['user_id' => Yii::$app->user->id])->andWhere(['course_id' => $course->id])->andWhere(['element_id' => 1])->one();
                    //\yii\helpers\VarDumper::dump($points->points, 10, true);
                    $feedPoints[$course->id] = $points->points;
                } else {
                    $points = new UserPoints();
                    $points->user_id = Yii::$app->user->id;
                    $points->course_id = $course->id;
                    $points->element_id = 1;
                    $points->points = 0;
                    $points->save();
                    $feedPoints[$course->id] = $points->points;
                }
                if (UserPoints::find()->where(['user_id' => Yii::$app->user->id])->andWhere(['course_id' => $course->id])->andWhere(['element_id' => 2])->one()) {
                    $points = UserPoints::find()->where(['user_id' => Yii::$app->user->id])->andWhere(['course_id' => $course->id])->andWhere(['element_id' => 2])->one();
                    $cleanPoints[$course->id] = $points->points;
                } else {
                    $points = new UserPoints();
                    $points->user_id = Yii::$app->user->id;
                    $points->course_id = $course->id;
                    $points->element_id = 2;
                    $points->points = 0;
                    $points->save();
                    $cleanPoints[$course->id] = $points->points;
                }
                if ($feedPoints[$course->id] + $cleanPoints[$course->id] < 100 && $feedPoints[$course->id] + $cleanPoints[$course->id] > 0) {
                    $allPoints = $feedPoints[$course->id] + $cleanPoints[$course->id];
                    $pointsMessage[$course->id] = "Курс " . $course->id . " Неплохо! Очков уже " . $allPoints . "!";
                }
                if ($feedPoints[$course->id] + $cleanPoints[$course->id] >= 100 && $feedPoints[$course->id] + $cleanPoints[$course->id] < 300) {
                    $allPoints = $feedPoints[$course->id] + $cleanPoints[$course->id];
                    $pointsMessage[$course->id] = "Курс " . $course->id . " Ого, как у тебя много очков! Уже целых " . $allPoints . "!";
                    //Yii::$app->session->setFlash('successPoints', "Ого, как у тебя много очков! Уже целых " . $allPoints ."!");
                }
                if ($feedPoints[$course->id] + $cleanPoints[$course->id] >= 300 && $feedPoints[$course->id] + $cleanPoints[$course->id] < 1000) {
                    $allPoints = $feedPoints[$course->id] + $cleanPoints[$course->id];
                    $pointsMessage[$course->id] = "Курс " . $course->id . " Ничего себе! Вот это ты набираешь очки! Уже целых " . $allPoints . "!";
                    //Yii::$app->session->setFlash('successPoints', "Ничего себе! Вот это ты набираешь очки! Уже целых " . $allPoints ."!");
                }
            }

            if ($feedMessage == true) {
                $number += 1;
            }

            if ($cleanMessage == true) {
                $number += 1;
            }

            $number += count($pointsMessage);

            if ($number == 0) {
                $badgeBackgroundColor = 'white';
                $badgeColor = 'grey';
            } else {
                $badgeBackgroundColor = '#F3565D';
                $badgeColor = 'white';
            }

            echo '<ul class="nav navbar-nav pull-right">
					<li class="separator hide">
					</li>
					<li class="dropdown dropdown-extended dropdown-dark" id="header_inbox_bar">
						<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" aria-expanded="false">
						<i class="icon-fire"></i>
						<span class="badge badge-danger" style="background-color: ' . $badgeBackgroundColor . '; color: ' . $badgeColor . '">' . $number . '</span>
						</a>';

            //if ($number != 0) {
            echo '<ul class="dropdown-menu">
							<li class="external">
								<h3>У тебя <span class="bold">' . $number . ' новых</span> сообщ.</h3>
								<a href="#">*</a>
							</li>
							<li>
								<div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 250px;"><ul class="dropdown-menu-list scroller" style="height: 250px; overflow: hidden; width: auto;" data-handle-color="#637283" data-initialized="1">';
            if (isset($feedMessage)) {
                echo '<li>
			    <a href="/feed' . '">
				
				<span class="details">
				<span class="label label-sm label-icon"> ' . '
				<!--<i class="fa fa-plus"></i>-->
				</span>
				';
                echo $feedMessage;
                echo '</a>
                  </li>';
            }

            if (isset($cleanMessage)) {
                echo '<li>
			    <a href="/clean' . '">
				<span class="details">
				<span class="label label-sm label-icon"> ' . '
				<!--<i class="fa fa-plus"></i>-->
				</span>';
                echo $cleanMessage;
                echo '</a></li>';
            }

            if (isset($pointsMessage) && $pointsMessage != []) {
                foreach ($pointsMessage as $courseId => $text) {
                    echo '<li>
  			    <a href="#' . '">
  				<span class="details">' . $text . '</a>
                   </li>';
                }
            }

            echo '</ul><div class="slimScrollBar" style="background: rgb(99, 114, 131); width: 7px; position: absolute; top: 114px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 160.904px;"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(234, 234, 234); opacity: 0.2; z-index: 90; right: 1px;"></div></div>
							</li>
						</ul>
						</li>
						</ul>';
        }
    }


    public function run(){

        //return $this->food;
    }
}