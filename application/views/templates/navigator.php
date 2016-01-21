<?php $userData = $this -> session -> userdata('logged_in'); ?>
<header class="main-header">
	<nav class="navbar navbar-static-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<a href="#" class="navbar-brand"><b>EZY System</b></a>
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
					<i class="fa fa-bars"></i>
				</button>
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="navbar-collapse">
				<ul class="nav navbar-nav">
					<li><?php echo anchor('attendance/student', 'Student Attendance'); ?></li>
					<li><?php echo anchor('finance/tuitionFeeDashboard', 'Student Payment'); ?></li>
					<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Student <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">			
							<li><?php echo anchor('student/student_home', 'Students Home'); ?></li>
							<li class="divider"></li>
							<li><?php echo anchor('student/student_log', 'Student Log'); ?></li>				
						</ul></li>

					<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Staffs <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><?php echo anchor('staff/summary', 'Staffs Info Summary'); ?></li>
							<li><?php echo anchor('attendance/staff', 'Staff Attendance'); ?></li>
							<li class="divider"></li>
							<li><?php echo anchor('finance/employee_payroll', 'Staffs Payroll'); ?></li>
						</ul></li>

					<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Courses <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><?php echo anchor('course/schedule', 'Schedules'); ?></li>
							<li class="divider"></li>
							<li><?php echo anchor('course/category', 'Categories'); ?></li>
							<li><?php echo anchor('course/venue', 'Venues'); ?></li>
							<li><?php echo anchor('course/package', 'Packages'); ?></li>
						</ul></li>
					<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Dashboard <span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><?php echo anchor('dashboard/student_overdue', 'Student Overdue'); ?></li>
							<li class="divider"></li>
							<li><?php echo anchor('dashboard/student_attendance', 'Student Attendance'); ?></li>
							<li class="divider"></li>
							<li><?php echo anchor('dashboard/timetable_instructor', 'Timetable - Instructor'); ?></li>
							<li><?php echo anchor('dashboard/timetable_class', 'Timetable - Class'); ?></li>
							<li class="divider"></li>
							<li><?php echo anchor('dashboard/tuitionFee', 'Received Fee'); ?></li>
							<li><?php echo anchor('dashboard/employee_payroll', 'Salary Payout'); ?></li>
							<li><?php echo anchor('dashboard/companyProfit', 'Company Profit'); ?></li>
						</ul></li>
				</ul>

				<ul class="nav navbar-nav navbar-right">
				
					<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $userData['username']?> <span
							class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="#">Change Password</a></li>
							<li class="divider"></li>
							<li><a href="<?php echo site_url(); ?>/logout">Log Out</a></li>
						</ul></li>
				</ul>
			</div>
			<!-- /.navbar-collapse -->
		</div>
		<!-- /.container-fluid -->
	</nav>
</header>