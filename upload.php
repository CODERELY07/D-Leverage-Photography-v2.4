<?php
session_start();
// Redirect if already logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.php');
    exit();
}
// Release the session lock before the (possibly slow) upload loop below —
// PHP's default session handler holds an exclusive lock on the session file
// for as long as it's open, so keeping it open here blocks every other
// request using this session (e.g. this same page's own fetchImages() AJAX
// calls) until the upload finishes, which is what was producing the blank
// "upload.php" page: those requests were queuing up, and once PHP-FPM's
// worker pool was busy with the queue, a new request would get refused
// outright with an empty response instead of waiting.
session_write_close();

require_once 'connection.php';
require_once 'includes/functions.php';
error_reporting(E_ALL); // Show all errors for debugging purposes

$status = "";

if (isset($_POST['upload'])) {
    $category = $_POST['category'];
    $total = count($_FILES['uploadImg']['name']);

    for ($i = 0; $i < $total; $i++) {
        // Retrieve file details
        $filename = sanitize_upload_filename($_FILES["uploadImg"]["name"][$i]);
        $tempname = $_FILES["uploadImg"]["tmp_name"][$i];

        // Check if file already exists in database
        $stmt_check = $db->prepare("SELECT id FROM image WHERE filename = ?");
        $stmt_check->bind_param("s", $filename);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows == 0) { // File does not exist in database
            // Prepare and execute insert statement
            $stmt = $db->prepare("INSERT INTO image (filename, category) VALUES (?, ?)");
            $stmt->bind_param("ss", $filename, $category);

            // Move uploaded file to desired location
            $folder = "./image/" . $filename;

            if (move_uploaded_file($tempname, $folder)) {
                // Execute prepared statement to insert into database
                if ($stmt->execute()) {
                    $status = "Image uploaded successfully!";
                } else {
                    $status = "Failed to insert image into database!";
                }
            } else {
                $status = "Failed to upload image!";
            }

            // Close statement
            $stmt->close();
        } else {
            $status = "File '$filename' already exists in database. Skipped.";
        }

        // Close check statement
        $stmt_check->close();
    }
    // Close database connection
    $db->close();

    // Reopen the session just long enough to write the result message
    session_start();
    $_SESSION['status'] = $status;
    session_write_close();

    header("Location: upload-portfolio.php");
    exit();
}
?>
