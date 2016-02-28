<style>
    .row .form-group {
        margin-bottom: 0px;
    }

    #table_attendance_student tr {
        'cursor: pointer;
    }
</style>


<!-- Custom Page JScript -->
<script src="<?php echo base_url(); ?>assets/dist/js/custom_attendance.js" type="text/javascript"></script>

<!-- MODAL FOR CLASS REPLACEMENT -->
<form id="classReplaceFrm" name="classReplaceFrm">
    <input type="hidden" id="action" name="action" value=""/>
    <div class="modal fade" role="dialog" aria-labelledby="myModalLabel" id="classReplaceModal">
        <div class="modal-dialog" style="margin-top: 2%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        <i class="fa fa-exchange fa-lg"></i> <span
                            id="modal_title">Something is wrong if you see me</span>
                    </h4>
                </div>
                <div id="statusMsg" style="padding-bottom: 0px; margin-bottom: 0px"></div>
                <input type="hidden" id="id" name="id" value=""/>
                <input type="hidden" name="sessionDate" value="<?php echo $currDate; ?>"/>
                <input type="hidden" name="sessionTime" value="<?php echo $next_slot_time; ?>"/>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Search Name/ID</label>
                        <input class="form-control"
                               placeholder="Student Name/ID / Passport" id="stdSearch"
                               name="stdSearch" autocomplete="off">
                        <div id="stdSearchResult"></div>
                    </div>
                    <div class="col-md-7 col-sm-7 col-xs-7" style="padding-left: 0px; padding-right: 2px">
                        <div class="form-group">
                            <label>Student Name</label>
                            <input class="form-control" placeholder="Student Name"
                                   id="stdName" name="stdName"
                                   readonly="readonly" required="required"
                                   autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-5 col-sm-5 col-xs-5" style="padding-left: 0px; padding-right: 2px">
                        <div class="form-group">
                            <label>Student ID</label>
                            <input class="form-control" placeholder="Student ID" id="stdID"
                                   name="stdID"
                                   readonly="readonly" required="required" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-5 col-sm-5 col-xs-5" style="padding-left: 0px; padding-right: 2px">
                        <div class="form-group">
                            <label>Class Left</label>
                            <input class="form-control" placeholder="Class Left."
                                   id="stdClassleft"
                                   name="stdClassleft" readonly="readonly" required="required"
                                   autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-7 col-sm-7 col-xs-7" style="padding-left: 0px; padding-right: 2px">
                        <div class="form-group">
                            <label>Expiry Date</label>
                            <input class="form-control" placeholder="Expiry Date."
                                   id="stdExpiry" name="stdExpiry"
                                   readonly="readonly" required="required"
                                   autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-5 col-sm-5 col-xs-5" style="padding-left: 0px; padding-right: 2px">
                        <div class="form-group">
                            <label>Level</label>
                            <input class="form-control" placeholder="Level." id="stdLevel"
                                   name="stdLevel"
                                   readonly="readonly" required="required" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-7 col-sm-7 col-xs-7" style="padding-left: 0px; padding-right: 2px">
                        <div class="form-group">
                            <label>Instructor</label>
                            <input class="form-control" placeholder="Instructor"
                                   id="stdInstructor"
                                   name="stdInstructor" readonly="readonly" required
                                   autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12" style="padding-left: 0px; padding-right: 2px">
                        <div class="form-group">
                            <label>Last log</label>
                            <textarea class="form-control" placeholder="No log" id="stdLog"
                                      readonly="readonly" autocomplete="off"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" disabled="disabled" id="btnSubmitReplace">Submit
                        Replacement
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
<!--form-replacement -->

