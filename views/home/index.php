<?php
/**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \yii\web\View $this
 *
 */
$this->title = Yii::t('home', 'Home');

$data = [];
$badgeBackgroundColor = 'white';
$badgeColor = 'grey';
$all = [];

$day = date("d");
$year = date("Y");
$month = date("n");
setlocale(LC_ALL, 'ru_RU');
$today = strftime("%A, %e %b.", mktime(0, 0, 0, $month, $day, $year));


?>
<div class="panel panel-default">
    <div class="panel-heading">
        Мой дом
    </div>
    <div class="panel-body">
        <p>Здесь будут новости, шутки и ежедневные задания! Наш общий дом!</p>
    </div>
</div>

<?php echo '<ul class="nav navbar-nav pull-right">
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
                <a href="#">*</a>
            </li>
            <li>
                <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 250px;"><ul class="dropdown-menu-list scroller" style="height: 250px; overflow: hidden; width: auto;" data-handle-color="#637283" data-initialized="1">';


                        if (isset($all)){
                        foreach ($all as $course => $webinar) {
                        echo '<li>
                            <a href="/' . $webinar['webinar_link'] . '">
				
				<span class="details">
				<span class="label label-sm label-icon">Вебинар №' . $webinar['webinar_id'] . '
                    <!--<i class="fa fa-plus"></i>-->
				</span>
				';
            echo 'По курсу ' . $webinar['course_name'];
            echo '<br>Осталось <span class="bold">' . $webinar['daysToWait'] . ' д., ' . $webinar['lastHours'] . ' ч., ' . $webinar['lastMinutes'] . ' мин.</span>';
            echo '</a></li>';
                        }



                        }
                        echo '</ul><div class="slimScrollBar" style="background: rgb(99, 114, 131); width: 7px; position: absolute; top: 114px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 160.904px;"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(234, 234, 234); opacity: 0.2; z-index: 90; right: 1px;"></div></div>
            </li>
        </ul>
    </li>
</ul>';?>