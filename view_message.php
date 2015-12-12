<!DOCTYPE html>
<html>
	<head>
		<title>Your Messages</title>
		<script type="text/javascript">
				function goBack() {
					window.location = "homepage.php";
				}			
		</script>
		<link href="styles.css" type="text/css" rel="stylesheet">
	</head>
	<body>

	<form name="main_form" method="post" action="view_message.php">
	</head>
	<body>

		<?php
		session_start();

		$mysqli = new mysqli("localhost","root","","dbadmin");
		#setting up the connection
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
			where a.account_id = ". $id;
			$result = mysqli_query($mysqli,$sql);
			if($result)
			{
				$account = mysqli_fetch_array($result, MYSQLI_ASSOC);
				$position = $account['position'];

				echo "<div class = 'bar'>";
				#Account details
				echo "<p class = 'logout'><a href = 'logout.php'>Log Out</a></p>";
				echo "<p class = 'details'>Welcome " . $account['first_name']. " to Food Buddy!<br>";

				if($position != 'Client')
				{	
					echo "Position: " . $account['position']. "<br>";
					if($position != 'Dev')
					{
						if($position != 'Boy') echo  "Mall: " . $account['mall_name'] . ", Restaurant: " . $account['rest_name']. "<br>";
						else echo  "Mall: " . $account['mall_name'];
					}
				}
				if($position == 'Client') echo "Load: " . $account['load_balance'] ."<br/>";
				echo "</p><a href = 'homepage.php'><img class = 'logo' src='logo.jpg' title='Home Page' align = 'middle'/></a></div>";
			
				echo "<div class = 'navi'>";
				#Menu Items
				if($position == 'Dev')
				{
					echo "<ul class = 'buttons'>
							<li class = 'home'><a href = 'view_account.php'>Account Information</a></li>
							<li class = 'home'><a href = 'edit_password.php'>Edit Password</a></li>
							<li class = 'home'><a href = 'display_malls.php'>Malls</a></li>
							<li class = 'home'><a href = 'display_rest.php'>Restaurants</a></li>
							<li class = 'home'><a href = 'display_accounts.php'>Promote/Demote Account</a></li>";

				}else if($position == 'Admin')
				{
					echo "<ul class = 'buttons'>
							<li class = 'home'><a href = 'view_account.php'>Account Information</a></li>
							<li class = 'home'><a href = 'edit_password.php'>Edit Password</a></li>
							<li class = 'home'><a href = 'display_items.php'>Food Items</a></li>
							<li class = 'home'><a href = 'display_accounts-2.php'>Load Accounts</a></li>";
				}else if($position == 'Boy')
				{
					echo "<ul class = 'buttons'>
							<li class = 'home'><a href = 'view_account.php'>Account Information</a></li>
							<li class = 'home'><a href = 'edit_password.php'>Edit Password</a></li>
							<li class = 'home'><a href = 'display_order.php'>All Orders</a></li>
							<li class = 'home'><a href = 'display_orders_to_be_prepared.php'>Orders to Prepare</a></li>";
				}
				else
				{
					echo "<ul class = 'buttons'>
							<li class = 'home'><a href = 'view_account.php'>Account Information</a></li>
							<li class = 'home'><a href = 'edit_password.php'>Edit Password</a></li>
							<li class = 'home'><a href = 'choose_mall.php'>Order Food</a></li>";
							
				}
				echo "<li class = 'home'><a href = 'view_message.php'>Messages</a></li>";
			
				echo "</div><div class = 'empty'></div>";
				
				echo "<div class = 'center'><ul>
						<li><input class='submit' type='submit' name='view' value='View Message'></li>
						<li><input class='submit' type='submit' name='delete' value='Delete'></li>			
						</ul></div>";
				
				$mall_id = $account['mall_id'];

				$table_query = "SELECT * FROM message 
					INNER JOIN account ON message.recipient_id = account.account_id
					WHERE message.recipient_id = " .$id."
					ORDER BY message.msg_date DESC";

				$table2_query = "SELECT * FROM orderitems";

				if(isset($_POST['view']))
				{ #check if the VIEW ORDER button was pressed
					foreach ($mysqli->query($table_query) as $myrow) 
					{ #for all entries in the order item list
						$display_entry = $myrow["message_id"];	#assign current entry's item_id to variable						
						if(isset($_POST[$display_entry]) != NULL) 
						{	#check if the entry's checkbox was selected/not null(meaning the entry should be deleted)
							#display order's items
							$display_message = $myrow["msg"];
							echo 
								"<fieldset class = 'notice'>
								<legend> Message [" .$display_entry."] </legend>";
							$display_message = $myrow["msg"];
							echo $display_message;
							echo "</fieldset>";
						}
					} 
			
				}
				else if(isset($_POST['deliver'])) 
				{ #check if the DIBS button was pressed
					#send notification to client (wala pa to)
				}

				#CHECK CASCADE DELETE
				else if(isset($_POST['delete'])) 
				{	#check if the DELETE button was pressed
					#print_r($_POST); #for checking purposes
					foreach ($mysqli->query($table_query) as $myrow) 
					{ #for all entries in the item list
						$delete_entry = $myrow["message_id"];	#assign current entry's item_id to variable
						if(isset($_POST[$delete_entry]) != NULL) 
						{	#check if the entry's checkbox was selected/not null(meaning the entry should be deleted)
							#execute deletion
							$deleteitem_query = "DELETE FROM message WHERE message_id = '$delete_entry'";
							$mysqli->query($deleteitem_query);
							$delete_query = "DELETE FROM orders WHERE order_id = '$delete_entry'";
						$mysqli->query($delete_query);					
						}
					}
				}
		
				#display mall table contents
				echo "<table class='list'>";
				echo 
					"<tr>
					<th>Message ID</th>
					<th>Time Received</th> 
					<th>From</th> 
					</tr>";

				#for layout purposes start	
				$num = 0;
				$class = "even";
				#end
				foreach ($mysqli->query($table_query) as $myrow) 
				{
					$checkbox_id = $myrow["message_id"];

					#gets the sender information
					$sender_id_query = "SELECT * FROM message WHERE message_id = " . $checkbox_id;
					$result = mysqli_query($mysqli,$sender_id_query);
					$senderArray = mysqli_fetch_array($result, MYSQLI_ASSOC);
					$sender_id = $senderArray['sender_id'];

					$sender_acc_query = "SELECT * FROM account WHERE account_id = " . $sender_id;
					$sender_acc_result = mysqli_query($mysqli,$sender_acc_query);
					$senderAccArray = mysqli_fetch_array($sender_acc_result, MYSQLI_ASSOC);
					$sender_acc= $senderAccArray['first_name'] . " ''".$senderAccArray['username']."'' " . $senderAccArray['last_name'];
					#for layout purposes start
					if($num%2 == 0) 	$class = "even";
					else 	$class = "odd";
					$num++;
					#end
					#echo $checkbox_id; #for checking purposes
					echo "<tr class='$class'> <td>";
					echo "<input type='checkbox' name='$checkbox_id'>", "   ", $myrow["message_id"];
					echo "</td><td>";
					echo $myrow["msg_date"];
					echo "</td><td>";
					echo $sender_acc;
					echo "</td>";
					echo "</tr>";
				}
				
				echo "</table>";
			}
		}
		#end of display
		?>
	</body>
</html>