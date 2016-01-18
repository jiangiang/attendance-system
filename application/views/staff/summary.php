
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

<form id="staffInfoFrm" name="staffInfoFrm" autocomplete="off">
	<input type="hidden" id="staffID" name="staffID" value="" />
	<div class="modal fade" role="dialog" aria-labelledby="myModalLabel" id="staffInfoModal">
		<div class="modal-dialog" style="margin-top: 2%;">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">
						<i class="fa fa-user-plus fa-lg"></i><span id="staffInfoModalTitle">If you see me smth is wrong yo</span>
					</h4>
				</div>
				<div id="statusMsg" style="padding-bottom: 0px; margin-bottom: 0px"></div>

				<div class="col-lg-6 col-xs-6" style="padding-right: 0px">
					<div class="modal-body">
						<div class="form-group">
							<label>Name</label>
							<input class="form-control" placeholder="staff Full Name" id="staffName" name="staffName" required="required">
						</div>
						<div class="form-group">
							<label>Short Name</label>
							<input class="form-control" placeholder="Staff Short Name, Max 8 Character" id="staffShortName" name="staffShortName" required="required" maxlength="8">
						</div>
						<div class="form-group">
							<label>IC/ Passport</label>
							<input class="form-control" placeholder="IC/ Passport Number" id="staffIdentity" name="staffIdentity" required="required">
						</div>
						<div class="col-lg-7 col-xs-7" style="padding-left: 0px; padding-right: 2px">
							<div class="form-group">
								<label>Contact No.</label>
								<input class="form-control" placeholder="Phone Number" id="staffContact" name="staffContact" required="required"
									data-inputmask='"mask": "999-9999999[9]"' data-mask>
							</div>
						</div>
						<div class="col-lg-5 col-xs-5" style="padding-left: 0px; padding-right: 2px">
							<div class="form-group">
								<label>Gender</label>
								<select class="form-control" id="staffGender" name="staffGender" required="required">
									<option value="m">Male</option>
									<option value="f">Female</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label>Email</label>
							<input class="form-control" placeholder="Email" id="staffEmail" name="staffEmail"
								data-inputmask="'alias': 'email'" data-mask>
						</div>
						<div class="form-group">
							<label>System Login Name</label>
							<input class="form-control" placeholder="Login Name" id="loginName" name="loginName">
						</div>
					</div>
				</div>
				<div class="col-lg-6 col-xs-6" style="padding-left: 2px">
					<div class="modal-body">
						<div class="form-group">
							<label>Address</label>
							<input class="form-control" placeholder="Address line 1" id="staffAddr1" name="staffAddr1">
							<input class="form-control" placeholder="Address line 2" id="staffAddr2" name="staffAddr2">
							<div class="col-lg-6 col-xs-6" style="padding-left: 0px; padding-right: 2px">
								<input class="form-control" placeholder="PostCode" id="Postcode" name="Postcode">
							</div>
							<div class="col-lg-6 col-xs-6" style="padding-left: 0px; padding-right: 0px">
								<input class="form-control" placeholder="City" id="City" name="City">
							</div>
							<input class="form-control" placeholder="State" id="State" name="State" value="Johor">
							<input class="form-control" placeholder="Country" id="Country" name="Country" value="Malaysia">
						</div>
						<div class="form-group" style="padding-bottom: 0px">
							<label>Staff Type</label>
							<select class="form-control" id="staffType" name="staffType">
								<?php foreach($get_staff_type as $row){ ?>
								<option value="<?php echo $row['type_id'];?>" <?php if($row['default']=='Y'){echo "SELECTED";}?>><?php echo $row['type_name']?></option>
								<?php } ?>
							</select>
						</div>
						<div class="form-group">
							<label>System Login Password</label>
							<input type="password" class="form-control" placeholder="Login Password" id="loginPassword" name="loginPassword">
							<input type="password" class="form-control" placeholder="Confirm your password" id="loginPassword2" name="loginPassword2">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary" id="btnSubmitstaffInfo">Update!</button>
				</div>
			</div>
		</div>
	</div>
</form>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<section class="content-header">
		<h1>Staff Management</h1>
	</section>

	<section class="content">
		<div class="row">
			<div class="col-xs-12" style="padding-bottom: 5px;">
				<div class="btn-group">
					<button type="button" class="btn btn-danger btnstaffNew" id="btnstaffNew">
						<i class="fa fa-user-plus fa-lg"></i> New staff
					</button>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-success">
					<div class="box-header">
						<h3 class="box-title">Active staff</h3>
					</div>
					<!-- /.box-header -->
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