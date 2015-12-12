<!DOCTYPE html>
<html>
	<head>
	<head>
	<title>All FooBu Malls</title>
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
				window.location = "display_malls.php";				
			}
			function goBack() {
				window.location = "homepage.php";
			}			
		</script>
		<link href="styles.css" type="text/css" rel="stylesheet">
	</head>
	<body>

	<form name="main_form" method="post" action="display_malls.php" onsubmit="return validate()">
	
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

			$count =  mysqli_num_rows($result);

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
			<li><input class='submit' type='submit' name='add' value='Add' onsubmit='redirect()'></li>
			<li><input class='submit' type='submit' name='delete' value='Delete'></li>
			<li><input class='submit' type='submit' name='edit' value='Edit'></li>			
			</ul></div>";

		#Doesn't work fak, but I might need it, so I'll keep it
		/*session_start();		

		if(isset($_SESSION['add']) != NULL || isset($_SESSION['delete']) != NULL || isset($_SESSION['edit']) != NULL || isset($_SESSION['delete_all']) != NULL) {
			if(($_SESSION['add'] == $_POST['add']) || ($_SESSION['delete'] == $_POST['delete']) || ($_SESSION['edit'] == $_POST['edit']) || ($_SESSION['delete_all'] == $_POST['delete_all'])) {
				header("Location: display_malls.php");
			}			
		}
		else {
			if(isset($_POST['add'])) {
				$_SESSION['add'] = $_POST['add'];
			}
			else if(isset($_POST['delete'])) {
				$_SESSION['delete'] = $_POST['delete'];
			}
			else if(isset($_POST['edit'])) {
				$_SESSION['edit'] = $_POST['edit'];
			}
			else if(isset($_POST['delete_all'])) {
				$_SESSION['delete_all'] = $_POST['delete_all'];
			}
		}*/

		$table_query = "SELECT * FROM mall";
		#$db->query($table_query);

		/*if(isset($_POST['delete_all'])) {
			$complete_delete_query = "TRUNCATE mall";
			$db->query($complete_delete_query);
			echo "<p class='notif'> List emptied. If you have done this by mistake, then oops. </p>";
		}*/
			if(isset($_POST['add']))
			{ #check if the ADD button was pressed
				#display form for ADD MALL
				echo 
				"<fieldset class = 'notice'>
					<legend> Add New Mall </legend>
					<!--<form name='add_form' method='post' action='display_malls.php'>-->";
				echo "<label class='in'>New Mall Name: </label>";
				echo			
					"<input class='text' type='text' name='mall_name'>
					<input class='sub_submit' type='submit' name='add_mall' value='Add Mall' onclick='return validate(this.value)'>
				<!--</form>-->
				</fieldset>";
				#end of display	
			}
			else if(isset($_POST['delete']))
			{	#check if the DELETE button was pressed
				#print_r($_POST); #for checking purposes
				foreach ($mysqli->query($table_query) as $myrow)
				{ #for all entries in the mall list
					$delete_entry = $myrow["mall_id"];	#assign current entry's mall_id to variable
					if(isset($_POST[$delete_entry]) != NULL) 
					{	#check if the entry's checkbox was selected/not null(meaning the entry should be deleted)
						#execute deletion
						$delete_query = "DELETE FROM mall WHERE mall_id = '$delete_entry'";
						$delete_result = mysqli_query($mysqli,$delete_query);

							if($delete_result === TRUE)
							{
								echo "<p class='notif'> Mall(s) deleted. </p>";
							}

							else
							{
								echo "<p class='notif'> Can't delete: Accounts/Restaurants are tied to this Mall!<br> Try to reassign the Accounts or Delete Restaurants from this Mall first then Delete!</p>";
							}				
					}
				}
			}
			else if(isset($_POST['edit'])) 
			{
				#print_r($_POST); #for checking purposes
				$edit_mall_name = NULL;
				foreach ($mysqli->query($table_query) as $myrow) 
				{ #for all entries in the mall list
					$edit_entry_id = $myrow["mall_id"];	#assign current entry's mall_id to variable
					if(isset($_POST[$edit_entry_id]) != NULL) 
					{	#check if the entry's checkbox was selected (meaning the entry should be deleted)
						$edit_mall_name = $myrow["mall_name"]; #assign entry's mall name to variable
						break; #get out of loop
					}
				}

				if($edit_mall_name == NULL) 
				{
					echo 
					"<p class='notif'> Please select one (1) mall entry to edit, then click [Edit]. <br> <br>
						Note that if multiple entries are selected, only the <b>first one</b> selected on the list will be considered for editing.
					</p>";
				}
				else 
				{
					#display form for EDIT MALL
					echo 
						"<fieldset class = 'notice'>
						<legend> Edit Mall Data </legend>
						<!--<form name='edit_form' method='post' action='display_malls.php'>-->";
					echo "<label class='in'>Mall Name: </label>";						
					echo
						"<input class='text' type='text' name='new_mall_name' value='$edit_mall_name'><br>
						<input class='sub_submit' type='submit' name='edit_mall' value='Save Changes' onclick='return validate(this.value)'>
						<input type='hidden' name='id' value='$edit_entry_id'><!--#to send mall_id-->
						<!--</form>-->
					</fieldset>";
				}
			
			}
			else if(isset($_POST['add_mall'])) 
			{
				$mall_name = $_POST['mall_name']; #get the mall name from the textbox named "mall_name"
				#print_r($_POST); #for checking purposes
				#execute insertion
				$insert_query = "INSERT INTO mall(mall_name) VALUES ('$mall_name')";
				$mysqli->query($insert_query);
				echo "<p class='notif'> Mall added. </p>";
				#header("Location: display_malls.php"); #to avoid refresh issues
			}
			else if(isset($_POST['edit_mall'])) 
			{
				$new_mall_name = $_POST['new_mall_name']; #get the mall name from the textbox named "new_mall_name"
				$mall_id = $_POST['id']; #get the mall id from the hidden input component of the form named "id"
				#print_r($_POST); #for checking purposes
				#execute update
				$update_query = "UPDATE mall SET mall_name = '$new_mall_name' WHERE mall_id = '$mall_id'";
				$mysqli->query($update_query);
				echo "<p class='notif'> Mall updated. </p>";			
				#header("Location: display_malls.php"); #to avoid refresh issues
			}

			#display mall table contents
			echo "<table class='list'>";
			echo 
			"<tr>
				<th>Mall ID</th> 
				<th>Mall Name</th> 
			</tr>";
			#for layout purposes start	
			$num = 0;
			$class = "even";
			#end
			foreach ($mysqli->query($table_query) as $myrow) 
			{
				$checkbox_id = $myrow["mall_id"];
				#for layout purposes start
				if($num%2 == 0) 	$class = "even";
				else 	$class = "odd";
				$num++;
				#end
				#echo $checkbox_id; #for checking purposes
				echo "<tr class='$class'> <td>";
				echo "<input type='checkbox' name='$checkbox_id'>", "   ", $myrow["mall_id"];
				echo "<td>";
				echo $myrow["mall_name"];
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