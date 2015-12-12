<?php
session_start();

echo "<html>
<head>
	<title>Order Sending Result</title>
	<link href = 'styles.css' type = 'text/css' rel = 'stylesheet'/>
</head><body>";
$mysqli = new mysqli("localhost","root","","dbadmin");

if(mysqli_connect_errno())
{
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
} else
{
	#protection
	$id  = $_SESSION['account_id'];
	$sql = "SELECT * FROM account a
    	LEFT JOIN mall b ON a.mall_id = b.mall_id
    	LEFT JOIN restaurant c ON a.rest_id = c.rest_id
    	where a.account_id = ". $id;
		$result = mysqli_query($mysqli,$sql);
	
			$account = mysqli_fetch_array($result, MYSQLI_ASSOC);
			$position = $account['position'];

			echo "<div class = 'bar'>";
			#Account details
			echo "<p class = 'logout'><a href = 'logout.php'>Log Out</a></p>";
			echo "<p class = 'details'>Welcome " . $account['first_name']. " to Food Buddy!<br>";
			echo "Load: " . $account['load_balance'] ."<br/>";
			echo "</p><a href = 'homepage.php'><img class = 'logo' src='logo.jpg' title='Home Page' align = 'middle'/></a></div>";
			
			echo "<div class = 'navi'>";
			#Menu Items
			echo "<ul class = 'buttons'>
						<li class = 'home'><a href = 'view_account.php'>View Account Information</a></li>
						<li class = 'home'><a href = 'edit_password.php'>Edit Password</a></li>
						<li class = 'home'><a href = 'choose_mall.php'>Order Food</a></li>
						<li class = 'home'><a href = 'view_message.php'>Messages</a></li>";
			
			echo "</div><div class = 'empty'></div>";
			
			
	$mall_id  = $_SESSION['mall_id'];
	$total_price  = $_SESSION['total_price'];

	$id = stripslashes($id);
	$id = mysqli_real_escape_string($mysqli,$id);
	$mall_id = stripslashes($mall_id);
	$mall_id = mysqli_real_escape_string($mysqli,$mall_id);
	$total_price = stripslashes($total_price);
	$total_price = mysqli_real_escape_string($mysqli,$total_price);
	$cinema_num = stripslashes($_POST['cinema_num']);
	$cinema_num = mysqli_real_escape_string($mysqli,$cinema_num);
	$seat_num = stripslashes($_POST['seat_num']);
	$seat_num = mysqli_real_escape_string($mysqli,$seat_num);

	$sessID = stripslashes($_COOKIE['PHPSESSID']);
	$sessID = mysqli_real_escape_string($mysqli,$sessID);


	#Inserts the New order into Order
	$place_order_sql = "INSERT INTO orders (cinema_num,order_time,mall_id,seat_num,status,account_id,total_price) 
						VALUES ('".$cinema_num. "', now(), " .$mall_id. ", '" .$seat_num."', 'Waiting', " .$id. ", " .$total_price.")";
	$result = mysqli_query($mysqli,$place_order_sql);

	$order_from_tracker_sql = "SELECT * FROM tracker WHERE session_id = '".$sessID."'";
	$resultTracker = mysqli_query($mysqli,$order_from_tracker_sql);

	$get_bal_query = "SELECT load_balance FROM account WHERE account_id = ".$id;
	$resultBal = mysqli_query($mysqli,$get_bal_query);
	$newArray = mysqli_fetch_array($resultBal, MYSQLI_ASSOC);
	$bal = $newArray['load_balance'];

	$bal -= $total_price;
		

	#Gets the last row of the table
	$place_order_sql = "SELECT order_id FROM orders
							ORDER BY order_id DESC
							LIMIT 1;";
	$last = mysqli_query($mysqli,$place_order_sql);
	$curr = mysqli_fetch_array($last, MYSQLI_ASSOC);
	$current_order_id =  $curr['order_id'];	
	

	//$count =  mysqli_num_rows($result);

	if ($result == TRUE) 
	{
		
		foreach(mysqli_query($mysqli,$order_from_tracker_sql) as $myRow)
		{
			$item_id = $myRow['item_id'];
			$item_qty = $myRow['item_qty'];
			$place_order_in_orderlist_sql = "INSERT INTO orderitems (order_id,item_id,item_qty,total_price) VALUES (".$current_order_id. "," .$item_id.", " .$item_qty.", " .$myRow['total_price'].")";
			$resultz = mysqli_query($mysqli,$place_order_in_orderlist_sql);

			$load_query = "UPDATE account SET load_balance = '$bal' WHERE account_id = '$id'";
			$resulta = mysqli_query($mysqli,$load_query);

			#if order was successfully inserted, the tracker counterpart will be deleted!
			if($resultz == TRUE)
			{
				$delete_item_in_tracker_sql = "DELETE FROM tracker WHERE
						session_id = '". $sessID. "'";
				$delete_item_res = mysqli_query($mysqli, $delete_item_in_tracker_sql) or die(mysqli_error($mysqli));
			}
		}

		echo "<h1>Order has been placed!</h1>";

	}
	else {
		printf("Could not insert record: %s\n", mysqli_error($mysqli));
	}
}

	echo "</body>";
	mysqli_close($mysqli);

	"INSERT INTO orders (cinema_num,order_time,mall_id,seat_num,status,account_id,total_price) 
						VALUES ('5F', now(), 3, 'F5', 'Waiting', 3, 69)";

?>

