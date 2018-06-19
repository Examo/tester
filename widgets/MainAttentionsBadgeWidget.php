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

        $feedPoints = 0;
        $cleanPoints = 0;

        //foreach (Course::findSubscribed(Yii::$app->user->id)->one() as $key => $course) {
            if (Course::findSubscribed(Yii::$app->user->id)->one()) {
                $course = Course::findSubscribed(Yii::$app->user->id)->one();
                if (ScaleFeed::find()->where(['user_id' => Yii::$app->user->id])->one()) {
                    //\yii\helpers\VarDumper::dump(ScaleFeed::find()->where(['user_id' => Yii::$app->user->id])->one(), 10, true);
                    //$scaleFeed = ScaleFeed::find()->where(['user_id' => Yii::$app->user->id])->one();
                    $lastFeedAttempt = Attempt::find()->innerJoinWith('challenge')->where(['challenge.element_id' => 1])->andWhere(['attempt.user_id' => Yii::$app->user->id])->orderBy(['attempt.id' => SORT_DESC])->limit(1)->one();
                    $lastFeedChallengeFinishTime = Yii::$app->getFormatter()->asTimestamp($lastFeedAttempt->finish_time);

                    // значение шкалы минус время после теста в единицах минутах равно часу, то

                    // узнаём текущее время и переводим его в простое число
                    $time = Yii::$app->getFormatter()->asTimestamp(time());
                    // получаем изменение времени с момента окончания теста до текущего момента
                    $timeAfterLastFeedChallengeTest = $time - $lastFeedChallengeFinishTime;
                    // округляем изменение времени до 100 и отнимаем 1, чтобы получить то значение, которое нужно отнимать для изменения шкалы с течением времени
                    $roundTime = ceil($timeAfterLastFeedChallengeTest / 100) - 1;
                    //\yii\helpers\VarDumper::dump($roundTime, 10, true);

                    if ($roundTime >= 60 && $roundTime < 60 * 3) {
                       // print 'Прошло больше часа, я кушать хочу!';
                        Yii::$app->session->setFlash('successFeed', "Прошло больше часа, я кушать хочу!");
                    }
                    if ($roundTime >= 60 * 3 && $roundTime < 60 * 12) {
                       // print 'Прошло три часа, я очень хочу есть!';
                        Yii::$app->session->setFlash('successFeed', "Прошло три часа, я очень хочу есть!");
                    }
                    if ($roundTime >= 60 * 12 && $roundTime < 60 * 24) {
                       // print 'Прошёл уже день, как я не ела!';
                        Yii::$app->session->setFlash('successFeed', "Прошёл уже день, как я не ела!");
                    }
                    if ($roundTime >= 60 * 24 && $roundTime < 60 * 24 * 2) {
                       // print 'Прошло уже два дня, как я не ела! Ну очень хочется есть!';
                        Yii::$app->session->setFlash('successFeed', "Прошёл уже день, как я не ела!");
                    }
                    if ($roundTime >= 60 * 24 * 2 && $roundTime < 60 * 24 * 3) {
                       // print 'Прошёл уже целых три дня, как я не ела!';
                        Yii::$app->session->setFlash('successFeed', "Прошёл уже целых три дня, как я не ела!");
                    }
                    if ($roundTime >= 60 * 24 * 3 && $roundTime < 60 * 24 * 7) {
                       // print 'Прошло уже больше трёх дней, как я не ела!';
                        Yii::$app->session->setFlash('successFeed', "Прошло уже больше трёх дней, как я не ела!");
                    }
                    if ($roundTime >= 60 * 24 * 7) {
                       // print 'Прошла уже неделя, как я не ела! Прощай!';
                        Yii::$app->session->setFlash('successFeed', "Прошла уже неделя, как я не ела! Прощай!");
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
                    $ScaleClean = ScaleClean::find()->where(['user_id' => Yii::$app->user->id])->one();
                    $lastFeedAttempt = Attempt::find()->innerJoinWith('challenge')->where(['challenge.element_id' => 2])->andWhere(['attempt.user_id' => Yii::$app->user->id])->orderBy(['attempt.id' => SORT_DESC])->limit(1)->one();
                    $lastFeedChallengeFinishTime = Yii::$app->getFormatter()->asTimestamp($lastFeedAttempt->finish_time);

                    // значение шкалы минус время после теста в единицах минутах равно часу, то

                    // узнаём текущее время и переводим его в простое число
                    $time = Yii::$app->getFormatter()->asTimestamp(time());
                    // получаем изменение времени с момента окончания теста до текущего момента
                    $timeAfterLastFeedChallengeTest = $time - $lastFeedChallengeFinishTime;
                    // округляем изменение времени до 100 и отнимаем 1, чтобы получить то значение, которое нужно отнимать для изменения шкалы с течением времени
                    $roundTime = ceil($timeAfterLastFeedChallengeTest / 100) - 1;
                    //\yii\helpers\VarDumper::dump($roundTime, 10, true);

                    if ($roundTime >= 60 && $roundTime < 60 * 3) {
                        //print 'Прошло больше часа, я делать уборку хочу!';
                        Yii::$app->session->setFlash('successClean', "Прошло больше часа, я делать уборку хочу!");
                    }
                    if ($roundTime >= 60 * 3 && $roundTime < 60 * 12) {
                        //print 'Прошло три часа, я очень хочу сделать уборку!';
                        Yii::$app->session->setFlash('successClean', "Прошло три часа, я очень хочу сделать уборку!");
                    }
                    if ($roundTime >= 60 * 12 && $roundTime < 60 * 24) {
                        //print 'Прошёл уже день, как я не делала уборку!';
                        Yii::$app->session->setFlash('successClean', "Прошёл уже день, как я не делала уборку!");
                    }
                    if ($roundTime >= 60 * 24 && $roundTime < 60 * 24 * 2) {
                       // print 'Прошло уже два дня, как я не делала уборку! Ну очень хочется сделать уборку!';
                        Yii::$app->session->setFlash('successClean', "Прошёл уже день, как я не делала уборку!");
                    }
                    if ($roundTime >= 60 * 24 * 2 && $roundTime < 60 * 24 * 3) {
                        //print 'Прошёл уже больше двух дней, как я не делала уборку!';
                        Yii::$app->session->setFlash('successClean', "Прошёл уже больше двух дней, как я не делала уборку!");
                    }
                    if ($roundTime >= 60 * 24 * 3 && $roundTime < 60 * 24 * 7) {
                        //print 'Прошло уже больше трёх дней, как я не делала уборку!';
                        Yii::$app->session->setFlash('successClean', "Прошло уже больше трёх дней, как я не делала уборку!");
                    }
                    if ($roundTime >= 60 * 24 * 7) {
                        //print 'Прошла уже неделя, как я не делала уборку! До свидания!';
                        Yii::$app->session->setFlash('successClean', "Прошла уже неделя, как я не делала уборку! До свидания!");
                    }
                } else {
                    $scale = new ScaleClean();
                    $scale->user_id = Yii::$app->user->id;
                    $scale->last_time = date("Y-m-d H:i:s");
                    $scale->points = 0;
                    $scale->step = 0;
                    $scale->save();
                }


                if (UserPoints::find()->where(['user_id' => Yii::$app->user->id])->andWhere(['course_id' => $course->id])->andWhere(['element_id' => 1])->one()){
                    $points = UserPoints::find()->where(['user_id' => Yii::$app->user->id])->andWhere(['course_id' => $course->id])->andWhere(['element_id' => 1])->one();
                    //\yii\helpers\VarDumper::dump($points->points, 10, true);
                    $feedPoints = $points->points;
                } else {
                    $points = new UserPoints();
                    $points->user_id = Yii::$app->user->id;
                    $points->course_id = $course->id;
                    $points->element_id = 1;
                    $points->points = 0;
                    $points->save();
                }
                if (UserPoints::find()->where(['user_id' => Yii::$app->user->id])->andWhere(['course_id' => $course->id])->andWhere(['element_id' => 2])->one()){
                    $points = UserPoints::find()->where(['user_id' => Yii::$app->user->id])->andWhere(['course_id' => $course->id])->andWhere(['element_id' => 2])->one();
                    $cleanPoints = $points->points;
                } else {
                    $points = new UserPoints();
                    $points->user_id = Yii::$app->user->id;
                    $points->course_id = $course->id;
                    $points->element_id = 2;
                    $points->points = 0;
                    $points->save();
                }
                if ($feedPoints + $cleanPoints >= 100 && $feedPoints + $cleanPoints < 300) {
                    $allPoints = $feedPoints + $cleanPoints;
                    Yii::$app->session->setFlash('successPoints', "Ого, как у тебя много очков! Уже целых " . $allPoints ."!");
                }
                if ($feedPoints + $cleanPoints >= 300 && $feedPoints + $cleanPoints < 100) {
                    $allPoints = $feedPoints + $cleanPoints;
                    Yii::$app->session->setFlash('successPoints', "Ничего себе! Вот это ты набираешь очки! Уже целых " . $allPoints ."!");
                }




                //$allAttempts = Attempt::find()->where(['user_id' => Yii::$app->user->id])->all();

                //print '<br><br><br><br><br><br><br><br><br>';
                $allQuestionCost = 0;
               // foreach ($allAttempts as $keyAttempt => $attempt){
                    //$challenge = ChallengeHasQuestion::find()->innerJoinWith('challenge')->where(['challenge.id' => $attempt->challenge_id])->all();
                // получить из challenge_has_question то задание, которое
                    // есть в тесте
                    // который есть в этой попытке
                    // достать тест
               //     $allQuestions[$keyAttempt] = ChallengeHasQuestion::find()->where(['challenge_id' => $attempt->challenge_id])->all();

                 //   for ($i = 0; $i < count($allQuestions); $i++){
                 //       for ($o = 0; $o < count($allQuestions[$i]); $o++){
                 //           $questionCost = Question::find()->where(['id' => $allQuestions[$i][$o]->question_id])->one();
                //            $allQuestionCost += $questionCost->cost;
                            //\yii\helpers\VarDumper::dump($allQuestionCost, 10, true);
                //        }

              //      }
            //    }
                //foreach ($challenge[0]->challenge as $one){
                //\yii\helpers\VarDumper::dump($allQuestions, 10, true);
               // }


            } else {
                print 'Нет подписки ни на один курс!';
            }



        echo '<ul class="nav navbar-nav pull-right">
					<li class="separator hide">
					</li>
					<li class="dropdown dropdown-extended dropdown-dark" id="header_inbox_bar">
						<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" aria-expanded="false">
						<i class="icon-fire"></i>
						<span class="badge badge-danger">4</span>
						</a>
						<ul class="dropdown-menu">
							<li class="external">
								<h3>У тебя <span class="bold">Количество новых</span> Сообщений</h3>
								<a href="#">посмотреть все</a>
							</li>
							<li>
								<div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 250px;"><ul class="dropdown-menu-list scroller" style="height: 250px; overflow: hidden; width: auto;" data-handle-color="#637283" data-initialized="1">';
        if( Yii::$app->session->hasFlash('successFeed') ){
            echo '<li>
			    <a href="#' . '">
				<span class="time">Курс '  . '</span>
				<span class="details">
				<span class="label label-sm label-icon"> ' . '
				<!--<i class="fa fa-plus"></i>-->
				</span>
				';
            echo Yii::$app->session->getFlash('successFeed');
            echo '</a>
                  </li>';

        } else {
            echo '<li>
			     <a href="#' . '">
				 <span class="time">Курс '  . '</span>
				 <span class="details">
				 <span class="label label-sm label-icon"> ' . '
				 <!--<i class="fa fa-plus"></i>-->
				 </span>';
            echo 'Ничего тут нет, кошка сыта!';
            echo '</a>
                  </li>';
        }
        if( Yii::$app->session->hasFlash('successClean') ){
            echo '<li>
			    <a href="#' . '">
				<span class="time">Курс '  . '</span>
				<span class="details">
				<span class="label label-sm label-icon"> ' . '
				<!--<i class="fa fa-plus"></i>-->
				</span>
				';
            echo Yii::$app->session->getFlash('successClean');
            echo '</a>
                  </li>';

        }        else {
            echo '<li>
			    <a href="#' . '">
				<span class="time">Курс '  . '</span>
				<span class="details">
				<span class="label label-sm label-icon"> ' . '
				<!--<i class="fa fa-plus"></i>-->
				</span>
				';
            echo 'Ничего тут нет, кошка делала уборку!';
            echo '</a>
                  </li>';

        }

        if( Yii::$app->session->hasFlash('successPoints') ){
            echo '<li>
			    <a href="#' . '">
				<span class="time">Курс ' . '</span>
				<span class="details">
				<span class="label label-sm label-icon"> ' . '
				<!--<i class="fa fa-plus"></i>-->
				</span>
				';
            echo Yii::$app->session->getFlash('successPoints');
            echo '</a>
                  </li>';

        }        else {
            echo '<li>
			    <a href="#' . '">
				<span class="time">Курс ' . '</span>
				<span class="details">
				<span class="label label-sm label-icon"> ' . '
				<!--<i class="fa fa-plus"></i>-->
				</span>
				';
            echo 'Маловато очков будет!';
            echo '</a>
                  </li>';

        }

		echo '</ul><div class="slimScrollBar" style="background: rgb(99, 114, 131); width: 7px; position: absolute; top: 114px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 160.904px;"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(234, 234, 234); opacity: 0.2; z-index: 90; right: 1px;"></div></div>
							</li>
						</ul>
						</li>
						</ul>';
						}


    public function run(){

        //return $this->food;
    }
}