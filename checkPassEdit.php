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
	echo "<html><head><title>Changing FooBu Password</title>";
	echo "</head><link href = 'styles.css' type = 'text/css' rel = 'stylesheet'/>";
	echo "<body>";
	
    $old_pass = $_POST["old_password"];
    $new_pass = $_POST["password1"];

    #protection
    $old_pass = stripslashes($old_pass);
    $old_pass = mysqli_real_escape_string($mysqli,$old_pass);
    $new_pass = stripslashes($new_pass);
    $new_pass = mysqli_real_escape_string($mysqli,$new_pass);

    $id  = $_SESSION['account_id'];
    $sql = "SELECT * FROM account a
    	LEFT JOIN mall b ON a.mall_id = b.mall_id
    	LEFT JOIN restaurant c ON a.rest_id = c.rest_id
    	where a.account_id = ". $id;
    $result = mysqli_query($mysqli,$sql);

    $count =  mysqli_num_rows($result);

    if($result)
    {
		
        $newArray = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $old_password = $newArray['password'];
		
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
					if($position != 'Boy') echo "Mall: " . $newArray['mall_name'] . "     Restaurant: " . $newArray['rest_name']. "<br>";
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
			
			echo "</div><div class = 'empty'></div>";

        if($old_password != $old_pass)
        {
            echo "<h1>You entered an invalid old password!</h1>";
			echo "<div class = 'center'><br><a href = 'edit_password.php' ><button>Back</button></a></div>";
        }
        else
        {
            $sql = "UPDATE account SET password = '" .$new_pass. "' WHERE account_id = " . $id  ;

			$res = mysqli_query($mysqli, $sql);
			if ($res === TRUE) 
			{
				echo "<h1>Your Password has been edited!</h1>";
			}
			else 
			{
				printf("Could not edit password: %s\n", mysqli_error($mysqli));
			}
        }
    }
}
        

?>