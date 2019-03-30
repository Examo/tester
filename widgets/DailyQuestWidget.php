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

class DailyQuestWidget extends Widget
{
    public function init()
    {
        parent::init();

        $dailyChallenges = [];
        $dailyQuestStartTime = [];
        if (Course::findSubscribed(Yii::$app->user->id)->one() && ScaleLearn::find()->where(['course_id' => Course::findSubscribed(Yii::$app->user->id)->one()->id])->andWhere(['user_id' => Yii::$app->user->id])->one()) {
    foreach (Course::findSubscribed(Yii::$app->user->id)->all() as $keyEvent => $course) {

        $events = Event::find()->where(['course_id' => $course->id])->all();

        $regexp = "/(ежедневное задание)([0-9]*)/ui";
        $match = [];
        foreach ($events as $key => $event) {
            if (preg_match($regexp, $event->title, $match[$course->id][$key])) {
                $time = Yii::$app->getFormatter()->asTimestamp(time());
                $dailyQuestRealStartTime = Yii::$app->getFormatter()->asTimestamp($event->start);
                $dailyQuestRealEndTime = Yii::$app->getFormatter()->asTimestamp($event->end);
                if ($time > $dailyQuestRealStartTime && $time < $dailyQuestRealEndTime) {
                    if (Attempt::find()->where(['challenge_id' => $match[$course->id][$key][2]])->andWhere(['points' => 1])->andWhere(['user_id' => Yii::$app->user->id])->one()) {
                        //$dailyChallenges[$course->id] = null;
                    } else {
                        $dailyChallenges[$course->id] = $match[$course->id][$key][2];
                        //print 'Найдено!';
                        $dailyQuestStartTime[$course->id] = Yii::$app->getFormatter()->asTimestamp($event->start);
                    }
                }
            }
        }


        //
        //$time = Yii::$app->getFormatter()->asTimestamp(time());
        //\yii\helpers\VarDumper::dump($dailyChallenges, 10, true);
    }


    $numberOfQuests = count($dailyChallenges);
    if ($numberOfQuests == 0) {
        $badgeBackgroundColor = 'white';
        $badgeColor = 'grey';
    } else {
        $badgeBackgroundColor = '#337ab7';
        $badgeColor = 'white';
    }

    print '<ul class="nav navbar-nav pull-right">
					<li class="separator hide">
					</li>
					<!-- BEGIN NOTIFICATION DROPDOWN -->
					<!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
					<li class="dropdown dropdown-extended dropdown-notification dropdown-dark" id="header_notification_bar">
						<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" aria-expanded="false">
						<i class="icon-pin"></i>
						<span class="badge badge-success" style="background-color: ' . $badgeBackgroundColor . '; color: ' . $badgeColor . '">' . $numberOfQuests . '</span>
						</a>';

    if ($numberOfQuests != 0) {
        print                '<ul class="dropdown-menu">
							<li class="external">
								<h3>Пропущено: <span class="bold">' . $numberOfQuests . '</span></h3>
								<!--<a href="extra_profile.html">view all</a>-->
							</li>
							<li>
								<div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 250px;"><ul class="dropdown-menu-list scroller" style="height: 250px; overflow: hidden; width: auto;" data-handle-color="#637283" data-initialized="1">';

        foreach ($dailyChallenges as $courseId => $challengeId) {
            foreach ($dailyQuestStartTime as $dailyCourseId => $startTime) {

                if ($courseId == $dailyCourseId && $challengeId != null) {
                    //print 'Ежедневное задание по курсу ' . $dailyCourseId . ' - тест номер ' . $challengeId;
                    print '<li>
	     	<a href="/challenge/start?id=' . $challengeId . '">
		     	<span>Курс ' . $dailyCourseId . '</span>
		     	<span class="details">Ежедневное задание: тест номер ' . $challengeId . ' для Еды/Уборки по курсу ' . $dailyCourseId . '
		     	<span class="label label-sm label-icon">Жми! ' . '
		     	<!--<i class="fa fa-plus"></i>-->
		     	</span>
		     	</span>
		     	</a>
		     </li>';
                }
            }
        }
        print '</ul>';

    }
}
            echo '<div class="slimScrollBar" style="background: rgb(99, 114, 131); width: 7px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 121.359px;"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(234, 234, 234); opacity: 0.2; z-index: 90; right: 1px;"></div></div>
							</li>
						</ul>
					</li>
					<!-- END NOTIFICATION DROPDOWN -->
					<li class="separator hide">
					</li>
					
					<li class="separator hide">
					</li>

				</ul>';


    }


    public function run(){

    }
}