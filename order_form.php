<!DOCTYPE html>
<html>
<head>
	<title>Order Form</title>
	<script>
	function validateForm() {
	var a = document.forms["orderForm"]["cinema_num"].value;
	var b = document.forms["orderForm"]["seat_num"].value;
    if (a == "" || b == "") {
        alert("Some fields need to be field!");
        return false;
    }
}
    </script>
	<link href="styles.css" type="text/css" rel="stylesheet">
</head>
<body>
	<form action = 'place_order.php'  onsubmit = "return validateForm()" method = 'post' name = 'orderForm'>

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
			$sql = "SELECT * FROM account WHERE account_id = " .$id;
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
						<li class = 'home'><a href = 'view_account.php'>View Account Information</a></li>
						<li class = 'home'><a href = 'edit_password.php'>Edit Password</a></li>
						<li class = 'home'><a href = 'choose_mall.php'>Order Food</a></li>
						<li class = 'home'><a href = 'view_message.php'>Messages</a></li>";
			
				echo "</div><div class = 'empty'></div><br>";
			}
		}
		
	?>

	
	<fieldset class = "creation">
		<legend>Your Location</legend>
		<label for 'cinema_num'>Cinema</label> <input type = 'text' name = 'cinema_num' placeholder = 'Cinema'> <br><br>
		<label for 'seat_num'>Seat Number</label> <input type = 'text' name = 'seat_num' placeholder = 'Seat Number'> 
	</fieldset>
	<br><br>
	<div class = 'center'>
	 <input class = "submit" type = 'submit' value = 'Confirm Order'> 
	 </div>
	</form>

	<?php 
	echo "<div class = 'center'><br><a href = 'view_cart.php'><button>Back to View Cart</button></a></div>";
	?>
	</div>
</body>
</html>
