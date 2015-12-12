<!DOCTYPE html>
<html>
	<head>
	<title>All FooBu Restaurants</title>
		<script type="text/javascript">
			function viewPage() {
				window.location = "display_rest.php";
			}
			function goBack() {
				window.location = "homepage.php";
			}
		</script>
		<link href="styles.css" type="text/css" rel="stylesheet">
	</head>
	<body>

	<form name="main_form" method="post" action="display_rest.php">

		<?php 
		session_start();

		#setting up the connection
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
					<li><input class='submit' type='submit' name='add' value='Add'></li>
					<li><input class='submit' type='submit' name='delete' value='Delete'></li>
					<li><input class='submit' type='submit' name='edit' value='Edit'></li>			
					</ul></div>";
		
				$select_query = "SELECT * FROM mall";
				$table_query = "SELECT * FROM restaurant INNER JOIN mall ON restaurant.mall_id = mall.mall_id";
				$result3 = mysqli_query($mysqli,$select_query);
				#$db->query($table_query);

				#print_r($_POST); #for checking purposes
		
				/*if(isset($_POST['delete_all'])) {
					$complete_delete_query = "TRUNCATE restaurant";
					$db->query($complete_delete_query);
					echo "<p class='notif'> List emptied. If you have done this by mistake, then oops. </p>";
				}*/
				if(isset($_POST['add'])) 
				{ #check if the ADD button was pressed
					#display form for ADD RESTAURANT
					echo 
						"<fieldset class = 'notice'>
						<legend> Add New Restaurant </legend>";
					echo "<label class='in'>New Restaurant Name: </label>";
					echo			
						"<input class='text' type='text' name='rest_name'><br>";
					echo 
						"<label class='in'>Select Mall: </label>
						<select class='mall_list' name='malls'>
						<option value='0' selected>Choose a Mall</option>";
					foreach(mysqli_query($mysqli,$select_query) as $option) 
					{
						$r_id = $option['mall_id'];
						$mall_name = $option['mall_name'];
						echo "<option value='$r_id'>$mall_name</option>";
					}
					echo
						"</select><br>
						<input class='sub_submit' type='submit' name='add_rest' value='Add Restaurant'>
					</fieldset>";

					#end of display	
				}
				else if(isset($_POST['delete'])) 
				{	#check if the DELETE button was pressed		
					foreach ($mysqli->query($table_query) as $myrow)
					{ #for all entries in the restaurant list
						$delete_entry = $myrow["rest_id"];	#assign current entry's rest_id to variable
						if(isset($_POST[$delete_entry]) != NULL) 
						{	#check if the entry's checkbox was selected/not null(meaning the entry should be deleted)
							#execute deletion
							$delete_query = "DELETE FROM restaurant WHERE rest_id = '$delete_entry'";
							$delete_result = mysqli_query($mysqli,$delete_query);

							if($delete_result === TRUE)
							{
								echo "<p class='notif'> Restaurant(s) deleted. </p>";
							}

							else
							{
								echo "<p class='notif'> Can't delete: Accounts are tied to this Restaurant! Try to reassign the Accounts from this Restaurant first then Delete!</p>";
							}

						}
					}		
				}
				else if(isset($_POST['edit'])) 
				{
					$edit_rest_name = NULL;
					foreach ($mysqli->query($table_query) as $myrow) 
					{ #for all entries in the restaurant list
						$edit_entry_id = $myrow["rest_id"];	#assign current entry's rest_id to variable
						if(isset($_POST[$edit_entry_id]) != NULL) 
						{	#check if the entry's checkbox was selected (meaning the entry should be deleted)
							$edit_rest_name = $myrow["rest_name"]; #assign entry's restaurant name to variable
							break; #get out of loop
						}
					}
					if($edit_rest_name == NULL) 
					{
						echo 
							"<p class='notif'> Please select one (1) restaurant entry to edit, then click [Edit]. <br> <br>
							Note that if multiple entries are selected, only the <b>first one</b> selected on the list will be considered for editing.
							</p>";
					}
					else 
					{
						#display form for EDIT RESTAURANT
						echo 
							"<fieldset class = 'notice'>
							<legend> Edit Restaurant Data </legend>";
						echo "<label class='in'>Restaurant Name: </label>";
						echo
							"<input class='text' type='text' name='new_rest_name' value='$edit_rest_name'><br>";
						echo 
							"<label class='in'>Select Mall: </label>
							<select class='mall_list' name='malls'>
							<option value='0'>Choose a Mall</option>";
						foreach($mysqli->query($select_query) as $option) 
						{
							$id = $option['mall_id'];
							$name = $option['mall_name'];
							echo "<option value='$id'>[$id] $name</option>";
						}
						echo
						"</select><br>
							<input class='sub_submit' type='submit' name='edit_rest' value='Save Changes'>
							<input type='hidden' name='id' value='$edit_entry_id'><!--#to send rest_id-->						
						</fieldset>";
					}
				}
				else if(isset($_POST['add_rest']))
				{
					$rest_name = $_POST['rest_name']; #get the restaurant name from the textbox named "rest_name"	
					$rest_mall_id = $_POST['malls']; #get the mall name from the dropdown list called "malls"
					#execute insertion
					#temporarily hard-coded to insert mall_id
					$insert_query = "INSERT INTO restaurant(mall_id, rest_name) VALUES ('$rest_mall_id', '$rest_name')";
					$mysqli->query($insert_query);
					echo "<p class='notif'> Restaurant added. </p>";
				}
				else if(isset($_POST['edit_rest'])) 
				{
					$new_rest_name = $_POST['new_rest_name']; #get the restaurant name from the textbox named "new_rest_name"
					$new_rest_mall_id = $_POST['malls'];
					$rest_id = $_POST['id']; #get the restaurant id from the hidden input component of the form named "id"			
					#execute update
					$update_query = "UPDATE restaurant SET rest_name = '$new_rest_name', mall_id = '$new_rest_mall_id' WHERE rest_id = '$rest_id'";
					$mysqli->query($update_query);
					echo "<p class='notif'> Restaurant updated. </p>";
				}

				#display restaurant table contents
				echo "<table class='list'>";
				echo 
					"<tr>
					<th>Restaurant ID</th>
					<th>Mall ID</th>		
					<th>Restaurant Name</th>
					</tr>";

				#for layout purposes start	
				$num = 0;
				$class = "even";
				#end
				foreach ($mysqli->query($table_query) as $myrow) 
				{
					$checkbox_id = $myrow["rest_id"];
					#for layout purposes start
					if($num%2 == 0) 	$class = "even";
					else 	$class = "odd";
					$num++;
					#end			
					echo "<tr class='$class'> <td>";
					echo "<input type='checkbox' name='$checkbox_id'>", "   ", $myrow["rest_id"];
					echo "<td>";
					echo $myrow["mall_name"];
					echo "<td>";
					echo $myrow["rest_name"];
					echo "</tr>";
				}
				echo "</table>";
			}
		}

		
		#end of display
		?>

	</form>
	</body>
</html>