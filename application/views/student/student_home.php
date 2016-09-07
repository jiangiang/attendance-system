<!-- DATA TABLES CSS-->
<link href = "<?php echo base_url(); ?>assets/plugins/datatables/css/dataTables.bootstrap.css" rel = "stylesheet"
      type = "text/css"/>
<link href = "<?php echo base_url(); ?>assets/plugins/datatables/css/responsive.bootstrap.min.css" rel = "stylesheet"
      type = "text/css"/>

<!-- InputMask -->
<script src = "<?php echo base_url(); ?>assets/plugins/input-mask/inputmask.js" type = "text/javascript"></script>
<script src = "<?php echo base_url(); ?>assets/plugins/input-mask/jquery.inputmask.js" type = "text/javascript"></script>
<script src = "<?php echo base_url(); ?>assets/plugins/input-mask/inputmask.extensions.js"
        type = "text/javascript"></script>

<!-- Custom Page JScript -->
<script src = "<?php echo base_url(); ?>assets/dist/js/student.js" type = "text/javascript"></script>

<style>
    .box-header {
        padding: 2px;
    }
</style>

<!-- Student activate/deactivation -->
<form id = "studentActivationForm" name = "studentActivationForm">
    <input type = "hidden" id = "activationID" name = "activationID">
    <input type = "hidden" id = "activationName" name = "activationName">
</form>

<?php $this -> view('student/Modals/StudentCreateEdit'); ?>

<!-- Content Wrapper. Contains page content -->
<div class = "content-wrapper">
    <!--
    <section class="content-header">
        <h1>Student Management</h1>
    </section>
    -->
    <section class = "content">
        <div class = "row">
            <form method = "post">
                <div class = "col-md-12 col-xs-12">
                    <div class = "box">
                        <div class = "box-header">
                            <div class = "col-md-2 col-xs-2" style = "padding-left:2px;">
                                <div class = "input-group">
                                    <span class = "input-group-addon"><label>Day</label></span>
                                    <select class = "form-control" id = "searchDay" name = "searchDay">
                                        <option value = "" selected = "selected">All</option>
                                        <?php for ($i = 1; $i <= 7; $i++) { ?>
                                            <option value = "<?php echo $i ?>" <?php if ($day == $i) {
                                                echo "SELECTED";
                                            } ?>><?php echo date('l', strtotime("Sunday +" . $i . " Days")); ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class = "col-md-2 col-xs-2" style = "padding-left:2px;">
                                <div class = "input-group">
                                    <span class = "input-group-addon"><label>Time</label></span>
                                    <select class = "form-control" id = "searchTime" name = "searchTime">
                                        <option value = "" selected = "selected">All</option>
                                        <?php for ($i = 8; $i <= 24; $i++) { ?>
                                            <option value = "<?php echo $i ?>" <?php if ($time == $i) {
                                                echo "SELECTED";
                                            } ?>><?php echo $i . ":00:00" ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class = "col-md-3 col-xs-4" style = "padding-left:2px;">
                                <div class = "input-group">
                                    <span class = "input-group-addon"><label>Name</label></span>
                                    <input class = "form-control" placeholder = "Search by Name" id = "searchName"
                                           name = "searchName" value = "<?php echo $name ?>">
                                </div>
                            </div>
                            <div class = "col-md-3 col-xs-4" style = "padding-left:2px;padding-right:2px;">
                                <div class = "form-group">
                                    <div class = "btn-group">
                                        <button type = "submit" id = "search" class = "btn btn-primary">Search</button>
                                        <button type = "button" class = "btn btn-danger btn-new_student" id = "btnStudentNew">
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
        <div class = "row">
            <div class = "col-md-12 col-xs-12">
                <div class = "box box-success">
                    <!--
                    <div class = "box-header">
                        <h3 class = "box-title">Active Student</h3>
                    </div>
                     -->
                    <!-- /.box-header -->
                    <div class = "box-body">
                        <table class = "table table-bordered table-hover dt-responsive" id = "tblActiveStudent">
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
                                    <td id = "student_id"><span id = "student_id_span"><?php echo $row['sid']; ?></span></td>
                                    <td>
                                        <a href = "#" id = "btn-update" class = "btn-update">
                                            <i class = "fa fa-pencil-square-o fa-lg"></i>
                                        </a>&nbsp;
                                        <a href = "#" class = "btn-deactivate">
                                            <i class = "fa fa-trash fa-lg"></i>
                                        </a>&nbsp;
                                        <a href = "#" class = "btn-show_detail">
                                            <i class = "fa fa-info-circle fa-lg"></i>
                                        </a>
                                    </td>
                                    <td id = "student_name"><?php echo $row['student_name']; ?></td>
                                    <td><?php echo $row['student_dob'] . "(" . (date('Y') - date('Y', strtotime($row['student_dob']))) . " y.o)"; ?></td>
                                    <!--<td><?php // echo $row['student_identity']; ?></td> -->
                                    <td><?php echo $row['student_contact']; ?></td>
                                    <td><?php echo date('l', strtotime("Sunday +" . $row['slot_day'] . " Days")); ?></td>
                                    <td><?php echo $row['slot_time']; ?></td>
                                </tr>
                            <?php } ?>
                            <tbody>
                            <tfoot>
                                <tr style="text-align: center">
                                   <td colspan="7">
                                       30 students is shown here. Please use search bar if you would like to find more.
                                   </td>
                                </tr>
                            </tfoot>


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
            <div class = "row">
                <div class = "col-xs-12">
                    <div class = "box">
                        <div class = "box-header">
                            <h3 class = "box-title">InActive Student</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class = "box-body">
                            <table class = "table table-bordered table-hover" id = "tblInactiveStudent">
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
                                        <td id = "student_id"><?php echo $row['sid']; ?></td>
                                        <td id = "student_name"><?php echo $row['student_name']; ?></td>
                                        <td><?php echo $row['student_identity']; ?></td>
                                        <td><?php echo $row['student_contact']; ?></td>
                                        <td><?php echo $row['guardian_name']; ?></td>
                                        <td><?php echo $row['guardian_contact']; ?></td>
                                        <td><?php echo $row['schedule_id']; ?></td>
                                        <td><span class = "label label-danger">InActive</span></td>
                                        <td><a href = "#" class = "studentActivate"><i
                                                    class = "fa fa-toggle-on fa-2x"></i></a></td>
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
    /*
     $(function () {
     $("#tblInactiveStudent").dataTable();
     $("#tblActiveStudent").dataTable({
     "iDisplayLength": 30
     });
     });
     */
</script>