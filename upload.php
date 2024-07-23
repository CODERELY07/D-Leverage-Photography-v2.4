<?php

require_once 'connection.php';
error_reporting(E_ALL); // Show all errors for debugging purposes

$msg = "";

if (isset($_POST['upload'])) {
	$total = count($_FILES['uploadImg']['name']);

    for ($i = 0; $i < $total; $i++) {
		// Establish database connection (adjust credentials as needed)
		// Prepare data for insertion using prepared statements
		$stmt = $db->prepare("INSERT INTO image (filename, category) VALUES (?, ?)");
		$stmt->bind_param("ss", $filename, $category);

		// Retrieve file details
		$filename = $_FILES["uploadImg"]["name"][$i];
		$tempname = $_FILES["uploadImg"]["tmp_name"][$i];
		$category = $_POST['category'];

		// Move uploaded file to desired location
		$folder = "./image/" . $filename;
		
		if (move_uploaded_file($tempname, $folder)) {
			// Execute prepared statement to insert into database
			if ($stmt->execute()) {
				echo "<h3>Image uploaded successfully!</h3>";
			} else {
				echo "<h3>Failed to insert image into database!</h3>";
			}
		} else {
			echo "<h3>Failed to upload image!</h3>";
		}
	}
    // Close statement and database connection
    $stmt->close();
    $db->close();
}
?>
