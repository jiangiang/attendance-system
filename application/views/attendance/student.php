<style>
<!--
.row .form-group {
	margin-bottom: 0px;
}
-->
</style>


<!-- Custom Page JScript -->
<script src="<?php echo base_url(); ?>assets/dist/js/custom_attendance.js" type="text/javascript"></script>

<!-- MODAL FOR CLASS REPLACEMENT -->
<form id="classReplaceFrm" name="classReplaceFrm">
	<input type="hidden" id="action" name="action" value="" />
	<div class="modal fade" role="dialog" aria-labelledby="myModalLabel" id="classReplaceModal">
		<div class="modal-dialog" style="margin-top: 2%;">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">
						<i class="fa fa-exchange fa-lg"></i> <span id="modal_title">Smth is wrong if you see me</span>
					</h4>
				</div>
				<div id="statusMsg" style="padding-bottom: 0px; margin-bottom: 0px"></div>
				<input type="hidden" id="bill_ID" name="bill_ID" value="" /> <input type="hidden" id="id" name="id" value="" /> <input
					type="hidden" name="sessionDate" value="<?php echo $currDate; ?>" /> <input type="hidden" name="sessionTime"
					value="<?php echo $slot_time; ?>" />
				<div class="modal-body">
					<div class="form-group">
						<label>Search Name/ID</label> <input class="form-control" placeholder="Student Name/ID / Passport" id="stdSearch"
							name="stdSearch" autocomplete="off">
						<div id="stdSearchResult"></div>
					</div>
					<div class="col-lg-7" style="padding-left: 0px; padding-right: 2px">
						<div class="form-group">
							<label>Student Name</label> <input class="form-control" placeholder="Student Name" id="stdName" name="stdName"
								readonly="readonly" required="required" autocomplete="off">
						</div>
					</div>
					<div class="col-lg-5" style="padding-left: 0px; padding-right: 2px">
						<div class="form-group">
							<label>Student ID</label> <input class="form-control" placeholder="Student ID" id="stdID" name="stdID"
								readonly="readonly" required="required" autocomplete="off">
						</div>
					</div>
					<div class="col-lg-5" style="padding-left: 0px; padding-right: 2px">
						<div class="form-group">
							<label>Class Left</label> <input class="form-control" placeholder="Class Left." id="stdClassleft"
								name="stdClassleft" readonly="readonly" required="required" autocomplete="off">
						</div>
					</div>
					<div class="col-lg-7" style="padding-left: 0px; padding-right: 2px">
						<div class="form-group">
							<label>Expiry Date</label> <input class="form-control" placeholder="Expiry Date." id="stdExpiry" name="stdExpiry"
								readonly="readonly" required="required" autocomplete="off">
						</div>
					</div>
					<div class="col-lg-5" style="padding-left: 0px; padding-right: 2px">
						<div class="form-group">
							<label>Level</label> <input class="form-control" placeholder="Level." id="stdLevel" name="stdLevel"
								readonly="readonly" required="required" autocomplete="off">
						</div>
					</div>
					<div class="col-lg-7" style="padding-left: 0px; padding-right: 2px">
						<div class="form-group">
							<label>Instructor</label> <input class="form-control" placeholder="Instructor" id="stdInstructor"
								name="stdInstructor" readonly="readonly" required autocomplete="off">
						</div>
					</div>
					<div class="col-lg-12" style="padding-left: 0px; padding-right: 2px">
						<div class="form-group">
							<label>Last log</label> <textarea class="form-control" placeholder="No log" id="stdLog" readonly="readonly" autocomplete="off"></textarea>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary" disabled="disabled" id="btnSubmitReplace">Submit Replacement</button>
				</div>
			</div>
		</div>
	</div>
