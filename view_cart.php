<?php
session_start(); 
?>

<!DOCTYPE html>
<html>
	<head>
	<title>Your Curent FooBu Cart</title>
	<link href = "styles.css" type = "text/css" rel = "stylesheet"/>
		<script type="text/javascript">
			function checkLoad() {
				var currentLoad = parseInt(<?php 
								$id  = $_SESSION['account_id'];
								$mysqli = new mysqli("localhost","root","","dbadmin");
								$sql = "SELECT * FROM account 
		    							where account_id = ". $id;
								$result = mysqli_query($mysqli,$sql);
				
								$account = mysqli_fetch_array($result, MYSQLI_ASSOC);
								$load = $account['load_balance'];

							echo json_encode($load); ?>);

				var totalPrice = parseInt(<?php
								$sessID = stripslashes($_COOKIE['PHPSESSID']);
								$sessID = mysqli_real_escape_string($mysqli,$sessID);
								$mysqli = new mysqli("localhost","root","","dbadmin");
								$sum_query = "SELECT SUM(total_price) AS totalSum FROM item
								INNER JOIN tracker
								ON item.item_id = tracker.item_id WHERE session_id = '" .$sessID."'";
								$sum_result = mysqli_query($mysqli,$sum_query);
								$sumArray = mysqli_fetch_array($sum_result, MYSQLI_ASSOC);
								$sum = $sumArray['totalSum'];

								echo json_encode($sum); ?>);

				if(currentLoad >= totalPrice)
				{
					window.location = "order_form.php";
				}

				else
				{
					alert("Insufficient Load.\nLoad more into balance or Remove items from cart!"); // fail
					delete window.alert; // true
				}
			}
		</script>
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

	#protection
	$sql = "SELECT * FROM account a
    	LEFT JOIN mall b ON a.mall_id = b.mall_id
    	LEFT JOIN restaurant c ON a.rest_id = c.rest_id
    	where account_id = " .$id;
	$result = mysqli_query($mysqli,$sql);

	$count =  mysqli_num_rows($result);

	if($result)
	{
		$newArray = mysqli_fetch_array($result, MYSQLI_ASSOC);
		$position = $newArray['position'];

			echo "<div class = 'bar'>";
			#Account details
			echo "<p class = 'logout'><a href = 'logout.php'>Log Out</a></p>";
			echo "<p class = 'details'>Welcome " . $newArray['first_name']. " to Food Buddy!<br>";
			echo "Load: " . $newArray['load_balance'] ."<br/>";
			echo "</p><a href = 'homepage.php'><img class = 'logo' src='logo.jpg' title='Home Page' align = 'middle'/></a></div>";
			
			echo "<div class = 'navi'>";
			#Menu Items
			echo "<ul class = 'buttons'>
						<li class = 'home'><a href = 'view_account.php'>View Account Information</a></li>
						<li class = 'home'><a href = 'edit_password.php'>Edit Password</a></li>
						<li class = 'home'><a href = 'choose_mall.php'>Order Food</a></li>
						<li class = 'home'><a href = 'view_message.php'>Messages</a></li>";
			
			echo "</div><div class = 'empty'></div>";
			
			$sessID = stripslashes($_COOKIE['PHPSESSID']);
			$sessID = mysqli_real_escape_string($mysqli,$sessID);
			$id  = $_SESSION['account_id'];

			#protection
			$select_item_query = "SELECT * FROM item a
								INNER JOIN tracker b ON a.item_id = b.item_id
								INNER JOIN restaurant c ON a.rest_id = c.rest_id
								WHERE session_id = '" .$sessID."'";
			$resultz = mysqli_query($mysqli,$select_item_query);

				#protection
			$sql = "SELECT * FROM account 
		    where account_id = ". $id;
			$result = mysqli_query($mysqli,$sql);
				
			$account = mysqli_fetch_array($result, MYSQLI_ASSOC);
			$load = $account['load_balance'];

			//$count =  mysqli_num_rows($result);

			if($result)
			{
				$newArray = mysqli_fetch_array($resultz, MYSQLI_ASSOC);
				$id = $newArray['session_id'];
				$item_name = $newArray['item_name'];

				echo "<h1>Your Cart</h1>";
				echo "<table>
						<tr>
						<th scope = 'col'>Restaurant Name</th>
						<th scope = 'col'>Food Name</th>
						<th scope = 'col'>Price</th>
						<th scope = 'col'>Quantity</th>
						<th scope = 'col'>Total Price</th>
						</tr>";
				$num = 0;
				$class = "even";
				foreach(mysqli_query($mysqli,$select_item_query) as $myRow)
				{
					if($num%2 == 0) 	$class = "even";
					else 	$class = "odd";
					$num++;
					$button_id = $myRow['tracker_id'];
					//$total_price_query = "SELECT price*item_qty AS totalPrice
					//FROM item INNER JOIN tracker 
					//ON item.item_id = tracker.item_id WHERE session_id = '" .$sessID."' AND tracker_id = " .$button_id;
					//$total_price_result = mysqli_query($mysqli,$total_price_query);
					//$tpArray = mysqli_fetch_array($total_price_result, MYSQLI_ASSOC);
					//$tp = $tpArray['totalPrice'];
					//$price =  sprintf('%.02f',$myRow['price']);
					//$button_id = $myRow['item_id'];

			       	echo "<tr class='$class'>
			       		<td>" .$myRow['rest_name']. "</td>
						<td>" .$myRow['item_name']. "</td>
						<td class = 'number'>" .$myRow['price']. "</td>
						<td class = 'number'>" .$myRow['item_qty']. "</td>
						<td class = 'number'>" .$myRow['total_price']. "</td>
						<td class = 'ignore'><a href = 'remove_from_cart.php?id=$button_id'><button class='tb'>Remove from Cart</button></td>
						</tr>"; 
				}
				if($num%2 == 0) 	$class = "even";
				else 	$class = "odd";
				#total price
				$sum_query = "SELECT SUM(total_price) AS totalSum FROM item
								INNER JOIN tracker
								ON item.item_id = tracker.item_id WHERE session_id = '" .$sessID."'";
				$sum_result = mysqli_query($mysqli,$sum_query);
				$sumArray = mysqli_fetch_array($sum_result, MYSQLI_ASSOC);
				$sum = $sumArray['totalSum'];
				$_SESSION['total_price'] = $sum;
				echo "<tr><td><t/d><td></td><td></td><th scope = 'row'>Total Bill<td class = 'number'>$sum</td></tr>";
				echo "</table>";


				//echo "<br><a href = 'order_form.php'><button>Submit</button></a><br>";
				echo "<div class = 'center'>";
				echo "<br><button onclick = 'checkLoad()'>Submit</button><br>";
				echo "<br><a href = 'menu.php'><button>Back</button></a>";
				echo "</div>";
				}
	}

			mysqli_close($mysqli);

		}

	?>
</body>
</html>
