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
				'size'	=> 30,
			);
			if ($this->config->item('use_username', 'tank_auth')) {
				$login_label = 'Email or login';
			} else {
				$login_label = 'Email';
			}
		?>
    <div class="login-box">
	<div class="login-logo">
	<a href="/"><b>Cloud</b>App</a>
	</div><!-- /.login-logo -->
	<div class="login-box-body">
	<p class="login-box-msg">Forget Password</p>
	<?php echo form_open($this->uri->uri_string()); ?>
			<div class="form-group has-feedback">
				<label><?php echo form_label($login_label, $login['id']); ?></label>
				<?php echo form_input($login); ?>
				<span style="color: red;"><?php echo form_error($login['name']); ?><?php echo isset($errors[$login['name']])?$errors[$login['name']]:''; ?></span>
			</div>
		<div class="row">
			<div class="col-xs-12">
				<button type="submit" class="btn btn-primary btn-block btn-flat">Get a new password</button>
			</div><!-- /.col -->
	  </div>
	<?php echo form_close(); ?>
	</div><!-- /.login-box-body -->
	</div><!-- /.login-box -->
    </body>
</html>