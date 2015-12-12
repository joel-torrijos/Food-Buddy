<?php session_start();
		$mysqli = new mysqli("localhost","root","","dbadmin");

		#setting up the connection
		try {			
			$db = new PDO('mysql:host=localhost;dbname=dbadmin', 'root', '');
			$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		} catch(PDOException $e) {
			echo "Connection failed: " . $e->getMessage();
		}
		$id  = $_SESSION['account_id'];
		$mysqli = new mysqli("localhost","root","","dbadmin");
		$sql = "SELECT * FROM account 
				INNER JOIN mall ON account.mall_id = mall.mall_id
				WHERE account_id = ". $id;
	    $result = mysqli_query($mysqli,$sql);
		$account = mysqli_fetch_array($result, MYSQLI_ASSOC);
		$mall_id = $account['mall_id'];
		$position = $account['position'];
		$sql2 = "SELECT * FROM mall
				WHERE mall_id = ".$mall_id;
	    $result2 = mysqli_query($mysqli,$sql2);
		$account2 = mysqli_fetch_array($result2, MYSQLI_ASSOC);
		
		
		echo "<html>
				<head>
					<title>".$account2['mall_name']." Orders</title>
					<script type='text/javascript'>
								function goBack() {
									window.location = 'homepage.php';
					}			
					</script>
					<link href='styles.css' type='text/css' rel='stylesheet'>
				</head>
				<body>

				<form name='main_form' method='post' action='display_order.php'>
				</head>
				<body>";
		
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
							<li class = 'home'><a href = 'view_account.php'>Account Information</a></li>
							<li class = 'home'><a href = 'edit_password.php'>Edit Password</a></li>
							<li class = 'home'><a href = 'display_order.php'>All Orders</a></li>
							<li class = 'home'><a href = 'display_orders_to_be_prepared.php'>Orders to Prepare</a></li>
							<li class = 'home'><a href = 'view_message.php'>Messages</a></li>";
			
				echo "</div><div class = 'empty'></div>";
				
				echo "<div class = 'center'><ul>
						<li><input class='submit' type='submit' name='view' value='View Order'></li>
						<li><input class='submit' type='submit' name='dibs' value='Dibs'></li>
						<li><input class='submit' type='submit' name='delivered' value='Delivered'></li>			
						</ul></div>";

		$table_query = "SELECT * FROM orders a 
			INNER JOIN mall b ON a.mall_id = b.mall_id
			INNER JOIN account  c ON a.account_id = c.account_id 
			WHERE a.mall_id = " .$mall_id. " AND a.status != 'Delivered'";

		$zz = "SELECT * from orderitems a
				INNER JOIN orders b ON a.order_id = b.order_id
				INNER JOIN account c ON b.account_id = c.account_id";
		$table2_query = "SELECT * FROM orderitems";

		if(isset($_POST['dibs'])) { #check if the dibs button was pressed
			#change status to preparing
			foreach ($db->query($table_query) as $myrow) { #for all entries in the item list
				$dibs_entry = $myrow["order_id"]; #assign current entry's item_id to variable
				$client_id = $myrow['account_id'];
				if(isset($_POST[$dibs_entry]) != NULL) {	#check if the entry's checkbox was selected/not null(meaning the entry should be deleted)
					#execute change
					#changing the status and placing boy 
					$update_query = "UPDATE orders SET status = 'Assembling', boy_id = '$id' WHERE order_id = '$dibs_entry'";
					$db->query($update_query);	

					#creating a message that it is being done
					$boy_id = $_SESSION['account_id'];		
					$boy_acc_query = "SELECT * FROM account WHERE account_id = " . $boy_id;
					$sender_acc_result = mysqli_query($mysqli,$boy_acc_query);
					$senderAccArray = mysqli_fetch_array($sender_acc_result, MYSQLI_ASSOC);
					$sender_acc= $senderAccArray['first_name'] . " ''".$senderAccArray['username']."'' " . $senderAccArray['last_name'];

					$msg = "Your Order has been received by " . $sender_acc. "! Please wait for 20 mins!";


					$message_query = "INSERT INTO message (recipient_id,sender_id,msg_date,msg) VALUES ( ".$client_id.", " .$boy_id .", now(), '".$msg."')";
					$result = mysqli_query($mysqli,$message_query);				
				}
			}
		}

		else if(isset($_POST['view'])){ #check if the VIEW ORDER button was pressed
			echo 
				"<fieldset class = 'notice'>
					<legend> Order Items </legend>";
					echo "<table>";
			echo 
				"<tr>
					<th>Restaurant</th>
					<th>Food Item</th> 
					<th>Quantity</th> 
				</tr>";
			foreach ($db->query($zz) as $myrow) { #for all entries in the order item list
				$display_entry = $myrow["order_id"];	#assign current entry's item_id to variable						
				if(isset($_POST[$display_entry]) != NULL) {	#check if the entry's checkbox was selected/not null(meaning the entry should be deleted)
					$checkbox_id = $myrow["order_id"];
					$cust_id = $myrow['account_id'];

						#for layout purposes start
							#end
							#echo $checkbox_id; #for checking purposes
							echo	"<td>";

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
							echo "</td>";
							echo "</tr>";
							}
							
					}
					echo "</table>";
					echo "</fieldset>";
	}
		#CHECK CASCADE DELETE
		else if(isset($_POST['delivered'])) {	#check if the DELETE button was pressed
			#print_r($_POST); #for checking purposes
			foreach ($db->query($table_query) as $myrow) { #for all entries in the item list
				$delivered_entry = $myrow["order_id"];	#assign current entry's item_id to variable
				if(isset($_POST[$delivered_entry]) != NULL) {	#check if the entry's checkbox was selected/not null(meaning the entry should be deleted)
					#execute deletion
					$select_order_query = "SELECT status FROM orders WHERE order_id = $delivered_entry";
					foreach(mysqli_query($mysqli,$select_order_query) as $myRow)
			        {
			        	if($myrow['status']== 'Assembling')
			        	{
			        		$delivered_query = "UPDATE orders SET status = 'Delivered' WHERE order_id = '$delivered_entry'";
							$db->query($delivered_query);
			        	}
			        	else
			        	{
			        		echo "Cannot deliver an order if no one is assigned to it yet!";
			        	}
			        }
				}
			} 
		}
		
		#display mall table contents
		echo "<table class='list'>";
		echo 
			"<tr>
				<th>Order ID</th>
				<th>Time Received</th> 
				<th>Username</th> 
				<th>Mall</th>  
				<th>Cinema</th> 
				<th>Seat Number</th> 
				<th>Status</th> 
				<th>Boy in-Charge</th> 
			</tr>";

		#for layout purposes start	
		$num = 0;
		$class = "even";
		#end
		foreach ($db->query($table_query) as $myrow) {
			$checkbox_id = $myrow["order_id"];
			$boy_id = $myrow['boy_id'];

			


			#for layout purposes start
			if($num%2 == 0) 	$class = "even";
			else 	$class = "odd";
			$num++;
			#end
			#echo $checkbox_id; #for checking purposes
			echo "<tr class='$class'> <td>";
			echo "<input type='checkbox' name='$checkbox_id'>", "   ", $myrow["order_id"];
			echo "</td><td class = 'number'>";
			echo $myrow["order_time"];
			echo "</td><td>";
			echo $myrow["username"];
			echo "</td><td>";
			echo $myrow["mall_name"];
			echo "</td><td class = 'number'>";
			echo $myrow["cinema_num"];
			echo "</td><td class = 'number'>";
			echo $myrow["seat_num"];
			echo "</td><td>";
			echo $myrow["status"];
			echo "</td>";
			echo "</td><td>";

			if($boy_id != null)
			{
				$boy_acc_query = "SELECT * FROM account WHERE account_id = " . $boy_id;
				$sender_acc_result = mysqli_query($mysqli,$boy_acc_query);
				$senderAccArray = mysqli_fetch_array($sender_acc_result, MYSQLI_ASSOC);
				$sender_acc= $senderAccArray['first_name'] . " ''".$senderAccArray['username']."'' " . $senderAccArray['last_name'];
				echo $sender_acc;
			}
			echo "</td>";

			echo "</tr>";
		}

		echo "</table>";
		#end of display

		?>
	</body>
</html>