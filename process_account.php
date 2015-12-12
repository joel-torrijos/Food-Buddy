
<html>
<body>
<?php
	#this method prevents the refresh problem which I cannot solve huhu

	session_start();	

	$mysqli = new mysqli("localhost","root","","dbadmin");

	try {			
			$db = new PDO('mysql:host=localhost;dbname=dbadmin', 'root', '');
			$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	} catch(PDOException $e) {
			echo "Connection failed: " . $e->getMessage();
	}

	$sql_query = "SELECT * FROM account WHERE position = 'Client'";
	$message = NULL;

	#3 possible data from display_accounts-2: accounts_add, promote and add_load
	if(isset($_POST['home'])) {
		header("location:homepage.php");
	}
	else {
		if(isset($_POST['cancel'])) {
			$message = "cancelled";
			header("location:display_accounts-2.php");
		}
		else if(isset($_POST['accounts_add'])) {
			if(isset($_POST['account'])) {
				$_SESSION['accounts_add'] = 1;
				$_SESSION['loadee'] = $_POST['account'];
			}
			else 	$message = "null";			
		}
		else if(isset($_POST['promote'])) {		
			if(isset($_POST['account'])) {
				$promote_entry = $_POST['account'];
				$promote_query = "UPDATE account SET position = 'Admin' WHERE account_id = '$promote_entry'";
				$db->query($promote_query);
				$message = "promoted";
			}
			else 	$message = "null";
		}		
		else if(isset($_POST['add_load'])) {
			$admin_id = $_SESSION['account_id'];		
			$id = $_SESSION['loadee'];
			$load = $_POST['load_amount'];

			$msg = $load. " has been loaded to your account! Enjoy Shopping!";

			$get_query = "SELECT load_balance FROM account WHERE account_id = '$id'";
			$stmt = $db->query($get_query);
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			$curr_load = $row['load_balance'];
			
			$load += $curr_load;

			$load_query = "UPDATE account SET load_balance = '$load' WHERE account_id = '$id'";
			$db->query($load_query);
			$message = "loaded";

			$message_query = "INSERT INTO message (recipient_id,sender_id,msg_date,msg) VALUES ( ".$id.", " .$admin_id.", now(), '".$msg."')";
			$result = mysqli_query($mysqli,$message_query);

			if($result === true)
			{

			}
			else
			{
				echo gg;
			}

			$_SESSION['loadee'] = NULL;
		}

		$_SESSION['message'] = $message;
		#$_POST = array(); #n00b way of clearing post data just to be sure
		header("location:display_accounts-2.php"); #go back to original page
	}
?>
</body>
</html>

