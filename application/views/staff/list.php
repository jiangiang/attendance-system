
<!-- DATA TABLES CSS-->
<link href="<?php echo base_url();?>assets/plugins/datatables/css/dataTables.bootstrap.css" rel="stylesheet"
	type="text/css" />

<!-- dataTables JS -->
<script src="<?php echo base_url();?>assets/plugins/datatables/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/plugins/datatables/js/dataTables.bootstrap.js" type="text/javascript"></script>


<!-- InputMask -->
<script src="<?php echo base_url();?>assets/plugins/input-mask/inputmask.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/plugins/input-mask/inputmask.extensions.js" type="text/javascript"></script>

<!-- Custom Page JScript -->
<script src="<?php echo base_url();?>assets/dist/js/custom_staff.js" type="text/javascript"></script>

<!-- staff activate/deactivation -->
<form id="staffActivationFrm" name="staffActivationFrm">
	<input type="hidden" id="activationID" name="activationID">
	<input type="hidden" id="activationName" name="activationName">
</form>

<?php $this -> view('Modals/StaffRegistrationForm'); ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<section class="content-header">
		<h1>Staff Management</h1>
	</section>

	<section class="content">

		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-body">
						<table class="table table-bordered table-hover" id="tblActiveStaff">
							<thead>
								<tr>
									<th>ID</th>
									<th>Name</th>
									<th>Short Name</th>
									<th>IC/Passport</th>
									<th>Contact</th>
									<th>Email</th>
									<th>Type</th>
									<th>Suggest Salary</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="8"></td>
									<td>
										<button type="button" class="btn btn-danger btnstaffNew" id="btnstaffNew">
											<i class="fa fa-user-plus fa-lg"></i> New staff
										</button>
									</td>
								</tr>
								<?php foreach ($staff_active_rows as $row){?>
								<tr>
									<td id="staff_id"><?php echo $row['id']; ?></td>
									<td id="staff_name"><?php echo $row['name']; ?></td>
									<td><?php echo $row['short_name']; ?></td>
									<td><?php echo $row['identity']; ?></td>
									<td><?php echo $row['contact']; ?></td>
									<td><?php echo $row['email']; ?></td>
									<td><?php echo $row['type_name']; ?></td>
									<td><?php echo $row['suggest_salary']; ?></td>
									<td><a href="#" class="staffDeactivate"><i class="fa fa-trash fa-2x"></i></a> &nbsp; <a href="#"
										id="btnStaffUpdate" class="btnStaffUpdate"><i class="fa fa-pencil-square-o fa-2x"></i></a></td>
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

		<!-- Show Inactive staff -->
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title">InActive staff</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<table class="table table-bordered table-hover" id="tblInactiveStaff">
							<thead>
								<tr>
									<th>ID</th>
									<th>Name</th>
									<th>IC/Passport</th>
									<th>Contact</th>
									<th>Email</th>
									<th>Status</th>
									<th>Type</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($staff_inactive_rows as $row){?>
								<tr>
									<td id="staff_id"><?php echo $row['id']; ?></td>
									<td id="staff_name"><?php echo $row['name']; ?></td>
									<td><?php echo $row['identity']; ?></td>
									<td><?php echo $row['contact']; ?></td>
									<td><?php echo $row['email']; ?></td>
									<td><?php echo $row['type_name']; ?></td>
									<td><span class="label label-danger">InActive</span></td>
									<td><a href="#" class="staffActivate"><i class="fa fa-toggle-on fa-2x"></i></a></td>
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

<script>
 $(function () {
        $("#tblInactivestaff").dataTable();
        $("#tblActivestaff").dataTable();
        $("#tblOutputBanker").dataTable();
      });
</script>