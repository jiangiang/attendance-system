<!-- InputMask -->
<script src="<?php echo base_url(); ?>assets/plugins/input-mask/inputmask.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/plugins/input-mask/inputmask.extensions.js"
        type="text/javascript"></script>

<!-- Custom Page JScript -->
<script src="<?php echo base_url(); ?>assets/dist/js/custom_course_schedule.js" type="text/javascript"></script>

<!-- course activate/deactivation -->
<form id="form_activation" name="form_activation">
    <input type="hidden" id="activation_id" name="activation_id">
</form>

<form id="form_schedule" name="form_schedule" autocomplete="off">
    <input type="hidden" id="schedule_id" name="schedule_id" value=""/>
    <input type="hidden" id="class_duration" name="class_duration" value=""/>
    <div class="modal fade" role="dialog" aria-labelledby="myModalLabel" id="modal_schedule">
        <div class="modal-dialog" style="margin-top: 2%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        <span id="modalTitle">If you see me smth is wrong yo</span>
                    </h4>
                </div>
                <div id="statusMsg" style="padding-bottom: 0px; margin-bottom: 0px"></div>

                <div class="col-lg-6 col-xs-6" style="padding-right: 0px">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="venue">Venue</label>
                            <select class="form-control" id="venue_id" name="venue_id">
                                <?php foreach ($list_venue as $row) { ?>
                                    <option
                                        value="<?php echo $row['venue_id']; ?>" <?php if ($row['default_place'] == 'Y') { ?> selected="selected"<?php } ?>>
                                        <?php echo $row['venue_name']; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Day</label>
                            <select class="form-control" id="schedule_day" name="schedule_day" required="required">
                                <option value="" disabled="" selected="selected">Please select an option</option>
                                <?php for ($i = 1; $i <= 7; $i++) { ?>
                                    <option value="<?php echo $i ?>" <?php if ($day_selected == $i) {
                                        echo "SELECTED";
                                    } ?>><?php echo date('l', strtotime("Sunday + $i Days")); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Insturctor</label>
                            <select class="form-control" id="instructor_id" name="instructor_id">
                                <option value="" selected disabled>Please select an option...</option>
                                <?php foreach ($list_instructor as $row) { ?>
                                    <option value="<?php echo $row['id']; ?>"><?php echo $row['name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xs-6" style="padding-left: 2px">
                    <div class="modal-body">

                        <div class="form-group">
                            <label>Type</label>
                            <select class="form-control" id="course_id" name="course_id" required="required">
                                <option value="" disabled="disabled" selected="selected">Select a Course</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Time</label>
                            <div class="form-group">
                                <div class="col-lg-6 col-sm-6 col-xs-6"
                                     style="padding-left: 0px; padding-right: 0px">
                                    <select class="form-control" id="schedule_hour" name="schedule_hour"
                                            required="required">
                                        <option value="" disabled="disabled" selected="selected">HOUR</option>
                                        <?php for ($i = 7; $i <= 22; $i++) { ?>
                                            <option value="<?php echo $i ?>"><?php echo $i ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-lg-6 col-sm-6 col-xs-6"
                                     style="padding-left: 0px; padding-right: 0px">
                                    <select class="form-control" id="schedule_minute" name="schedule_minute"
                                            required="required">
                                        <option value="" disabled="disabled">MINUTE</option>
                                        <option value="0">0</option>
                                        <option value="15">15</option>
                                        <option value="30" selected="selected">30</option>
                                        <option value="45">45</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-lg-6 col-xs-6" style="padding-left: 2px; padding-top: 10px">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="btn_submit_modal">Update!</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <h1>Course Management</h1>
    </section>

    <section class="content">

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="box">
                    <div class="box-body" style="text-align: center">

                        <div class="form-group form-inline">
                            <div class="col-md-2 col-sm-2 col-xs-2">
                                <div class="form-group">
                                    <label for="venue" class="sr-only">Venue</label>
                                    <select class="form-control" id="search_venue" name="search_venue">
                                        <?php foreach ($list_venue as $row) { ?>
                                            <option
                                                value="<?php echo $row['venue_id']; ?>" <?php if ($row['default_place'] == 'Y') { ?> selected="selected"<?php } ?>>
                                                <?php echo $row['venue_name']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="btn-group">
                                <a href="1">
                                    <button type="button" class="btn btn-<?php if ($day_selected == 1) {
                                        echo "success";
                                    } else {
                                        echo "default";
                                    } ?>">Monday
                                    </button>
                                </a>
                                <a href="2">
                                    <button type="button" class="btn btn-<?php if ($day_selected == 2) {
                                        echo "success";
                                    } else {
                                        echo "default";
                                    } ?>">Tuesday
                                    </button>
                                </a>
                                <a href="3">
                                    <button type="button" class="btn btn-<?php if ($day_selected == 3) {
                                        echo "success";
                                    } else {
                                        echo "default";
                                    } ?>">Wednesday
                                    </button>
                                </a>
                                <a href="4">
                                    <button type="button" class="btn btn-<?php if ($day_selected == 4) {
                                        echo "success";
                                    } else {
                                        echo "default";
                                    } ?>">Thursday
                                    </button>
                                </a>
                                <a href="5">
                                    <button type="button" class="btn btn-<?php if ($day_selected == 5) {
                                        echo "success";
                                    } else {
                                        echo "default";
                                    } ?>">Friday
                                    </button>
                                </a>
                                <a href="6">
                                    <button type="button" class="btn btn-<?php if ($day_selected == 6) {
                                        echo "success";
                                    } else {
                                        echo "default";
                                    } ?>">Saturday
                                    </button>
                                </a>
                                <a href="7">
                                    <button type="button" class="btn btn-<?php if ($day_selected == 7) {
                                        echo "success";
                                    } else {
                                        echo "default";
                                    } ?>">Sunday
                                    </button>
                                </a>
                                <a>
                                    <button type="button" class="btn btn-danger" id="btn_schedule_new">Add New Course
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-sm-12 col-xs-12">
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title">Active Courses</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered table-hover" id="tbl_content_main">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Slot Day</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Level</th>
                                <th>Instructor</th>
                                <th>Duration</th>
                                <th>Max Capacity</th>
                                <th>Venue</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($list_schedule as $row) { ?>
                                <?php
                                $temp_slot_day = $row ['slot_day'];
                                if (is_null($temp_slot_day))
                                    $dayStr = "Competitive";
                                else
                                    $dayStr = date('l', strtotime("Sunday + $temp_slot_day Days"));
                                ?>
                                <tr>
                                    <td id="current_schedule_id"><?php echo $row['id']; ?></td>
                                    <td><?php echo $dayStr; ?></td>
                                    <td><?php echo date('h:i A', strtotime($row['slot_time'])); ?></td>
                                    <td><?php echo date('h:i A', strtotime($row['slot_time_end'])); ?></td>
                                    <td><?php echo $row['level_name']; ?></td>
                                    <td><?php echo $row['instuctor_name']; ?></td>
                                    <td><?php echo $row['duration_minute'] . " mins"; ?></td>
                                    <td><?php echo $row['max_capacity']; ?></td>
                                    <td><?php echo $row['venue_name']; ?></td>
                                    <td><a href="#" class="deactivate_schedule"><i class="fa fa-trash fa-2x"></i></a>
                                       <!-- &nbsp; <a href="#"
                                                  id="btnCourseUpdate" class="btnCourseUpdate"><i
                                                class="fa fa-pencil-square-o fa-2x"></i></a>
                                                -->
                                    </td>
                                </tr>
                            <?php } ?>


                            <tbody>

                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>

    </section>
</div>
