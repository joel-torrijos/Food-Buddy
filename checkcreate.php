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
	
	$fname = $_POST["firstname"];
	$mname = $_POST["middlename"];
	$lname = $_POST["lastname"];
	$username = $_POST["username"];
	$password = $_POST["password1"];

	#protection
	$fname = stripslashes($fname);
	$fname = mysqli_real_escape_string($mysqli,$fname);
	$mname = stripslashes($mname);
	$mname = mysqli_real_escape_string($mysqli,$mname);
	$lname = stripslashes($lname);
	$lname = mysqli_real_escape_string($mysqli,$lname);
	$username = stripslashes($username);
	$username = mysqli_real_escape_string($mysqli,$username);
	$password = stripslashes($password);
	$password = mysqli_real_escape_string($mysqli,$password);

	#username has to be unique!
	$sql = "SELECT * FROM account WHERE username = '" .$username. "'";
	$result = mysqli_query($mysqli,$sql);

	$count =  mysqli_num_rows($result);

	if($count == 0)
	{
		$sql = "INSERT INTO account (first_name,middle_name,last_name,username,password,position,load_balance) VALUES ('" .$fname. "','" . $mname. "','" .$lname. "','" .$username. "','" .$password. "','Client',0)";
		$res = mysqli_query($mysqli, $sql);
		if ($res === TRUE) 
		{
			echo "<h1>A record has been inserted.</h1>";
			echo "<div class = 'center'>";
			echo "<br><a href = 'create_account.php' ><button>Back to Create Account</button></a></br>";
			echo "<br><a href = 'login.php' ><button>Cancel</button></a></br>";
			echo "</div>";
		}
		else {
		printf("Could not insert record: %s\n", mysqli_error($mysqli));
		echo "<br><a href = 'create_account.php' ><button>Back to Create Account</button></a></br>";
			echo "<br><a href = 'login.php' ><button>Cancel</button></a></br>";
		}
	}
	else
	{
		echo "<h1>Username Taken!</h1>";
		echo "<div class = 'center'>";
		echo "<br><a href = 'create_account.php' ><button>Back to Create Account</button></a></br>";
		echo "<br><a href = 'login.php' ><button>Cancel</button></a></br>";
		echo "</div>";
	}
	
	echo "</body>";
	echo "</html>";
	
	mysqli_close($mysqli);
}
?>