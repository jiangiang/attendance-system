
<!-- DATA TABLES CSS-->
<link href="<?php echo base_url();?>assets/plugins/datatables/css/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />

<!-- dataTables JS -->
<script src="<?php echo base_url();?>assets/plugins/datatables/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/plugins/datatables/js/dataTables.bootstrap.js" type="text/javascript"></script>

<!-- Student activate/deactivation -->
<form id="salaryFrm" name="salaryFrm">
	<input type="hidden" id="salaryID" name="salaryID">
</form>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<section class="content-header">
		<h1><?php echo $title; ?> - <?php echo date('F', strtotime("December +". $month." Months"));?> <?php echo $selectedYear;?></h1>
	</section>

	<section class="content">
		<div class="row">
			<form method="post">
				<div class="col-md-12">
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
							<div class="col-md-2 col-xs-2" style="padding-left:2px;">
					        	<div class="form-group">
									<label>Year</label>
									<select class="form-control" id="searchYear" name="searchYear">
										<option value="" selected="selected">All</option>
										<?php foreach($payroll_get_years as $year){ ?>
											<option value="<?php echo $year['year']?>" <?php if($selectedYear == $year['year']){echo "SELECTED";}?>><?php echo $year['year'];?></option>
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
									<label>Payout</label>
									<select class="form-control" id="payout_status" name="payout_status">
										<option value="" selected="selected">All</option>
										<option value="N" <?php if($payout_status == 'N'){echo "SELECTED";}?>>Unpaid</option>
										<option value="Y" <?php if($payout_status == 'Y'){echo "SELECTED";}?>>Paid</option>
									</select>
								</div>
							</div>
							<div class="col-md-3 col-xs-3" style="padding-left:2px;">
								<div class="form-group"  style="padding-top: 25px;">
									<div class="btn-group">
										<button type="submit" id="search" class="btn btn-primary">Search</button>
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
						<h4 class="box-title">Payment are all in Ringgit Malaysia</h4>
					</div>
					<div class="box-body">
						<table class="table table-bordered table-hover" id="tblEmployeeSalaryPayout">
							<thead>
								<tr>
									<th>Payroll ID</th>
									<th>Name</th>
									<th>Month/Year</th>
									<th>Session(s)</th>
									<th>Basic Salary</th>
									<th>Bonus</th>
									<th>Total Salary</th>
									<th>Remark</th>
									<th>Taken</th>
								</tr>
							</thead>
							<tbody>
								<?php $totalSalary = 0;?>
								<?php foreach ($payroll_records as $row){?>
									<?php $totalSalary +=  $row['salary']?>
								<tr>
									<td id="salary_id"><?php echo $row['salary_id']; ?></td>
									<td><?php echo $row['name']; ?></td>
									<td><?php echo $row['month']; ?></td>
									<td><?php echo $row['session_count']; ?></td>
									<td><?php echo number_format($row['salary_basic'],2); ?></td>
									<td><?php echo number_format($row['adjustment'],2); ?></td>
									<td style="background-color: #add1e5 "><?php echo number_format($row['salary'],2); ?></td>
									<td><?php echo $row['remark']; ?></td>
									<td>
										<?php if($row['taken'] == 'Y'){?>
											Paid
										<?php }else{ ?>
										<a href="#" id="btnPayout" class="btnSalaryPayout">
											<span style="text-align: center ">Take Now</span>
											<i class="fa fa-angle-double-right  fa-1x text-orange"></i> 
										</a>
										<?php } ?>
									</td>
								</tr>
								<?php }?>							

							</tbody>
							<tfoot>
								<tr>
									<td colspan="6" style="text-align: right;font-weight: bolder"> Total Payment</td>
									<td style="text-align: center; font-weight: bolder;background-color: #add1e5"><?php echo "RM ".number_format($totalSalary,2); ?></td>
									<td colspan="2"></td>
								</tr>
							</tfoot>
						
						</table>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->
			</div>
		</div>
	</section>
</div>

<script>
$(document).ready(function() {
	$('#tblEmployeeSalaryPayout').on('click', '.btnSalaryPayout', function() {

		activation_url = 'staffDeactivate';
		var salary_id = $(this).closest('tr').children('td#salary_id').text();

		$('#salaryID').val(salary_id);

		var r = confirm("Sure ?");
		if (r == true) {
			$('#salaryFrm').submit();
		}
	});
	
	// Submission
	$("#salaryFrm").validate({
		submitHandler : function(form, event) {

			$('#help-block').remove();
			var formData = $(form).serialize();

			$.ajax({
				type : 'POST',
				url : 'salary_take',
				data : formData,
				dataType : 'json',
				encode : true
			})
			// using the done promise callback
			.done(function(data) {
				console.log(data);
				if (data.error) {
					$('#help-block').remove();
					$('#statusMsg').append('<div class="alert alert-danger" id="help-block">' + data.message + '</div>');
				} else {// Success !
					document.getElementById("salaryFrm").reset();
					$('#help-block').remove();
					$('#statusMsg').append('<div class="alert alert-success" id="help-block">' + data.message + '</div>');
					setTimeout(function() {
						location.reload();
					}, 200);

				}
			}).fail(function(data) {
				$('#btnSubmitstaffInfo').prop('disabled', false);
				console.log(data);
			});
			event.preventDefault();

		}
	});
});
</script>