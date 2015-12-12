<?php

$mysqli = new mysqli("localhost","root","","dbadmin");

if(mysqli_connect_errno())
{
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}
else
{
	echo "<html><head><title>Create Account</title>";
	echo "</head><link href = 'styles.css' type = 'text/css' rel = 'stylesheet'/>";
	echo "<body>";
	
	$username = $_POST["username"];
	$password = $_POST["password"];

	#protection
	$username = stripslashes($username);
	$password = stripslashes($password);
	$username = mysqli_real_escape_string($mysqli,$username);
	$password = mysqli_real_escape_string($mysqli,$password);
	$sql = "SELECT * FROM account WHERE username = '" .$username. "' and password = '" .$password. "'";
	$result = mysqli_query($mysqli,$sql);

	$count =  mysqli_num_rows($result);

	if($count == 1)
	{
		$newArray = mysqli_fetch_array($result, MYSQLI_ASSOC);
		$id = $newArray['account_id'];
		$fname = $newArray['first_name'];
		
		session_start();
		$_SESSION['account_id'] = $id;
		$_SESSION['first_name'] = $fname;
		header("location:homepage.php"); #research
	}
	else
	{
		echo "<h1>Wrong Username or Password!</h1>";
		echo "<div class = 'center'>";
		echo "<br><a href = 'login.php' ><button>Back</button></a>";
		echo "</div>";
	}
	
	echo "</body>";
}
?>