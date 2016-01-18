<?php

require_once("include/auth.class.php");

$auth = new Auth();
?>
<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8" />
		<title>QCManager</title>
		
	</head>
	<body>
	
		<script>
<?php

if($auth->isLogged()) {
	
	if(isset($_FILES['file'])) {

		$path_parts = pathinfo($_FILES['file']['name']);

		$filename = $path_parts['filename'];
		$extension = $path_parts['extension'];

		$new_filename = "media/user/" . md5($filename) . '-' . time(). '.' . strtolower($extension);

		if (move_uploaded_file($_FILES['file']['tmp_name'], $new_filename)) {
			
			$mine_query = "UPDATE user SET user_photo_path = '".$new_filename."' WHERE user_id = " . Auth::getUserId();
			$mine_result = $_MYSQLI->query($mine_query);
			
			echo "parent.location.href = parent.location.href";

		}
		else {
			
			echo "alert('Le fichier est trop volumineux')";

		}
		
	}
	else {
		
		echo "POST file upload missing";
		
	}

}
else {
	
	echo "You're not logged";
	
}

?> 
		</script>
	
	</body>
</html>