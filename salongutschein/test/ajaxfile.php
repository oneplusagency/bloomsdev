<?php

// file name
$filename = $_FILES['file']['name'];

// Location
$location = 'upload/'.$filename;

// file extension
$file_extension = pathinfo($location, PATHINFO_EXTENSION);
$file_extension = strtolower($file_extension);

// Valid image extensions
$image_ext = array("jpg","png","jpeg","gif");

$response = 0;
if(in_array($file_extension,$image_ext)){
	// Upload file
	if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){
		$response = $location;
	}
}

echo '<pre>';
	var_export($_FILES);
echo '</pre>';
// exit;

// echo $response;
