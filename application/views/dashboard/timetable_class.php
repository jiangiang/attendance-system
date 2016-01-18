<!-- dataTables JS -->
<link href='<?php echo base_url(); ?>assets/plugins/fullcalendar/fullcalendar.css' rel='stylesheet'/>
<link href='<?php echo base_url(); ?>assets/plugins/fullcalendar/fullcalendar.print.css' rel='stylesheet'
      media='print'/>
<script src='<?php echo base_url(); ?>assets/plugins/fullcalendar/lib/moment.min.js'></script>
<script src='<?php echo base_url(); ?>assets/plugins/fullcalendar/fullcalendar.min.js'></script>

<script>
    $(document).ready(function () {
        $('#venue_id').on('change', function () {
            $('#timetable_venue').submit();
        });

        $('#calendar').fullCalendar({
            header: {
                //left : '',
                left: 'prev,next today',
                center: 'title',
                right: 'agendaWeek,agendaDay'
                //right: 'month,agendaWeek,agendaDay'
            },
            slotDuration: '00:15:00',
            allDaySlot: false,
            firstDay: 1,
            defaultView: 'agendaWeek',
            editable: false,
            eventLimit: false, // allow "more" link when too many events
            slotEventOverlap: false,
            minTime: "07:00:00",
            maxTime: "22:00:00",
            contentHeight: "auto",
            slotLabelFormat: 'h:mm a',
            events: [
                <?php
                $i = 1;$data = array();
                $tempStr = "";
                foreach ($timetable as $row) {
                    $tempStr .= "{title:'" . $row['level_short_name'] . "(" . $row['instructors_name'] . ") - " . $row['headcount'] . "', start:'" . date('Y-m-d', strtotime(date('Y-m-d',strtotime("Last Sunday"))." + " . $row['slot_day'] . " Days")) . "T" . $row['slot_time'] . "',";
                    $tempStr .= "end:'" . date('Y-m-d', strtotime(date('Y-m-d',strtotime("Last Sunday"))." + " . $row['slot_day'] . " Days")) . "T" . $row['end_slot_time'] . "'}";
                    if ($i < count($timetable)) {
                        $tempStr .= ",";
                    }
                }
                echo $tempStr;
                ?>]
        });

    });


</script>

<style>
    .fc-title {
        word-wrap: break-word;
        display: inline;
        overflow: visible;
    }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content">
        <form id="timetable_venue" name="timetable_venue" method="post">
            <div class="row" style="text-align: center;">
                <div class="col-xs-12">
                    <div class="box box-success">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-2">
                                    <div class="form-group">
                                        <select id="venue_id" name="venue_id" class="form-control">
                                            <option selected disabled></option>
                                            <?php foreach ($venues as $venue) { ?>
                                                <option value="<?php echo $venue['venue_id'] ?>" <?php
                                                if ($venue['venue_id'] == $venue_id) {
                                                    echo "selected='selected'";
                                                } ?>><?php echo $venue['venue_name']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-success">
                    <div class="box-body">
                        <div id='calendar'></div>
                    </div>
                    <!-- /.box-body -->

                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
</div>

