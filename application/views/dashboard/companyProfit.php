
<!-- dataTables JS -->
<script src="<?php echo base_url();?>assets/plugins/chartjs/Chart.js" type="text/javascript"></script>

<?php
	$months = "";
	$profit = "";
	$commission ="";
	$totalRevenue = 0; $totalCost = 0; $totalProfit = 0;
	$revenue_grow = 0; $cost_grow=0; $profit_grow=0;
	$i=1;
	foreach($rows as $row){
		
		$totalRevenue += $row['revenue'];
		$totalCost += $row['commission'] + $row['salary'];
		$profitSubTotal = $row['revenue'] - $row['commission'];
		$totalProfit += $profitSubTotal;
				
		$months = $months. "\"".  date('F', strtotime("December +". $row['month']." Months")) . "\"";
		if(count($rows) > 1 && $i != count($rows)){
			$months = $months .",";
		}
		
		$profit = $profit. $profitSubTotal;
		if(count($rows) > 1 && $i != count($rows)){
			$profit = $profit .",";
		}
		
		$commission = $commission. $row['commission'];
		if(count($rows) > 1 && $i != count($rows)){
			$commission = $commission .",";
		}
		$i++;
	}
	
	if(count($rows) < 2){
			$revenue_grow = 0; $cost_grow=0; $profit_grow=0;
		}else{
			$revenueNew = $rows[count($rows)-1]['revenue'];
			$revenueOld = $rows[count($rows)-2]['revenue'];
			$revenue_grow = ($revenueNew - $revenueOld)/$revenueNew * 100;
			
			$costNew = $rows[count($rows)-1]['commission'] + $rows[count($rows)-1]['commission'];
			$costOld = $rows[count($rows)-2]['commission'] + $rows[count($rows)-2]['commission'];
			$cost_grow = ($costNew - $costOld)/$costNew * 100;
			
			$profitNew = $revenueNew - $costNew;
			$profitOld = $revenueOld - $costOld;
			$profit_grow = ($profitNew - $profitOld)/$profitNew * 100;;
		}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<section class="content">
		<div class="row">
			<form method="post">
				<div class="col-md-2"></div>
				<div class="col-md-8">
					<div class="box">
				        <div class="box-header">
							<div class="col-md-4 col-xs-4" style="padding-left:2px;">
					        	<div class="form-group">
									<label class="sr-only">Search by Day</label>
									<select class="form-control" id="searchYear" name="searchYear">
										<option value="" selected="selected">All</option>
										<?php foreach($getYear as $year){ ?>
											<option value="<?php echo $year['year']?>" <?php if($year['year'] == date('Y')){echo "SELECTED";}?>><?php echo $year['year'];?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="col-md-3 col-xs-3" style="padding-left:2px;">
								<div class="form-group" >
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
					<div class="box-footer">
              			<div class="row">
			                <div class="col-sm-4 col-xs-6">
								<div class="description-block border-right">
									<?php if($revenue_grow > 0 ){ ?>
										<span class="description-percentage text-green"><i class="fa fa-caret-up"></i> <?php echo number_format($revenue_grow,2); ?>%</span>
									<?php }else if($revenue_grow < 0 ){ ?>
										<span class="description-percentage text-red"><i class="fa fa-caret-down"></i> <?php echo number_format((-1*$revenue_grow),2); ?>%</span>
									<?php }else{ ?>
										<span class="description-percentage text-yellow"><i class="fa fa-caret-left"></i> 0.00%</span>
									<?php } ?>
									<h5 class="description-header">RM <?php echo number_format($totalRevenue,2);?></h5>
									<span class="description-text">TOTAL REVENUE</span>
								</div>
								<!-- /.description-block -->
							</div>
							<!-- /.col -->
							<div class="col-sm-4 col-xs-6">
								<div class="description-block border-right">
									<?php if($cost_grow > 0 ){ ?>
										<span class="description-percentage text-green"><i class="fa fa-caret-up"></i> <?php echo number_format($cost_grow,2); ?>%</span>
									<?php }else if($revenue_grow <0 ){ ?>
										<span class="description-percentage text-red"><i class="fa fa-caret-down"></i> <?php echo number_format((-1*$cost_grow),2); ?>%</span>
									<?php }else{ ?>
										<span class="description-percentage text-yellow"><i class="fa fa-caret-left"></i> 0.00%</span>
									<?php } ?>
									<h5 class="description-header">RM <?php echo number_format($totalCost,2);?></h5>
									<span class="description-text">TOTAL COST</span>
								</div>
								<!-- /.description-block -->
							</div>
							<!-- /.col -->
							<div class="col-sm-4 col-xs-6">
								<div class="description-block border-right">
									<?php if($profit_grow > 0 ){ ?>
										<span class="description-percentage text-green"><i class="fa fa-caret-up"></i> <?php echo number_format($profit_grow,2); ?>%</span>
									<?php }else if($profit_grow < 0 ){ ?>
										<span class="description-percentage text-red"><i class="fa fa-caret-down"></i> <?php echo number_format((-1*$profit_grow),2); ?>%</span>
									<?php }else{ ?>
										<span class="description-percentage text-yellow"><i class="fa fa-caret-left"></i> 0.00%</span>
									<?php } ?>
									<h5 class="description-header">RM <?php echo number_format($totalProfit,2);?></h5>
									<span class="description-text">TOTAL PROFIT</span>
								</div>
								<!-- /.description-block -->
							</div>
							<!-- /.col -->
						</div>
						<!-- /.row -->
					</div>
					<!-- /.box-footer -->
				</div>
				<!-- /.box -->
			</div>
		</div>
	</section>
</div>

<?php
	$months = "";$profit = "";$commission = "";$salary_payout="";
	$i=1;
	foreach($rows as $row){
		$months = $months. "\"".  date('F', strtotime("December +". $row['month']." Months")) . "\"";
		if(count($rows) > 1 && $i != count($rows)){
			$months = $months .",";
		}
		$commission = $commission. $row['commission'];
		if(count($rows) > 1 && $i != count($rows)){
			$commission = $commission .",";
		}
		$profit = $profit. $row['profit'];
		if(count($rows) > 1 && $i != count($rows)){
			$profit = $profit .",";
		}
		$salary_payout = $salary_payout. $row['salary'];
		if(count($rows) > 1 && $i != count($rows)){
			$salary_payout = $salary_payout .",";
		}
		$i++;
	}
	
	
?>

<script>

// Get the context of the canvas element we want to select
var ctx = document.getElementById("myChart").getContext("2d");

var data = {
    labels: [<?php echo $months;?>],
    datasets: [
        {
            label: "Profit",
            fillColor: "rgba(220,220,20,0.2)",
            strokeColor: "rgba(220,220,20,1)",
            pointColor: "rgba(220,220,20,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,20,1)",
            data: [<?php echo $profit;?>]
        },
         {
            label: "Commission",
            fillColor: "rgba(151,187,205,0.2)",
            strokeColor: "rgba(151,187,205,1)",
            pointColor: "rgba(151,187,205,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(151,187,205,1)",
            data: [<?php echo $commission;?>]
        },
        {
            label: "Salary payout",
            fillColor: "rgba(131,117,205,0.2)",
            strokeColor: "rgba(131,117,205,1)",
            pointColor: "rgba(131,117,205,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(131,117,205,1)",
            data: [<?php echo $salary_payout;?>]
        }
    ]
};

//legendTemplate takes a template as a string, you can populate the template with values from your dataset 
var options = {
	multiTooltipTemplate: "<%= datasetLabel %> - <%= value %>"
};
var myChart = new Chart(ctx).Line(data, options);

</script>