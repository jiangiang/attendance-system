<style>
<!--
.row .form-group {
	margin-bottom: 0px;
}
-->
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Student Attendance Dashboard - <?php echo date('F', strtotime("December +". $selectedMonth." Months"))." ". $selectedYear?> - <?php echo $selectedSlot;?>
		</h1>
	</section>

	<section class="content">
		<form id="slotFrm" name="slotFrm" method="post" >
			<div class="row">
				<div class="col-xs-12">
					<div class="box box-success">
						<div class="box-body">
							<div class="row">
								<div class="col-xs-2">
									<div class="form-group">
										<label>Year</label>
										<select id="searchYear" name="searchYear" class="form-control">
											<option selected="selected" disabled="disabled"> </option>
											<?php foreach($getYear as $year){ ?>
											<option value="<?php echo $year['year']?>" <?php if ($year['year'] == $selectedYear) {echo "selected='selected'";}?>>
												<?php echo $year['year'] ?>
											</option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="col-xs-2">
									<div class="form-group">
										<label>Month</label>
										<select id="searchMonth" name="searchMonth" class="form-control">
											<option selected="selected" disabled="disabled"> </option>
											<?php foreach($getMonth as $month){ ?>
											<option value="<?php echo $month['month']?>" <?php if ($month['month'] == $selectedMonth) {echo "selected='selected'";}?>>
												<?php echo date('F', strtotime("December +". $month['month']." Months"));?>
											</option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="col-xs-2">
									<div class="form-group">
										<label>Day</label>
										<select id="searchDay" name="searchDay" class="form-control">
											<option selected="selected" disabled="disabled"> </option>
											<?php for($i=1; $i<=7; $i++){ ?>
											<option value="<?php echo $i;?>" <?php if ($i == $selectedDay) {echo "selected='selected'";}?>>
												<?php echo date('l', strtotime("Sunday +". $i." Days")); ?>
											</option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="col-xs-2">
									<div class="form-group">
										<label>Time: </label>
										<select id="slot_time" name="slot_time" class="form-control">
											<option selected="selected" disabled="disabled"> </option>
											<?php foreach($list_slot_time as $row){ ?>
											<option value="<?php echo $row['slot_time']?>" <?php
												if ($row['slot_time'] == $selectedSlot) {echo "selected='selected'";}
											?>><?php echo $row['slot_time']; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="col-xs-4">
									<div class="form-group" style="padding-top: 20px;">
										<button type="submit" class="btn btn-primary" id="btnSearch">
											Search
										</button>
									</div>
								</div>
								<div class="col-xs-3"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
		
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-success">
					<!-- /.box-header -->
					<div class="box-header">
						<h3 class="box-title"><?php echo date('F', strtotime("December +". $selectedMonth." Months"));?></h3>
					</div>
					<div class="box-body">
						<table class="table table-bordered table-hover table-striped" id="tblActiveStudent">
							<thead>
								<tr>
									<th>Name</th>
									<?php foreach ($dates as $date) { ?>
									<th><?php echo $date -> format("Y-m-d");?></th>
									<?php } ?>
									<th>Replacement</th>
									<th>Lastest Payment</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($students_attendance_list as $list){ ?>
								<tr>
									<td><?php echo $list['student_name']?></td>
									
									<?php $i = 0;?>
									<?php foreach ($dates as $date) { ?>
										<?php $replacement_date = array(); ?>
									<td>
										
										<?php foreach($list['attendance_date'] as $att_date){ ?>
											
											<?php if($date -> format("Y-m-d") == $att_date && $list['attendance_status'][$i] ==  'Y'){ ?>
													<?php echo $att_date . " <i class=\"fa fa-check-circle fa-lg text-green\"></i>"?>
													
											<?php }  ?>
										<?php } ?>
									</td>
									<?php }$i++; ?>
									<td>
										<?php $j = 0; ?>
										
										<?php foreach($list['attendance_date'] as $att_date){ ?>
											<?php if($list['replacement'][$j] == 'Y' && $list['attendance_status'][$j] ==  'Y'){ ?>
												<?php echo $att_date."\n"?>
											<?php } $j++; ?>
										<?php } ?>
									</td>
									<td><span class="label label-primary"><?php echo $list["latest_payment_date"]?></span></td>
								</tr>
								<?php } ?>
								
									
								
							</tbody>
						
						</table>
					</div>
					
					<!-- /.box-body -->
				</div>
				<!-- /.box -->
			</div>
		</div>
	</section>
</div>
