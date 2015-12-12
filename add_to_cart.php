<?php

session_start();
$mysqli = new mysqli("localhost","root","","dbadmin");

if (isset($_GET['id'])) 
{
	if(mysqli_connect_errno())
	{
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	} else
	{

		#protection
		$id = $_GET['id'];
		$qty = $_GET['qty'];
		$sessID = stripslashes($_COOKIE['PHPSESSID']);
		$sessID = mysqli_real_escape_string($mysqli,$sessID);

		$total_price_query = "SELECT price FROM item WHERE item_id = " .$id;
		$total_price_result = mysqli_query($mysqli,$total_price_query);
		$tpArray = mysqli_fetch_array($total_price_result, MYSQLI_ASSOC);
		$tp = $tpArray['price'];

		$tp = $tp*$qty;

		#username has to be unique!
		$sql = "INSERT INTO tracker (session_id,item_id,item_qty,total_price) VALUES ('".$sessID. "', " .$id.", " .$qty.", " .$tp.")";
		$result = mysqli_query($mysqli,$sql);


		//$count =  mysqli_num_rows($result);

		if ($result === TRUE) 
		{
			echo "A record has been inserted.";
			header("Location:menu.php");
			exit;
		}
		else {
			printf("Could not insert record: %s\n", mysqli_error($mysqli));

		}
	}


	mysqli_close($mysqli);
}
else{
	echo"wew";

}


?>