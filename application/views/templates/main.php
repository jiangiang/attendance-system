<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title><?php echo $title; ?></title>
		<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <?php $this -> view('templates/include_css'); ?>
	</head>
	
	
  	<?php $this -> view('templates/include_js'); ?>
  	<?php $this -> view('templates/navigator'); ?>
  	
	<body class="layout-top-nav skin-blue">
		<div class="wrapper">
		<?php $this -> view($content); ?>
		<?php $this -> view('templates/footer'); ?>
		</div><!-- ./wrapper -->
	</body>
</html>
