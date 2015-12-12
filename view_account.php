<?php

session_start();
$mysqli = new mysqli("localhost","root","","dbadmin");

echo "<html><head><title>Your FooBu Account</title>";
echo "</head><link href = 'styles.css' type = 'text/css' rel = 'stylesheet'/>";
echo "<body>";

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

			if($position != 'Client')
			{
				echo "Position: " . $newArray['position']. "<br>";
				if($position != 'Dev')
				{
					if($position != 'Boy') echo  "Mall: " . $newArray['mall_name'] . ", Restaurant: " . $newArray['rest_name']. "<br>";
					else echo  "Mall: " . $newArray['mall_name'];
				}
			}
			if($position == 'Client') echo "Load: " . $newArray['load_balance'] ."<br/>";
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
			
			echo "</div><div class = 'empty'></div><br>";
			
		$id = $newArray['account_id'];
		$fname = $newArray['first_name'];
		$mname = $newArray['middle_name'];
		$lname = $newArray['last_name'];
		$username = $newArray['username'];
		$load_balance = $newArray['load_balance'];
		
		#
		echo "<table class = 'account'>
				<tr class = 'odd'>
				<th scope = 'row'>Account ID:<td class = 'number'>" .$id. "</td></tr>"; 
		echo "<tr class = 'even'>
				<th scope = 'row'>First Name:<td>" .$fname. "</td></tr>"; 
		echo "<tr class = 'odd'>
				<th scope = 'row'>Middle Name:<td>" .$mname. "</td></tr>"; 
		echo "<tr class = 'even'>
				<th scope = 'row'>Last Name:<td>" .$lname. "</td></tr>"; 
		echo "<tr class = 'odd'>
				<th scope = 'row'>Position:<td>" .$position. "</td></tr>";
		echo "<tr class = 'even'>
				<th scope = 'row'>Mall Name:<td>" . $newArray['mall_name']. "</td></tr>";
		echo "<tr class = 'odd'>
				<th scope = 'row'>Restaurant Name:<td>" . $newArray['rest_name']. "</td></tr>";
		echo "<tr class = 'even'>
				<th scope = 'row'>Load Balance (in Pesos):<td class = 'number'>" .$load_balance. "</td></tr>
				</table>"; 

		echo "<div class = 'center'>";
		echo "<br><a href = 'edit_account.php' ><button>Edit</button></a></br>";
		echo "</div>";
	}
	else
	{
		echo "<br>Wrong Username or Password!";
		
	}
}
?>
