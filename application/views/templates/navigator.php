<?php $userData = $this->session->userdata( 'logged_in' ); ?>
<header class = "main-header">
    <nav class = "navbar navbar-static-top">
        <div class = "container-fluid">

            <div class = "navbar-header">
                <button type = "button" class = "navbar-toggle collapsed" data-toggle = "collapse" data-target = "#navbar-collapse">
                    <i class = "fa fa-bars"></i>
                </button>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class = "collapse navbar-collapse" id = "navbar-collapse">
                <ul class = "nav navbar-nav">
                    <li><?php echo anchor( 'attendance/student', 'Attendance' ); ?></li>
                    <li><?php echo anchor( 'finance/tuitionFeeDashboard', 'Payment' ); ?></li>
                    <li class = "dropdown"><a href = "#" class = "dropdown-toggle" data-toggle = "dropdown">Student <span
                                class = "caret"></span></a>
                        <ul class = "dropdown-menu" role = "menu">
                            <li><?php echo anchor( 'student/Student/index', 'Students Home' ); ?></li>
                            <li class = "divider"></li>
                            <li><?php echo anchor( 'student/Academy_record/index', 'Student Log' ); ?></li>
                        </ul>
                    </li>

                    <li class = "dropdown"><a href = "#" class = "dropdown-toggle" data-toggle = "dropdown">Staffs <span
                                class = "caret"></span></a>
                        <ul class = "dropdown-menu" role = "menu">
                            <li><?php echo anchor( 'staff/summary', 'Staffs Info Summary' ); ?></li>
                            <li><?php echo anchor( 'attendance/staff', 'Staff Attendance' ); ?></li>
                            <li class = "divider"></li>
                            <li><?php echo anchor( 'finance/employee_payroll', 'Staffs Payroll' ); ?></li>
                        </ul>
                    </li>

                    <li class = "dropdown"><a href = "#" class = "dropdown-toggle" data-toggle = "dropdown">Academy <span
                                class = "caret"></span></a>
                        <ul class = "dropdown-menu" role = "menu">
                            <li><?php echo anchor( 'Academy/Instructor/assignment', 'Instructor Assignment' ); ?></li>
                            <li><?php echo anchor( 'Academy/Schedule/index', 'Schedules' ); ?></li>
                            <li class = "divider"></li>
                            <li><?php echo anchor( 'Academy/Level/index', 'Academy level' ); ?></li>
                        </ul>
                    </li>
                    <li class = "dropdown"><a href = "#" class = "dropdown-toggle" data-toggle = "dropdown">Dashboard <span
                                class = "caret"></span></a>
                        <ul class = "dropdown-menu" role = "menu">
                            <li><?php echo anchor( 'dashboard/student_overdue', 'Student Overdue' ); ?></li>
                            <li class = "divider"></li>
                            <li><?php echo anchor( 'dashboard/student_attendance', 'Student Attendance' ); ?></li>
                            <li class = "divider"></li>
                            <li><?php echo anchor( 'dashboard/timetable_instructor', 'Timetable - Instructor' ); ?></li>
                            <li><?php echo anchor( 'dashboard/timetable_classes', 'Timetable - classes' ); ?></li>
                            <li class = "divider"></li>
                            <li><?php echo anchor( 'dashboard/tuitionFee', 'Received Fee' ); ?></li>
                            <li><?php echo anchor( 'dashboard/employee_payroll', 'Salary Payout' ); ?></li>
                            <li><?php echo anchor( 'dashboard/companyProfit', 'Company Profit' ); ?></li>
                        </ul>
                    </li>
                    <li class = "dropdown">
                        <a href = "#" class = "dropdown-toggle" data-toggle = "dropdown">Competitive <span
                                class = "caret"></span></a>
                        <ul class = "dropdown-menu" role = "menu">
                            <li><?php echo anchor( 'Elite/Swimmer/swimmer_list', 'Swimmers Info' ); ?></li>
                            <li class = "divider"></li>
                            <li><?php echo anchor( 'Elite/Competition/index', 'Competitions' ); ?></li>
                            <li class = "divider"></li>
                            <li><?php echo anchor( 'Elite/Performance/index', 'Individual performance analysis' ); ?></li>
                        </ul>
                    </li>
                </ul>

                <ul class = "nav navbar-nav navbar-right">

                    <li class = "dropdown"><a href = "#" class = "dropdown-toggle"
                                              data-toggle = "dropdown"><?php echo $userData[ 'username' ] ?> <span
                                class = "caret"></span></a>
                        <ul class = "dropdown-menu" role = "menu">
                            <li><a href = "#">System Setting</a></li>
                            <li class = "divider"></li>
                            <li><a href = "#">Change Password</a></li>
                            <li class = "divider"></li>
                            <li><a href = "<?php echo site_url(); ?>/logout">Log Out</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>
</header>