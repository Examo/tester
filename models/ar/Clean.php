<?php

namespace app\models\ar;

use Yii;

/**
 * This is the model class for table "clean".
 *
 * @property integer $id
 * @property integer $course_subscription_id
 * @property integer $user_id
 * @property integer $week_id
 * @property string $challenges_done
 */
class Clean extends \yii\db\ActiveRecord
{
    const CLEANING_CONST = [
        'one', 'two', 'three', 'four', 'five',
        'six', 'seven', 'eight', 'nine', 'ten',
        'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen',
        'sixteen', 'seventeen', 'eighteen', 'nineteen', 'twenty',
        'twenty_one', 'twenty_two', 'twenty_three', 'twenty_four', 'twenty_five',
        'twenty_six', 'twenty_seven', 'twenty_eight', 'twenty_nine', 'thirty'];
    public $tests = 'TASTE SOME TEST';
    public $image = ['/i/orange.png',
        '/i/broom.png',
        '/i/brush.png',
        '/i/sponge.png',
        '/i/wisp.png',
        '/i/vacuum-cleaner.png',
        '/i/pet-shovel.png',
        '/i/carpet-beater.png',
        '/i/kitchen-rag.png',
        '/i/dust-brush.png',
        '/i/toilet-brush.png',
        '/i/bucket-mop.png',

    ];
    public $title = 'Первый тест по теме Приставки ПРЕ- и ПРИ-';
    public $time = 5;
    public $percent = 10;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'clean';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
       // return [
       //     [['course_subscription_id', 'user_id', 'week_id'], 'required'],
       //     [['course_subscription_id', 'user_id', 'week_id'], 'integer'],
       //     [['challenges_done'], 'string'],
       // ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
      // return [
      //     'id' => Yii::t('app', 'ID'),
      //     'course_subscription_id' => Yii::t('app', 'Course Subscription ID'),
      //     'user_id' => Yii::t('app', 'User ID'),
      //     'we//ek_id' => Yii::t('app', 'Week ID'),
      //     'challenges_done' => Yii::t('app', 'Challenges Done'),
      // ];
    }

    public function getTests()
    {
        return $this->tests;
    }

    public function getCleaningConst($number)
    {
        return self::CLEANING_CONST[$number];
    }


    public function getImageCleaning($class)
    {
        return '/i/' . $class . '.png';
    }

    /*public function getImageCleaning($i)
    {
        return $this->image[$i];
    }*/

    // Нужен метод для получения модели теста с привязанным продуктом - это имя класса
    // Получаем класс - проверяем перед выводом ссылки-картинки, нет ли у нас уже такого класса
    // Если вообще пустой массив с классами - просто применяем метод и записываем этот класс в массив (ключ - класс, а дальше цифра один, два и так далее - определить, как так сделать; можно цикл for и $i++)
    // Если массив не пустой, проверяем, есть ли наш класс в ключах массива: если существует $classes[$class], то пересчитываем количество элементов в нём и дописываем ему новый элемент
    // Далее эти классы подставляются в class="" - и появляется соответствующее
    public function getClass($classes, $class)
    {
        $result = [];
        if (!empty($classes[$class])) {
            for ($i = 1; $i <= count($classes[$class]); $i++){
            }
            $classes[$class][] = $i;
            $currentClass = $class . '_' . $i;
            $result['currentClass'] = $currentClass;
            $result['classes'] = $classes;
            return $result;
            //\yii\helpers\VarDumper::dump($classes, 10, true);
            //\yii\helpers\VarDumper::dump($class, 10, true);
            //\yii\helpers\VarDumper::dump($currentClass, 10, true);
        } else {
            //return 'Вложенного массива нет';
            $classes[$class][] = 1;
            $result['currentClass'] = $class;
            $result['classes'] = $classes;
            return $result;
            //\yii\helpers\VarDumper::dump($classes, 10, true);
        }
    }
    public function getChallengeClean($id)
    {
        //$food_id = ChallengeFood::find()->select('food_id')->where(['challenge_id' => $id])->one();
        //$challengeFood = Food::find()->select('food_name')->where(['id' => $food_id])->one();

        $challengeElementsItem = Challenge::find()->select('elements_item_id')->where(['id' => $id])->one();
        $challengeClean = ElementsItem::find()->select('name')->where(['id' => $challengeElementsItem])->one();
        return $challengeClean;
    }

    //   public function getChallengeFood($id)
    //   {
    //      $food_id = ChallengeFood::find()->select('food_id')->where(['challenge_id' => $id])->one();
    //       $challengeFood = Food::find()->select('food_name')->where(['id' => $food_id])->one();
    //      return $challengeFood;
    //  }

    public function getTopLeftStyleNumber($class)
    {
        $classes = [];
        switch ($class){
            case 'cat':
                $classes['top'] = 120;
                $classes['left'] = 330;
                break;
            case 'catontoilet':
                $classes['top'] = 120;
                $classes['left'] = 330;
                break;
            case 'bath':
                $classes['top'] = 290;
                $classes['left'] = 670;
                break;
            case 'bucket':
                $classes['top'] = 420;
                $classes['left'] = 550;
                break;
            case 'mop':
                $classes['top'] = 240;
                $classes['left'] = 520;
                break;
            case 'broom':
            $classes['top'] = 340;
            $classes['left'] = 530;
                break;
            case 'broom_2':
                $classes['top'] = 140;
                $classes['left'] = 330;
                break;
            case 'broom_3':
                $classes['top'] = 140;
                $classes['left'] = 330;
                break;
            case 'broom_4':
                $classes['top'] = 140;
                $classes['left'] = 330;
                break;
            case 'broom_5':
                $classes['top'] = 140;
                $classes['left'] = 330;
                break;
            case 'broom_6':
                $classes['top'] = 140;
                $classes['left'] = 330;
                break;
            case 'wisp':
                $classes['top'] = 340;
                $classes['left'] = 590;
                break;
            case 'wisp_2':
                $classes['top'] = 240;
                $classes['left'] = 410;
                break;
            case 'wisp_3':
                $classes['top'] = 240;
                $classes['left'] = 410;
                break;
            case 'wisp_4':
                $classes['top'] = 240;
                $classes['left'] = 410;
                break;
            case 'wisp_5':
                $classes['top'] = 240;
                $classes['left'] = 410;
                break;
            case 'wisp_6':
                $classes['top'] = 240;
                $classes['left'] = 410;
                break;
            case 'brush':
                $classes['top'] = 140;
                $classes['left'] = 390;
                break;
            case 'brush_2':
                $classes['top'] = 150;
                $classes['left'] = 420;
                break;
            case 'brush_3':
                $classes['top'] = 150;
                $classes['left'] = 420;
                break;
            case 'brush_4':
                $classes['top'] = 150;
                $classes['left'] = 420;
                break;
            case 'brush_5':
                $classes['top'] = 150;
                $classes['left'] = 420;
                break;
            case 'brush_6':
                $classes['top'] = 150;
                $classes['left'] = 420;
                break;
            case 'brush_7':
                $classes['top'] = 150;
                $classes['left'] = 420;
                break;












            case 'catstoilet':
                $classes['top'] = 150;
                $classes['left'] = 420;
                break;
            case 'toiletbrush':
                $classes['top'] = 150;
                $classes['left'] = 420;
                break;
            case 'petshovel':
                $classes['top'] = 150;
                $classes['left'] = 420;
                break;
            case 'urn':
                $classes['top'] = 150;
                $classes['left'] = 420;
                break;
            case 'toiletpaper':
                $classes['top'] = 150;
                $classes['left'] = 420;
                break;
            case 'scoop':
                $classes['top'] = 150;
                $classes['left'] = 420;
                break;
            case 'scoopbrush':
                $classes['top'] = 150;
                $classes['left'] = 420;
                break;
            case 'washer':
                $classes['top'] = 150;
                $classes['left'] = 420;
                break;
            case 'towel':
                $classes['top'] = 150;
                $classes['left'] = 420;
                break;
            case 'bowl':
                $classes['top'] = 150;
                $classes['left'] = 420;
                break;
            case 'bathsponge':
                $classes['top'] = 150;
                $classes['left'] = 420;
                break;
            case 'chlorinepurifier':
                $classes['top'] = 150;
                $classes['left'] = 420;
                break;
            case 'freshener':
                $classes['top'] = 150;
                $classes['left'] = 420;
                break;
            case 'washingpowder':
                $classes['top'] = 150;
                $classes['left'] = 420;
                break;
            case 'cleaningagentbottle':
                $classes['top'] = 150;
                $classes['left'] = 420;
                break;
            case 'cleaningagentbank':
                $classes['top'] = 150;
                $classes['left'] = 420;
                break;
            case 'cleaningagentbox':
                $classes['top'] = 150;
                $classes['left'] = 420;
                break;
            case 'bathspongeforbody':
                $classes['top'] = 150;
                $classes['left'] = 420;
                break;
            case 'bathbrush':
                $classes['top'] = 150;
                $classes['left'] = 420;
                break;
            case 'metalwoolcloth':
                $classes['top'] = 150;
                $classes['left'] = 420;
                break;
            case 'wetwipes':
                $classes['top'] = 150;
                $classes['left'] = 420;
                break;
            case 'latexgloves':
                $classes['top'] = 150;
                $classes['left'] = 420;
                break;
            case 'rubberbrush':
                $classes['top'] = 150;
                $classes['left'] = 420;
                break;
            case 'jug':
                $classes['top'] = 150;
                $classes['left'] = 420;
                break;
            case 'toothpaste':
                $classes['top'] = 150;
                $classes['left'] = 420;
                break;
            case 'toothbrush':
                $classes['top'] = 150;
                $classes['left'] = 420;
                break;
            case 'toothpicks':
                $classes['top'] = 150;
                $classes['left'] = 420;
                break;
            case 'dentalfloss':
                $classes['top'] = 150;
                $classes['left'] = 420;
                break;
            case 'soap':
                $classes['top'] = 150;
                $classes['left'] = 420;
                break;
            case 'massagecomb':
                $classes['top'] = 150;
                $classes['left'] = 420;
                break;
            case 'tubecream':
                $classes['top'] = 150;
                $classes['left'] = 420;
                break;
            case 'twotowels':
                $classes['top'] = 150;
                $classes['left'] = 420;
                break;
            case 'vacuumcleaner':
                $classes['top'] = 150;
                $classes['left'] = 420;
                break;
            case 'massager':
                $classes['top'] = 150;
                $classes['left'] = 420;
                break;
        }
        return $classes;
    }
}