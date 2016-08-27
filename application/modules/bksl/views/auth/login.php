<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Cloud App | Log in</title>
		<link rel="shortcut icon" type="image/png" href="<?php echo base_url(); ?>img/calendar-ico.png" />
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        <link href="<?php echo base_url(); ?>css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="<?php echo base_url(); ?>css/AdminLTE.css" rel="stylesheet" type="text/css" />
    	<link rel="stylesheet" href="<?php echo base_url(); ?>js/lib/plugins/iCheck/square/blue.css">
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>
   <body class="login-page">
    
		<?php
		$login = array(
			'name'	=> 'login',
			'id'	=> 'login',
			'value' => set_value('login'),
			'maxlength'	=> 80,
			'class' => 'form-control',
			'placeholder' => 'User ID',
			'size'	=> 30,
		);
		if ($login_by_username AND $login_by_email) {
			$login_label = 'Email or login';
		} else if ($login_by_username) {
			$login_label = 'Login';
		} else {
			$login_label = 'Email';
		}
		$password = array(
			'name'	=> 'password',
			'id'	=> 'password',
			'class' => 'form-control',
			'placeholder' => 'Password',
			'size'	=> 30,
		);
		$remember = array(
			'name'	=> 'remember',
			'id'	=> 'remember',
			'value'	=> 1,
			'checked'	=> set_value('remember'),
			'style' => 'margin:0;padding:0',
		);
		$captcha = array(
			'name'	=> 'captcha',
			'id'	=> 'captcha',
			'maxlength'	=> 8,
		);
		?>
    <div class="login-box">
      <div class="login-logo">
        <a href="../../index2.html"><b>Cloud</b>App</a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">Sign in to manage appointments</p>
        <?php echo form_open($this->uri->uri_string()); ?>
				<div class="form-group has-feedback">
					<?php echo form_input($login); ?>
					<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
					<span style="color: red;"><?php echo form_error($login['name']); ?><?php echo isset($errors[$login['name']])?$errors[$login['name']]:''; ?></span>
				</div>
				<div class="form-group has-feedback">	
					<?php echo form_password($password); ?>
            		<span class="glyphicon glyphicon-lock form-control-feedback"></span>
					<span style="color: red;"><?php echo form_error($password['name']); ?><?php echo isset($errors[$password['name']])?$errors[$password['name']]:''; ?></span>
				</div>
			<div class="row">
				<div class="col-xs-8">
				  <div class="checkbox icheck">
					<label>	
					<?php echo form_checkbox($remember); ?>
					<?php echo form_label('Remember me', $remember['id']); ?>
				 </label>
              </div>
            </div><!-- /.col -->
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
            </div><!-- /.col -->
          </div>
		<?php echo form_close(); ?>
        <a href="<?php echo base_url(); ?>bksl/auth/forgot_password">I forgot my password</a><br>
      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->


        <!-- jQuery 2.0.2 -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
        <!-- Bootstrap -->
        <script src="<?php echo base_url(); ?>js/lib/bootstrap.min.js" type="text/javascript"></script>        
			<!-- iCheck -->
			<script src="<?php echo base_url(); ?>js/lib/plugins/iCheck/icheck.min.js"></script>
			<script>
			  $(function () {
				$('input').iCheck({
				  checkboxClass: 'icheckbox_square-blue',
				  radioClass: 'iradio_square-blue',
				  increaseArea: '20%' // optional
				});
			  });
			</script>
    </body>
</html>