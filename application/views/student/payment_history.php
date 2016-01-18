
<!-- Custom Page JScript -->
<script src="<?php echo base_url(); ?>assets/dist/js/custom_student.js" type="text/javascript"></script>
 
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-success">
					<!-- /.box-header -->
					<div class="box-body">
						<table class="table table-bordered table-striped">
							<thead>
								<tr>
									<th colspan="5">Payment History</th>
									
								</tr>
							</thead>
							<tbody>
								<tr>
									<td style="width:20%">Bill ID</td>
									<td style="width:10%">Date</td>
									<td style="width:20%">Amount</td>
									<td style="width:30%">Package Name</td>
									<td style="width:20%">Term</td>
								</tr>
								<?php foreach($payment_history as $history){?>
								<tr>
									<td><?php echo $history['bill_id'] ?></td>
									<td><?php echo $history['issue_date'] ?></td>
									<td><?php echo $history['price'] ?></td>
									<td><?php echo $history['package_name'] ?></td>
									<td><?php echo $history['term'] ?></td>
								</tr>
								<?php } ?>				
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
