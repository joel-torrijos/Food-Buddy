<?php
session_start();
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Choose a Mall</title>
		<link href = "styles.css" type = "text/css" rel = "stylesheet"/>
	</head>
	<body>

	<form method = 'post' action = 'menu.php'>

		<?php
		$mysqli = new mysqli("localhost","root","","dbadmin");

		if(mysqli_connect_errno())
		{
		    printf("Connect failed: %s\n", mysqli_connect_error());
		    exit();
		}else
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
			echo "Load: " . $account['load_balance'] ."<br/>";
			echo "</p><a href = 'homepage.php'><img class = 'logo' src='logo.jpg' title='Home Page' align = 'middle'/></a></div>";
			
			echo "<div class = 'navi'>";
			#Menu Items
			echo "<ul class = 'buttons'>
						<li class = 'home'><a href = 'view_account.php'>Account Information</a></li>
						<li class = 'home'><a href = 'edit_password.php'>Edit Password</a></li>
						<li class = 'home'><a href = 'choose_mall.php'>Order Food</a></li>
						<li class = 'home'><a href = 'view_message.php'>Messages</a></li>";
			
			echo "</div><div class = 'empty'></div>";
			$select_mall_query = "SELECT * FROM mall";
			
			echo "<div class = 'center'>";
		    echo "<select class='mall_list' name='mall'>
								<option value='0'>Choose a mall</option>";
					foreach(mysqli_query($mysqli,$select_mall_query)  as $option) {
						$id = $option['mall_id'];
						$name = $option['mall_name'];
						echo "<option value='$id'>$name</option>";
			}
			echo "<input class = 'submit' type = 'submit' name = 'chooseMall' value = 'Im here at this Mall!'>";
			echo "</div>";
		}
			session_regenerate_id();
		}
		?>

	</form>
	</body>
</html>
