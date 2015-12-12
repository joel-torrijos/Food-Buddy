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
    	where account_id = " .$id;
	$result = mysqli_query($mysqli,$sql);
			if($result)
	{
		$newArray = mysqli_fetch_array($result, MYSQLI_ASSOC);
		$position = $newArray['position'];
		echo "<html>
		<head>
			<title>Available Menus</title>
			<link href='styles.css' type='text/css' rel='stylesheet'>
			<script>
			function askForQuantity(button) {
				var lol = button;
				var qty = prompt('How many orders do you want?');

				if(qty > 0)
				{
					document.location = 'add_to_cart.php?id='+lol+'&qty='+qty;
				}
			}
			</script>
		</head>
		<body>
		<form name='main_form' method='post' action='menu.php'>";
		

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
			
			$temp = $_COOKIE['PHPSESSID'];
			//echo "$temp";
			if(isset($_POST['chooseMall']))
			{
				echo "<div class = 'center'>";
				$mall_id = $_POST['mall'];
				$_SESSION['mall_id'] = $mall_id;

				$rest_query = "SELECT * FROM restaurant WHERE mall_id = " . $mall_id;;

				echo 
					"<select class='rest_list' name='rest'>
					<option value='0' selected>Choose a Restaurant</option>";
					foreach(mysqli_query($mysqli,$rest_query) as $option) {
		                    $r_id = $option['rest_id'];
		                    $rest_name = $option['rest_name'];
		                    echo "<option value='$r_id'>$rest_name</option>";
		                }
					echo "</select>";
				echo "<input class='submit' type='submit' name='chooseRest' value='Choose a Restaurant'>";
				echo "<div class = 'center'>";
			}
			
			else if(isset($_SESSION['mall_id']))
			{
				echo "<div class = 'center'>";
				$mall_id = $_SESSION['mall_id'];
				$_SESSION['mall_id'] = $mall_id;

				$rest_query = "SELECT * FROM restaurant WHERE mall_id = " . $mall_id;;

				echo 
					"<select class='rest_list' name='rest'>
					<option value='0' selected>Choose a Restaurant</option>";
					foreach(mysqli_query($mysqli,$rest_query) as $option) {
		                    $r_id = $option['rest_id'];
		                    $rest_name = $option['rest_name'];
		                    echo "<option value='$r_id'>$rest_name</option>";
		                }
					echo "</select>";
				echo "<input class='submit' type='submit' name='chooseRest' value='Choose a Restaurant'>";
				echo "</div>";
			}
	}

	?>
	</form>
	<?php
		$currentRest = 0;

			if(isset($_POST['chooseRest']))
			{
				$currentRest = $_POST['rest'];
				$_SESSION['rest_id'] = $currentRest;
			}

			else if(isset($_POST['rest']))
			{
				$currentRest = $_POST['rest'];
			}
			else if(isset($_SESSION['rest_id']))
			{
				$currentRest = $_SESSION['rest_id'];
			}

			$select_food_query = "SELECT * FROM item
										INNER JOIN restaurant
										ON item.rest_id = restaurant.rest_id WHERE restaurant.rest_id = " .$currentRest ." AND is_available = 1";
				$result = mysqli_query($mysqli,$select_food_query);

			    if($result)
			    {
			        $newArray = mysqli_fetch_array($result, MYSQLI_ASSOC);

			        echo "<h1>". $newArray['rest_name']. " Menu </h1>";
			        
			        //echo "<h1>$current_mall Menu</h1>";
					echo "<table>
							<tr>
							<th scope = 'col'>Restaurant Name</th>
							<th scope = 'col'>Food Name</th>
							<th scope = 'col'>Price</th>
							</tr>";

							
					$num = 0;
					$class = "even";
			        foreach(mysqli_query($mysqli,$select_food_query) as $myRow)
			        {
			        	$price =  sprintf('%.02f',$myRow['price']);
			        	$button_id = $myRow['item_id'];

						if($num%2 == 0) 	$class = "even";
						else 	$class = "odd";
						$num++;
			        	echo "<tr class='$class'>
							<td>" .$myRow['rest_name']. "</td>
							<td>" .$myRow['item_name']. "</td>
							<td class = 'number'>" .$price. "</td>
							<td class = 'ignore'><button class='tb' id = $button_id onclick ='askForQuantity(this.id)'>Add to Cart</button></a></td>
							</tr>"; 
			        }
			        echo "</table></div>";
							
				}


		}
		?>
		<?php echo "<div class = 'center'><br><a href = 'view_cart.php'><button>View Cart</button></a>";
				echo "<br><br><a href = 'choose_mall.php'><button>Back</button></a></div>";?>
	</body>
</html>