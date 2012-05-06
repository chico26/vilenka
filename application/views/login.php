<div id="welcome_message"></div>
<div id="login_attempts">
	<?php if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] > 0) :?>
		<div id="id"><?php echo $_SESSION['login_attempts'];?></div>
	<?php endif; ?>
</div>
<div id="login" class="ui-widget ui-widget-content">
<?php 
	echo form_open('/login');
	echo display_input('text',$user,'email','E-mail');
	echo display_input_no_autocomplete('password',$user,'password','Contraseña');
	echo form_submit('logear','Entrar');
	echo form_close();
?>
</div>
