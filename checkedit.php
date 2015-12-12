<?php

session_start();

$mysqli = new mysqli("localhost","root","","dbadmin");

if(mysqli_connect_errno())
{
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}else
{
	$fname = $_POST["firstname"];
	$mname = $_POST["middlename"];
	$lname = $_POST["lastname"];

	#protection
	$fname = stripslashes($fname);
	$fname = mysqli_real_escape_string($mysqli,$fname);
	$mname = stripslashes($mname);
	$mname = mysqli_real_escape_string($mysqli,$mname);
	$lname = stripslashes($lname);
	$lname = mysqli_real_escape_string($mysqli,$lname);

	$id  = $_SESSION['account_id'];
	$sql = "SELECT * FROM account WHERE account_id = " .$id;
	$result = mysqli_query($mysqli,$sql);

	$count =  mysqli_num_rows($result);

	if($result)
	{
		$newArray = mysqli_fetch_array($result, MYSQLI_ASSOC);
		$password = $newArray['password'];

		$sql = "UPDATE account SET first_name = '" .$fname. "', middle_name = '" .$mname. "', last_name = '" .$lname. "' WHERE account_id = " . $id  ;

		$res = mysqli_query($mysqli, $sql);
		if ($res === TRUE) 
		{
			echo "Your information has been edited!.";
			echo "<br><a href = 'view_account.php' ><button>Back</button></a></br>";
		}
		else {
		printf("Could not edit account: %s\n", mysqli_error($mysqli));
		echo "<br><a href = 'homepage.php' ><button>Back</button></a></br>";
		}
	}
}
		

?>