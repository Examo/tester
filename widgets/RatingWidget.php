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
use app\models\CourseSubscription;
use app\models\Event;
use app\models\Question;
use Yii;
use yii\base\Widget;

class RatingWidget extends Widget
{
    public $courseId;

    public function init()
    {
        parent::init();

        $course = Course::find()->where(['id' => $this->courseId])->one();
        $subscriptionStart = CourseSubscription::find()->one();
        $courseRating = $subscriptionStart->getCourseRating($this->courseId);
        $courseTime = $subscriptionStart->getCourseStart($this->courseId);
        $challengesCount = $subscriptionStart->getChallenges($this->courseId);
        $webinarsCount = $subscriptionStart->getWebinarsCount($this->courseId);
        $webinarsDone = $subscriptionStart->getWebinarChallengesCheck($this->courseId);
        $homeworksCount = $subscriptionStart->getHomeworksCount($this->courseId);
        $examsCount = $subscriptionStart->getExamsCount($this->courseId);

  echo '
     
        <div class="portlet-title">
            <center><div class="caption caption-md">
                    <i class="icon-bar-chart theme-font-color hide"></i>
                    <span  style="font-size: large" class="caption-subject theme-font-color bold uppercase"><br>Рейтинг учащихся</span>
                    </div>
            </center>
        </div>';
      if ($courseRating['rating']) {
      echo   '
     <div class="portlet-body">
        <div class="table-scrollable table-scrollable-borderless">
            <table class="table table-hover table-light">
                <thead>
                <tr class="uppercase">
                    <th colspan="2">
                    Учащийся
                    </th>

                    <th>
                    "Еда"
                    </th>

                    <th>
                    "Уборка"
                    </th>

                    <th>
                    "Игра"
                    </th>

                    <th>
                    "Учёба"
                    </th>

                    <th>
                    Всего
                    </th>

                    <th>
                    Место
                    </th>
                </tr>
                </thead>
                <tbody>';
        foreach ($courseRating['rating'] as $userId => $userPoints) {
            foreach ($courseRating['data'] as $userData) {
                if ($userData['isSelf'] == true && $userData['user_id'] == $userId) {
                    echo '<tr style="border-width: thin; border-bottom: dashed; border-top: dashed; border-left: groove; border-color: #26A69A; overflow-x: hidden;">';
                    break;
                }
            }
      echo '<td class="fit">
            <img class="user-pic" src="/i/hintemoticon.jpg">
            </td>

            <td>';
            foreach ($courseRating['data'] as $userData) {
                if ($userData['user_id'] == $userId && $userData['element_id'] == 1) {
                    echo $userData['username'];
                }
            }
      echo '</td>

            <td>';
            foreach ($courseRating['data'] as $userData) {
                if ($userData['user_id'] == $userId && $userData['element_id'] == 1) {
                    echo $userData['points'];
                }
            }
      echo '</td>

            <td>';
            foreach ($courseRating['data'] as $userData) {
                if ($userData['user_id'] == $userId && $userData['element_id'] == 2) {
                    echo $userData['points'];
                }
            }
      echo '</td>

            <td>';
            foreach ($courseRating['data'] as $userData) {
                if ($userData['user_id'] == $userId && $userData['element_id'] == 3) {
                    echo $userData['points'];
                }
            }
            echo '- 
            </td>

            <td>
            -
            </td>

            <td>';
            echo $userPoints;
      echo '</td>

            <td>
            <span class="bold theme-font-color">';
            foreach ($courseRating['data'] as $userData) {
                if ($userData['user_id'] == $userId && $userData['element_id'] == 1) {
                    echo $userData['position'];
                }
            }echo '</span></td></tr>';
        }

     echo    '
         
      </tbody>
      </table>
</div>
</div>
';
} else {
echo '<div class="portlet-body">
      <center><strong>Никто не выполнял тесты по курсу, поэтому нет и рейтинга!</strong></center>
      </div>';
}

echo '<div class="portlet-body">';
    $attemptNumber = 0;
    $feedNumber = 0;
    $cleanNumber = 0;
    foreach( $course->getChallenges()->all() as $challenge ) {
        $attemptNumber += $challenge->getAttemptsCount(Yii::$app->user->id);
        $feedNumber += $challenge->getAttemptsElementsCount(1, $challenge->id, $challenge->element_id);
        $cleanNumber += $challenge->getAttemptsElementsCount(2, $challenge->id, $challenge->element_id);
    }

    echo '<div class="portlet-title text-center"><strong style="font-size: large" class="caption-subject theme-font-color bold uppercase">Сделано / Обязательных:</strong><br><br></div>
    <table class="table table-striped table-hover">

        <tr>
            <th class="col-md-2 text-center">Тестов для "Еды"</th>
            <th class="col-md-2 text-center">Тестов для "Уборки"</th>
            <th class="col-md-2 text-center">Всего "Игр"</th>
            <th class="col-md-2 text-center">Домашних заданий</th>
            <th class="col-md-2 text-center">Экзаменов</th>
            <th class="col-md-2 text-center">Вебинаров</th>
        </tr>
        <tr>
            <td class="text-center"><strong style="font-size: large">'; echo $feedNumber . '/'; if (isset($challenge)){echo $challenge->getElementChallengesCount($course->id, 1); } else{echo '0';} echo '</strong></td>
            <td class="text-center"><strong style="font-size: large">';echo $cleanNumber. '/'; if (isset($challenge)){ echo $challenge->getElementChallengesCount($course->id, 2); } else{echo '0';} echo '</strong></td>
            <td class="text-center"><strong style="font-size: large">-</td>
            <td class="text-center"><strong style="font-size: large">_ / '; echo $homeworksCount; echo '</strong></td>
            <td class="text-center"><strong style="font-size: large">_ / '; echo $examsCount; echo '</strong></td>
            <td class="text-center"><strong style="font-size: large">'; echo $webinarsDone['counted'] . '/'; echo $webinarsCount; echo '</strong></td>
        </tr>
    </table>
</div>';



    }


    public function run(){

    }
}

