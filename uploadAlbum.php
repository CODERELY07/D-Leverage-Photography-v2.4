<?php

    require_once 'connection.php';
    // print_r($_FILES);
    function get_size($size){
        $sizekb = $size/1024;
        $format_size = number_format($sizekb,2);
        return $format_size;
    }
    $albumName = htmlspecialchars($_POST['album-name']);
    $albumLink = htmlspecialchars($_POST['album-link']);
    $albumImg = htmlspecialchars($_FILES['upload-album']['name']);
    $albumCategory = htmlspecialchars($_POST['album-category']);

 
    // echo $albumName . $albumImg . $albumLink;
    $size = get_size($_FILES['upload-album']['size']);
    $path = 'image/upload-album';
    
    $temp_file = $_FILES['upload-album']['tmp_name'];

    if($temp_file != ""){
        $newfilepath = $path . "/" . $_FILES['upload-album']['name'];

        if(move_uploaded_file($temp_file,$newfilepath)){
            echo "Upload SuccessFully";
            $stmt = $db->prepare("INSERT INTO album(album_name,album_link,album_img,album_category) VALUES(?,?,?,?)");
            $stmt->bind_param("ssss", $albumName,$albumLink,$albumImg,$albumCategory);

            if($stmt->execute()){
                echo "Album details saved";
                header("Location: admin.php");
                exit();
            }else{
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        }else{
            echo "error";
        }
    }
    $db->close();
?>