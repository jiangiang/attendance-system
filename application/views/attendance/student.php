<style>
    .row .form-group {
        margin-bottom: 0px;
    }

    #table_attendance_student tr {
    'cursor: pointer;
    }
</style>


<!-- Custom Page JScript -->
<script src = "<?php echo base_url(); ?>assets/dist/js/custom_attendance.js" type = "text/javascript"></script>

<?php $this->view( 'Modals/ClassReplacementForm' ); ?>

<?php $this->view( 'Modals/ClassCancellationForm' ); ?>


<!-- Content Wrapper. Contains page content -->
<div class = "content-wrapper">
    <section class = "content-header">
        <h1><?php echo $title; ?>
            - <?php echo date( 'Y-m-d l, ', strtotime( $currDate ) ) . date( 'g:i:s A', strtotime( $next_slot_time ) ) ?></h1>
    </section>

    <section class = "content">
        <form id = "slotFrm" name = "slotFrm" method = "post" class = "" role = "form">
            <div class = "row">
                <div class = "col-xs-12">
                    <div class = "box">
                        <div class = "box-body">
                            <div class = "row">
                                <div class = "col-md-3 col-sm-3">
                                    <div class = "input-group">
                                        <span class="input-group-addon">
                                            <label>Date</label>
                                        </span>
                                        <input type = "text" id = "slot_date" name = "slot_date" class = "form-control"
                                               value = "<?php echo $currDate; ?>">
                                    </div>
                                </div>
                                <div class = "col-md-3 col-sm-3">
                                    <div class = "input-group">
                                        <span class="input-group-addon">
                                            <label>Time</label>
                                        </span>
                                        <select id = "schedule_id" name = "schedule_id" class = "form-control">
                                            <option selected disabled>Select a time</option>
                                            <?php foreach ( $slot_time_list as $row ) { ?>
                                                <option value = "<?php echo $row[ 'schedule_id' ] ?>" <?php
                                                if ( $row[ 'schedule_id' ] == $schedule_id ) {
                                                    echo "selected='selected'";
                                                }
                                                ?>><?php echo $row[ 'slot_time_12' ]; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class = "col-md-3 col-sm-3">
                                    <?php if ( count( $slot_time_list ) > 0 ) { ?>
                                        <?php if ( $check_cancellation == false ) { ?>
                                            <div class = "form-group">
                                                <div class = "btn-group">
                                                    <button type = "button" class = "btn btn-success btnClassReplace"
                                                            id = "btnClassReplace"> IsReplacement
                                                    </button>

                                                    <button type = "button" class = "btn btn-danger btn-class_cancel"
                                                            id = "btn-class_cancel">
                                                        Cancel Class
                                                    </button>
                                                </div>
                                            </div>
                                        <?php } else { ?>
                                            <div class = "form-group">
                                                <div class = "btn-group">
                                                    <button type = "button" disabled = "disabled" class = "btn btn-danger btn-class_cancel"
                                                            id = "btn-class_cancel">
                                                        Class Cancelled
                                                    </button>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <form id = "form_attendance" name = "form_attendance">
            <input type = "hidden" name = "session_schedule_date" value = "<?php echo $currDate; ?>"/>
            <input type = "hidden" name = "session_schedule_id" value = "<?php echo $schedule_id; ?>"/>
            <div class = "row">
                <div class = "col-md-12 col-xs-12">
                    <div class = "box">
                        <!-- /.box-header -->
                        <div class = "box-body">
                            <table class = "table table-bordered table-hover" id = "table_attendance_student">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Slot</th>
                                        <th>Remain</th>
                                        <th>Last Payment</th>
                                        <th>Level</th>
                                        <th>Instr.</th>
                                        <th>Last Log</th>
                                        <th>Ext.</th>
                                        <th>Attend</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ( $students_attendance as $row ) { ?>
                                        <tr>
                                            <td id = "student_id"><?php echo $row[ 'student_id' ] ?></td>
                                            <td id = "student_name"><?php echo $row[ 'student_name' ] ?></td>
                                            <td><?php echo date( 'D', strtotime( "Sunday +" . $row[ 'slot_day' ] . " Days" ) ) . " " . date( 'g:i A', strtotime( $row[ 'slot_time' ] ) ); ?></td>
                                            <td>
                                                <?php
                                                $lesson_left = 0;
                                                if ( !is_null( [ 'lesson_overdue' ] ) ) {
                                                    $lesson_left = $lesson_left + ( $row[ 'lesson_overdue' ] * -1 );
                                                }
                                                if ( !is_null( [ 'lesson_left' ] ) ) {
                                                    $lesson_left = $lesson_left + $row[ 'lesson_left' ];
                                                }
                                                ?>
                                                <span <?php if ( $lesson_left <= 0 ) { ?> style = "color: red" <?php } ?> ><?php echo $lesson_left ?></span>
                                            </td>
                                            <td><?php echo $row[ 'issue_date' ] ?></td>
                                            <td><?php echo $row[ 'level_name' ] ?></td>
                                            <td><?php echo $row[ 'instructor_name' ] ?></td>
                                            <td><?php echo $row[ 'log' ] ?></td>
                                            <td>
                                                <a class = "clear_extension" href = "#<?php echo $row[ 'ce_id' ] ?>"
                                                   class = ""><?php echo $row[ 'extend_minute' ] ?></a>
                                            </td>
                                            <td>

                                                <?php if ( is_null( $row[ 'IsTaken' ] ) ) { ?>
                                                    <input type = "checkbox" name = "std_attend[]"
                                                           value = "<?php echo 'ovr_' . $row[ 'student_id' ] . '_' . $next_slot_time . '_' . $currDate ?>">
                                                <?php } else if ( $row[ 'IsTaken' ] == 4 ) { ?>
                                                    <input type = "checkbox" name = "std_attend[]"
                                                           value = "<?php echo 'att_' . $row[ 'attendance_id' ] ?>">
                                                <?php } else if ( $row[ 'IsTaken' ] == 3 ) { ?>
                                                    <?php if ( $row[ 'IsReplacement' ] == 4 ) { ?>
                                                        Taken
                                                    <?php } else if ( $row[ 'IsReplacement' ] == 3 ) { ?>
                                                        <span style = "color: blue">IsReplacement</span>
                                                    <?php } ?>
                                                <?php } else { ?>
                                                    <?php echo $row[ 'IsTaken' ] ?>
                                                    Something Is Wrong.
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php } ?>


                                <tbody>

                            </table>
                        </div>
                        <div class = "box-footer">
                            <div class = "row">
                                <div class = "col-md-4"></div>
                                <div class = "col-md-4 col-xs-12">
                                    <?php if ( !empty( $students_attendance ) && ( $check_cancellation == false ) ) { ?>
                                        <button type = "submit" class = "btn btn-block btn-primary"
                                                id = "btnUpdateAttendance">Update Attendance
                                        </button>
                                    <?php } else { ?>
                                        <button type = "button" class = "btn btn-block" disabled = "disabled"> DISABLED
                                        </button>
                                    <?php } ?>
                                </div>
                                <div class = "col-md-4"></div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
            </div>
        </form>
    </section>
</div>
