<?php
	$mysqli = mysqli_connect("localhost","root","joel2013","testDB");
	if(mysqli_connect_errno())
	{
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}
	else
	{
		$clean_id = $_POST["item"];
		$clean_text = mysqli_real_escape_string($mysqli, $_POST["textfield"]);
		$sql = "UPDATE testTable SET testField = '".$clean_text."' WHERE id = ".$clean_id."";
		$res = mysqli_query($mysqli,$sql)  or die (mysqli_error($mysqli));

		if($res === TRUE)
		{
			echo "Record has been edited.";
		}
		else{
			printf("Could not edit record: %s\n",mysqli_error($mysqli ));
		}

		mysqli_close($mysqli);
	}

?>

<!DOCTYPE html>
<html>
<head><title>TEST</title></head>
<body>
<a href = "edit_form.html"><button>Back to Edit!</button></a>
</body>
</html>