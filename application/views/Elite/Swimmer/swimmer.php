<!-- InputMask -->
<script src="<?php echo base_url();?>assets/plugins/input-mask/inputmask.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/plugins/input-mask/inputmask.extensions.js" type="text/javascript"></script>

<!-- Custom Page JScript -->
<script src="<?php echo base_url();?>assets/dist/js/Elite/swimmer.js" type="text/javascript"></script>
<style>
	.box-header{
		padding :2px;
	}
</style>

<!-- Student activate/deactivation -->
<form id="SwimmerActivationForm" name="SwimmerActivationForm">
	<input type="hidden" id="TargetID" name="TargetID">
</form>

<?php $this -> view('Modals/Elite/SwimmerCreate'); ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<section class="content-header">
		<h1>Student Management</h1>
	</section>

	<section class="content">
		<!-- Start Search bar -->
		<div class="row">
			<form method="post">
				<div class="col-md-12">
					<div class="box">
				        <div class="box-header">
							<div class="col-md-3" style="padding-left:2px;">
								<div class = "input-group">
									<span class = "input-group-addon"><label>Name</label></span>
									<input class="form-control" placeholder="Search by Name" id="searchName" name="searchName" value="<?php echo $name?>">
								</div>
							</div>
							<div class="col-md-3" style="padding-left:2px;">
								<div class="form-group" >
									<div class="btn-group">
										<button type="submit" id="search" class="btn btn-primary">Search</button>
									</div>
									<div class="btn-group">
										<button type="button" class="btn btn-warning" id="btn-Export">
											<i class="fa fa-cloud-download fa-lg"></i> Download
										</button>
									</div>
									<div class="btn-group">
										<button type="button" class="btn btn-danger" id="btn-Create">
											<i class="fa fa-user-plus fa-lg"></i> New Student
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
		<!-- End of Search bar -->

		<!-- Start of main list -->
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-success">
					<div class="box-header">
						<h3 class="box-title">Active Student</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<table class="table table-bordered table-hover dt-responsive" id="tbl-MainTable">
							<thead>
								<tr>
									<th>ID</th>
									<th>Action</th>
									<th>Name</th>
									<th>Gender</th>
									<th>Identity</th>
									<th>DOB</th>
									<th>Nationality</th>
									<th>Phone</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($main_list as $row){?>
								<tr>
									<td id="row_TargetID"><?php echo $row['swimmer_id']; ?></td>
									<td>
										<a href="#" class="btn-deactivate"><i class="fa fa-trash fa-lg"></i></a>&nbsp;
										<a href="#" class="btn-update"><i class="fa fa-pencil-square-o fa-lg"></i></a>&nbsp;
										<a href="#" class="btn-details"><i class="fa fa-info-circle fa-lg"></i></a>
									</td>
									<td id="student_name"><?php echo $row['name']; ?></td>
									<td><?php echo $row['gender']; ?></td>
									<td><?php echo $row['identity']; ?></td>
									<td><?php echo $row['dob']; ?></td>
									<td><?php echo $row['nationality']; ?></td>
									<td><?php echo $row['phone']; ?></td>
									<td><span class="label label-success">Active</span></td>
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
		<!-- End of main list -->
	</section>
</div>