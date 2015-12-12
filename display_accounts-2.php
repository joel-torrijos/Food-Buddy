<!DOCTYPE html>
<html>
	<head>
		<title>FooBu Clients</title>
		<link href="styles.css" type="text/css" rel="stylesheet">
	</head>
	<body>	
	<form name="main_form" method="post" action="process_account.php"></li>

		<?php
		session_start();
		#process_account
		#display_accounts-2
		#print_r($_POST);
		#print_r($_SESSION);
		#setting up the connection
		$mysqli = new mysqli("localhost","root","","dbadmin");
		if(mysqli_connect_errno())
		{
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
		else
		{
			$id  = $_SESSION['account_id'];
			$sql = "SELECT * FROM account a
        	LEFT JOIN mall b ON a.mall_id = b.mall_id
        	LEFT JOIN restaurant c ON a.rest_id = c.rest_id
            WHERE position <> 'Dev'
        	ORDER BY account_id ASC";
			$result = mysqli_query($mysqli,$sql);
			
			if($result)
			{
				$newArray = mysqli_fetch_array($result, MYSQLI_ASSOC);
				$position = $newArray['position'];

				echo "<div class = 'bar'>";
				#Account details
				echo "<p class = 'logout'><a href = 'logout.php'>Log Out</a></p>";
				echo "<p class = 'details'>Welcome " . $newArray['first_name']. " to Food Buddy!<br>";
				echo "Position: " . $newArray['position']. "<br>";
				echo "Mall: " . $newArray['mall_name'] . ", Restaurant: " . $newArray['rest_name']. "<br>";
				echo "</p><a href = 'homepage.php'><img class = 'logo' src='logo.jpg' title='Home Page' align = 'middle'/></a></div>";
			
				echo "<div class = 'navi'>";
				#Menu Items
					echo "<ul class = 'buttons'>
							<li class = 'home'><a href = 'view_account.php'>Account Information</a></li>
							<li class = 'home'><a href = 'edit_password.php'>Edit Password</a></li>
							<li class = 'home'><a href = 'display_items.php'>Food Items</a></li>
							<li class = 'home'><a href = 'display_accounts-2.php'>Load Accounts</a></li>
							<li class = 'home'><a href = 'view_message.php'>Messages</a></li>";
			
				echo "</div><div class = 'empty'></div>";
				
				echo "<div class = 'center'><ul><li><input class='submit' type='submit' name='accounts_add' value='Add Load'></li></ul></div>";
		
				#should not display Admin and Dev accounts to avoid exploitation
				$table_query = "SELECT * FROM account WHERE position = 'Client'";

				$message = null;
				#check message in $_SESSION
				if(isset($_SESSION['message'])) 
				{
					$message = $_SESSION['message'];
					$notif = "Please select an account.";
					if($message == 'promoted') $notif = "Account promoted.";				
					else if($message == 'loaded') $notif = 'Load added to account.';
					else if($message == 'cancelled') $notif = 'Transaction cancelled.';
					echo "<p class='notif'> $notif </p>";
				}

				if(isset($_SESSION['accounts_add'])) 
				{	
					$_SESSION['accounts_add'] = NULL;	#n00b method, but it works for my purposes
					$loadee_id = $_SESSION['loadee'];
					$select_query = "SELECT first_name FROM account WHERE account_id = '$loadee_id'";

					$stmt = $mysqli->query($select_query);
					$row = mysqli_fetch_array($stmt,MYSQLI_ASSOC);

					echo 
						"<fieldset class = 'notice'>
							<legend> Add Load to " . $row['first_name'] . " </legend>";						
					echo "<label class='in'>Load Amount: </label>";
					echo			
						"<input class='text' type='text' name='load_amount' value='00.00'><br>
						<input class='sub_submit' type='submit' name='add_load' value='Add Load to Account'>
						<input class='sub_submit' type='submit' name='cancel' value='Cancel'>
						</fieldset>";
					#end of display	
				}
				#display mall table contents
				echo "<table class='list'>";
				echo 
					"<tr>
					<th>Account ID</th>
					<th>Name</th>
					<th>Username</th>
					<th class='money'>Balance</th>
					<th>Position</th>				
					</tr>";

				#for layout purposes start	
				$num = 0;
				$class = "even";
				#end
				foreach ($mysqli->query($table_query) as $myrow) 
				{
					$id = $myrow['account_id'];
					#for layout purposes start
					if($num%2 == 0) 	$class = "even";
					else 	$class = "odd";
					$num++;
					#end
					#echo $checkbox_id; #for checking purposes
					echo "<tr class='$class'> <td>";			
					echo "<input type='radio' name='account' value='$id'>", $id;
					echo "<td>";
					echo $myrow['first_name'], " ", $myrow['middle_name'], " ", $myrow['last_name'];
					echo "<td>";
					echo $myrow['username'];			
					echo "<td class = 'number'>";
					echo $myrow['load_balance'];
					echo "<td>";
					echo $myrow['position'];
					echo "</tr>";			
				}
				echo "</table>";
				#end of display

				$_SESSION['message'] = NULL;
			}
		}?>

	</form>

	</body>
</html>