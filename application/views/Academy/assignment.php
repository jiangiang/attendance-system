<!-- Custom Page JScript -->
<script src="<?php echo base_url();?>assets/dist/js/Academy/assignment.js" type="text/javascript"></script>

<!-- classes activate/deactivation -->
<form id="AssignmentActivationForm" name="AssignmentActivationForm">
	<input type="hidden" id="TargetID" name="TargetID">
</form>

<?php $this -> view('Academy/Modals/ClassAssignmentCreateForm'); ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<section class="content-header">
		<h1><?php echo $title; ?></h1>
	</section>
	<section class="content">

		<div class="row">
			<form method="post">
				<div class="col-md-12 col-xs-12">
					<div class="box">
						<div class="box-header">
							<div class="col-md-2 col-xs-2" style="padding-left:2px;">
								<div class = "input-group">
									<span class = "input-group-addon"><label>Venue</label></span>
									<select class="form-control" id="venue" name="venue">
										<option value = "" selected> ALL </option>
										<?php foreach($venue_list as $row){ ?>
											<option value="<?php echo $row['venue_id']?>" <?php if($row['venue_id'] == $venue){echo "SELECTED";}?>><?php echo $row['venue_name'];?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="col-md-3 col-xs-3" style="padding-left:2px;">
								<div class = "input-group">
									<span class = "input-group-addon"><label>Name</label></span>
									<input class="form-control" placeholder="Search by Name" id="searchName" name="searchName" value="<?php echo $name?>">
								</div>
							</div>
							<div class="col-md-3 col-xs-5" style="padding-left:2px;">
								<div class="form-group">
									<div class="btn-group">
										<button type="submit" id="search" class="btn btn-primary">Search</button>

										<button type="button" class="btn btn-danger" id="btn-AssignmentCreate">Add New classes</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>

		<div class="row">
			<div class="col-lg-12 col-sm-12 col-xs-12">
				<div class="box box-success">
					<div class="box-header">

					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
							<?php foreach ($assignment_list as $assignment){?>
								<div class="panel panel-default">
									<div class="panel-heading" role="tab" id="headingOne">
										<h4 class="panel-title">
											<a role="button" data-toggle="collapse" data-parent="#accordion" href="#<?php echo $assignment['employee_id']?>" aria-expanded="true" aria-controls="collapseOne">
												<?php echo $assignment['employee_name']?>
											</a>
										</h4>
									</div>
									<div id="<?php echo $assignment['employee_id']?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
										<div class="panel-body">
											<table class="table table-bordered table-hover" id="tbl-Active">
												<thead>
												<tr>
													<th>classes_id</th>
													<th>Level</th>
													<th>Duration</th>
													<th>Max Capacity</th>
													<th>Venue</th>
													<th>Multi Instructor Allow</th>
													<th>Action</th>
												</tr>
												</thead>
												<tbody>
												<?php foreach ($assignment['class'] as $row){?>
													<tr>
														<td id="AssignmentID"><?php echo $row['id']; ?></td>
														<td><?php echo $row['level_name']; ?></td>
														<td><?php echo $row['duration_minute']." mins"; ?></td>
														<td><?php echo $row['max_capacity']; ?></td>
														<td><?php echo $row['venue_name']; ?></td>
														<td><?php echo $row['allow_multi_instructor']; ?></td>
														<td>
															<a href="#" class="AssignmentDeactivate">
																<i class="fa fa-trash fa-2x"></i>
															</a>
														</td>
													</tr>
												<?php }?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							<?php } ?>
						</div>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->
			</div>
		</div>

	</section>
</div>
