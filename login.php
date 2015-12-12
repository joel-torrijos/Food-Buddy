<!DOCTYPE html>
<html>
<head>
	<title>FooBu Log In</title>
	<link href = "styles.css" type = "text/css" rel = "stylesheet"/>
	</head>

<body><br><br>
<img class = 'login' src='login.jpg' title='Welcome!' align = 'middle'/>
<h1>Your One-Stop Movie Snack Shop</h1>
<form action = "checklogin.php" style = "text-align:center" method = "post">
	<p>Username:<br>
	<input type = "text" name = "username" placeholder = "Username"></p><p>
	Password:<br>
	<input type = "password" name = "password" placeholder = "Password"></p>
	<br><input class = "submit" type = "submit" value = "Login">
</form>
	<div class = "center">
	<br><p>OR</p><br>
	<a href = "create_account.php" ><button style = "text-align:center">Create Account</button></a>
	</div>
</body>
</html>