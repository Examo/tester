<?php

use yii\helpers\Html;

$this->title = Yii::t('challenge', 'Weeks');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Admin'), 'url' => ['admin/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('challenge', 'Challenges'), 'url' => ['admin/challenge/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="challenge-index">

    <h1><?= Html::encode($this->title) ?> по курсу "<?= $course->name?>"</h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create') . ' новый ' . Yii::t('challenge', 'Challenge'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

<table class="table table-striped table-bordered">
    <tbody>
    <th class="col-md-1">ID недели</th>
    <th class="col-md-10">Тесты</th>
        <tr>
            <td>
            </td>
            <td>
  <?php  foreach ($course->challenges as $num => $challenge): ?>
      <?php echo '<span class="badge badge-success">ID ' . $challenge->id . '</span>' ?>
  <?php endforeach;?>
            </td>
        </tr>
    </tbody>
</table>
</div>

<div>


</div>
