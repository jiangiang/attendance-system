
<!-- Custom Page JScript -->
<script src="<?php echo base_url();?>assets/dist/js/custom_attendance.js" type="text/javascript"></script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<section class="content-header">
		<h1>Student Overdue Dashboard</h1>
	</section>

	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-success">
					<!-- /.box-header -->
					<div class="box-body">
						<table class="table table-bordered table-hover" id="tblActiveStudent">
							<thead>
								<tr>
									<th>ID</th>
									<th>Name</th>
									<th>IC/Passport</th>
									<th>Day</th>
									<th>Time</th>
									<th>Level</th>
									<th>Overdue</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($students_attendance as $row){ ?>
								<tr>
									<td id="student_id"><?php echo $row['id']?></td>
									<td id="student_name"><?php echo $row['std_name']?></td>
									<td><?php echo $row['std_identity']?></td>
									<td><?php echo $row['slot_day']?></td>
									<td><?php echo date('h:i A', strtotime($row['slot_time'])) ?></td>
									<td><?php echo $row['level_name']?></td>
									<td><?php echo $row['overdue_count']?></td>
									
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
