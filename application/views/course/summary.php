
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
<script src="<?php echo base_url();?>assets/dist/js/custom_course.js" type="text/javascript"></script>

<!-- course activate/deactivation -->
<form id="courseActivationFrm" name="courseActivationFrm">
	<input type="hidden" id="activationID" name="activationID">
</form>

<form id="courseInfoFrm" name="courseInfoFrm" autocomplete="off">
	<input type="hidden" id="courseID" name="courseID" value="" />
	<div class="modal fade" role="dialog" aria-labelledby="myModalLabel" id="courseInfoModal">
		<div class="modal-dialog" style="margin-top: 2%;">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">
						<i class="fa fa-user-plus fa-lg"></i><span id="courseInfoModalTitle">If you see me smth is wrong yo</span>
					</h4>
				</div>
				<div id="statusMsg" style="padding-bottom: 0px; margin-bottom: 0px"></div>

				<div class="col-lg-6" style="padding-right: 0px">
					<div class="modal-body">
						<div class="form-group">
							<label for="venue">Venue</label>
							<select class="form-control" id="lessonVenue" name="lessonVenue">
								<?php foreach ($venue_code_rows as $row){ ?>
								<option value="<?php echo $row['venue_id'];?>" <?php if($row['default_place']=='Y'){ ?> selected="selected"<?php } ?>>
									<?php echo $row['venue_name'];?>
								</option>
								<?php } ?>
							</select>
						</div>
						<div class="form-group">
							<label>Type</label>
							<select class="form-control" id="courseLevel" name="courseLevel" required="required">
								<?php foreach($get_course_type as $row){ ?>
								<option value="<?php echo $row['level_id'];?>"><?php echo $row['level_name']?></option>
								<?php } ?>
							</select>
						</div>
						<div class="form-group">
								<label>Capacity</label>
								<input class="form-control" placeholder="Capacity" id="courseCapacity" name="courseCapacity" required="required">
							</div>	
					</div>
				</div>
				<div class="col-lg-6" style="padding-left: 2px">
					<div class="modal-body">
						<div class="form-group">
							<label>Day</label>
							<select class="form-control" id="courseDay" name="courseDay" required="required">
								<option value="NULL">Pre Comp/ Competitive</option>
								<?php for($i=1; $i<=7; $i++){ ?>
								<option value="<?php echo $i?>"><?php echo date('l', strtotime("Sunday + $i Days")); ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="col-lg-6" style="padding-left: 0px; padding-right: 2px">
							<div class="form-group">
								<label>Hour</label>
								<select class="form-control" id="courseTimeHour" name="courseTimeHour" required="required">
								<?php for($i = 7; $i < 21; $i ++ ){ ?>
								<?php
									if ($i == 12) {
										$H = $i;
										$A = 'PM';
									} else if ($i > 12) {
										$H = $i - 12;
										$A = 'PM';
									} else if ($i < 12) {
										$H = $i;
										$A = 'AM';
									}
									?>
									<option value="<?php echo $i ?>"><?php echo $H." ".$A; ?></option>
								<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-lg-6" style="padding-left: 0px; padding-right: 2px">
							<div class="form-group">
								<label>Minute</label>
								<select class="form-control" id="courseTimeMinute" name="courseTimeMinute" required="required">
									<option value="00">00</option>
									<option value="30">30</option>
								</select>
							</div>
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
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary" id="btnSubmitCourseInfo" disabled="disabled">Update!</button>
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
			<div class="col-md-2"></div>
			<div class="col-md-7">
				<div class="box">
			        <div class="box-body" style="text-align: center">
				 		<div class="btn-group">
							<a href="1"><button type="button" class="btn btn-<?php if($day_selected==1){echo "success";}else{echo "default";}?>">Monday</button></a>
							<a href="2"><button type="button" class="btn btn-<?php if($day_selected==2){echo "success";}else{echo "default";}?>">Tuesday</button></a>
							<a href="3"><button type="button" class="btn btn-<?php if($day_selected==3){echo "success";}else{echo "default";}?>">Wednesday</button></a>
							<a href="4"><button type="button" class="btn btn-<?php if($day_selected==4){echo "success";}else{echo "default";}?>">Thursday</button></a>
							<a href="5"><button type="button" class="btn btn-<?php if($day_selected==5){echo "success";}else{echo "default";}?>">Friday</button></a>
							<a href="6"><button type="button" class="btn btn-<?php if($day_selected==6){echo "success";}else{echo "default";}?>">Saturday</button></a>
							<a href="7"><button type="button" class="btn btn-<?php if($day_selected==7){echo "success";}else{echo "default";}?>">Sunday</button></a>
							<a>
								<button type="button" class="btn btn-danger btncourseNew" id="btncourseNew">
									<i class="fa fa-user-plus fa-lg"></i> Add New Course
								</button>
							</a>
	                    </div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-success">
					<div class="box-header">
						<h3 class="box-title">Active Courses</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<table class="table table-bordered table-hover" id="tblActiveCourse">
							<thead>
								<tr>
									<th>ID</th>
									<th>Slot Day</th>
									<th>Slot Time</th>
									<th>Level</th>
									<th>Instuctor</th>
									<th>Capacity</th>
									<th>Venue</th>
									<th>Action</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($course_active_rows as $row){?>
								<?php
									$temp_slot_day = $row ['slot_day'];
									if (is_null ( $temp_slot_day ))
										$dayStr = "Competitive";
									else
										$dayStr = date('l', strtotime("Sunday + $temp_slot_day Days"));
									?>
								<tr>
									<td id="course_id"><?php echo $row['id']; ?></td>
									<td><?php echo $dayStr; ?></td>
									<td><?php echo date('h:i A', strtotime($row['slot_time'])); ?></td>
									<td><?php echo $row['level_name']; ?></td>
									<td><?php echo $row['instuctor']; ?></td>
									<td><?php echo $row['capacity']; ?></td>
									<td><?php echo $row['venue_name']; ?></td>
									<td><span class="label label-success">Active</span></td>
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

<script>
 $(function () {
        $("#tblActiveCourse").dataTable({
            "aaSorting": [],
            "bSortClasses": false,
            "iDisplayLength": 50
        });
      });
</script>