<?php
    session_start();

    // Check if the user is logged in
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header('Location: index.php'); 
        exit();
    }
    require_once 'connection.php';
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/admin.css?v=<?php echo time(); ?>">
  </head>
  <body>
        <header>
            <div class="container">
            <div class="flex">
                <div class="logo">
                    <a href="admin.php">
                        <img src="image/static-img/logo.png" width="150px" alt="D'Leverage Logo"
                    />
                    </a>
                   </div>
                <div class="user-icon-con">
                <div class="user-icon" id="user">
                    <i class="fa-solid fa-user" ></i>
                </div>
                
                    <div class="hide dropdown absolute card">
                        <a href="upload-portfolio.php">Portfolio Images</a><br>
                        <a href="albumImages.php">Album Images</a><br>
                        <a href="inbox.php">Inbox</a><br>
                        <a href="logout.php">Logout</a>
                    </div>
                </div>
            </div>
            </div>
        </header>
        <main class="container">
            <h4 class="display-5 mt-5  mb-4">Upload Album Images</h4>
            <?php 
            if(isset($_SESSION['status']) && $_SESSION['status'] != ""){
                ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong><?php echo $_SESSION['status']?></strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                <?php
                    }
                    unset($_SESSION['status']);
                ?>
             <div class="card-body" style="overflow-x:hidden">
                <form action="uploadAlbum.php"  accept="image/*" enctype="multipart/form-data" method="POST">
                    <div class="inputFields  mt-2">
                        <label for="album-name">Album Name</label>
                        <input type="text" name="album-name" id="upload-name" class="form-control" required>
                    </div>  
                    <div class="inputFields  mt-2">
                        <label for="album-category">Album Category</label>
                        <select name="album-category" id="album-category" class="form-control">
                            <option value="" selected></option>
                            <option value="wedding">Wedding</option>
                                <option value="birthday">Birthday</option>
                                <option value="others">Others</option>
                        </select>
                    </div>  
                    <div class="inputFields  mt-2">
                        <label for="album-link">Album Link</label>
                        <input type="text" name="album-link" id="upload-link" class="form-control" required>
                    </div>  
                    <div class="inputFields  mt-2">
                        <label for="upload-album">Your Album Image</label>
                        <input type="file" accept="image/*" name="upload-album" id="upload-album" class="form-control" required>
                    </div>
                    <input type="submit" name="albumSubmit" class="btn  mt-4 btn-primary" value="Submit">
                </form>
            </div>
            <div class="card mt-5" style="overflow-x:auto">
                <?php
                    $sql = "SELECT * FROM album";
                    $result = $db->query($sql);
                ?>
                <table class="p-3 table table-striped" style="overflow-x:auto">
                    <thead>
                        <tr> 
                            <th>Album Image</th>
                            <th>Album Name</th>
                            <th>Album Category</th>
                            <th>Album Link</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            // Output data of each row
                            while($row = $result->fetch_assoc()) {

                            ?>
                                <tr id="<?php echo $row['id']?>">
                                    <td class='p-2'><img src='image/upload-album/<?php echo $row['album_img']; ?>' alt='Image' width='100'></td>
                                    <td class='p-2'><?php echo $row['album_name']?></td>
                                    <td class='p-2'><?php echo $row['album_category'] ?></td>
                                   
                                    <td class='p-2'><a href="<?php echo $row['album_link']?>"><?php echo $row['album_link']?></a></td>
                                   
                                    <td class='p-2'>
                                        <form action="delete_album.php" method="POST">
                                            <input type="hidden" name="delete_id" value="<?php echo $row['id']?>">
                                            <input type="hidden" name="delete_img" value="<?php echo $row['album_img'] ?>">
                                            <input type="submit" class="btn btn-danger" value="Delete" name="delete_img_btn">
                                        </form>
                                    </td>
                                </tr>
                        <?php
                                
                            }
                        } else {
                            echo "<tr><td colspan='5'>No records found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
        <script src="js/adminScript.js"></script>
    </body>
</html>