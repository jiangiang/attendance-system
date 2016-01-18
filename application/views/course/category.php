<!-- Custom Page JScript -->
<script src="<?php echo base_url();?>assets/dist/js/custom_course_category.js" type="text/javascript"></script>

<!-- course activate/deactivation -->
<?php $allowDelete = false;?>
<?php if($allowDelete == true){?>
<form id="categoryActivationFrm" name="courseActivationFrm">
	<input type="hidden" id="activationID" name="activationID">
</form>
<?php } ?>
<form id="categoryInfoFrm" name="categoryInfoFrm" autocomplete="off">
	<input type="hidden" id="catID" name="catID" value="" />
	<div class="modal fade" role="dialog" aria-labelledby="myModalLabel" id="catInfoModal">
		<div class="modal-dialog" style="margin-top: 2%;">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">
						<i class="fa fa-user-plus fa-lg"></i><span id="catInfoModalTitle">If you see me smth is wrong yo</span>
					</h4>
				</div>
				<div id="statusMsg" style="padding-bottom: 0px; margin-bottom: 0px"></div>

				<div class="col-lg-12" style="padding-right: 0px">
					<div class="modal-body">
						<div class="form-group">
							<label>Level Name</label>
							<input class="form-control" placeholder="Level Name" id="catName" name="catName" required="required">
						</div>	
						<div class="form-group">
							<label>Level Description</label>
							<input class="form-control" placeholder="Level Description" id="catInfo" name="catInfo" required="required">
						</div>
						<div class="form-group">
							<label>Private ?</label>
							<select name="private" id="private" class="form-control">
								<option value="N">NO</option>
								<option value="Y">YES</option>
							</select>
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
		<h1>Category Summary</h1>
	</section>

	<section class="content">		
		<div class="row">
			<div class="col-xs-12">
				<button type="button" class="btn btn-danger btnCatNew" id="btnCatNew">
					<i class="fa fa-user-plus fa-lg"></i> Add New Course
				</button>
			</div>	
			<div class="col-xs-12">
				<div class="box box-success">
					<div class="box-body">
						<table class="table table-bordered table-hover" id="tblActiveCategory" style="text-align: center">
							<thead>
								<tr>
									<th style="width: 5%; text-align: center">ID</th>
									<th style="width: 15%; text-align: center">Level Name</th>
									<th style="width: 50%; text-align: center">Level Info</th>
									<th style="width: 5%; text-align: center">Private</th>
									<th style="width: 20%; text-align: center">Last Edit</th>
									<th style="width: 5%; text-align: center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($category_row as $row){?>
								<tr>
									<td id="category_id"><?php echo $row['level_id']; ?></td>
									<td><?php echo $row['level_name']; ?></td>
									<td><?php echo $row['level_info']; ?></td>
									<td><?php echo $row['private_state']; ?></td>
									<td><?php echo date('Y-m-d H:i:s',$row['timestamp']); ?></td>
									<td>
										<!--<a href="#" class="categoryDeactivate"><i class="fa fa-trash fa-2x"></i></a> &nbsp;-->
										<a href="#" id="btnCategoryUpdate" class="btnCategoryUpdate">
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
