<?php

    require_once 'connection.php';
    $title = "Admin Image";
    require_once 'includes/admin-head.php';
    // Check if the user is logged in
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header('Location: index.php'); 
        exit();
    }
    

    $stmt = $db->prepare("SELECT DISTINCT * FROM album");
    $stmt->execute();

    $result = $stmt->get_result();

 
?>
    <main class="container">
        <h4 class="mt-5  mb-4">Upload Album Images</h4>
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
                <div class="flex flex-wrap">
                    <input type="hidden" name="album-id" id="album-id">
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
                    <input type="submit" name="albumSubmit" class="btn mt-4 btn-primary" value="Submit">

                </div>                  
            </form>
        </div>
        
        <div class="mt-5">
            <h5>Upload Image to Album</h5>

            <form id="uploadForm" enctype="multipart/form-data">
                <div class="flex flex-wrap">
                <div>
                    <label>Select Album:</label>
                    <select name="album_id" required class="form-control">
                        <?php 
                            while($row = $result->fetch_assoc()):
                        ?>
                            <option value="<?php echo $row['id']?>"><?= $row['album_name'];?></option>
                        <?php
                            endwhile;
                        ?>
                    </select>
                </div>
                <div>
                    <label>Select Image:</label>
                    <input class="form-control" type="file" name="img" accept="image/*" required>
                </div>
                </div>    
                <div>
                    <input type="submit" class="btn my-3 btn-primary" value="Upload">
                </div>   
            </form>

            <div id="response"></div>
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
                                    <form action="delete_album.php" class="delete-form" method="POST" style="display:inline-block;">
                                        <input type="hidden" name="delete_id" value="<?php echo $row['id'] ?>">
                                        <input type="hidden" name="delete_img" value="<?php echo $row['album_img'] ?>">
                                        <input type="submit" class="btn btn-danger" value="Delete" name="delete_img_btn">
                                    </form>
                                    
                                    <button 
                                        class="btn btn-warning edit-btn"
                                        data-id="<?php echo $row['id'] ?>"
                                        data-name="<?php echo $row['album_name'] ?>"
                                        data-category="<?php echo $row['album_category'] ?>"
                                        data-link="<?php echo $row['album_link'] ?>"
                                        data-img="<?php echo $row['album_img'] ?>"
                                    >Edit</button>
                                    <a href="view.php?id=<?php echo $row['id']?>" class="btn btn-info">View</a>

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
<?php
    require_once 'includes/admin-footer.php';
?>