
<!-- dataTables JS -->
<script src="<?php echo base_url();?>assets/plugins/chartjs/Chart.js" type="text/javascript"></script>

<!-- Custom Page JScript -->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<form method="post">
				<div class="col-md-2"></div>
				<div class="col-md-8">
					<div class="box">
				        <div class="box-header">
							<div class="col-md-2" style="padding-left:2px;">
					        	<div class="form-group">
									<select class="form-control" id="searchYear" name="searchYear">
										<option value="" selected="selected">All</option>
										<?php foreach($getYear as $year){ ?>
											<option value="<?php echo $year['year']?>" <?php if($year['year'] == date('Y')){echo "SELECTED";}?>><?php echo $year['year'];?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<div class="btn-group">
										<button type="submit" id="search" class="btn btn-primary">Search</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-success">
					<div class="box-header">
						<h3 class="box-title"><?php echo $title; ?></h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<canvas id="myChart" style="min-height: 400px;"></canvas>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-success">
					<div class="box-header">
						<h3 class="box-title">Number of Payment</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<canvas id="myChart2" style="min-height: 400px;"></canvas>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->
			</div>
		</div>
	</section>
</div>

<?php
	$months = "";
	$i=1;
	foreach($rows as $row){
		$months = $months. "\"".  date('F', strtotime("December +". $row['month']." Months")) . "\"";
		if(count($rows) > 1 && $i != count($rows)){
			$months = $months .",";
		}
		$i++;
	}
	
	$i=1;
	$price = "";
	foreach($rows as $row){
		$price = $price. $row['price'];
		if(count($rows) > 1 && $i != count($rows)){
			$price = $price .",";
		}
		$i++;
	}
	$i=1;
	$numberPayment = "";
	foreach($rows2 as $row){
		$numberPayment = $numberPayment. $row['numberPayment'];
		if(count($rows2) > 1 && $i != count($rows2)){
			$numberPayment = $numberPayment .",";
		}
		$i++;
	}
?>

<script>

// ------------- 1st graph ---------------------------
// Get the context of the canvas element we want to select
var ctx = document.getElementById("myChart").getContext("2d");

var data = {
    labels: [<?php echo $months;?>],
    datasets: [
        {
            label: "Fees received",
             fillColor: "rgba(151,187,205,0.5)",
            strokeColor: "rgba(151,187,205,0.8)",
            highlightFill: "rgba(151,187,205,0.75)",
            highlightStroke: "rgba(151,187,205,1)",
            data: [<?php echo $price;?>]
        }
    ]
};

var myBarChart = new Chart(ctx).Bar(data);

// ------------- 2nd graph ---------------------------
var ctx = document.getElementById("myChart2").getContext("2d");
var data = {
    labels: [<?php echo $months;?>],
    datasets: [
        {
            label: "numberPayment",
             fillColor: "rgba(151,187,205,0.5)",
            strokeColor: "rgba(151,187,205,0.8)",
            highlightFill: "rgba(151,187,205,0.75)",
            highlightStroke: "rgba(151,187,205,1)",
            data: [<?php echo $numberPayment;?>]
        }
    ]
};

var myBarChart2 = new Chart(ctx).Bar(data);

</script>