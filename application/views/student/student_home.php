<!-- DATA TABLES CSS-->
<link href="<?php echo base_url(); ?>assets/plugins/datatables/css/dataTables.bootstrap.css" rel="stylesheet"
      type="text/css"/>
<link href="<?php echo base_url(); ?>assets/plugins/datatables/css/responsive.bootstrap.min.css" rel="stylesheet"
      type="text/css"/>

<!-- dataTables JS -->
<script src="<?php echo base_url(); ?>assets/plugins/datatables/js/jquery.dataTables.js"
        type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/plugins/datatables/js/dataTables.bootstrap.js"
        type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/plugins/datatables/js/dataTables.responsive.min.js"
        type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/plugins/datatables/js/responsive.bootstrap.min.js"
        type="text/javascript"></script>

<!-- InputMask -->
<script src="<?php echo base_url(); ?>assets/plugins/input-mask/inputmask.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/plugins/input-mask/inputmask.extensions.js"
        type="text/javascript"></script>

<!-- Custom Page JScript -->
<script src="<?php echo base_url(); ?>assets/dist/js/custom_student.js" type="text/javascript"></script>
<style>
    .box-header {
        padding: 2px;
    }
</style>
<!-- Student activate/deactivation -->
<form id="studentActivationFrm" name="studentActivationFrm">
    <input type="hidden" id="activationID" name="activationID">
    <input type="hidden" id="activationName" name="activationName">
</form>

