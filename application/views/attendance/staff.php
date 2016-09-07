<!-- InputMask -->
<script src="<?php echo base_url();?>assets/plugins/input-mask/inputmask.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/plugins/input-mask/inputmask.extensions.js" type="text/javascript"></script>


<style>
<!--
.row .form-group {
	margin-bottom: 0px;
}
-->
</style>

<!-- staff activate/deactivation -->
<form id="attendanceActivationFrm" name="attendanceActivationFrm">
	<input type="hidden" id="activationID" name="activationID">
</form>

<!-- Custom Page JScript -->
<script src="<?php echo base_url(); ?>assets/dist/js/custom_attendance_staff.js" type="text/javascript"></script>

<?php $this -> view('Modals/StaffAttendanceForm'); ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<section class="content-header">
		<h1><?php echo $title; ?> - <?php echo date('F', strtotime("December +". $month." Months"));?></h1>
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
									<label>Year</label>
									<select class="form-control" id="searchYear" name="searchYear">
										<option value="" selected="selected">All</option>
										<?php foreach($years as $year){ ?>
											<option value="<?php echo $year['year']?>" <?php if($selectedYear == $year['year']){echo "SELECTED";}?>><?php echo $year['year'];?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="col-md-2 col-xs-2" style="padding-left:2px;">
					        	<div class="form-group">
									<label>Month</label>
									<select class="form-control" id="searchMonth" name="searchMonth">
										<option value="" selected="selected">All</option>
										<?php for($i=1; $i<=12; $i++){ ?>
											<option value="<?php echo $i?>" <?php if($month == $i){echo "SELECTED";}?>><?php echo date('F', strtotime("December +". $i." Months"));?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="col-md-3 col-xs-3" style="padding-left:2px;">
								<div class="form-group">
									<label>Name</label>
									<input class="form-control" placeholder="Search by Name" id="searchName" name="searchName" value="<?php echo $name?>">
								</div>
							</div>
							<div class="col-md-2 col-xs-2" style="padding-left:2px;">
								<div class="form-group">
									<label>Status</label>
									<select class="form-control" id="payoutStatus" name="payoutStatus">
										<option value="" selected="selected">All</option>
										<option value="Y" <?php if($payoutStatus == 'Y'){echo "SELECTED";}?>>Paid</option>
										<option value="N" <?php if($payoutStatus == 'N'){echo "SELECTED";}?>>Unpaid</option>
									</select>
								</div>
							</div>
							<div class="col-md-3 col-xs-3" style="padding-left:0px; padding-right : 0px">
								<div class="form-group"  style="padding-top: 25px;">
									<div class="btn-group">
										<button type="submit" id="search" class="btn btn-primary">Search</button>
										<button type="button" class="btn btn-success btnAddAttendance"id="btnAddAttendance">
											Add Attendance
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
		
		<form id="attendaceFrm" name="attendaceFrm">
			
			<div class="row">
				<div class="col-xs-12">
					<div class="box box-success">
						<!-- /.box-header -->
						<div class="box-body">
							<table class="table table-bordered table-hover" id="tblAttendance">
								<thead>
									<tr>
										<th>ID</th>
										<th>Employee Name</th>
										<th>IC/Passport</th>
										<th>Date</th>
										<th>Session Count</th>
										<th>Payout Status</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
								<?php foreach($staff_attendance as $row){ ?>
								<tr>
										<td id="att_id"><?php echo $row['attendance_id']?></td>
										<td><?php echo $row['name']?></td>
										<td><?php echo $row['identity']?></td>
										<td><?php echo $row['session_date']?></td>
										<td><?php echo $row['session_count']?></td>
										<td><?php  if($row['payout_status']=='N'){echo "Unpaid";}else{echo "PAID";} ?></td>
										<td>
											<?php if($row['payout_status'] == 'N'){ ?>
											<a href="#" class="attendanceVoid">
												<i class="fa fa-trash fa-2x"></i>
											</a> &nbsp; 
											<!-- <a href="#" id="attendanceUpdate" class="attendanceUpdate"><i class="fa fa-pencil-square-o fa-2x"></i></a> -->
											<?php } ?>
										</td>
									</td>
									</tr>
								<?php } ?>
								
								
								
								<tbody>
							
							</table>
						</div>
						<div class="box-footer">
							
						</div>
						<!-- /.box-body -->
					</div>
					<!-- /.box -->
				</div>
			</div>
		</form>
	</section>
</div>
