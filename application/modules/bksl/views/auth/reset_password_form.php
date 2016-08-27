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
	$new_password = array(
		'name'	=> 'new_password',
		'id'	=> 'new_password',
		'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
		'size'	=> 30,
	);
	$confirm_new_password = array(
		'name'	=> 'confirm_new_password',
		'id'	=> 'confirm_new_password',
		'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
		'size' 	=> 30,
	);
	?>
	<div class="login-box">
		<div class="login-logo">
			<a href="/"><b>Cloud</b>App</a>
		</div><!-- /.login-logo -->
		<div class="login-box-body">
			<p class="login-box-msg">Reset Password</p>
			<?php echo form_open($this->uri->uri_string()); ?>
					<div class="form-group has-feedback">
						<label><?php echo form_label('New Password', $new_password['id']); ?></label>
						<?php echo form_password($new_password); ?>
						<span style="color: red;"><?php echo form_error($new_password['name']); ?><?php echo isset($errors[$new_password['name']])?$errors[$new_password['name']]:''; ?></span>
					</div>
					<div class="form-group has-feedback">
						<label><?php echo form_label('Confirm New Password', $confirm_new_password['id']); ?></label>
						<?php echo form_password($confirm_new_password); ?>
						<span style="color: red;"><?php echo form_error($confirm_new_password['name']); ?><?php echo isset($errors[$confirm_new_password['name']])?$errors[$confirm_new_password['name']]:''; ?></span>
					</div>
				<div class="row">
					<div class="col-xs-12">
						<button type="submit" class="btn btn-primary btn-block btn-flat">Change Password</button>
					</div><!-- /.col -->
			  </div>
			<?php echo form_close(); ?>
		</div><!-- /.login-box-body -->
	</div><!-- /.login-box -->
   </body>
</html>