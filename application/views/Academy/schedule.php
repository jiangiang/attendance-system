<!-- InputMask -->
<script src="<?php echo base_url(); ?>assets/plugins/input-mask/inputmask.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/plugins/input-mask/inputmask.extensions.js"
        type="text/javascript"></script>

<!-- Custom Page JScript -->
<script src="<?php echo base_url(); ?>assets/dist/js/Academy/schedule.js" type="text/javascript"></script>

<!-- course activate/deactivation -->
<form id="form_activation" name="form_activation">
    <input type="hidden" id="activation_id" name="activation_id">
</form>

<?php $this -> view('Academy/Modals/ScheduleCreateEdit'); ?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <h1><?php echo $title; ?></h1>
    </section>

    <section class="content">

        <div class="row">
            <form id="search_form" name="search_form" method="post">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="box">
                        <div class="box-body" style="text-align: center">
                            <div class="form-group form-inline">

                                    <div class="input-group">
                                        <span class = "input-group-addon"><label>Venue</label></span>
                                        <select class="form-control" id="search_venue" name="search_venue">
                                            <?php foreach ($list_venue as $row) { ?>
                                                <option
                                                    value="<?php echo $row['venue_id']; ?>" <?php if ($venue_id == $row['venue_id']) { ?> selected="selected"<?php } ?>>
                                                    <?php echo $row['venue_name']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                <div class="btn-group">
                                    <a href="1">
                                        <button type="button" class="btn btn-<?php if ($day_selected == 1) {
                                            echo "info";
                                        } else {
                                            echo "default";
                                        } ?>">Monday
                                        </button>
                                    </a>
                                    <a href="2">
                                        <button type="button" class="btn btn-<?php if ($day_selected == 2) {
                                            echo "info";
                                        } else {
                                            echo "default";
                                        } ?>">Tuesday
                                        </button>
                                    </a>
                                    <a href="3">
                                        <button type="button" class="btn btn-<?php if ($day_selected == 3) {
                                            echo "info";
                                        } else {
                                            echo "default";
                                        } ?>">Wednesday
                                        </button>
                                    </a>
                                    <a href="4">
                                        <button type="button" class="btn btn-<?php if ($day_selected == 4) {
                                            echo "info";
                                        } else {
                                            echo "default";
                                        } ?>">Thursday
                                        </button>
                                    </a>
                                    <a href="5">
                                        <button type="button" class="btn btn-<?php if ($day_selected == 5) {
                                            echo "info";
                                        } else {
                                            echo "default";
                                        } ?>">Friday
                                        </button>
                                    </a>
                                    <a href="6">
                                        <button type="button" class="btn btn-<?php if ($day_selected == 6) {
                                            echo "info";
                                        } else {
                                            echo "default";
                                        } ?>">Saturday
                                        </button>
                                    </a>
                                    <a href="7">
                                        <button type="button" class="btn btn-<?php if ($day_selected == 7) {
                                            echo "info";
                                        } else {
                                            echo "default";
                                        } ?>">Sunday
                                        </button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
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
                                <th>
                                    <button type="button" class="btn btn-success btn-sm" id="btn_schedule_new">Add New
                                        Course
                                    </button>
                                </th>
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
                                    <td id="current_schedule_id"><?php echo $row['schedule_id']; ?></td>
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
