<?php

require_once("../include/auth.class.php");

$picture_user_repertory = "media/user/";



$path_parts = pathinfo($_FILES['file']['name']);

$file_name = $path_parts['filename'];
$file_extension = $path_parts['extension'];


$new_filename = $picture_user_repertory.$file_name.time().'.'.$file_extension;


if (move_uploaded_file($_FILES['file']['tmp_name'], '../'.$new_filename)) {
	
	$mine_query = "UPDATE user SET user_photo_path='".$new_filename."' WHERE user_id=".Auth::getUserId();
	$mine_result = $_MYSQLI->query($mine_query);
	
	echo "OK";

} else {
    echo "KO";
}

?> 