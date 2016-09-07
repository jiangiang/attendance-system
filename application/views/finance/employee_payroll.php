<!-- Custom Page JScript -->
<script src="<?php echo base_url();?>assets/dist/js/custom_salary_process.js" type="text/javascript"></script>

<!-- Student activate/deactivation -->
<form id="billActivationFrm" name="billActivationFrm">
	<input type="hidden" id="activationID" name="activationID">
</form>

<?php $this -> view('Modals/StaffPayroll'); ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<section class="content-header">
		<h1><?php echo $title; ?></h1>
	</section>

	<section class="content">
		<div class="row">
			<form method="post">
				<div class="col-md-2"></div>
				<div class="col-md-9 col-xs-12">
					<div class="box">
				        <div class="box-header">
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
							<div class="col-md-3 col-xs-2" style="padding-left:2px;">
								<div class="form-group">
									<label>Name:</label>
									<input class="form-control" placeholder="Search by Name" id="searchName" name="searchName" value="<?php echo $name?>">
								</div>
							</div>
							<div class="col-md-2 col-xs-2" style="padding-left:2px;">
								<div class="form-group">
									<label>Status:</label>
									<select class="form-control" id="salary_status" name="salary_status">
										<option value="" selected="selected">All</option>
										<option value="N" <?php if($salary_status == 'N'){echo "SELECTED";}?>>Unproccessed</option>
										<option value="Y" <?php if($salary_status == 'Y'){echo "SELECTED";}?>>Processed</option>
									</select>
								</div>
							</div>
							<div class="col-md-2 col-xs-2" style="padding-left:2px;">
								<div class="form-group">
									<label>Payout:</label>
									<select class="form-control" id="payout_status" name="payout_status">
										<option value="" selected="selected">All</option>
										<option value="N" <?php if($payout_status == 'N'){echo "SELECTED";}?>>Unpaid</option>
										<option value="Y" <?php if($payout_status == 'Y'){echo "SELECTED";}?>>Paid</option>
									</select>
								</div>
							</div>
							<div class="col-md-3 col-xs-3" style="padding-left:2px;">
								<div class="form-group"  style="padding-top: 25px;">
									<button type="submit" id="search" class="btn btn-primary">Search</button>
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
					<div class="box-body">
						<table class="table table-bordered table-hover" id="tblEmployeeSalaryPayout">
							<thead>
								<tr>
									<th>ID</th>
									<th>Name</th>
									<th>IC/Passport</th>
									<th>Month/Year</th>
									<th>Session Count</th>
									<th>Salary (RM)</th>
									<th>Process</th>
									<th>Taken</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($payroll_records as $row){?>
								<tr>
									<td id="employee_id"><?php echo $row['id']; ?></td>
									<td><?php echo $row['name']; ?></td>
									<td><?php echo $row['identity']; ?></td>
									<td><?php echo $row['month']; ?></td>
									<td><?php echo $row['session_count']; ?></td>
									<td><?php echo number_format($row['salary'],2); ?></td>
									<td><?php echo $row['salary_status']; ?></td>
									<td><?php echo $row['taken']; ?></td>
									<td>
										<?php if($row['salary_status'] != 'Processed'){ ?>
										&nbsp; <a href="#" id="btnPayout" class="btnSalaryPayout"><i
											class="fa fa-arrow-right fa-2x text-red"></i></a>
										<?php } ?>
									</td>
								</tr>
								<?php }?>							

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