<!-- MODAL FOR CLASS CANCELLATION -->
<form id="form_cancellation" name="form_cancellation">
    <input type="hidden" id="action" name="action" value=""/>
    <div class="modal fade" role="dialog" aria-labelledby="myModalLabel" id="modal-class_cancel">
        <div class="modal-dialog" style="margin-top: 2%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        Class Cancellation
                    </h4>
                </div>
                <input type="hidden" id="cancellation_date" name="cancellation_date" value="<?php echo $currDate; ?>"/>
                <input type="hidden" id="cancellation_time" name="cancellation_time" value="<?php echo $next_slot_time; ?>"/>
                <input type="hidden" id="extend_minute" name="extend_minute" value="0"/>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="radio">
                            <label>
                                <input type="radio" name="cancellation_type" value="0" checked>
                                NO ACTION
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="cancellation_type" value="1">
                                EXTEND on NEXT CLASS
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="cancellation_type" value="2">
                                CANCEL current class
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-group">
                            <label>Additional Log</label>
                            <textarea name="cancellation_log" maxlength="100" class="form-control" rows="3" placeholder="Max 100 Characters"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger" disabled="disabled" id="btn-submit_cancellation">
                        Submit
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
<!--form-replacement -->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <h1>Student Attendance
            - <?php echo date('Y-m-d l, ', strtotime($currDate)) . date('g:i:s A', strtotime($next_slot_time)) ?></h1>
    </section>

    <section class="content">
        <form id="slotFrm" name="slotFrm" method="post">
            <div class="row" style="text-align: center;">
                <div class="col-xs-12">
                    <div class="box box-success">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-1">
                                    <label>Date: </label>
                                </div>
                                <div class="col-xs-2" style="padding-left: 2px; padding-right: 2px">
                                    <div class="form-group">
                                        <input type="text" id="slot_date" name="slot_date" class="form-control"
                                               value="<?php echo $currDate; ?>">
                                    </div>
                                </div>
                                <div class="col-xs-1">
                                    <label>Time: </label>
                                </div>
                                <div class="col-xs-3" style="padding-left: 2px; padding-right: 2px">
                                    <div class="form-group">
                                        <select id="schedule_id" name="schedule_id" class="form-control">
                                            <option selected disabled></option>
                                            <?php foreach ($slot_time_list as $row) { ?>
                                                <option value="<?php echo $row['schedule_id'] ?>" <?php
                                                if ($row['schedule_id'] == $schedule_id) {
                                                    echo "selected='selected'";
                                                }
                                                ?>><?php echo $row['slot_time_12']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <?php if (count($slot_time_list) > 0) { ?>
                                    <?php if($check_cancellation==false){?>
                                        <div class="col-xs-5 col-md-5" style="padding-left: 2px; padding-right: 2px; text-align: left;">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-success btnClassReplace"
                                                        id="btnClassReplace"> Replacement
                                                </button>

                                                <button type="button" class="btn btn-danger btn-class_cancel"
                                                        id="btn-class_cancel">
                                                    Cancel Class
                                                </button>
                                            </div>
                                        </div>
                                    <?php } else{ ?>
                                        <div class="col-xs-5 col-md-5" style="padding-left: 2px; padding-right: 2px; text-align: left;">
                                            <div class="btn-group">
                                                <button type="button" disabled="disabled" class="btn btn-danger btn-class_cancel"
                                                        id="btn-class_cancel">
                                                    Class Cancelled
                                                </button>
                                            </div>
                                        </div>
                                    <?php }?>
                                <?php } ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <form id="form_attendance" name="form_attendance">
            <input type="hidden" name="session_scheddule_date" value="<?php echo $currDate; ?>"/>
            <input type="hidden" name="session_schedule_id" value="<?php echo $schedule_id; ?>"/>
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="box box-success">
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table class="table table-bordered table-hover" id="table_attendance_student">
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
                                <?php foreach ($students_attendance as $row) { ?>
                                    <tr>
                                        <td id="student_id"><?php echo $row['student_id'] ?></td>
                                        <td id="student_name"><?php echo $row['student_name'] ?></td>
                                        <td><?php echo date('D', strtotime("Sunday +" . $row['slot_day'] . " Days")) . " " . date('g:i A', strtotime($row['slot_time'])); ?></td>
                                        <td>
                                            <?php
                                            $lesson_left = 0;
                                            if (!is_null(['lesson_overdue'])) {
                                                $lesson_left = $lesson_left + ($row['lesson_overdue'] * -1);
                                            }
                                            if (!is_null(['lesson_left'])) {
                                                $lesson_left = $lesson_left + $row['lesson_left'];
                                            }
                                            ?>
                                            <span <?php if ($lesson_left <= 0) { ?> style="color: red" <?php } ?> ><?php echo $lesson_left ?></span>
                                        </td>
                                        <td><?php echo $row['issue_date'] ?></td>
                                        <td><?php echo $row['level_name'] ?></td>
                                        <td><?php echo $row['instructor_name'] ?></td>
                                        <td><?php echo $row['log'] ?></td>
                                        <td>
                                            <a class="clear_extension" href="#<?php echo $row['ce_id'] ?>" class=""><?php echo $row['extend_minute'] ?></a>
                                        </td>
                                        <td>

                                            <?php if (is_null($row['attendance_status'])) { ?>
                                                <input type="checkbox" name="std_attend[]"
                                                       value="<?php echo 'ovr_' . $row['student_id'] . '_' . $next_slot_time . '_' . $currDate ?>">
                                            <?php } else if ($row['attendance_status'] == 'N') { ?>
                                                <input type="checkbox" name="std_attend[]"
                                                       value="<?php echo 'att_' . $row['attendance_id'] ?>">
                                            <?php } else if ($row['attendance_status'] == 'Y') { ?>
                                                <?php if ($row['replacement'] == 'N') { ?>
                                                    Taken
                                                <?php } else if ($row['replacement'] == 'Y') { ?>
                                                    <span style="color: blue">Replacement</span>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <?php echo $row['attendance_status'] ?>
                                                Something Is Wrong.
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>


                                <tbody>

                            </table>
                        </div>
                        <div class="box-footer">
                            <div class="row">
                                <div class="col-md-4"></div>
                                <div class="col-md-4 col-xs-12">
                                    <?php if (!empty($students_attendance) && ($check_cancellation==false)) { ?>
                                        <button type="submit" class="btn btn-block btn-primary"
                                                id="btnUpdateAttendance">Update Attendance
                                        </button>
                                    <?php } else { ?>
                                        <button type="button" class="btn btn-block" disabled="disabled"> DISABLED
                                        </button>
                                    <?php } ?>
                                </div>
                                <div class="col-md-4"></div>
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
