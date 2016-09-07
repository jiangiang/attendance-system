
<!-- InputMask -->
<script src="<?php echo base_url();?>assets/plugins/input-mask/inputmask.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/plugins/input-mask/inputmask.extensions.js" type="text/javascript"></script>

<!-- Custom Page JScript -->
<script src="<?php echo base_url();?>assets/dist/js/custom_course_venue.js" type="text/javascript"></script>

<!-- course activate/deactivation -->
<?php $allowDelete = false;?>
<?php if($allowDelete == true){?>
<form id="categoryActivationFrm" name="courseActivationFrm">
	<input type="hidden" id="activationID" name="activationID">
</form>
<?php } ?>

<form id="venueInfoFrm" name="venueInfoFrm" autocomplete="off">
	<input type="hidden" id="venueID" name="venueID" value="" />
	<div class="modal fade" role="dialog" aria-labelledby="myModalLabel" id="venueInfoModal">
		<div class="modal-dialog" style="margin-top: 2%;">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">
						<i class="fa fa-user-plus fa-lg"></i><span id="modalTitle">If you see me smth is wrong yo</span>
					</h4>
				</div>
				<div id="statusMsg" style="padding-bottom: 0px; margin-bottom: 0px"></div>

				<div class="col-lg-12" style="padding-right: 0px">
					<div class="modal-body">
						<div class="form-group">
							<label>Venue Name</label>
							<input class="form-control" placeholder="Venue Name" id="venueName" name="venueName" required="required">
						</div>	
						<div class="form-group">
							<label>Building</label>
							<input class="form-control" placeholder="Venue building" id="building" name="building" required="required">
						</div>
						<div class="form-group">
							<label>Street</label>
							<input class="form-control" placeholder="street" id="street" name="street" required="required">
						</div>
						<div class="form-group">
							<label>Postkod</label>
							<input class="form-control" placeholder="postkod" id="postkod" name="postkod" required="required">
						</div>
						<div class="form-group">
							<label>City</label>
							<input class="form-control" placeholder="City" id="city" name="city" required="required">
						</div>
						<div class="form-group">
							<label>State</label>
							<input class="form-control" placeholder="State" id="state" name="state" required="required">
						</div>
						<div class="form-group">
							<label>Country</label>
							<input class="form-control" placeholder="Country" id="country" name="country" required="required" readonly="readonly" value="Malaysia">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary" id="btnSubmit">Update!</button>
				</div>
			</div>
		</div>
	</div>
</form>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<section class="content-header">
		<h1><?php echo $title; ?></h1>
	</section>

	<section class="content">		
		<div class="row">
			<div class="col-xs-12">
				<button type="button" class="btn btn-danger btnNew" id="btnNew">
					<i class="fa fa-user-plus fa-lg"></i> Add New Venue
				</button>
			</div>	
			<div class="col-xs-12">
				<div class="box box-success">
					<div class="box-body">
						<table class="table table-bordered table-hover" id="tblActiveCategory" style="text-align: center">
							<thead>
								<tr>
									<th style="width: 5%; text-align: center">ID</th>
									<th style="width: 15%; text-align: center">Venue Name</th>
									<th style="width: 75%; text-align: center">Venue Address</th>
									<th style="width: 5%; text-align: center">Action</th>
							</thead>
							<tbody>
								<?php foreach ($result_rows as $row){?>
								<tr>
									<td id="venue_id"><?php echo $row['venue_id']; ?></td>
									<td><?php echo $row['venue_name']; ?></td>
									<td><?php echo $row['venue_building'].",".$row['venue_street'].",".$row['venue_postkod'].",".$row['city'].",".$row['state'].",".$row['country']; ?></td>
									<td>
										<!--<a href="#" class="categoryDeactivate"><i class="fa fa-trash fa-2x"></i></a> &nbsp;-->
										<a href="#" id="btnUpdate" class="btnUpdate">
											<i class="fa fa-pencil-square-o fa-2x"></i>
										</a>
									</td>
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
