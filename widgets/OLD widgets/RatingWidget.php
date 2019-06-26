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

class RatingWidget extends Widget
{
    public function init()
    {
        parent::init();



        echo '<div class="portlet light ">
						<div class="portlet-title">
							<center><div class="caption caption-md">
								<i class="icon-bar-chart theme-font-color hide"></i>
								<span class="caption-subject theme-font-color bold uppercase">Рейтинг учащихся курса<br>Подготовка к ЕГЭ по русскому языку<br> </span>
								
							</div></center>
							
						</div>
						<div class="portlet-body">
							<div class="row number-stats margin-bottom-30">
								<div class="col-md-6 col-sm-6 col-xs-6">
									<div class="stat-left">
										<!-- <div class="stat-chart">
											do not line break "sparkline_bar" div. sparkline chart has an issue when the container div has line break 
											<div id="sparkline_bar"><canvas width="90" height="45" style="display: inline-block; width: 90px; height: 45px; vertical-align: top;"></canvas></div>
										</div>-->
										<div class="stat-number">
											<div class="title">
    Всего учащихся:
											</div>
											<div class="number">
    4
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6 col-sm-6 col-xs-6">
									<div class="stat-right">
										<div class="stat-chart">
											<!-- do not line break "sparkline_bar" div. sparkline chart has an issue when the container div has line break
											<div id="sparkline_bar2"><canvas width="90" height="45" style="display: inline-block; width: 90px; height: 45px; vertical-align: top;"></canvas></div> -->
										</div>
										<div class="stat-number">
											<div class="title">
    Новых за сегодня:
											</div>
											<div class="number">
    2
											</div>
										</div>
									</div>
								</div>
							</div>
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
    Место
									</th>
								</tr>
								</thead>
								<tbody><tr>
									<td class="fit">
										<img class="user-pic" src="/i/hintemoticon.jpg">
									</td>
									<td>
										<a href="javascript:;" class="primary-link">Brain</a>
									</td>
									<td>
    345
    </td>
									<td>
    45
									</td>
									<td>
    124
									</td>
													<td>
    124
									</td>
									<td>
										<span class="bold theme-font-color">1</span>
									</td>
								</tr>
								<tr>
									<td class="fit">
										<img class="user-pic" src="/i/hintemoticon.jpg">
									</td>
									<td>
										<a href="javascript:;" class="primary-link">Nick</a>
									</td>
									<td>
    560
    </td>
									<td>
    12
									</td>
									<td>
    24
									</td>
									<td>
    24
									</td>
									<td>
										<span class="bold theme-font-color">2</span>
									</td>
								</tr>
								<tr>
									<td class="fit">
										<img class="user-pic" src="/i/hintemoticon.jpg">
									</td>
									<td>
										<a href="javascript:;" class="primary-link">Tim</a>
									</td>
									<td>
    1,345
    </td>
									<td>
    450
									</td>
									<td>
    46
									</td>
									<td>
    46
									</td>
									<td>
										<span class="bold theme-font-color">3</span>
									</td>
								</tr>
								<tr>
									<td class="fit">
										<img class="user-pic" src="/i/hintemoticon.jpg">
									</td>
									<td>
										<a href="javascript:;" class="primary-link">Tom</a>
									</td>
									<td>
    645
    </td>
									<td>
    50
									</td>
									<td>
    89
									</td>
									<td>
    89
									</td>
									<td>
										<span class="bold theme-font-color">4</span>
									</td>
								</tr>
								</tbody></table>
							</div>
						</div>
					</div>';

    }


    public function run(){

    }
}