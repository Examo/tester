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
        //$data2 = EventChecker::getWeekSubject(1);
        $badgeColor = $data['badgeColor'];
        $badgeBackgroundColor = $data['badgeBackgroundColor'];
        $countEvent = $data['countEvent'];
        $today = $data['today'];
        $all = $data['all'];

if (isset($all) && !Yii::$app->user->isGuest){
   echo '<li class="dropdown dropdown-extended dropdown-notification dropdown-dark" id="calendar">
						<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
						<i class="icon-calendar"></i>
						<span class="badge badge-danger" style="background-color: ' . $badgeBackgroundColor . '; color: ' . $badgeColor . '">' . $countEvent .  '</span>
						</a>
						<ul class="dropdown-menu">
							<li class="external">
								<h3>Сегодня <span class="bold">' . $today . '</span></h3><br>
								<h3>Событий на неделе: <span class="bold">' . $countEvent .  '</span></h3>
								<!--<a href="extra_profile.html">view all</a>-->
							</li>';
						if ($countEvent == 0) {
                 print '</ul>';
                        } else {
							print '
                            <li>
								<ul class="dropdown-menu-list scroller" style="height: 250px;" data-handle-color="white">';
								foreach ($all as $course => $webinar) {
                                    if ($webinar['webinar_begining'] == 0) {
                                        print '
                                        <li>
										<!--<a href="javascript:;">-->
										<!--<span class="time">just now</span>-->
										<!--<span class="details">-->
										<center>
										<span class="label label-sm label-icon" style="color: white"><br>Вебинар №' . $webinar['webinar_id'] . '
                                        <!--<i class="fa fa-plus"></i>-->
										</span>
										<p style="color: white">по курсу<br>' . $webinar['course_name'] . '</p>
                                        <p style="color: white">Осталось <span class="bold" style="color: white">' . $webinar['daysToWait'] . ' д., ' . $webinar['lastHours'] . ' ч., ' . $webinar['lastMinutes'] . ' мин.</span></p>';
                                        if ($webinar['daysToWait'] == 0 && $webinar['lastHours'] == 0 && $webinar['lastMinutes'] <= 15) {
                                            print '<a href="' . \yii\helpers\Url::to(['webinar/webinar', 'id' => $webinar['webinar_id']]) . '" class="btn btn-success" style="font-size: large">Присоединиться</a></center>';
                                        }
                                        print '
                                        </center>
                                        <!--</a>-->
                                        </li>';
                                    }
                                    if ($webinar['webinar_begining'] == 1) {
                                        print '
                                        <li>
										<!--<a href="javascript:;">-->
										<!--<span class="time">just now</span>-->
										<!--<span class="details">-->
                                        <center>
	     			                    <span class="label label-sm label-icon" style="color: white"><br>Вебинар №' . $webinar['webinar_id'];
                                        print ' по курсу <br>"' . $webinar['course_name'] . '"';
                                        print '<br>уже начался!<br>До конца остаётся <span class="bold">' . $webinar['webinar_hours_before_end']. ' ч. ' . $webinar['webinar_minutes_before_end']. ' мин.</span>';
                                        print '<br><br><center><a href="' . \yii\helpers\Url::to(['webinar/webinar', 'id' => $webinar['webinar_id']]) . '" class="btn btn-success" style="font-size: large">Присоединиться</a></center>';
                                        print '
                                        </center>
                                        <!--</a>-->
                                        </li>';
                                    }
                                }
								print '
								</ul>
							</li>
						</ul>';
						}
   print ' </li>';
} else {

print '<li class="dropdown dropdown-extended dropdown-dark" id="calendar">
      <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" aria-expanded="false">
          <i class="icon-calendar"></i>
          <span class="badge badge-danger" style="background-color: ' . $badgeBackgroundColor . '; color: ' . $badgeColor . '">' . $countEvent . '</span>
      </a>
          <ul class="dropdown-menu">
              <li class="external">
                  <h3>Сегодня <span class="bold">' . $today . '</span></h3><br>
                  <h3>События на неделе: <span class="bold"><br><br>Ожидаются!<br>Заходи, авторизуйся!<br>Или зарегистрируйся!</span></h3>
              </li>
              <li>
              <center>
                  <p style="color: white"><br>У нас есть
                      <br>курс</p>
                  <p  style="color: white"><br>Подготовка к ЕГЭ по русскому языку</p>
              </center>
              </li>
          </ul>
      </li>';
}
        //\yii\helpers\VarDumper::dump($data2, 10, true);

    }

    public function run(){

        //return $this->food;
    }
}