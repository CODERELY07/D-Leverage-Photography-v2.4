<?php
    session_start();

    // Check if the user is logged in
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header('Location: index.php'); 
        exit();
    }
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
       <?php require_once 'includes/admin-header.php';?>

        <main class="container pb-5">
            <h4 class="mt-5  mb-4">Upload Portfolio Images</h4>
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
            <form action="upload.php" method="POST" class="card py-4 p-3" enctype="multipart/form-data" id="uploadForm">
               
                <span>Choose Category</span>
                <div class="d-flex flex-column flex-md-row align-items-center gap-3">
                    <div class='formInput'>
                        <select name="category" id="category" class="form-control">
                            <option value="" selected></option>
                            <option value="wedding">Wedding/Prenuptial </option>
                            <option value="birthday">Birthday</option>
                            <option value="others">Others</option>
                        </select>
                    </div>
                    <div class="formInput">
                        <input type="file" accept="image/*" name="uploadImg[]" class="form-control" multiple>
                    </div>
                    <div class="formInput">
                        <input type="submit" id="uploadBtn" value="Upload Files" class="btn btn-primary" name="upload">
                    </div>
                </div>
            </form>

            <div class="mt-5">
                <div class="card-header">
                    <div class="hidden-parent filter">
                        <button class=' btn-filter' id="filtered">Filter</button>
                        <div id="filter-hide" class="card">
                            <ul id="filter-list" class="rounded p-3">
                                <li class="mb-2" data-filter="all">All</li>
                                <li class="mb-2" data-filter="wedding">Wedding/Prenuptial</li>
                                <li class="mb-2" data-filter="birthday">Birthday</li>
                                <li class="mb-2" data-filter="others">Others</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="overflow-x:auto;">
                    <div class="card-text"> 
                        <form class="card">
                            <table class="image_table table table-striped table-border">
                                <thead>
                                    <tr>
                                        <td>Image</td>
                                        <td>Image Name</td>
                                        <td>Image Category</td>
                                        <td>Action</td>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </main>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
       
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
        const filterList = document.getElementById('filter-list');
        const tableBody = document.querySelector('.image_table tbody');

        // Event listener for filter buttons
        filterList.addEventListener('click', (event) => {
            const filter = event.target.getAttribute('data-filter');
            console.log(filter);
            if (filter) {
                fetchImages(filter);
            }
        });

        // Function to fetch images based on the filter
        function fetchImages(filter) {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', `table-portfolio.php?filter=${filter}`, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    let html = '';
                    let response;
                    if (this.responseText == "no") {
                        html = '<h1 class="mt-5 text-secondary text-center"> No Image</h1>';
                        tableBody.innerHTML = html;
                    } else {
                        try {
                            response = JSON.parse(this.responseText);
                            response.forEach(img => {
                                html += `
                                    <tr>
                                        <td style="display:none" class="img_id">${img.id}</td>
                                        <td>
                                            <img src="image/${img.filename}" alt="${img.filename}" width="100" class="editable-image" 
                                            data-id="${img.id}">
                                            <input type="file" accept="image/*" class="d-none upload-input" data-id="${img.id}" />
                                        </td>
                                        <td>${img.filename}</td>
                                           <td>
                                        <select class="form-control category-select" data-id="${img.id}">
                                                <option value="wedding" ${img.category === 'wedding' ? 'selected' : ''}>Wedding/Prenuptial</option>
                                                <option value="birthday" ${img.category === 'birthday' ? 'selected' : ''}>Birthday</option>
                                                <option value="others" ${img.category === 'others' ? 'selected' : ''}>Others</option>
                                            </select>
                                        </td>
                                        <td><button class="btn btn-danger delete" data-filename="${img.filename}">Delete</button></td>
                                     
                                    </tr>
                                `;
                            });
                            tableBody.innerHTML = html;
                        } catch (error) {
                            console.error("Error Parsing JSON: ", error);
                        }
                    }
                } else {
                    tableBody.innerHTML = 'Error loading images.';
                }
            };
            xhr.send();
        }
        
        fetchImages('all');
        tableBody.addEventListener('click', (event) => {
            if (event.target.classList.contains('delete')) {
                const filename = event.target.getAttribute('data-filename');
                deleteImage(event, filename);
            }
        });
        function deleteImage(event, filename) {
            event.preventDefault();
            const row = event.target.closest('tr');
            const imgIdElement = row.querySelector('.img_id');
            const img_id = imgIdElement ? imgIdElement.textContent : 'ID not found';
            // Log the image ID to the console
            console.log(img_id);

            const xhr = new XMLHttpRequest();

            xhr.open("POST", "deleteImage.php",true);
            xhr.setRequestHeader("Content-type", 'application/x-www-form-urlencoded');

            xhr.onload = function(){
                if(xhr.status === 200){
                    fetchImages('all');
                }else{
                    console.error('Error deleting image:', xhr.statusText);
                }
            }
            xhr.onerror = function() {
                console.error('Error deleting image:', xhr.statusText);
            };
            
            const data = `click_delete_btn=true&img_id=${encodeURIComponent(img_id)}&filename=${encodeURIComponent(filename)}`;

            xhr.send(data);
        }   


        // Open file input on image click
            tableBody.addEventListener('click', function(e) {
                if (e.target.classList.contains('editable-image')) {
                    const id = e.target.getAttribute('data-id');
                    const input = document.querySelector(`.upload-input[data-id="${id}"]`);
                    input.click();
                }
            });

            // Upload new image via AJAX
            tableBody.addEventListener('change', function(e) {
                if (e.target.classList.contains('upload-input')) {
                    const id = e.target.getAttribute('data-id');
                    const file = e.target.files[0];
                    if (!file) return;

                    const formData = new FormData();
                    formData.append('new_image', file);
                    formData.append('img_id', id);

                    fetch('updateImage.php', {
                        method: 'POST',
                        body: formData
                    }).then(res => res.text())
                    .then(response => {
                        console.log(response);
                        fetchImages('all');
                    }).catch(err => console.error(err));
                }
            });

            // Change category via AJAX
            tableBody.addEventListener('change', function(e) {
                if (e.target.classList.contains('category-select')) {
                    const id = e.target.getAttribute('data-id');
                    const newCategory = e.target.value;

                    const formData = new FormData();
                    formData.append('img_id', id);
                    formData.append('new_category', newCategory);

                    fetch('updateCategory.php', {
                        method: 'POST',
                        body: formData
                    }).then(res => res.text())
                    .then(response => {
                        console.log(response);
                    }).catch(err => console.error(err));
                }
            });

        </script>
         <script src="js/adminScript.js?<?php echo time()?>"></script>
    </body>
</html>