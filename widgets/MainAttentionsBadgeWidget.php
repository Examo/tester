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
                    $feedMessage = 'Прошло уже почти целых три дня, как я не ела! Хочется ку-у-ушать!..';
                    //Yii::$app->session->setFlash('successFeed', "Прошёл уже целых три дня, как я не ела! Очень хочется ку-у-ушать!..");
                }
                if ($roundTime >= 60 * 24 * 3 && $roundTime < 60 * 24 * 7) {
                    $feedMessage = 'Прошло уже больше трёх дней, как я не ела! Начались необратимые изменения в организме!..';
                    //Yii::$app->session->setFlash('successFeed', "Прошло уже больше трёх дней, как я не ела!");
                }
                if ($roundTime >= 60 * 24 * 7) {
                    $feedMessage = 'Прошла уже неделя, как я не ела! Прощай...';
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
                    $cleanMessage = 'Прошёл уже день, как я не делала уборку! Обрастаю грязью...';
                    //Yii::$app->session->setFlash('successClean', "Прошёл уже день, как я не делала уборку! Обрастаю грязью...");
                }
                if ($roundTime >= 60 * 24 && $roundTime < 60 * 24 * 2) {
                    $cleanMessage = 'Прошло уже больше дня, как я не делала уборку! Ну очень хочется сделать уборку!';
                    //Yii::$app->session->setFlash('successClean', "Прошло уже больше дня, как я не делала уборку!");
                }
                if ($roundTime >= 60 * 24 * 2 && $roundTime < 60 * 24 * 3) {
                    $cleanMessage = 'Прошло уже больше двух дней, как я не делала уборку! Я вся в грязи!';
                    //Yii::$app->session->setFlash('successClean', "Прошло уже больше двух дней, как я не делала уборку!  Я вся в грязи!");
                }
                if ($roundTime >= 60 * 24 * 3 && $roundTime < 60 * 24 * 7) {
                    $cleanMessage = 'Прошло уже больше трёх дней, как я не делала уборку! Я намылилась уходить от тебя!';
                    //Yii::$app->session->setFlash('successClean', "Прошло уже больше трёх дней, как я не делала уборку! Я намылилась уходить от тебя!");
                }
                if ($roundTime >= 60 * 24 * 7) {
                    $cleanMessage = 'Прошла уже неделя, как я не делала уборку! Прошай, грязнуля!';
                    //Yii::$app->session->setFlash('successClean', "Прошла уже неделя, как я не делала уборку! Прощай, грязнуля!");
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
            //print 'Нет подписки ни на один Курс  №!';
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
                    $pointsMessage[$course->id] = "Курс  №" . $course->id . " Неплохо! Очков уже " . $allPoints . "!";
                }
                if ($feedPoints[$course->id] + $cleanPoints[$course->id] >= 100 && $feedPoints[$course->id] + $cleanPoints[$course->id] < 300) {
                    $allPoints = $feedPoints[$course->id] + $cleanPoints[$course->id];
                    $pointsMessage[$course->id] = "Курс  №" . $course->id . " Ого, как у тебя много очков! Уже целых " . $allPoints . "!";
                    //Yii::$app->session->setFlash('successPoints', "Ого, как у тебя много очков! Уже целых " . $allPoints ."!");
                }
                if ($feedPoints[$course->id] + $cleanPoints[$course->id] >= 300 && $feedPoints[$course->id] + $cleanPoints[$course->id] < 1000) {
                    $allPoints = $feedPoints[$course->id] + $cleanPoints[$course->id];
                    $pointsMessage[$course->id] = "Курс  №" . $course->id . " Ничего себе! Вот это ты набираешь очки! Уже целых " . $allPoints . "!";
                    //Yii::$app->session->setFlash('successPoints', "Ничего себе! Вот это ты набираешь очки! Уже целых " . $allPoints ."!");
                }
                if ($feedPoints[$course->id] + $cleanPoints[$course->id] >= 1000 && $feedPoints[$course->id] + $cleanPoints[$course->id] < 2000) {
                    $allPoints = $feedPoints[$course->id] + $cleanPoints[$course->id];
                    $pointsMessage[$course->id] = "Курс  №" . $course->id . " Вот это ты умничка! Вот это ты даёшь по очкам! У тебя целых " . $allPoints . "!";
                    //Yii::$app->session->setFlash('successPoints', "Вот это ты умничка! Вот это ты даёшь по очкам! У тебя целых " . $allPoints ."!");
                }
                if ($feedPoints[$course->id] + $cleanPoints[$course->id] >= 2000 && $feedPoints[$course->id] + $cleanPoints[$course->id] < 10000) {
                    $allPoints = $feedPoints[$course->id] + $cleanPoints[$course->id];
                    $pointsMessage[$course->id] = "Курс  №" . $course->id . " Нет слов... У тебя сейчас очков вот столько: " . $allPoints . "!";
                    //Yii::$app->session->setFlash('successPoints', "Вот это ты умничка! Вот это ты даёшь по очкам! У тебя целых " . $allPoints ."!");
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

    print ' <li class="dropdown dropdown-extended dropdown-notification dropdown-dark" id="fire">
						<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
						<i class="icon-fire"></i>
						<span class="badge badge-danger" style="background-color: ' . $badgeBackgroundColor . '; color: ' . $badgeColor . '">' . $number . '</span>
						</a>
						
						<ul class="dropdown-menu">
							<li class="external">
								<h3><span class="bold">' . $number . ' новых</span> сообщ.</h3>
								<!--<a href="profile.html">view all</a>-->
							</li>
							<li>
							    <ul class="dropdown-menu-list scroller" style="height: 250px;" data-handle-color="white">';
									if (isset($feedMessage)) {
						    			print '
						    			<li>
						    				<!--<a href="javascript:;">-->
						    				<a href="/feed' . '">
						    				<span class="details">
				                            <span class="label label-sm label-icon label-danger">
				                            <i class="fa fa-exclamation"></i>
				                            </span>
				                            </span>';
                                            print $feedMessage;
                                            print '</a>
						    			</li>';
                                    }

                                    if (isset($cleanMessage)) {
                                        print '
						    			<li>
						    				<!--<a href="javascript:;">-->
						    				<a href="/clean' . '">
						    				<span class="details">
				                            <span class="label label-sm label-icon label-danger">
				                            <i class="fa fa-exclamation"></i>
				                            </span>
				                            </span>';
                                            print $cleanMessage;
                                            print '</a>
						    			</li>';
                                    }

                                    if (isset($pointsMessage) && $pointsMessage != []) {
                                        foreach ($pointsMessage as $courseId => $text) {
                                            print '
                                            <li>
  	                        		            <a href="/subscription">
  	                        			        <span class="details">
  	                        			        <span class="label label-sm label-icon label-success">
				                                <i class="fa fa-star"></i>
				                                </span>
				                                </span>';
                                                print $text;
  	                        			        print '</a>
                                           </li>';
                                        }
                                    }
									
						print	'</ul>
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