<?php
/**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \yii\web\View $this
 *
 */
$this->title = Yii::t('home', 'Home');
$day = date("d");
$year = date("Y");
$month = date("n");
setlocale(LC_ALL, 'ru_RU.UTF8');
$today = strftime("%A, %e %b.", mktime(0, 0, 0, $month, $day, $year));
?>

<div class="panel panel-default">
    <div class="panel-heading">
        Мой дом
    </div>
    <div class="panel-body">


<?php if ($all){
    foreach ($all as $course => $webinar) {
    echo '<center>';
    echo '<div class="tiles">
                <div class="tile double-down double bg-red-sunglo">
                    <div class="tile-body">
                        <i class="fa fa-bell-o"></i>
                        <i class="fa fa-calendar"></i>
                    </div>
                    <div class="tile-object">
                        <div class="name">';

                                            echo 'Сегодня <span class="bold">' . $today . '</span>';
                                            echo '<h4 class="">';
                                            if ($webinar['webinar_begining'] == 0) {
                                                echo 'До вебинара №' . $webinar['webinar_id'];
                                                echo ' по курсу <br>"' . $webinar['course_name'] . '"';
                                                echo '<br>осталось ждать <span class="bold">' . $webinar['daysToWait'] . ' д., ' . $webinar['lastHours'] . ' ч., ' . $webinar['lastMinutes'] . ' мин.</span>';
                                                if ($webinar['daysToWait'] == 0 && $webinar['lastHours'] == 0 && $webinar['lastMinutes'] <= 15) {
                                                    echo '<br><br><center><a href="' . \yii\helpers\Url::to(['webinar/webinar', 'id' => $webinar['webinar_id']]) . '" class="btn btn-success" style="font-size: large">Присоединиться</a></center></h4>';
                                                } else {
                                                    echo '<br><br><br></h4>';
                                                }
                                            }
                                            if ($webinar['webinar_begining'] == 1) {
                                                echo 'Вебинар №' . $webinar['webinar_id'];
                                                echo ' по курсу <br>"' . $webinar['course_name'] . '"';
                                                echo '<br>уже начался!<br>До конца остаётся <span class="bold">' . $webinar['webinar_hours_before_end']. ' ч. ' . $webinar['webinar_minutes_before_end']. ' мин.</span>';
                                                echo '<br><br><center><a href="' . \yii\helpers\Url::to(['webinar/webinar', 'id' => $webinar['webinar_id']]) . '" class="btn btn-success" style="font-size: large">Скорее!</a></center></h4>';
                                            }
                   echo '</div>
                    </div>
                </div>
            </div>
            </center>';
    }
    }
?>
    </div>
<?php //\yii\helpers\VarDumper::dump($all, 10, true); ?>