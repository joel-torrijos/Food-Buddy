<!DOCTYPE html>
<html>
<head><title>Create Account</title>
<script>
function validateForm() {
	var a = document.forms["myForm"]["firstname"].value;
	var b = document.forms["myForm"]["middlename"].value;
	var c = document.forms["myForm"]["lastname"].value;
    var x = document.forms["myForm"]["username"].value;
    var y = document.forms["myForm"]["password1"].value;
    var z = document.forms["myForm"]["password2"].value;
    if (a == "" || b == "" || c == "" || x == "" || y == "" || z == "") {
        alert("Some fields need to be field!");
        return false;
    } else if (y != z)
    {
    	alert("Passwords are not matching!");
        return false;
    }
}
</script>
</head>
<link href = "styles.css" type = "text/css" rel = "stylesheet"/>

<body>
<h1>FooBu Create Account</h1>
<p>
<form action = "checkcreate.php" onsubmit = "return validateForm()" method = "POST" name = "myForm">
<fieldset class = "long">
	<legend>Personal Information</legend>
	Full Name:<br>
	<input type = "text" id = "firstname" name = "firstname" placeholder = "First Name"> 
	<input type = "text" id = "middlename" name = "middlename" placeholder = "Middle Name">
	<input type = "text" id = "lastname" name = "lastname" placeholder = "Last Name">
</fieldset>
<fieldset class = "creation">
	<legend>Account Details</legend>
	Username:<br>
	<input type = "text" id = "username"  name = "username"  placeholder = "Username"><br><br>
	Password:<br>
	<input type = "password" id = "password1" name = "password1" placeholder = "Password"> 
	<input type = "password" id = "password2" name = "password2" placeholder = "Re-enter Password">
</fieldset>
</p>
	<div class = "center">
	<input class = "submit" type = "submit" value = "Submit">
	</div>
</form>
<!--Buttons-->
<!--WIP-->
<div class = "center">
<br><a href = "login.php"><button class = "create">Cancel</button></a>
</div>
</body>
</html>