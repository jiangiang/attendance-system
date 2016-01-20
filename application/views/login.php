<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>EZY SYSTEM - Log in</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
  </head>
  
  <?php $this->view('templates/include_css'); ?>
 
  
  <body class="login-page">
    <div class="login-box">
      <div class="login-logo">
        <a href="#"><b>EZY</b>Swimming</a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">Log in to start your session.</p>
        <?php echo validation_errors(); ?>
        <form action="<?php echo site_url();?>/login" method="post">
          <div class="form-group has-feedback">
            <input type="text" class="form-control" placeholder="User Name" name="username" id="username" required />
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="Password" name="password" id="password" required/>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
            
            <div class="col-xs-12">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
            </div><!-- /.col -->
          </div>
        </form>

      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

    <!-- jQuery 2.1.4 -->
    <script src="<?php echo base_url();?>assets/plugins/jQuery/jQuery-2.1.3.min.js"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="<?php echo base_url();?>assets/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- iCheck -->
    <script src="<?php echo base_url();?>assets/plugins/iCheck/icheck.min.js" type="text/javascript"></script>
   
    <script>
      $(document).ready(function() {
          $('#username').focus();
          });
  </script>
  </body>
</html>