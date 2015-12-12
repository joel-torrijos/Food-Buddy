<?
session_start();

if (isset($_GET['id'])) 
{
	$mysqli = new mysqli("localhost","root","","dbadmin");

	$promote_account_id = $_SESSION["promote_account_id"];

	echo "promote_account_id";
	

	//close connection to MySQL
	mysqli_close($mysqli);
	//redirect to showcart page
}
else
{

}

?>
