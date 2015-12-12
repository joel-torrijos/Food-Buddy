<?php session_start();?>
<!DOCTYPE html>
<html>
	<head>
		<title>Your Claimed Orders</title>
		<script type="text/javascript">
					function goBack() {
						window.location = "homepage.php";
		}			
		</script>
		<link href="styles.css" type="text/css" rel="stylesheet">
	</head>
	<body>
	<form name="main_form" method="post" action="display_orders_to_be_prepared.php">
	</head>
	<body>

		<?php

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
				echo "Position: " . $account['position']. "<br>";
				echo "Mall: " . $account['mall_name'];
				echo "</p><a href = 'homepage.php'><img class = 'logo' src='logo.jpg' title='Home Page' align = 'middle'/></a></div>";
			
				echo "<div class = 'navi'>";
				#Menu Items
				echo "<ul class = 'buttons'>
						<li class = 'home'><a href = 'view_account.php'>View Account Information</a></li>
						<li class = 'home'><a href = 'edit_password.php'>Edit Password</a></li>
						<li class = 'home'><a href = 'display_order.php'>All Orders</a></li>
						<li class = 'home'><a href = 'display_orders_to_be_prepared.php'>Orders to Prepare</a></li>
						<li class = 'home'><a href = 'view_message.php'>Messages</a></li>";
			
				echo "</div><div class = 'empty'></div>";
				
				echo "<div class = 'center'><ul>
						<li><input class='submit' type='submit' name='sortA' value='Sort by Account'></li>
						<li><input class='submit' type='submit' name='sortR' value='Sort by Restaurant'></li>
						</ul></div>";
				

				$table_query = "SELECT * from orderitems a
					INNER JOIN orders b ON a.order_id = b.order_id
					INNER JOIN account c ON b.account_id = c.account_id
					WHERE b.boy_id = " .$id. " AND b.status = 'Assembling'";

				if(isset($_POST['sortA'])) 
				{ #Sort by Account
					$id  = $_SESSION['account_id'];
					$table_query = "SELECT * from orderitems a
						INNER JOIN orders b ON a.order_id = b.order_id
						INNER JOIN account c ON b.account_id = c.account_id
						WHERE b.boy_id = ".$id."
						AND b.status = 'Assembling'
						ORDER BY c.account_id";
				}

				else if(isset($_POST['sortR'])){ #Sort by Restaurant
					$id  = $_SESSION['account_id'];
					$table_query = "SELECT * from orderitems a
						INNER JOIN orders b ON a.order_id = b.order_id
						INNER JOIN item c ON a.item_id = c.item_id
						INNER JOIN restaurant d ON c.rest_id = d.rest_id
						WHERE b.boy_id = ".$id."
						AND b.status = 'Assembling'
						ORDER BY d.rest_id";
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
						$delete_entry = $myrow["order_id"];	#assign current entry's item_id to variable
						if(isset($_POST[$delete_entry]) != NULL) 
						{	#check if the entry's checkbox was selected/not null(meaning the entry should be deleted)
							#execute deletion
							$deleteitem_query = "DELETE FROM orderitems WHERE order_id = '$delete_entry'";
							$mysqli->query($deleteitem_query);
							$delete_query = "DELETE FROM orders WHERE order_id = '$delete_entry'";
							$mysqli->query($delete_query);					
						}
					}
				}
				#display mall table contents
				echo "<table class='special'>";
				echo 
					"<tr>
					<th>Account</th>
					<th>Restaurant</th>
					<th>Food Item</th> 
					<th>Quantity</th> 
					<th>Cinema</th> 
					<th>Seat Number</th> 
					</tr>";
				#for layout purposes start	
				$num = 0;
				$class = "even";
				#end
				foreach ($mysqli->query($table_query) as $myrow) 
				{
					$checkbox_id = $myrow["order_id"];
					$cust_id = $myrow['account_id'];

			
					#for layout purposes start
					if($num%2 == 0) 	$class = "even";
					else 	$class = "odd";
					$num++;
					#end
					#echo $checkbox_id; #for checking purposes
					echo "<tr class='$class'> <td>";
					$boy_acc_query = "SELECT * FROM account WHERE account_id = " . $cust_id;
					$sender_acc_result = mysqli_query($mysqli,$boy_acc_query);
					$senderAccArray = mysqli_fetch_array($sender_acc_result, MYSQLI_ASSOC);
					$sender_acc= $senderAccArray['first_name'] . " ''".$senderAccArray['username']."'' " . $senderAccArray['last_name'];

					echo $sender_acc;
					echo "</td><td>";

					$rest_name_query = "SELECT rest_name FROM restaurant 
								INNER JOIN item ON restaurant.rest_id = item.rest_id 
								WHERE item_id = " . $myrow['item_id'];
					$rest_name_result = mysqli_query($mysqli,$rest_name_query);
					$rest_Array = mysqli_fetch_array($rest_name_result, MYSQLI_ASSOC);
					$rest_name= $rest_Array['rest_name'];

					echo $rest_name;
					echo "</td><td>";
	
					$item_name_query = "SELECT item_name FROM item WHERE item_id = " . $myrow['item_id'];
					$item_name_result = mysqli_query($mysqli,$item_name_query);
					$item_Array = mysqli_fetch_array($item_name_result, MYSQLI_ASSOC);
					$item_name= $item_Array['item_name'];

					echo $item_name;
					echo "</td><td class = 'number'>";
					echo $myrow["item_qty"];
					echo "</td><td class = 'number'>";
					echo $myrow["cinema_num"];
					echo "</td><td class = 'number'>";
					echo $myrow["seat_num"];
					echo "</td>";

					echo "</tr>";
				}
				echo "</table>";
			}
		}
		#end of display

		?>
	</body>
	</form>
</html>