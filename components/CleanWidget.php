<?php
// подключаем пространство имен
namespace app\components;
// импортируем класс Windget и Html хелпер
use app\models\ar\FeedSearch;
use app\models\ar\Food;
use app\models\ar\ScaleClean;
use app\models\ar\ScaleFeed;
use app\models\Attempt;
use app\models\Challenge;
use app\models\ChallengeHasQuestion;
use app\models\Course;
use app\models\Feed;
use app\models\Question;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;
// расширяем класс Widget
class CleanWidget extends Widget
{
    //public $food;

    public function init(){
        parent::init();
        //$searchModel = new CleanSearch();
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        //$feedingTests = new Feed();

        //получить все попытки
        //найти последнюю попытку с тестом для Уборки и от неё дальше идти
        //то же самое сделать с Едой
        $attempts = Attempt::find()->where(['user_id' => Yii::$app->user->id])->orderBy('id DESC')->all();
        $allChallenges = Challenge::find()->all();

//        $test = Attempt::find()->select(['a' => Attempt::tableName()])->leftJoin(['c'=>Challenge::tableName()], 'c.id = a.challenge_id')->where(['c.element_id' => 2])->all();

       //
        // $test = Attempt::find()->leftJoin('challenge', 'challenge.id = attempt.challenge_id')->where(['challenge.element_id' => 2])->orderBy(['attempt.id'=> SORT_DESC])->limit(1);

        // рабочий вариант $test = Attempt::find()->innerJoin('challenge', 'challenge.id = attempt.challenge_id')->where(['challenge.element_id' => 2])->orderBy(['attempt.id' => SORT_DESC])->limit(1)->one();

        //$test = Attempt::find()->innerJoinWith('challenge')->where(['challenge.element_id' => 1])->orderBy(['attempt.id' => SORT_DESC])->limit(1)->one();

        //\yii\helpers\VarDumper::dump($test->challenge->id, 10, true);
        $lastChallenge = Attempt::find()->innerJoinWith('challenge')->where(['challenge.element_id' => 2])->andWhere(['attempt.user_id' => Yii::$app->user->id])->orderBy(['attempt.id' => SORT_DESC])->limit(1)->one(); //Attempt::find()->where(['user_id' => Yii::$app->user->id])->orderBy('id DESC')->one();

       $lastChallengeId = $lastChallenge->challenge_id;// Attempt::find()->select(['challenge_id'])->where(['user_id' => Yii::$app->user->id])->orderBy('id DESC')->one();

        //надо сделать JOIN таблица теста с таблицай результатов
        $lastChallengeQuestions = ChallengeHasQuestion::find()->where(['challenge_id' => $lastChallengeId])->all();


        $allLastChallengeQuestionsCost = 0;
       foreach ($lastChallengeQuestions as $lastChallengeQuestion){
            $lastChallengeQuestionCost = Question::find()->select('cost')->where(['id' => $lastChallengeQuestion->question_id])->one();
            $allLastChallengeQuestionsCost += $lastChallengeQuestionCost->cost;
        }

        $allLastChallengeQuestionsCost = ceil($allLastChallengeQuestionsCost / 5 * intval($lastChallenge->mark));

       $finishTime = Yii::$app->getFormatter()->asTimestamp($lastChallenge->finish_time);

       $time = Yii::$app->getFormatter()->asTimestamp(time());

       $scaleTwist = $time - $finishTime;

       $scaleValue = 0;

        if (ScaleClean::find()->where(['user_id' => Yii::$app->user->id])->one()) {
            $scale = ScaleFeed::find()->where(['user_id' => Yii::$app->user->id])->one();
        } else {
            $scale = new ScaleClean();
            $scale->user_id = Yii::$app->user->id;
            $scale->last_time = Yii::$app->formatter->asDatetime('now', 'php:Y-m-d H:i:s');
            $scale->points = 0;
            $scale->step = 0;
            $scale->save();
        }

       // \yii\helpers\VarDumper::dump($scale, 10, true);

       // $allDoneChallenges = Attempt::find()->where(['user_id' => Yii::$app->user->id])->all();

       // $allDoneChallengesCosts = [];
       // $allLastCosts = [];
       // $costAmount = 0;
       // $nearlyFinishCostAmount = 0;
       // $finishCostAmount = 0;
       // for ($i =0; $i < count($allDoneChallenges); $i++){
       //     $cost = ChallengeHasQuestion::find()->select('question_id')->where(['challenge_id' => $allDoneChallenges[$i]->challenge_id])->all();
       //     $allDoneChallengesMarks = Attempt::find()->select('mark')->where(['user_id' => Yii::$app->user->id])->all();
       //     for ($o = 0; $o < count($cost); $o++){
       //         $lastCost = Question::find()->select('cost')->where(['id' => $cost[$o]->question_id])->all();
       //         foreach ($lastCost as $item) {
       //             $allLastCosts[] = $item->cost;
       //             $costAmount += $item->cost;
       //             $nearlyFinishCostAmount += $item->cost;
       //         }
       //     }
       //     $finishCostAmount += ceil($nearlyFinishCostAmount / 5 * intval($allDoneChallengesMarks[$i]->mark));
       //     $nearlyFinishCostAmount = 0;
       // }

        // $scale = ScaleFeed::find()->where(['user_id' => Yii::$app->user->id])->one();

       for ($i = 0; $i < 10000; $i = $i + 100) {

           if ($i >= 9900) {
               // print 'Шкала на нуле!.. ';
               $scaleValue = 0;
               break;
           }
           if ($scaleTwist > $i && $scaleTwist < ($i + 100)) {
               // print 'Значение ScaleTwist чуть больше $i, но меньше $i + 100 и равно ' . $i . '<br>';
               $scaleValue = $i;
               $scale = ScaleClean::find()->where(['user_id' => Yii::$app->user->id])->one();

               if ($i == 0) {
                   //   print 'ScaleValue равно Нулю';
                   $scaleValue = $scale->points;
                   $scale->points = $scaleValue;
                   $scale->step = $i;
                   $scale->save();
               } elseif ($i == $scale->step) {
                   //   print 'ScaleValue равно Step';
                   $scaleValue = $scale->points;
                   $scale->points = $scaleValue;
                   $scale->step = $i;
                   $scale->save();}
               else {
                   //print 'ScaleValue НЕ равно Step';
                   $scaleValue = $scale->points - (($i - $scale->step) / 100);
                   $scaleValue = $allLastChallengeQuestionsCost - ($i / 100);
                   //print $scaleValue;
                   $scale->points = $scaleValue;
                   $scale->step = $i;
                   $scale->save();
                   //\yii\helpers\VarDumper::dump($scale, 10, true);
               }
               break;
           }
       }
        if ($scaleValue <= 0) {$scaleValue = 0;$scale->points = 0;$scale->save();}

        // $foods = Food::find()->all();

        //$challenges = [];
        // foreach (Course::findSubscribed(Yii::$app->user->id)->all() as $course) {
        //     $challenges = array_merge($challenges, $course->getNewChallenges(Yii::$app->user->id)->all());
        // }

        //  $attempt = Attempt::find()->select(['finish_time'])->where(['user_id' => Yii::$app->user->id])->orderBy('id DESC')->one();
        // $this->food;
        //\yii\helpers\VarDumper::dump($scaleTwist, 10, true);
        //\yii\helpers\VarDumper::dump($scale->points, 10, true);
        //\yii\helpers\VarDumper::dump($allLastChallengeQuestionsCost, 10, true);
        //\yii\helpers\VarDumper::dump($finishCostAmount, 10, true);
        //\yii\helpers\VarDumper::dump($scaleValue, 10, true);

       if ($scaleValue <= 10) {
           $backgroundColor = 'red';
       } else {
           $backgroundColor = 'green';
       }
       $heightScaleValue = 100 - $scaleValue;

       //echo '<br><br><br>';
       echo '<a href="/clean">' .
           '<div class="bar-wrapper"><p>Уборка</p>' .
           '<div class="feeding-progress-bar-block" style=" background-color:' . $backgroundColor .'">' .
           '<div class="feeding-progress-bar-fill" style="height:' . $heightScaleValue . '%; width:100%;"><center><p><b>' . $scaleValue . '%</b></p></center></div>' .
           '</div>' .
           '</div></a>';

    }
    // возвращаем результат


    public function run(){
        //return $this->food;
    }

}
