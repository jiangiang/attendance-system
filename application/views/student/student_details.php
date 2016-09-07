
<!-- Custom Page JScript -->
<script src="<?php echo base_url(); ?>assets/dist/js/custom_student.js" type="text/javascript"></script>

<style>
	.title{
		background-color: #6E92A1;
		color: #032536;
	}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<section class="content">
		<?php if($student_details['student_gender'] == 'M'){$std_gender = "Male";}else{$std_gender = "Female";}?>
		<?php if($student_details['guardian_gender'] == 'M'){$guardian_gender = "Male";}else if($student_details['guardian_gender'] == 'F'){$guardian_gender = "Female";}else{$guardian_gender="";}?>
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-success">
					<!-- /.box-header -->
					<div class="box-body">
						<table class="table table-bordered" id="tblActiveStudent">
							<thead>
								<tr>
									<th colspan="4">Student Info</th>
									
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="title" style="width:20%">Name</td>
									<td style="width:30%"><?php echo $student_details['student_name'] ?></td>
									<td class="title" style="width:20%">Age</td>
									<td style="width:30%"><?php echo (date('Y') - date('Y',strtotime($student_details['student_dob']))) ?></td>
								</tr>
								<tr>
									<td class="title" style="width:20%">Gender</td>
									<td style="width:30%"><?php echo $std_gender ?></td>
									<td class="title" style="width:20%">I/C Number</td>
									<td style="width:30%"><?php echo $student_details['student_identity'] ?></td>
								</tr>
								<tr>
									<td class="title" >Address</td>
									<td><?php echo $student_details['address_line1'].", ".$student_details['address_line2'] ?></td>
									<td class="title">Contact Number</td>
									<td><?php echo $student_details['student_contact'] ?></td>
								</tr>
								<tr>
									<td class="title"></td>
									<td><?php echo $student_details['postcode'].", ".$student_details['city'] ?></td>
									<td class="title">Email</td>
									<td><?php echo $student_details['student_email'] ?></td>
								</tr>
								<tr>
									<td class="title"></td>
									<td><?php echo $student_details['state'].", ".$student_details['country'] ?></td>
									<td class="title">Student Status</td>
									<td><?php echo $student_details['student_status'] ?></td>
								</tr>					
							<tbody>
							<thead>
								<tr>
									<th colspan="4">Course Info</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="title">Lastest Payment</td>
									<td><?php echo $student_details_payment['last_payment_date'] ?></td>
									<td class="title">Slot</td>
									<td><?php echo date('l', strtotime("Sunday +". $student_details['slot_day']." Days")) . "|" . $student_details['slot_time'] ?></td>
								</tr>
								<tr>
									<td class="title">Lastest Payment ID</td>
									<td><?php echo $student_details_payment['last_bill_id'] ?></td>
									<td class="title">Instructor</td>
									<td><?php echo $student_details['staff_name'] ?></td>
								</tr>	
								<tr>
									<td class="title">Course Left</td>
									<td><?php echo $student_details_payment['att_count'] ?></td>
									<td class="title">Transaction History</td>
									<td><?php echo anchor('student/payment_history/'.$student_details['sid'], 'Payment History');?></td>
								</tr>
								<tr>
									<td class="title">Attendance History</td>
									<td><?php echo anchor('student/attendance_history/'.$student_details['sid'], 'Attendance History');?></td>
									<td class="title"></td>
									<td></td>
								</tr>
							<tbody>	
							<thead>
								<tr>
									<th colspan="4">Guardian Info</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="title">Name</td>
									<td style="width:30%"><?php echo $student_details['guardian_name'] ?></td>
									<td class="title">Gender</td>
									<td style="width:30%"><?php echo $guardian_gender ?></td>
								</tr>
							<tr>
								<td class="title">Contact Number</td>
								<td style="width:30%"><?php echo $student_details['guardian_contact'] ?></td>
								<td class="title"></td>
								<td></td>
							</tr>
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
