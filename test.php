<?php
    require_once 'connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Wedding</h1>
    <div class="wedding">
        <?php 
            $query = "SELECT * FROM image WHERE category = 'wedding'";
            $result  = $db->query($query);
            while($row = $result->fetch_assoc()){
            ?>
            <img src="./image/<?php echo $row['filename']; ?>">
            <?php
            }
        ?>
    </div>
    <h1>Birthday</h1>
    <div class="birthday">
        <?php 
            $query = "SELECT * FROM image WHERE category = 'birthday'";
            $result  = $db->query($query);
            while($row = $result->fetch_assoc()){
            ?>
            <img src="./image/<?php echo $row['filename']; ?>">
            <?php
            }
        ?>
    </div>
</body>
</html>