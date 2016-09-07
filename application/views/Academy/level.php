<!-- Custom Page JScript -->
<script src="<?php echo base_url();?>assets/dist/js/Academy/level.js" type="text/javascript"></script>

<!-- course activate/deactivation -->
<?php $allowDelete = false;?>
<?php if($allowDelete == true){?>
<form id="categoryActivationFrm" name="courseActivationFrm">
	<input type="hidden" id="activationID" name="activationID">
</form>
<?php } ?>

<?php $this -> view('Academy/Modals/LevelCreateEdit'); ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<section class="content-header">
		<h1><?php echo $title; ?></h1>
	</section>

	<section class="content">		
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-success">
					<div class="box-body">
						<table class="table table-bordered table-hover" id="tblActiveCategory" style="text-align: center">
							<thead>
								<tr>
									<th style="width: 5%;">ID</th>
									<th style="width: 15%;">Level Name</th>
									<th style="width: 50%;">Level Info</th>
									<th style="width: 5%;">Duration</th>
									<th style="width: 5%;">Max Capacity</th>
									<th style="width: 5%;">Private</th>
									<th style="width: 5%;">
										<button type="button" class="btn btn-success btn-sm btnCatNew" id="btnCatNew">
											<i class="fa fa-user-plus fa-lg"></i> Add New Course
										</button></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($category_row as $row){?>
								<tr>
									<td id="category_id"><?php echo $row['level_id']; ?></td>
									<td><?php echo $row['level_name']; ?></td>
									<td><?php echo $row['level_info']; ?></td>
									<td><?php echo $row['duration_minute']; ?> mins</td>
									<td><?php echo $row['max_capacity']; ?></td>
									<td><?php if( $row['IsPrivate'] ){echo "YES"; }else{ echo "NO"; }; ?></td>
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