<form id="studentInfoFrm" name="studentInfoFrm" autocomplete="off">
    <input type="hidden" id="student_sid" name="student_sid" value=""/>
    <div class="modal fade" role="dialog" aria-labelledby="myModalLabel" id="modal_main">
        <div class="modal-dialog modal-lg" style="margin-top: 2%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        <i class="fa fa-user-plus"></i><span
                            id="modal_title_text">If you see me something is wrong yo</span>
                    </h4>
                </div>
                <div id="statusMsg" style="padding-bottom: 0px; margin-bottom: 0px"></div>

                <div class="col-md-6 col-sm-6 col-xs-6" style="padding-right: 0px">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input class="form-control" placeholder="Student Full Name" id="student_name" name="student_name"
                                   required="required">
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-6" style="padding-left: 0px; padding-right: 2px">
                            <div class="form-group">
                                <label>IC/ Passport</label>
                                <input class="form-control" placeholder="IC/ Passport Number" id="student_id" name="student_id"
                                       required="required">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-6" style="padding-left: 0px; padding-right: 2px">
                            <div class="form-group">
                                <label>DOB</label>
                                <input type="text" id="student_dob" name="student_dob" class="form-control" value="" required
                                       data-inputmask='"mask": "9999-99-99"' data-mask></div>
                        </div>
                        <div class="col-md-7 col-sm-7 col-xs-7" style="padding-left: 0px; padding-right: 2px">
                            <div class="form-group">
                                <label>Contact No.</label>
                                <input class="form-control" placeholder="Phone Number" id="student_contact" name="student_contact"
                                       required="required" data-inputmask='"mask": "999-9999999[9]"' data-mask>
                            </div>
                        </div>
                        <div class="col-md-5 col-sm-5 col-xs-5" style="padding-left: 0px; padding-right: 2px">
                            <div class="form-group">
                                <label>Gender</label>
                                <select class="form-control" id="student_gender" name="student_gender" required="required">
                                    <option value="M">Male</option>
                                    <option value="F">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input class="form-control" placeholder="Email" id="student_email" name="student_email"
                                   data-inputmask="'alias': 'email'" data-mask>
                        </div>
                        <div class="form-group">
                            <label>Guardian Name</label>
                            <input class="form-control" placeholder="Student Guardian" id="guardian_name"
                                   name="guardian_name">
                        </div>
                        <div class="col-md-7 col-sm-7 col-xs-7" style="padding-left: 0px; padding-right: 0px">
                            <div class="form-group">
                                <label>Guardian Contact</label>
                                <input class="form-control" placeholder="Student Guardian" id="guardian_contact"
                                       name="guardian_contact" data-inputmask='"mask": "999-9999999[9]"' data-mask>
                            </div>
                        </div>
                        <div class="col-md-5 col-sm-5 col-xs-5" style="padding-left: 0px; padding-right: 0px">
                            <div class="form-group">
                                <label>Gender</label>
                                <select class="form-control" id="guardian_gender" name="guardian_gender">
                                    <option value=" ">NA</option>
                                    <option value="M">Male</option>
                                    <option value="F">Female</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-6" style="padding-left: 2px">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Address</label>
                            <input class="form-control" placeholder="Address line 1" id="address_1" name="address_1">
                            <input class="form-control" placeholder="Address line 2" id="address_2" name="address_2">
                            <div class="col-md-6 col-sm-6 col-xs-6" style="padding-left: 0px; padding-right: 2px">
                                <input class="form-control" placeholder="PostCode" id="postcode" name="postcode">
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6" style="padding-left: 0px; padding-right: 0px">
                                <input class="form-control" placeholder="City" id="city" name="city">
                            </div>
                            <input class="form-control" placeholder="State" id="state" name="state" value="Johor">
                            <input class="form-control" placeholder="Country" id="country" name="country"
                                   value="Malaysia">
                        </div>
                        <div class="form-group" style="padding-bottom: 0px">
                            <div class="col-md-6 col-sm-6 col-xs-6" style="padding-left: 0px; padding-right: 0px;">
                                <div class="form-group">
                                    <label for="venue">Venue</label>
                                    <select class="form-control" id="lesson_venue" name="lesson_venue">
                                        <?php foreach ($venue_code_rows as $row) { ?>
                                            <option
                                                value="<?php echo $row['venue_id']; ?>" <?php if ($row['default_place'] == 'Y') {
                                                echo " selected=\"selected\"";
                                            } ?>>
                                                <?php echo $row['venue_name']; ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6" style="padding-left: 0px; padding-right: 0px;">
                                <div class="form-group">
                                    <label>Lesson Type</label>
                                    <select class="form-control" id="lesson_type" name="lesson_type">
                                        <option disabled="" selected="selected" value=""> Select a lesson</option>
                                        <?php foreach ($course_level_rows as $row) { ?>
                                            <option
                                                value="<?php echo $row['level_id']; ?>"><?php echo $row['level_name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6" style="padding-left: 0px; padding-right: 0px;">
                                <label>Day</label>
                                <select class="form-control" id="lesson_day" name="lesson_day">
                                    <option value="1">Mon</option>
                                    <option value="2">Tue</option>
                                    <option value="3">Wed</option>
                                    <option value="4">Thurs</option>
                                    <option value="5">Fri</option>
                                    <option value="6">Sat</option>
                                    <option value="7">Sun</option>
                                </select>

                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6" style="padding-left: 0px; padding-right: 0px;">
                                <label>Time</label>
                                <select class="form-control" id="schedule_id" name="schedule_id"
                                        style="visibility: visible; display: inline-block">
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btn-modal_submit" disabled="disabled">Update!
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <h1>Student Management</h1>
    </section>

    <section class="content">
        <div class="row">
            <form method="post">
                <div class="col-md-1"></div>
                <div class="col-md-10 col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <div class="col-md-2 col-xs-2" style="padding-left:2px;">
                                <div class="form-group">
                                    <label>Day</label>
                                    <select class="form-control" id="searchDay" name="searchDay">
                                        <option value="" selected="selected">All</option>
                                        <?php for ($i = 1; $i <= 7; $i++) { ?>
                                            <option value="<?php echo $i ?>" <?php if ($day == $i) {
                                                echo "SELECTED";
                                            } ?>><?php echo date('l', strtotime("Sunday +" . $i . " Days")); ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-xs-2" style="padding-left:2px;">
                                <div class="form-group">
                                    <label>Time</label>
                                    <select class="form-control" id="searchTime" name="searchTime">
                                        <option value="" selected="selected">All</option>
                                        <?php for ($i = 8; $i <= 24; $i++) { ?>
                                            <option value="<?php echo $i ?>" <?php if ($time == $i) {
                                                echo "SELECTED";
                                            } ?>><?php echo $i . ":00:00" ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-xs-4" style="padding-left:2px;">
                                <div class="form-group">
                                    <label>Name:</label>
                                    <input class="form-control" placeholder="Search by Name" id="searchName"
                                           name="searchName" value="<?php echo $name ?>">
                                </div>
                            </div>
                            <div class="col-md-3 col-xs-4" style="padding-left:2px;padding-right:2px;">
                                <div class="form-group" style="padding-top: 25px;">
                                    <div class="btn-group">
                                        <button type="submit" id="search" class="btn btn-primary">Search</button>
                                        <button type="button" class="btn btn-danger btn-new_student" id="btnStudentNew">
                                            New Student
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title">Active Student</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered table-hover dt-responsive" id="tblActiveStudent">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Action</th>
                                <th>Name</th>
                                <th>Age</th>
                                <!--<th>IC/Passport</th> -->
                                <th>Contact</th>
                                <th>Course Day</th>
                                <th>Course Time</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($std_active_rows as $row) { ?>
                                <tr>
                                    <td id="student_id"><span id="student_id_span"><?php echo $row['sid']; ?></span></td>
                                    <td>
                                        <a href="#" id="btn-update" class="btn-update">
                                            <i class="fa fa-pencil-square-o fa-lg"></i>
                                        </a>&nbsp;
                                        <a href="#" class="btn-deactivate">
                                            <i class="fa fa-trash fa-lg"></i>
                                        </a>&nbsp;
                                        <a href="#" class="btn-show_detail">
                                            <i class="fa fa-info-circle fa-lg"></i>
                                        </a>
                                    </td>
                                    <td id="student_name"><?php echo $row['student_name']; ?></td>
                                    <td><?php echo $row['student_dob'] . "(" . (date('Y') - date('Y',strtotime($row['student_dob'])))." y.o)"; ?></td>
                                    <!--<td><?php // echo $row['student_identity']; ?></td> -->
                                    <td><?php echo $row['student_contact']; ?></td>
                                    <td><?php echo date('l', strtotime("Sunday +" . $row['slot_day'] . " Days")); ?></td>
                                    <td><?php echo $row['slot_time']; ?></td>
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
        <?php $showInactive = false ?>
        <?php if ($showInactive == true) { ?>
            <!-- Show Inactive student -->
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">InActive Student</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table class="table table-bordered table-hover" id="tblInactiveStudent">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>IC/Passport</th>
                                    <th>Contact</th>
                                    <th>Guardian</th>
                                    <th>Guardian Contact</th>
                                    <th>Course ID</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($std_inactive_rows as $row) { ?>
                                    <tr>
                                        <td id="student_id"><?php echo $row['sid']; ?></td>
                                        <td id="student_name"><?php echo $row['student_name']; ?></td>
                                        <td><?php echo $row['student_identity']; ?></td>
                                        <td><?php echo $row['student_contact']; ?></td>
                                        <td><?php echo $row['guardian_name']; ?></td>
                                        <td><?php echo $row['guardian_contact']; ?></td>
                                        <td><?php echo $row['schedule_id']; ?></td>
                                        <td><span class="label label-danger">InActive</span></td>
                                        <td><a href="#" class="studentActivate"><i
                                                    class="fa fa-toggle-on fa-2x"></i></a></td>
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
        <?php } ?>
    </section>
</div>

<script>
    $(function () {
        $("#tblInactiveStudent").dataTable();
        $("#tblActiveStudent").dataTable({
            "iDisplayLength": 30
        });
    });
</script>