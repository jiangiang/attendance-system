<!-- Custom Page JScript -->
<script src="<?php echo base_url();?>assets/dist/js/custom_course.js" type="text/javascript"></script>

<!-- course activate/deactivation -->
<form id="courseActivationFrm" name="courseActivationFrm">
	<input type="hidden" id="activationID" name="activationID">
</form>

<form id="courseInfoFrm" name="courseInfoFrm" autocomplete="off">
	<input type="hidden" id="courseID" name="courseID" value="" />
	<div class="modal fade" role="dialog" aria-labelledby="myModalLabel" id="courseInfoModal">
		<div class="modal-dialog modal-sm" style="margin-top: 2%;">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">
						<span id="courseInfoModalTitle">If you see me smth is wrong yo</span>
					</h4>
				</div>
				<div id="statusMsg" style="padding-bottom: 0px; margin-bottom: 0px"></div>

			
					<div class="modal-body">
						<div class="form-group">
							<label for="venue">Venue</label>
							<select class="form-control" id="lessonVenue" name="lessonVenue">
								<?php foreach ($venue_list as $row){ ?>
								<option value="<?php echo $row['venue_id'];?>" <?php if($row['default_place']=='Y'){ ?> selected="selected"<?php } ?>>
									<?php echo $row['venue_name'];?>
								</option>
								<?php } ?>
							</select>
						</div>
						<div class="form-group">
							<label>Insturctor</label>
							<select class="form-control" id="courseInstructor" name="courseInstructor" required="required">
								<option value="" selected disabled>Please select an option...</option>
								<?php foreach($instructor_list as $row){ ?>
								<option value="<?php echo $row['id'];?>"><?php echo $row['name']?></option>
								<?php } ?>
							</select>
						</div>
                        <div class="form-group">
							<label>Type</label>
							<select class="form-control" id="courseLevel" name="courseLevel" required="required">
								<option value="" disabled selected>Select a Course</option>
								<?php foreach($course_level_list as $row){ ?>
								<option value="<?php echo $row['level_id'];?>"><?php echo $row['level_name']?></option>
								<?php } ?>
							</select>
						</div>
					</div>
				
				
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary" id="btnSubmitCourseInfo">Update!</button>
				</div>
			</div>
		</div>
	</div>
</form>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<section class="content-header">
		<h1>Course Management</h1>
	</section>

	<section class="content">
		<div class="row">
			<div class="col-lg-12 col-sm-12 col-xs-12">
				<div class="box box-success">
					<div class="box-header">
                        <button type="button" class="btn btn-danger btncourseNew" id="btncourseNew">Add New Course</button>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<table class="table table-bordered table-hover" id="tblActiveCourse">
							<thead>
								<tr>
									<th>ID</th>
                                    <th>Level</th>
									<th>Instructor</th>
									<th>Duration</th>
									<th>Max Capacity</th>
									<th>Venue</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($course_list as $row){?>
									<td id="course_id"><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['level_name']; ?></td>
									<td><?php echo $row['instuctor_name']; ?></td>
									<td><?php echo $row['duration_minute']." mins"; ?></td>
									<td><?php echo $row['max_capacity']; ?></td>
									<td><?php echo $row['venue_name']; ?></td>	
									<td><a href="#" class="courseDeactivate"><i class="fa fa-trash fa-2x"></i></a> &nbsp; <a href="#"
										id="btnCourseUpdate" class="btnCourseUpdate"><i class="fa fa-pencil-square-o fa-2x"></i></a></td>
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
