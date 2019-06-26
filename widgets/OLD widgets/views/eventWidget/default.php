<?php

use yii\helpers\Json;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/** @var \app\models\Event $model */
/** @var \app\models\Event $events */
/** @var array $colorSetting */
?>

<div class="row">
    <div class="col-lg-12 text-center">
        <div id="calendar" class="col-centered" style="max-width: 800px; margin: auto">
        </div>
    </div>
</div>

<!-- Modal Create -->
<?php Modal::begin([
        'header' => 'Добавить событие',
        'id' => 'ModalAdd',
        'size' => 'modal-md',
        'closeButton' => false,
    ]);
$form = ActiveForm::begin(['action' => '/admin/event/create', 'id' => 'add-event-form']);
echo $form->field($model, 'title')->textInput(['id' => 'title']);
echo $form->field($model, 'color')->dropDownList($colorSetting[0], $colorSetting[1]);
echo $form->field($model, 'start')->textInput(['id' => 'start']);
echo $form->field($model, 'end')->textInput(['id' => 'end']);
echo $form->field($model, 'course_id')->hiddenInput(['id' => 'course_id'])->label(false);
?>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
    <button type="submit" class="btn btn-primary">Сохранить</button>
</div>
<?php
ActiveForm::end();
Modal::end();
?>

<!-- Modal Update -->
<?php Modal::begin([
    'header' => 'Редактировать событие',
    'id' => 'ModalEdit',
    'size' => 'modal-md',
    'closeButton' => false,
]);
$form = ActiveForm::begin(['action' => '/admin/event/update?id=', 'id'=>'edit-event-form']);
echo $form->field($model, 'title')->textInput(['id' => 'title']);
echo $form->field($model, 'color')->dropDownList($colorSetting[0], $colorSetting[1]);
?>

<div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
        <div class="checkbox">
            <label class="text-danger"><input type="checkbox" id="CheckEventDel" name="delete">Удалить событие</label>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
    <button type="submit" class="btn btn-primary">Сохранить</button>
</div>

<?php ActiveForm::end(); ?>
<?php Modal::end(); ?>

<script>
    $(document).ready(function() {

        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            defaultDate: '<?= Yii::$app->formatter->asDate('now', 'yyyy-MM-dd') ?>',
            editable: true,
            eventLimit: true, // allow "more" link when too many events
            selectable: true,
            selectHelper: true,
            select: function(start, end, course_id) {
                $('#ModalAdd #start').val(moment(start).format('YYYY-MM-DD HH:mm:ss'));
                $('#ModalAdd #end').val(moment(end).format('YYYY-MM-DD HH:mm:ss'));
                $('#ModalAdd').modal('show');
            },
            eventRender: function(event, element) {
                element.bind('dblclick', function() {
                    $('#ModalEdit #edit-event-form').attr('action', '/admin/event/update?id='+event.id);
                    $('#ModalEdit #CheckEventDel').attr('checked', false);
                    $('#ModalEdit #title').val(event.title);
                    $('#ModalEdit #event-color option[value='+event.color+']').attr('selected', true);
                    $('#ModalEdit').modal('show');
                });
            },
            eventDrop: function(event, delta, revertFunc) {

                edit(event);

            },
            eventResize: function(event,dayDelta,minuteDelta,revertFunc) {

                edit(event);

            },

            events: [
                <?php foreach($events as $event):

                $start = explode(" ", $event->start);
                $end = explode(" ", $event->end);
                if($start[1] == '00:00:00'){
                    $start = $start[0];
                }else{
                    $start = $event->start;
                }
                if($end[1] == '00:00:00'){
                    $end = $end[0];
                }else{
                    $end = $event->end;
                }
                ?>
                {
                    id: '<?php echo $event->id; ?>',
                    title: '<?php echo $event->title; ?>',
                    start: '<?php echo $start; ?>',
                    end: '<?php echo $end; ?>',
                    color: '<?php echo $event->color ? $event->color : '#40E0D0'; ?>',
                    course_id: '<?php echo $event->course_id; ?>'
                },
                <?php endforeach; ?>
            ]
        });

        function edit(event){

            start = event.start.format('YYYY-MM-DD HH:mm:ss');
            if(event.end){
                end = event.end.format('YYYY-MM-DD HH:mm:ss');
            }else{
                end = start;
            }

            id = event.id;
            course_id = event.course_id;

            data = [];
            data['id'] = id;
            data['start'] = start;
            data['end'] = end;
            data['course_id'] = course_id;
            data['color'] = event.color;
            $.ajax({
                url: '/admin/event/update?id='+data['id'],
                type: "POST",
                data: {
                    '<?= Yii::$app->request->csrfParam ?>':'<?= Yii::$app->request->getCsrfToken()?>',
                    'Event[start]':data['start'],
                    'Event[end]':data['end'],
                    'Event[color]':data['color'],
                },
                success: function(res) {
                    if(res === 'OK'){
                        alert('Сохранено');
                    }else{
                        alert('Не сохранено, попробуйте еще раз');
                    }
                }
            });
        }

        $('#add-event-form').on('beforeSubmit', function () {
            var f = $(this);
            var form = $(this).serialize();
            var data;
            data = new FormData($(this)[0]);
            $.ajax({
                url: '/admin/event/create',
                type: 'POST',
                data: data,
                async: false,
                success: function (res) {
                    if (!res) alert('Ошибка!');
                    var source = { events: [
                        {
                            id: res.id,
                            title: res.title,
                            start: res.start,
                            end: res.end,
                            color: res.color,
                            course_id: res.course_id
                        }
                    ]};
                    $('#calendar').fullCalendar( 'addEventSource', source );
                    $('#ModalAdd').modal('toggle');
                    return false;
                },
                error: function () {
                    alert('Ошибка!');
                },
                cache: false,
                contentType: false,
                processData: false
            });
            return false;
        });

        Array.prototype.filterObjects = function(key, value) {
            return this.filter(function(x) { return x[key] === value; })
        }

        $('#edit-event-form').on('beforeSubmit', function () {
            var f = $(this);
            var form = $(this).serialize();
            var data;
            var url = $(this).attr('action');
            data = new FormData($(this)[0]);
            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                async: false,
                success: function (res) {
                    if (!res) alert('Ошибка!');

                    var es = $('#calendar').fullCalendar('clientEvents');
                    var ev = es.filterObjects("id",  res.id.toString() );

                    if (url.indexOf("update") != (-1)) {
                        //update
                        ev[0].title = res.title;
                        ev[0].color = res.color;
                        $('#calendar').fullCalendar('updateEvent', ev[0]);
                    } else {
                        //delete
                        if (ev[0]._id) {
                            $('#calendar').fullCalendar('removeEvents', ev[0]._id);
                        }
                    }

                    $('#ModalEdit').modal('toggle');
                    return false;
                },
                error: function () {
                    alert('Ошибка!');
                },
                cache: false,
                contentType: false,
                processData: false
            });
            return false;
        });


        $('#ModalEdit #CheckEventDel').change(function () {
            var checked = $(this).is(':checked');
            if (checked) {
                $('#ModalEdit #edit-event-form').attr('action', $('#ModalEdit #edit-event-form').attr('action').replace(/update/g, 'delete'));
            } else {
                $('#ModalEdit #edit-event-form').attr('action', $('#ModalEdit #edit-event-form').attr('action').replace(/delete/g, 'update'));
            }
        });

    });
</script>
