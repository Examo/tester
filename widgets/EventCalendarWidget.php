<?php
namespace app\widgets;
use app\helpers\EventChecker;
use app\models\ar\ScaleClean;
use app\models\ar\ScaleFeed;
use app\models\ar\UserPoints;
use app\models\Attempt;
use app\models\Challenge;
use app\models\Course;
use app\models\Event;
use app\models\Question;
use Yii;
use yii\base\Widget;

class EventCalendarWidget extends Widget
{
    public function init()
    {
        parent::init();

        $data = EventChecker::getEventsData();
        //echo '<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
       // echo time();
        //\yii\helpers\VarDumper::dump($data, 10, true);
        $badgeColor = $data['badgeColor'];
        $badgeBackgroundColor = $data['badgeBackgroundColor'];
        $countEvent = $data['countEvent'];
        $today = $data['today'];
        $all = $data['all'];

        if (isset($all) && !Yii::$app->user->isGuest){
            echo '<ul class="nav navbar-nav pull-right">
					<li class="separator hide">
					</li>
					<li class="dropdown dropdown-extended dropdown-dark" id="header_inbox_bar">
						<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" aria-expanded="false">
						<i class="icon-calendar"></i>
						<span class="badge badge-danger" style="background-color: ' . $badgeBackgroundColor . '; color: ' . $badgeColor . '">' . $countEvent .  '</span>
						</a>';

            echo '<ul class="dropdown-menu">
							<li class="external">
							    <h3>Сегодня <span class="bold">' . $today . '</span></h3><br>
								<h3>Событий на неделе: <span class="bold">' . $countEvent .  '</span></h3>
								<a href="#"></a>
							</li>
							<li>
								<div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 250px;"><ul class="dropdown-menu-list scroller" style="height: 250px; overflow: hidden; width: auto;" data-handle-color="#637283" data-initialized="1">';

            foreach ($all as $course => $webinar) {
            echo '<li>';
            if ($webinar['webinar_begining'] == 0) {

                echo '<center>
				<span class="label label-sm label-icon" style="color: white"><br>Вебинар №' . $webinar['webinar_id'] . '
				<!--<i class="fa fa-plus"></i>-->
				</span>
				';
                echo '<p  style="color: white">по курсу<br>' . $webinar['course_name'] . '</p>';
                echo '<p  style="color: white">Осталось <span class="bold" style="color: white">' . $webinar['daysToWait'] . ' д., ' . $webinar['lastHours'] . ' ч., ' . $webinar['lastMinutes'] . ' мин.</span></p>';
                if ($webinar['daysToWait'] == 0 && $webinar['lastHours'] == 0 && $webinar['lastMinutes'] <= 15) {
                    echo '<a href="' . \yii\helpers\Url::to(['webinar/webinar', 'id' => $webinar['webinar_id']]) . '" class="btn btn-success" style="font-size: large">Присоединиться</a></center>';
                }
            }
            if ($webinar['webinar_begining'] == 1) {
                if ($webinar['webinar_begining'] == 1) {
                    echo '<center>
				<span class="label label-sm label-icon" style="color: white"><br>Вебинар №' . $webinar['webinar_id'];
                    echo ' по курсу <br>"' . $webinar['course_name'] . '"';
                    echo '<br>уже начался!<br>До конца остаётся <span class="bold">' . $webinar['webinar_hours_before_end']. ' ч. ' . $webinar['webinar_minutes_before_end']. ' мин.</span>';
                    echo '<br><br><center><a href="' . \yii\helpers\Url::to(['webinar/webinar', 'id' => $webinar['webinar_id']]) . '" class="btn btn-success" style="font-size: large">Присоединиться</a></center>';
                }
            }

        }

        } else {

            echo '<ul class="nav navbar-nav pull-right">
					<li class="separator hide">
					</li>
					<li class="dropdown dropdown-extended dropdown-dark" id="header_inbox_bar">
						<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" aria-expanded="false">
						<i class="icon-calendar"></i>
						<span class="badge badge-danger" style="background-color: ' . $badgeBackgroundColor . '; color: ' . $badgeColor . '">' . $countEvent .  '</span>
						</a>';

            echo '<ul class="dropdown-menu">
							<li class="external">
							    <h3>Сегодня <span class="bold">' . $today . '</span></h3><br>
								<h3>События на неделе: <span class="bold"><br><br>Ожидаются!<br>Заходи, авторизуйся!<br>Или зарегистрируйся!</span></h3>
							</li>
							<li>
								<div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 250px;"><ul class="dropdown-menu-list scroller" style="height: 250px; overflow: hidden; width: auto;" data-handle-color="#637283" data-initialized="1">';


            echo '<li>';

                echo '<center>
                <p style="color: white"><br>У нас есть
				<br>курс</p>';
                echo '<p  style="color: white"><br>Подготовка к ЕГЭ по русскому языку</p>';
                echo '</center>';
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