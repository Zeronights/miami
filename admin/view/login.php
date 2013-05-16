<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title></title>
	<link rel="stylesheet" href="<?=$this->asset('css/miami.css')?>" />
	<link rel="stylesheet" href="<?=$this->asset('css/login.css')?>" />
</head>

<body>
	<div id="login">
		<form method="post">
			<?php if ($this->sgf('login.error')): ?>
			<p class="error"><?=$this->sgf('login.error')?></p>
			<?php endif; ?>
			<div class="inline">
				<label>Username:</label>
				<input type="text" name="login_username" />
			</div>
			<div class="inline">
				<label>Password:</label>
				<input type="password" name="login_password" />
			</div>
			<input type="submit" name="login_submit" value="Login" />
		</form>
	</div>
</body>
</html>