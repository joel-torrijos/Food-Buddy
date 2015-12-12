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
			if($result)
			{
				$newArray = mysqli_fetch_array($result, MYSQLI_ASSOC);
				$mall_id = $newArray['mall_id'];
				$rest_id = $newArray['rest_id'];
				
				echo "<html>
				<head>
					<title>" .$newArray['rest_name']. " Menu</title>
					<script type='text/javascript'>
						function viewPage() {
							window.location='display_items.php';
						}
						function goBack() {
							window.location='homepage.php';
						}
					</script>
					<link href='styles.css' type='text/css' rel='stylesheet'>
				</head>
				<body>

				<form name='main_form' method='post' action='display_items.php'>";

				$position = $newArray['position'];

				echo "<div class = 'bar'>";
				#Account details
				echo "<p class = 'logout'><a href = 'logout.php'>Log Out</a></p>";
				echo "<p class = 'details'>Welcome " . $newArray['first_name']. " to Food Buddy!<br>";
				echo "Position: " . $newArray['position']. "<br>";
				echo "Mall: " . $newArray['mall_name'] . ", Restaurant: " . $newArray['rest_name']. "<br>";
				echo "</p><a href = 'homepage.php'><img class = 'logo' src='logo.jpg' title='Home Page' align = 'middle'/></a></div>";
			
				echo "<div class = 'navi'>";
				#Menu Items
					echo "<ul class = 'buttons'>
							<li class = 'home'><a href = 'view_account.php'>Account Information</a></li>
							<li class = 'home'><a href = 'edit_password.php'>Edit Password</a></li>
							<li class = 'home'><a href = 'display_items.php'>Food Items</a></li>
							<li class = 'home'><a href = 'display_accounts-2.php'>Load Accounts</a></li>
							<li class = 'home'><a href = 'view_message.php'>Messages</a></li>";
			
				echo "</div><div class = 'empty'></div>";
				
				echo "<div class = 'center'><ul>
						<!--<li><input class='submit' type='submit' name='delete_all' value='Delete All'></li>-->
						<li><input class='submit' type='button' name='view' value='View List' onclick='viewPage()'></li>
						<li><input class='submit' type='submit' name='add' value='Add'></li>
						<li><input class='submit' type='submit' name='delete' value='Delete'></li>
						<li><input class='submit' type='submit' name='edit' value='Edit'></li>			
						</ul></div>";

				$table_query = "SELECT * FROM item a
							INNER JOIN restaurant b ON a.rest_id=b.rest_id
							INNER JOIN mall c ON b.mall_id = c.mall_id
							WHERE b.rest_id = ". $rest_id;
				$select_query = "SELECT * FROM restaurant WHERE mall_id = ". $mall_id;
				#$db->query($table_query);
			
				/*if(isset($_POST['delete_all'])) {
					$complete_delete_query = "TRUNCATE item";
					$db->query($complete_delete_query);
					echo "<p class='notif'> List emptied. If you have done this by mistake, then oops. </p>";
				}*/
				if(isset($_POST['add'])) 
				{ #check if the ADD button was pressed
					#display form for ADD MENU ITEM
					echo 
						"<fieldset class = 'notice'>
							<legend> Add Menu Item </legend>";
					echo "<label class='in'>Item Name: </label>";
					echo			
						"<input class='text' type='text' name='item_name'><br>";				
					echo
						"<br>
							<label class='in'>Item Price: </label>
							<input class='text' type='text' name='price'><br>
							<label class='in'>Item Type: </label>
							<div class='rad'>						
							<input type='radio' name='item_type' value='Snacks' id='sn'> <label for='sn'>Snacks</label>
							<input type='radio' name='item_type' value='Drinks' id='dr'> <label for='dr'>Drinks</label><br>
							</div>
							<label class='in'>Availability: </label>
							<div class='rad'>						
							<input type='radio' name='avail' value='yes' id='y'> <label for='y'>Available</label>
							<input type='radio' name='avail' value='no' id='n'> <label for='n'>Not Available</label>
							</div>
							<input class='sub_submit' type='submit' name='add_item' value='Add Menu Item'>
						</fieldset>";
				}
				else if(isset($_POST['delete'])) 
				{	#check if the DELETE button was pressed
					#print_r($_POST); #for checking purposes
					foreach ($mysqli->query($table_query) as $myrow) 
					{ #for all entries in the item list
						$delete_entry = $myrow["item_id"];	#assign current entry's item_id to variable
						if(isset($_POST[$delete_entry]) != NULL) 
						{	#check if the entry's checkbox was selected/not null(meaning the entry should be deleted)
							#execute deletion
							$delete_query = "DELETE FROM item WHERE item_id = '$delete_entry'";
							$mysqli->query($delete_query);					
						}
					}
					echo "<p class='notif'> Item(s) deleted. </p>";
				}
				else if(isset($_POST['edit'])) 
				{#print_r($_POST); #for checking purposes
					$edit_item_name = NULL;
					foreach ($mysqli->query($table_query) as $myrow) 
					{ #for all entries in the item list
						$edit_entry_id = $myrow["item_id"];	#assign current entry's item_id to variable
						if(isset($_POST[$edit_entry_id]) != NULL) 
						{	#check if the entry's checkbox was selected (meaning the entry should be deleted)
							$edit_item_name = $myrow["item_name"]; #assign entry's item name to variable
							$edit_item_price = $myrow["price"];
							break; #get out of loop
						}
					}
					if($edit_item_name == NULL) 
					{
						echo 
							"<p class='notif'> Please select one (1) menu item entry to edit, then click [Edit]. <br> <br>
								Note that if multiple entries are selected, only the <b>first one</b> selected on the list will be considered for editing.
							</p>";		
					}
					else 
					{
						#display form for EDIT MENU ITEM
						echo 
						"<fieldset class = 'notice'>
							<legend> Edit Menu Item </legend>";
						echo "<label class='in'>Menu Item Name: </label>";
						echo			
							"<input class='text' type='text' name='new_item_name' value='$edit_item_name'><br>";
						echo "<label class='in'>Item Price: </label>
							<input class='text' type='text' name='new_price' value='$edit_item_price'><br>
							<label class='in'>Item Type: </label>
							<div class='rad'>				
							<input type='radio' name='new_item_type' value='Snacks' id='sn'> <label for='sn'>Snacks</label>
							<input type='radio' name='new_item_type' value='Drinks' id='dr'> <label for='dr'>Drinks</label><br>
							</div>
							<label class='in'>Availability: </label>
							<div class='rad'>						
							<input type='radio' name='change_avail' value='yes' id='y'> <label for='y'>Available</label>
							<input type='radio' name='change_avail' value='no' id='n'> <label for='n'>Not Available</label>
							</div>
							<input class='sub_submit' type='submit' name='edit_item' value='Save Changes'>
							<input type='hidden' name='id' value='$edit_entry_id'><!--#to send item_id-->
							</fieldset>";
					}
				}
				else if(isset($_POST['add_item'])) 
				{		
					$restz_id = $rest_id;	#
					$item_name = $_POST['item_name']; #get the menu item name from the textbox named "item_name"
					$item_price = $_POST['price'];
					if(isset($_POST['item_type']))
					{
						$item_type = $_POST['item_type'];
					}
					else
					{
						$item_type = NULL;
					}
					if(isset($_POST['avail']))
					{
						if($_POST['avail'] == 'yes')	$is_available = 1;
						else $is_available = 0;
					}
					else
					{
						$is_available = -1;
					}

					#print_r($_POST); #for checking purposes
					#execute insertion
					#temporarily hard-coded to insert other attributes
					if($item_type != NULL && $is_available != -1)
					{
						$insert_query = "INSERT INTO item(rest_id, item_name, price, type, is_available) VALUES ('$restz_id', '$item_name', '$item_price', '$item_type', '$is_available')";
						$mysqli->query($insert_query);
						echo "<p class='notif'> Item added. </p>";
					}
					else
					{
						echo "<p class='notif'> Error: Fill-up all the fields.</p>";
					}
				}
				else if(isset($_POST['edit_item'])) 
				{
					$new_item_name = $_POST['new_item_name']; #get the menu item name from the textbox named "new_item_name"
					$item_id = $_POST['id']; #get the item id from the hidden input component of the form named "id"
					#$new_rest_id = $_POST['new_rest'];
					$new_item_price = $_POST['new_price'];

					#print_r($_POST); #for checking purposes
					#execute update
					if(isset($_POST['new_item_type']))
					{
						$new_item_type = $_POST['new_item_type'];
					}
					else
					{
						$new_item_type = NULL;
					}
					if(isset($_POST['change_avail']))
					{
						if($_POST['change_avail'] == 'yes')	$change_is_available = 1;
						else $change_is_available = 0;
					}
					else
					{
						$change_is_available = -1;
					}

					#print_r($_POST); #for checking purposes
					#execute insertion
					#temporarily hard-coded to insert other attributes
					if($new_item_type != NULL && $change_is_available != -1)
					{
						$update_query = "UPDATE item SET item_name = '$new_item_name', price = '$new_item_price', type = '$new_item_type', is_available = '$change_is_available' WHERE item_id = '$item_id'";
						$mysqli->query($update_query);
						echo "<p class='notif'> Item updated. </p>";
					}
					else
					{
						echo "<p class='notif'> Error: Fill-up all the fields.</p>";
					}
				}
				#display item table contents
				echo "<table class='list'>";
				echo 
					"<tr>
					<th>Item ID</th>		
					<th>Item Name</th>
					<th class='money'>Price</th>
					<th>Type</th>
					<th>Is Available</th>
					</tr>";

					#for layout purposes start	
				$num = 0;
				$class = "even";
				#end
				foreach ($mysqli->query($table_query) as $myrow)
				{
					$checkbox_id = $myrow['item_id'];
					#for layout purposes start
					if($num%2 == 0) 	$class = "even";
					else 	$class = "odd";
					$num++;
					#end

					#for readability
					$availability = 'Yes';
					if($myrow["is_available"] == 0) 	$availability = 'No';
					else 	$availability = 'Yes';
					#echo $checkbox_id; #for checking purposes
					echo "<tr class='$class'> <td>";
					echo "<input type='checkbox' name='$checkbox_id'>", "   ", $myrow["item_id"];
					echo "<td>";
					echo $myrow["item_name"];
					echo "<td class = 'number'>";
					echo $myrow["price"];
					echo "<td>";
					echo $myrow["type"];
					echo "<td>";
					echo $availability;
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