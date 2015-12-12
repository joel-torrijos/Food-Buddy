<!DOCTYPE html>
<html>
<head>
	<title>FooBu Homepage</title>
	<link href = "styles.css" type = "text/css" rel = "stylesheet"/>
</head>

<body>

<form action = "checkcreate.php" onsubmit = "return validateForm()" method = "POST" name = "myForm">
<?php
	session_start();
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
    	where a.account_id = ". $id;
		$result = mysqli_query($mysqli,$sql);
		$count =  mysqli_num_rows($result);
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
				echo "Position: " . $account['position'] . "<br>";
				if($position != 'Dev')
				{
					if($position != 'Boy') echo  "Mall: " . $account['mall_name'] . "     Restaurant: " . $account['rest_name']. "<br>";
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
			
			echo "</div>";
		}
	}

?>
<br><br><br><br><br><br>
<div class = "promo">
<h1>Tis' the Season to be Jolly</h1>
<p>December 1, 2015 - FooBu</p>
<img src="star_wars_christmas_3.jpg" title="Christmas Promo"/>
<p>Christmas is just around the corner! What better way to celebrate than to watch the upcoming Star Wars film! You can win a movie-date to Star Wars VII: The Force Awakens presented by FoodBuddy and Ayala Malls Cinema. For every P500 worth of purchase in Trinoma restaurants, you automatically have a chance to win two tickets for a December 20 screening!
</p>
<p>May the Force be with you and Merry Christmas!</p>
</div>

<div>
<br><br><br>
<p class = "rights">FoodBuddy&copy is a project for movie-lovers, food enthusiasts and philanthropists by Joel Torrijos, Viktor Dela Cruz, April Guevara and Adrian Cordero.</p>
</div>
</body>
</html>