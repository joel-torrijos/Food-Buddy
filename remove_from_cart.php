<?php
session_start();

if (isset($_GET['id'])) 
{
	$mysqli = new mysqli("localhost","root","","dbadmin");

	$safe_id = mysqli_real_escape_string($mysqli, $_GET['id']);
	$sessID = stripslashes($_COOKIE['PHPSESSID']);
	$sessID = mysqli_real_escape_string($mysqli,$sessID);

	$delete_item_sql = "DELETE FROM tracker WHERE
						tracker_id = ".$safe_id." AND session_id =
						'". $sessID. "'";
	$delete_item_res = mysqli_query($mysqli, $delete_item_sql) or die(mysqli_error($mysqli));

	//close connection to MySQL
	mysqli_close($mysqli);
	//redirect to showcart page
	header("Location: view_cart.php");
	exit;
}
else
{

}
?>


