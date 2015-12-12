<!DOCTYPE html>
<html>
	<head>
		<link href="css\database_layout2.css" type="text/css" rel="stylesheet">
	</head>
	<body>

		<?php

		#setting up the connection
		try {			
			$db = new PDO('mysql:host=localhost;dbname=dbadmin', 'root', '');
			$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		} catch(PDOException $e) {
			echo "Connection failed: " . $e->getMessage();
		}

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

		$table_query = "SELECT * FROM orderitems a
						INNER JOIN item b ON a.item_id = b.item_id
						INNER JOIN restaurant c ON b.rest_id = c.rest_id
						ORDER BY a.order_id ASC";
						

		#display mall table contents
		echo "<table class='list'>";
		echo 
			"<tr>
				<th>From Order ID</th> 
				<th>Restaurant</th> 
				<th>Item Name</th> 
			</tr>";

		#for layout purposes start	
		$num = 0;
		$class = "even";
		#end
		foreach ($db->query($table_query) as $myrow) {
			$checkbox_id = $myrow["orderitem_id"];
			#for layout purposes start
			if($num%2 == 0) 	$class = "even";
			else 	$class = "odd";
			$num++;
			#end
			#echo $checkbox_id; #for checking purposes
			echo "<tr class='$class'> <td>";
			echo "<input type='checkbox' name='$checkbox_id'>", "   ", $myrow["order_id"];
			echo "</td><td>";
			echo $myrow["rest_name"];
			echo "</td><td>";
			echo $myrow["item_name"];
			echo "</td>";
			echo "</tr>";
		}

		echo "</table>";
		#end of display

		?>
	</body>
</html>