<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head><title>Edit FooBu Password</title>
<script>
function validateForm() {
	var a = document.forms["myForm"]["old_password"].value;
    var b= document.forms["myForm"]["password1"].value;
	var c = document.forms["myForm"]["password2"].value;
    if (a== "" || b == "" || c == "") {
        alert("Please fill in all fields.");
        return false;
    }else if(b != c)
    {
    	alert("New Passwords are not equal!");
        return false;
    }
}
</script>
</head>
<link href = "styles.css" type = "text/css" rel = "stylesheet"/>

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
				echo  "Position: " . $account['position']. "<br>";
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
		}
	}
?>
<h1>Edit Password</h1>
<p>
<form action = "checkPassedit.php" onsubmit = "return validateForm()" method = "POST" name = "myForm">
<fieldset class = "creation">
	<legend>Password</legend>
	Old Password:<br>
    <input type = "password" id = "old_password" name = "old_password"  placeholder = "Old Password"><br><br>
    New Password:<br>
    <input type = "password" id = "password1" name = "password1" placeholder = "New Password"> 
    <input type = "password" id = "password2" name = "password2" placeholder = "Re-enter New Password">
</fieldset>
</p>
	<div class = 'center'>
	<input class = "submit" type = "submit" value = "Submit">
	</div>
	
</form>
<!--Buttons-->
<!--WIP-->
</body>
</html>