<!DOCTYPE html>
<html>
    <head>
	<title>All FooBu Accounts</title>
        <script type="text/javascript">
            function validate(val) {                            
                if(val == "Add Mall") {
                    var x = document.forms["main_form"]["mall_name"].value;                 
                    if(x == null || x == "") {
                        alert("Please put a valid mall name.");
                        return false;
                    }
                }
                else if(val == "Save Changes") {
                    var y = document.forms["main_form"]["new_mall_name"].value;
                    if(y == null || y == "") {
                        alert("Please put a new valid mall name.");
                        return false;
                    }
                }
            }
            function viewPage() {
                window.location = "display_accounts.php";              
            }
            function goBack() {
                window.location = "homepage.php";
            }           
        </script>
        <link href="styles.css" type="text/css" rel="stylesheet">
    </head>
    <body>

    <form name="main_form" method="post" action="display_accounts.php" onsubmit="return validate()">

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
			$sql = "SELECT * FROM account a
        	LEFT JOIN mall b ON a.mall_id = b.mall_id
        	LEFT JOIN restaurant c ON a.rest_id = c.rest_id
            WHERE position <> 'Dev'
        	ORDER BY account_id ASC";
			$sql2 = "SELECT * FROM account a
			LEFT JOIN mall b ON a.mall_id = b.mall_id
			LEFT JOIN restaurant c ON a.rest_id = c.rest_id
			where account_id = " .$id;
			
			#SELECT * FROM account a
			#LEFT JOIN mall b ON a.mall_id = b.mall_id
			#LEFT JOIN restaurant c ON a.rest_id = c.rest_id
			#where account_id = " .$id;
			$result = mysqli_query($mysqli,$sql);
			$result2 = mysqli_query($mysqli,$sql2);
		
			if($result)
			{
				$newArray = mysqli_fetch_array($result2, MYSQLI_ASSOC);
				$position = $newArray['position'];

				echo "<div class = 'bar'>";
				#Account details
				echo "<p class = 'logout'><a href = 'logout.php'>Log Out</a></p>";
				echo "<p class = 'details'>Welcome " . $newArray['first_name']. " to Food Buddy!<br>";
				echo "Position: " . $newArray['position']. "<br>";
				echo "</p><a href = 'homepage.php'><img class = 'logo' src='logo.jpg' title='Home Page' align = 'middle'/></a></div>";
			
				echo "<div class = 'navi'>";
				#Menu Items
					echo "<ul class = 'buttons'>
							<li class = 'home'><a href = 'view_account.php'>Account Information</a></li>
							<li class = 'home'><a href = 'edit_password.php'>Edit Password</a></li>
							<li class = 'home'><a href = 'display_malls.php'>Malls</a></li>
							<li class = 'home'><a href = 'display_rest.php'>Restaurants</a></li>
							<li class = 'home'><a href = 'display_accounts.php'>Promote/Demote Account</a></li>
							<li class = 'home'><a href = 'view_message.php'>Messages</a></li>";
			
				echo "</div><div class = 'empty'></div>";
				
				echo "<div class = 'center'><ul>
						<!--<li><input class='submit' type='submit' name='delete_all' value='Delete All'></li>-->
						<li><input class='submit' type='button' name='view' value='View List' onclick='viewPage()'></li>
						<li><input class='submit' type='submit' name='promoteToAdmin' value='Promote to Admin'></li>
						<li><input class='submit' type='submit' name='promoteToBoy' value='Promote to Boy'></li>
						<li><input class='submit' type='submit' name='demote' value='Demote'></li>          
						</ul></div>";
				
				$select_mall_query = "SELECT * from restaurant INNER JOIN mall ON restaurant.mall_id = mall.mall_id";
				$result2 = mysqli_query($mysqli,$select_mall_query);

				$mall_query = "SELECT * from mall";
				$result3 = mysqli_query($mysqli,$mall_query);

				//$count =  mysqli_num_rows($result);

				if(isset($_POST['promoteToAdmin'])) 
				{ #check if the ADD button was pressed
					$edit_rest_name = NULL;
					foreach(mysqli_query($mysqli,$sql)  as $myrow) 
					{ #for all entries in the restaurant list
						$edit_entry_id = $myrow['account_id']; #assign current entry's rest_id to variable
						if(isset($_POST[$edit_entry_id]) != NULL) 
						{ #check if the entry's checkbox was selected (meaning the entry should be deleted)
							$edit_rest_name = $myrow['account_id']; #assign entry's restaurant name to variable
							break; #get out of loop
						}
					}
					if($edit_rest_name == NULL) 
					{
						echo 
						"<p class='notif'> Please select one (1) account entry to edit, then click [Promote to Admin]. <br> <br>
							Note that if multiple entries are selected, only the <b>first one</b> selected on the list will be considered for promotion.
						</p>";
					}
					else 
					{
						#display form for EDIT RESTAURANT
						echo "<input type='hidden' name='account_id' value='$edit_rest_name'>";
						echo 
							"<fieldset class = 'notice'>
							<legend> Promote to Admin </legend>";
						echo 
                        "<label class='in'>Select Mall-Restaurant: </label>
                        <select class='mall_list' name='malls'>
                            <option value='0' selected>Choose a Mall</option>";"<label class='in'>Mall Name: </label>";
						foreach(mysqli_query($mysqli,$select_mall_query) as $option) 
						{
							$r_id = $option['rest_id'];
							$rest_name = $option['rest_name'];
							$mall_name = $option['mall_name'];
							echo "<option value='$r_id'>$mall_name - $rest_name</option>";
						}
						echo
							"</select><br>
							<input class='sub_submit' type='submit' name='promoteAdmin' value='Promote to Admin'>
							</fieldset>";
					}
				}
				else if(isset($_POST['promoteAdmin']))
					{
						$account_id = $_POST['account_id'];
						$restaurant_id = $_POST['malls'];


						$mall_query = "SELECT mall_id FROM restaurant where rest_id = ". $restaurant_id;
						$mall_result = mysqli_query($mysqli,$mall_query);
						$mallArray = mysqli_fetch_array($mall_result, MYSQLI_ASSOC);

						$mall_id = $mallArray['mall_id'];

						$account_id = stripslashes($account_id);
						$restaurant_id = stripslashes($restaurant_id);
						$mall_id = stripslashes($mall_id);
						$account_id = mysqli_real_escape_string($mysqli,$account_id);
						$restaurant_id = mysqli_real_escape_string($mysqli,$restaurant_id);
						$mall_id = mysqli_real_escape_string($mysqli,$mall_id);


						$update_account_query = "UPDATE account SET rest_id = " .$restaurant_id. ", mall_id = " .$mall_id. ", position = 'Admin' WHERE account_id = ". $account_id;
						$result = mysqli_query($mysqli,$update_account_query);
					}
					#boy
					else if(isset($_POST['promoteToBoy'])) 
					{ #check if the ADD button was pressed
						$edit_rest_name = NULL;
						foreach(mysqli_query($mysqli,$sql)  as $myrow) 
						{ #for all entries in the restaurant list
							$edit_entry_id = $myrow['account_id']; #assign current entry's rest_id to variable
							if(isset($_POST[$edit_entry_id]) != NULL) 
							{ #check if the entry's checkbox was selected (meaning the entry should be deleted)
								$edit_rest_name = $myrow['account_id']; #assign entry's restaurant name to variable
								break; #get out of loop
							}
						}
						if($edit_rest_name == NULL) 
						{
							echo 
								"<p class='notif'> Please select one (1) account entry to edit, then click [Promote to Boy]. <br> <br>
									Note that if multiple entries are selected, only the <b>first one</b> selected on the list will be considered for promotion.
								</p>";
						}
						else 
						{ #display form for EDIT RESTAURANT
							echo "<input type='hidden' name='account_id' value='$edit_rest_name'>";
							echo 
								"<fieldset class = 'notice'>
								<legend> Promote to Boy </legend>";
							echo 
								"<label class='in'>Select Mall-Restaurant: </label>
								<select class='mall_list' name='mall'>
								<option value='0' selected>Choose a Mall</option>";"<label class='in'>Mall Name: </label>";
							foreach(mysqli_query($mysqli,$mall_query) as $option) 
							{
								$r_id = $option['mall_id'];
								$mall_name = $option['mall_name'];
								echo "<option value='$r_id'>$mall_name</option>";
							}
							echo
								"</select><br>
								<input class='sub_submit' type='submit' name='promoteBoy' value='Promote to Boy'>
								</fieldset>";
						}
					}
					else if(isset($_POST['promoteBoy']))
						{
								$account_id = $_POST['account_id'];
							$mall_id = $_POST['mall'];

							$account_id = stripslashes($account_id);
							$mall_id = stripslashes($mall_id);
							$account_id = mysqli_real_escape_string($mysqli,$account_id);
							$mall_id = mysqli_real_escape_string($mysqli,$mall_id);


							$update_account_query = "UPDATE account SET mall_id = " .$mall_id. ", rest_id = NULL, position = 'Boy' WHERE account_id = ". $account_id;
							$resultz = mysqli_query($mysqli,$update_account_query);
						}
						else if(isset($_POST['demote']))
						{
							foreach(mysqli_query($mysqli,$sql)  as $myrow) 
							{ 
								$account_id = $myrow['account_id'];  #assign current entry's rest_id to variable
								if(isset($_POST[$account_id]) != NULL) 
								{  #check if the entry's checkbox was selected/not null(meaning the entry should be deleted)
									#execute deletion
									$demote_query = "UPDATE account SET position = 'Client', mall_id = NULL, rest_id = NULL WHERE account_id = ". $account_id;
									$demoteResult = mysqli_query($mysqli,$demote_query);                  
								}
							}
							echo "<p class='notif'> Accounts(s) demoted to Client. </p>";
						}
						
						echo "<table class = 'list'>
							<tr>
							<th scope = 'col'>Account ID</th>
							<th scope = 'col'>Full Name</th>
							<th scope = 'col'>Username</th>
							<th scope = 'col'>Position</th>
							<th scope = 'col'>Mall</th>
							<th scope = 'col'>Restaurant</th>
							<th scope = 'col'>Load Balance (in Peso)</th>
							</tr>";
						
						$tables = "SELECT * FROM account a
						LEFT JOIN mall b ON a.mall_id = b.mall_id
						LEFT JOIN restaurant c ON a.rest_id = c.rest_id
						WHERE position <> 'Dev'
						ORDER BY account_id ASC";
						
						$num = 0;
						$class = "even";
						foreach(mysqli_query($mysqli,$tables) as $myRow)
						{
							$checkbox_id = $myRow['account_id'];
							if($num%2 == 0) 	$class = "even";
							else 	$class = "odd";
							$num++;
							echo "<tr class='$class'> 
								<td><input type='checkbox' name='$checkbox_id'>", "   ", $myRow['account_id'];
							echo "<td>" .$myRow['first_name']. " " .$myRow['middle_name']. " ".  $myRow['last_name'] ."</td>
									<td>" .$myRow['username']. "</td>
									<td>" .$myRow['position']. "</td>
									<td>" .$myRow['mall_name']. "</td>
									<td>" .$myRow['rest_name']. "</td>
									<td class = 'number'>" .$myRow['load_balance']. "</td>";
									"</tr>"; 
						}
						echo "</table>";

        
			}
	}?>
        </form>
    </body>
</html>