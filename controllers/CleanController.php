<?php
namespace app\controllers;
use app\models\ar\Clean;
use app\models\ar\DifficultSubjects;
use app\models\Attempt;
use app\models\Challenge;
use app\models\Course;
use app\models\ElementsItem;
use app\models\Subject;
use Yii;
use yii\web\Controller;

class CleanController extends Controller
{
    public $layout = 'metronic_sidebar';

    public function actionIndex() // основной экшн
    {
        $cleaningTests = new Clean();
        $challenges = [];
        $cleanChallenges = [];
        $number = 1;
        $newCleanChallenges = [];
        $difficultSubjects = DifficultSubjects::find()->where(['user_id' => Yii::$app->user->id])->all();

            foreach (Course::findSubscribed(Yii::$app->user->id)->all() as $course) {
                $challenges = array_merge($challenges, $course->getNewCleanChallenges(Yii::$app->user->id)->all());
            }

            $subjectsChecked = [];
            foreach ($challenges as $challenge) {
                if ($challenge->element_id == 2) {
                    $subject = Challenge::find()->innerJoinWith('subject')->where(['challenge.subject_id' => $challenge->subject_id])->andWhere(['challenge.id' => $challenge->id])->one();
                    if (!isset($subjectsChecked[$subject->subject->id])) {
                        $cleanChallenges[$number]['subject_id'] = $subject->subject->id;
                        $cleanChallenges[$number]['subject_name'] = $subject->subject->name;
                        $cleanChallenges[$number]['challenge_id'] = $subject->id;
                        $cleanChallenges[$number]['challenge_name'] = $subject->name;
                        $elements_item = Challenge::find()->innerJoinWith('elements_item')->where(['challenge.elements_item_id' => $subject->elements_item_id])->one();
                        $cleanChallenges[$number]['challenge_clean_item'] = $elements_item->elements_item->name;
                        $number++;
                        $subjectsChecked[$subject->subject->id] = $subject->subject->id;
                    }
                }
            }

        $difficultSubjects = DifficultSubjects::find()->where(['user_id' => Yii::$app->user->id])->all();

        $mainChallenges = $cleanChallenges;

        if ($mainChallenges) {
            if ($difficultSubjects) {
                foreach ($difficultSubjects as $difficultSubject) {
                    foreach ($cleanChallenges as $cleanChallengeNumber => $cleanChallenge) {
                        if ($difficultSubject->subject_id == $cleanChallenge['subject_id']) {
                            $newCleanChallenges[$difficultSubject->subject_id]['subject_points'] = $difficultSubject->points;
                            $newCleanChallenges[$difficultSubject->subject_id]['subject_id'] = $cleanChallenge['subject_id'];
                            $newCleanChallenges[$difficultSubject->subject_id]['subject_name'] = $cleanChallenge['subject_name'];
                            $newCleanChallenges[$difficultSubject->subject_id]['challenge_id'] = $cleanChallenge['challenge_id'];
                            $newCleanChallenges[$difficultSubject->subject_id]['challenge_name'] = $cleanChallenge['challenge_name'];
                            $newCleanChallenges[$difficultSubject->subject_id]['challenge_clean_item'] = $cleanChallenge['challenge_clean_item'];
                            break;
                        } else {
                            $subject = Subject::find()->select(['name'])->where(['id' => $difficultSubject->subject_id])->one();
                            $newCleanChallenges[$difficultSubject->subject_id]['subject_points'] = $difficultSubject->points;
                            $newCleanChallenges[$difficultSubject->subject_id]['subject_id'] = $difficultSubject->subject_id;
                            $newCleanChallenges[$difficultSubject->subject_id]['subject_name'] = $subject->name;
                            $newCleanChallenges[$difficultSubject->subject_id]['challenge_id'] = null;
                            $newCleanChallenges[$difficultSubject->subject_id]['challenge_name'] = null;
                            $newCleanChallenges[$difficultSubject->subject_id]['challenge_clean_item'] = null;
                        }
                    }
                }
                foreach ($newCleanChallenges as $key => $row) {
                    $subjectPoints[$key] = $row['subject_points'];
                }
                array_multisort($subjectPoints, SORT_ASC, $newCleanChallenges);
            } else {
                 $newCleanChallenges = null;
            }
        } else {
             $newCleanChallenges = null;
        }

        return $this->render('index', [
            'cleaningTests' => $cleaningTests,
            'challenges' => $challenges,
            'difficultSubjects' => $difficultSubjects,
           'newCleanChallenges' => $newCleanChallenges
        ]);
    }
}