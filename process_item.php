<?php
	session_start();	
	#possible messages to be sent: add, delete, edit, add_item, edit_item
	
	#set up connection
	try {
		$db = new PDO('mysql:host=localhost;dbname=dbadmin', 'root', '');
		$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	} catch( PDOException $e ) {
		echo "Connection failed: " . $e->getMessage();
	}

	$table_query = "SELECT * FROM item";

	if(isset($_POST["cancel"])) {
		header("location:display_items-2.php");
	}
	else if(isset($_POST["add"])) {
		$_SESSION["message"] = "add";
	}
	else if(isset($_POST["edit"])) {
		foreach ($db->query($table_query) as $myrow) { #for all entries in the item list
			$edit_entry_id = $myrow["item_id"];	#assign current entry's item_id to variable					
			if(isset($_POST[$edit_entry_id]) != NULL) {	#check if the entry's checkbox was selected (meaning the entry should be deleted)
				$_SESSION["edit_id"] = $edit_entry_id;
				break; #get out of loop
			}
		}
		$_SESSION["message"] = "edit";
	}
	else if(isset($_POST["delete"])) {
		foreach ($db->query($table_query) as $myrow) { #for all entries in the item list
			$delete_entry = $myrow["item_id"];	#assign current entry's item_id to variable
			if(isset($_POST[$delete_entry]) != NULL) {	#check if the entry's checkbox was selected/not null(meaning the entry should be deleted)
				#execute deletion
				$delete_query = "DELETE FROM item WHERE item_id = '$delete_entry'";
				$db->query($delete_query);					
			}
		}
		$_SESSION["message"] = "delete";
	}
	else if(isset($_POST["add_item"])) {
		$loadee_id = $_SESSION["account_id"];
		$select_query = "SELECT * FROM account WHERE account_id = '$loadee_id'";

		$stmt = $db->query($select_query);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		$rest_id = $row['rest_id'];
		$item_name = $_POST['item_name']; #get the menu item name from the textbox named "item_name"
		$item_price = $_POST['price'];
		$item_type = $_POST['item_type'];
		if($_POST['avail'] == 'yes')	$is_available = 1;
		else 	$is_available = 0;
		#print_r($_POST); #for checking purposes
		#execute insertion
		#temporarily hard-coded to insert other attributes
		$insert_query = "INSERT INTO item(rest_id, item_name, price, type, is_available) VALUES ('$rest_id', '$item_name', '$item_price', '$item_type', '$is_available')";
		$db->query($insert_query);
		$_SESSION["message"] = "add_item";
	}
	else if(isset($_POST["edit_item"])) {
		$new_item_name = $_POST['new_item_name']; #get the menu item name from the textbox named "new_item_name"
		$item_id = $_POST['id']; #get the item id from the hidden input component of the form named "id"			
		$new_item_price = $_POST['new_price'];
		$new_item_type = $_POST['new_item_type'];
		if($_POST['change_avail'] == 'yes')	$change_is_available = 1;
		else 	$change_is_available = 0;
		#print_r($_POST); #for checking purposes
		#execute update
		$update_query = "UPDATE item SET item_name = '$new_item_name', price = '$new_item_price', type = '$new_item_type', is_available = '$change_is_available' WHERE item_id = '$item_id'";
		$db->query($update_query);
		$_SESSION["message"] = "edit_item";
	}

	$_POST = array();
	header("location:display_items-2.php");

?>