<style>
<!--
.row .form-group {
	margin-bottom: 0px;
}
-->
</style>

<!-- Custom Page JScript -->
<script src="<?php echo base_url(); ?>assets/dist/js/custom_student_log.js" type="text/javascript"></script>

<form id="activationForm" name="activationForm" >
	<input type="hidden" id="activationID" name="activationID">
</form>

<form id="studentLogFrm" name="studentLogFrm">
	<input type="hidden" id="action" name="action" value="" />
	<div class="modal fade" role="dialog" aria-labelledby="myModalLabel" id="modal_student_log">
		<div class="modal-dialog" style="margin-top: 2%;">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">
						<i class="fa fa-exchange fa-lg"></i> <span id="modal_title">Smth is wrong if you see me</span>
					</h4>
				</div>
				<div id="statusMsg" style="padding-bottom: 0px; margin-bottom: 0px"></div>
				<input type="hidden" id="student_id" name="student_id" value="" /> 
				
				<div class="modal-body">
					<div class="col-lg-12" style="padding-left: 0px; padding-right: 2px">
						<div class="form-group">
							<label>Search Name/ID</label> <input class="form-control" placeholder="Student Name/ID / Passport" id="stdSearch"
								name="stdSearch" autocomplete="off">
							<div id="stdSearchResult"></div>
						</div>
					</div>
					<div class="col-lg-12" style="padding-left: 0px; padding-right: 2px">
						<div class="form-group">
							<label>Student Name</label> <input class="form-control" placeholder="Student Name" id="stdName" name="stdName"
								readonly="readonly" autocomplete="off">
						</div>
					</div>
					
					<div class="col-lg-12" style="padding-left: 0px; padding-right: 2px">
						<div class="form-group">
							<textarea maxlength="300" required class="form-control" name = "log" id="log" placeholder="Max 300 Character" ></textarea>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary" id="btnSubmit">Submit Replacement</button>
				</div>
			</div>
		</div>
	</div>
</form>
<!--form-replacement -->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<section class="content-header">
		<h1>Student Log</h1>
	</section>

	<section class="content">
		<div class="row">
			<form method="post">
				<div class="col-md-12 col-xs-12">
					<div class="box">
				        <div class="box-header">
							<div class="col-md-3 col-xs-8" style="padding-left:2px;">
								<div class="form-group">
									<label class="sr-only">Name:</label>
									<input class="form-control" placeholder="Search by Name" id="searchName" name="searchName" value="<?php echo $name?>">
								</div>
							</div>
							<div class="col-md-3 col-xs-4" style="padding-left:2px;">
								<div class="form-group">
									<div class="btn-group">
										<button type="submit" id="search" class="btn btn-primary">Search</button>
									</div>
									<div class="btn-group">
										<button type="button" class="btn btn-success" id="btnNewStudentLog">
											<i class="fa fa-user-plus fa-lg"></i> New Log
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
					<!-- /.box-header -->
					<div class="box-body">
						<table class="table table-bordered table-hover" id="tbl_student_log">
							<thead>
								<tr>
									<th style="width:5%">Log ID</th>
									<th style="width:10%">Name</th>
									<th style="width:50%">Log</th>
									<th style="width:10%">Enter by</th>
									<th style="width:15%">Entry Date</th>
									<th style="width:10%">Action</th>
								</tr>
							</thead>
							<tbody>
							<?php foreach($student_logs as $row){ ?>
							<tr>
									<td class="log_id"><?php echo $row['log_id']?></td>
									<td><?php echo $row['student_name']?></td>
									<td><?php echo $row['log']?></td>
									<td><?php echo $row['staff_name']?></td>
									<td><?php echo date('Y-m-d H:i:s', $row['timestamp'])?></td>
									<td>
										<a href="#" class="deactivate_log"><i class="fa fa-trash fa-lg"></i></a>
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