</form>
<!--form-replacement -->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<section class="content-header">
		<h1>Student Attendance - <?php echo date('Y-m-d l, ', strtotime($currDate)) . date('g:i:s A', strtotime($slot_time)) ?></h1>
	</section>

	<section class="content">
		<form id="slotFrm" name="slotFrm" method="post" >
			<div class="row" style="text-align: center;">
				<div class="col-xs-12">
					<div class="box box-success">
						<div class="box-body">
							<div class="row">
								<div class="col-xs-1">
									<label>Date: </label>
								</div>
								<div class="col-xs-2">
									<div class="form-group">
										<input type="text" id="slot_date" name="slot_date" class="form-control" value="<?php echo $currDate; ?>">
									</div>
								</div>
								<div class="col-xs-1">
									<label>Time: </label>
								</div>
								<div class="col-xs-2">
									<div class="form-group">
										<select id="slot_time" name="slot_time" class="form-control">
											<option selected disabled> </option>
											<?php foreach($slot_time_list as $row){ ?>
											<option value="<?php echo $row['slot_time']?>" <?php
												if ($row['slot_curr'] == 'Y') {echo "selected='selected'";
												}
											?>><?php echo $row['slot_time_12']; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<?php if(count($slot_time)>0){ ?>
								<div class="col-xs-1">
									<div class="btn-group">
										<button type="button" class="btn btn-success btnClassReplace" id="btnClassReplace">
											<i class="fa fa-user-plus fa-lg"></i> Replacement / Overdue
										</button>
									</div>
								</div>
								<!--
								<div class="col-xs-2">
									<div class="btn-group">
										<button type="button" class="btn btn-danger btnClassOverdue" id="btnClassOverdue">
											<i class="fa fa-user-plus fa-lg"></i> Overdue
										</button>
									</div>
								</div>
								-->
								<?php } ?>
								<div class="col-xs-3"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
		<form id="attendaceFrm" name="attendaceFrm">
			<input type="hidden" name="sessionDate" value="<?php echo $currDate; ?>" /> <input type="hidden" name="sessionTime"
				value="<?php echo $slot_time; ?>" />
			<div class="row">
				<div class="col-xs-12">
					<div class="box box-success">
						<!-- /.box-header -->
						<div class="box-body">
							<table class="table table-bordered table-hover" id="tblActiveStudent">
								<thead>
									<tr>
										<th>ID</th>
										<th>Name</th>
										<th>Day</th>
										<th>Time</th>
										<th>Lesson Left</th>
										<th>Expired in</th>
										<th>Level</th>
										<th>Instructor</th>
										<th>Last Log</th>
										<th>Attend</th>
									</tr>
								</thead>
								<tbody>
								<?php foreach($students_attendance as $row){ ?>
								<tr>
										<td id="student_id"><?php echo $row['id']?></td>
										<td id="student_name"><?php echo $row['std_name']?></td>
										<td><?php echo date('l', strtotime("Sunday +". $row['slot_day']." Days"));?></td>
										<td><?php echo $row['slot_time']?></td>
										<td><?php echo $row['lesson_left']?></td>
										<td><?php echo $row['expiry_date']?></td>
										<td><?php echo $row['level_name']?></td>
										<td><?php echo $row['instructor']?></td>
										<td><?php echo $row['log']?></td>
										<td>
										<?php if($row['attendance_status'] == 'N'){ ?>
										<input type="checkbox" name="std_attend[]" value="<?php echo $row['attendance_id'] ?>">
										<?php }else if($row['attendance_status'] == 'Y'){ ?>
										Taken
										<?php }else if($row['replacement'] == 'Y'){ ?>
										<span style="color: blue">Replacement</span> 									
										<?php
										}else{ ?>
										Something Is Wrong.
										<?php } ?>
									</td>
									</tr>
								<?php } ?>
								
								
								
								<tbody>
							
							</table>
						</div>
						<div class="box-footer">
							<div class="form-group" style="text-align: right;">
                		<?php if(!empty($students_attendance)){ ?>
                    	<button type="submit" class="btn btn-block btn-primary" id="btnUpdateAttendance">Update Attendance</button>
                		<?php }else{ ?>
                    	<button type="button" class="btn btn-block" disabled="disabled">No Student</button>
                		<?php } ?>
                		</div>
						</div>
						<!-- /.box-body -->
					</div>
					<!-- /.box -->
				</div>
			</div>
		</form>
	</section>
</div>
