<!-- DATA TABLES CSS-->
<link href="<?php echo base_url();?>assets/plugins/datatables/css/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url();?>assets/plugins/datatables/css/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />

<!-- dataTables JS -->
<script src="<?php echo base_url();?>assets/plugins/datatables/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/plugins/datatables/js/dataTables.bootstrap.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/plugins/datatables/js/dataTables.responsive.min.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/plugins/datatables/js/responsive.bootstrap.min.js" type="text/javascript"></script>


<!-- InputMask -->
<script src="<?php echo base_url();?>assets/plugins/input-mask/inputmask.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/plugins/input-mask/inputmask.extensions.js" type="text/javascript"></script>

<!-- Custom Page JScript -->
<script src="<?php echo base_url();?>assets/dist/js/custom_tuition_fee.js" type="text/javascript"></script>

<style>
	.custom_slot_time{
		padding : 0px;
	}
	.custom_slot_date{
		padding-right:0px;
	}
</style>
<!-- Student activate/deactivation -->
<form id="billActivationFrm" name="billActivationFrm">
	<input type="hidden" id="activationID" name="activationID">
</form>

<form id="tuitionFeeReceivableFrm" name="tuitionFeeReceivableFrm" autocomplete="off">
  <div class="modal fade" role="dialog" aria-labelledby="myModalLabel"  id="tuitionFeeReceivableModal" >
      <div class="modal-dialog modal-lg" style="margin-top: 2%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa fa-user-plus fa-lg"></i> New Tuition Fee Receivable</h4>
            </div>
            <div id="statusMsg" style="padding-bottom: 0px; margin-bottom: 0px"></div>
            <input type="hidden" id="id" name="id" value=""/>
            <input type="hidden" id="billID" name="billID" value=""/>
            <div class="modal-body">
				<div class="col-lg-6 col-xs-6" style="padding-left: 0px; padding-right: 2px">
					<div class="form-group">
						<label>Search Name/ID</label>
						<input class="form-control" placeholder="Student Name/ID" id="stdSearch" name="stdSearch">
						<div id="stdSearchResult"></div>
					</div>
				</div>
				<div class="col-lg-6 col-xs-6" style="padding-left: 0px; padding-right: 2px">
					<div class="form-group">
						<label>Payment Date</label>
						<input class="form-control" placeholder="Payment Date" id="payment_date" name="payment_date" >
					</div>
				</div>
                <div class="col-lg-6 col-xs-6" style="padding-left: 0px; padding-right: 2px">
                    <div class="form-group">
                        <label>Student Name</label>
                        <input class="form-control" placeholder="Student ID" id="stdName" name="stdName" readonly="readonly" >
                    </div>
                </div>
                <div class="col-lg-6 col-xs-6" style="padding-left: 0px; padding-right: 2px">
                    <div class="form-group">
                        <label>Student ID</label>
                        <input class="form-control" placeholder="Student ID" id="stdID" name="stdID" readonly="readonly" >
                    </div>
                </div>
                <div class="col-lg-6 col-xs-6" style="padding-left: 0px; padding-right: 2px">
                    <div class="form-group">
                        <label>Bill Receipt No.</label>
                        <input class="form-control" placeholder="Bill Receipt No. (Optional)" id="stdReceipt" name="stdReceipt" >
                    </div>
                </div>
                <div class="col-lg-6 col-xs-6" style="padding-left: 0px; padding-right: 2px">
                    <div class="form-group">
                        <label>Package</label>
                        <select class="form-control" id="billPackage" name="billPackage" required>
                        	<option disabled="disabled" selected="selected">Select an option</option>
                    <?php foreach($package_rows as $row ){ ?>
                            <option value="<?php echo $row['package_id'];?>"><?php echo $row['package_name']."-".$row['term']."class(es)-RM ".$row['price'];?></option>
                    <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-lg-12 col-xs-12" style="padding-left: 0px; padding-right: 2px">
	                <div class="form-group" id="custom_date" style="display:block">
	                	<!-- <label>Custom Date</label> -->
		           </div>
	           </div>
	           <div class="form-group" >
	                <div class="form-group">
	                    <label>Amount</label>
	                    <input class="form-control" placeholder="Amount" id="billAmount" name="billAmount" readonly="readonly" value="RM 0.00" >
	                </div>
	                
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="btnSubmitTuitionFeeReceivable">Submit Payment</button>
            </div>
        </div>
      </div>
  </div>
</form><!--form-newpayment-->


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<section class="content-header">
		<h1>Student Payment Dashboard</h1>
	</section>

	<section class="content">
		<div class="row">
			<form method="post">
				<div class="col-md-12 col-xs-12">
					<div class="box">
				        <div class="box-header">
							<div class="col-md-2 col-xs-2" style="padding-left:2px;">
					        	<div class="form-group">
									<label>Month</label>
									<select class="form-control" id="searchMonth" name="searchMonth">
										<option value="" selected="selected">All</option>
										<?php for($i=1; $i<=12; $i++){ ?>
											<option value="<?php echo $i?>" <?php if($month == $i){echo "SELECTED";}?>><?php echo date('F', strtotime("December +". $i." Months"));?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="col-md-3 col-xs-3" style="padding-left:2px;">
								<div class="form-group">
									<label>Name:</label>
									<input class="form-control" placeholder="Search by Name" id="searchName" name="searchName" value="<?php echo $name?>">
								</div>
							</div>
							<div class="col-md-3 col-xs-2" style="padding-left:2px;">
								<div class="form-group">
									<label>Type:</label>
									<select class="form-control" id="billType" name="billType">
										<option value="" selected="selected">All</option>
										<option value="N" <?php if($proccessed == 'N'){echo "SELECTED";}?>>Unproccessed</option>
										<option value="Y" <?php if($proccessed == 'Y'){echo "SELECTED";}?>>Processed</option>
									</select>
								</div>
							</div>
							<div class="col-md-3 col-xs-5" style="padding-left:2px;">
								<div class="form-group"  style="padding-top: 25px;">
									<div class="btn-group">
										<button type="submit" id="search" class="btn btn-primary">Search</button>
									
										<button type="button" class="btn btn-success btnTuitionFeeReceivable"id="btnTuitionFeeReceivable">New Payment</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
		<div class="row">
			<div class="col-xs-12 col-xs-12">
				<div class="box box-success">
					<!-- /.box-header -->
					<div class="box-body">
						<table class="table table-bordered table-hover dt-responsive" id="tblTuitionFeeDashboard">
							<thead>
								<tr>
									<th>Action</th>
									<th>Bill ID</th>
									<th>Receipt No.</th>
									<th>Name</th>
									<th>Issue Date</th>
									<th>Expiry Date</th>
									<th>Price</th>
									<th>Package</th>
									<th>Term</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($bill_record_rows as $row){?>
								<tr>
									<td><a href="#" class="voidReceipt"><i class="fa fa-trash fa-2x"></i></a>
										&nbsp; <a href="#" id="btnUpdate" class="btnUpdate"><i
											class="fa fa-pencil-square-o fa-2x"></i></a></td>
									<td id="bill_id"><?php echo $row['bill_id']; ?></td>
									<td><?php echo $row['receipt_no']; ?></td>
									<td><?php echo $row['student_name']; ?></td>
									<td><?php echo $row['issue_date']; ?></td>
									<td><?php echo $row['expiry_date']; ?></td>
									<td><?php echo $row['price']; ?></td>
									<td><?php echo $row['package_name']; ?></td>
									<td><?php echo $row['term']; ?></td>
									
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
        $("#tblTuitionFeeDashboard").dataTable({
            "order": [[ 2, "desc" ]],
            "iDisplayLength": 50
            });
      });
</script>