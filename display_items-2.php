<?php session_start(); ?>
<!DOCTYPE html>
<html>
	<head>
		<script type="text/javascript">
			function goBack() {
				window.location="homepage.php";
			}
		</script>
		<link href="css\database_layout2.css" type="text/css" rel="stylesheet">
	</head>
	<body>

	<form name="main_form" method="post" action="process_item.php">
		<ul>	
			<!--<li><input class="submit" type="submit" name="delete_all" value="Delete All"></li>-->
			<li><input class="submit" type="button" name="back" value="Homepage" onclick="goBack()"></li>				
			<li><input class="submit" type="submit" name="add" value="Add"></li>
			<li><input class="submit" type="submit" name="delete" value="Delete"></li>
			<li><input class="submit" type="submit" name="edit" value="Edit"></li>			
		</ul>

		<?php
				
		print_r($_SESSION);
		#setting up the connection
		try {
			$db = new PDO('mysql:host=localhost;dbname=dbadmin', 'root', '');
			$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		} catch( PDOException $e ) {
			echo "Connection failed: " . $e->getMessage();
		}
		$mysqli = new mysqli("localhost","root","joel2013","dbadmin");
		$account_id = $_SESSION['account_id'];
		$sql = "SELECT * FROM account WHERE account_id = " .$account_id;
		$result = mysqli_query($mysqli,$sql);
		$newArray = mysqli_fetch_array($result, MYSQLI_ASSOC);
		$position = $newArray['mall_id'];

		$table_query = "SELECT * FROM item";
		$select_query = "SELECT * FROM restaurant";
		#$db->query($table_query);
			
		/*if(isset($_POST['delete_all'])) {
			$complete_delete_query = "TRUNCATE item";
			$db->query($complete_delete_query);
			echo "<p class='notif'> List emptied. If you have done this by mistake, then oops. </p>";
		}*/
		if(isset($_SESSION["message"])) {
			if($_SESSION["message"] == "add") { #check if the ADD button was pressed
				#display form for ADD MENU ITEM
				echo 
					"<fieldset>
						<legend> Add menu item </legend>";
				echo "<label class='in'>Insert menu item name: </label>";
				echo			
						"<input class='text' type='text' name='item_name'><br>";
				echo 	"<label class='in'>Insert item price: </label>
						<input class='text' type='text' name='price'><br>
						<label class='in'>Item type: </label>
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
						<input class='sub_submit' type='submit' name='cancel' value='Cancel'>
					</fieldset>";
			}
			else if($_SESSION["message"] == "delete") {	#check if the DELETE button was pressed
				echo "<p class='notif'> Item(s) deleted. </p>";
			}
			else if($_SESSION["message"] == "edit") {
				#print_r($_POST); #for checking purposes				
				$edit_entry_id = $_SESSION['edit_id'];

				$select_query = "SELECT * FROM item WHERE item_id = '$edit_entry_id'";

				$stmt = $db->query($select_query);
				$row = $stmt->fetch(PDO::FETCH_ASSOC);

				$edit_item_name = $row['item_name'];
				$edit_item_price = $row['price'];

				if($edit_item_name == NULL) {
					echo 
						"<p class='notif'> Please select one (1) menu item entry to edit, then click [Edit]. <br> <br>
							Note that if multiple entries are selected, only the <b>first one</b> selected on the list will be considered for editing.
						</p>";		
				}
				else {
					#display form for EDIT MENU ITEM
					echo 
					"<fieldset>
							<legend> Edit menu item </legend>";
					echo "<label class='in'>Insert new menu item name: </label>";
					echo			
							"<input class='text' type='text' name='new_item_name' value='$edit_item_name'><br>";				
					echo	"<label class='in'>Insert new item price: </label>
							<input class='text' type='text' name='new_price' value='$edit_item_price'><br>
							<label class='in'>New item type: </label>
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
							<input class='sub_submit' type='submit' name='cancel' value='Cancel'>
							<input type='hidden' name='id' value='$edit_entry_id'><!--#to send item_id-->
						</fieldset>";
					}
			}
			else if($_SESSION["message"] == "add_item") {		
				echo "<p class='notif'> Item added. </p>";
			}
			else if($_SESSION["message"] == "edit_item") {
				echo "<p class='notif'> Item updated. </p>";
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
		foreach ($db->query($table_query) as $myrow) {
			$checkbox_id = $myrow["item_id"];
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
			echo "<td class='money'>";
			echo $myrow["price"];
			echo "<td>";
			echo $myrow["type"];
			echo "<td>";
			echo $availability;
			echo "</tr>";
		}

		echo "</table>";
		#end of display
		$_SESSION["message"] = NULL;
		$_SESSION["edit_id"] = NULL;
		?>

	</form>

	</body>
</html>