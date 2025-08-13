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

     require_once 'includes/admin-header.php';
?>

    <main class="container py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h4 class="mb-4">Upload Album Images</h4>
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
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-5">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Create New Album</h5>
                        <form action="uploadAlbum.php" accept="image/*" enctype="multipart/form-data" method="POST">
                            <div class="form-row">
                                <input type="hidden" name="album-id" id="album-id">
                                <div class="form-group col-md-12">
                                    <label for="album-name">Album Name</label>
                                    <input type="text" name="album-name" id="upload-name" class="form-control" required>
                                </div>  
                                <div class="form-group col-md-12">
                                    <label for="album-category">Album Category</label>
                                    <select name="album-category" id="album-category" class="form-control">
                                        <option value="" selected></option>
                                        <option value="wedding">Wedding</option>
                                        <option value="birthday">Birthday</option>
                                        <option value="others">Others</option>
                                    </select>
                                </div>  
                                <div class="form-group col-md-12">
                                    <label for="album-link">Album Link</label>
                                    <input type="text" name="album-link" id="upload-link" class="form-control" required>
                                </div>  
                                <div class="form-group col-md-12">
                                    <label for="upload-album">Your Album Image</label>
                                    <input type="file" accept="image/*" name="upload-album" id="upload-album" class="form-control-file" required>
                                </div>
                                <div class="form-group col-md-12">
                                    <input type="submit" name="albumSubmit" class="btn btn-primary btn-block" value="Submit">
                                </div>
                            </div>                  
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-5">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Add Images to Existing Album</h5>
                        <form id="uploadForm" enctype="multipart/form-data">
                            <div class="form-row">
                                <div class="form-group col-md-12">
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
                                <div class="form-group col-md-12">
                                    <label>Select Image:</label>
                                    <input class="form-control-file" type="file" name="img" accept="image/*" required>
                                </div>
                                <div class="form-group col-md-12">
                                    <input type="submit" class="btn btn-primary btn-block" value="Upload">
                                </div>   
                            </div>
                        </form>
                        <div id="response" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card shadow-sm">
            <div class="card-body">
                <?php
                    $sql = "SELECT * FROM album";
                    $result = $db->query($sql);
                ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
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
                                        <td><img src='image/upload-album/<?php echo $row['album_img']; ?>' alt='Image' class="img-thumbnail" width='100'></td>
                                        
                                        <td><?php echo $row['album_name']?></td>
                                        <td><?php echo ucfirst($row['album_category']) ?></td>
                                        
                                        <td><a href="<?php echo $row['album_link']?>" target="_blank"><?php echo $row['album_link']?></a></td>
                                        
                                        <td>
                                            <div class="btn-group" role="group">
                                                <form action="delete_album.php" class="delete-form" method="POST" style="display:inline-block;">
                                                    <input type="hidden" name="delete_id" value="<?php echo $row['id'] ?>">
                                                    <input type="hidden" name="delete_img" value="<?php echo $row['album_img'] ?>">
                                                    <button type="submit" class="btn btn-danger btn-sm" name="delete_img_btn">Delete</button>
                                                </form>
                                                
                                                <button 
                                                    class="btn btn-warning btn-sm edit-btn"
                                                    data-id="<?php echo $row['id'] ?>"
                                                    data-name="<?php echo $row['album_name'] ?>"
                                                    data-category="<?php echo $row['album_category'] ?>"
                                                    data-link="<?php echo $row['album_link'] ?>"
                                                    data-img="<?php echo $row['album_img'] ?>"
                                                >Edit</button>
                                                <a href="view.php?id=<?php echo $row['id']?>" class="btn btn-info btn-sm">View</a>
                                            </div>
                                        </td>
                                    </tr>
                            <?php
                                    
                                }
                            } else {
                                echo "<tr><td colspan='5' class='text-center'>No records found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
<?php
    require_once 'includes/admin-footer.php';
?>