<?php
    require_once 'connection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>D'Leverage Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/admin.css?v=<?php echo time(); ?>">
</head>
<body>
    <main>
        <h2>Welcome, Admin</h2>
        <p>What is our business today?</p>
        <!-- <div class="modal">
            <input type="text" class="form-control" name="addCat" id="addCat">
            <button id="addCatBtn" class="btn btn-primary" onclick="addNewCategory()">Add</button>
        </div> -->
        <div class="setting">
            <div class="hidden-parent">
                <i class="fa-solid fa-caret-down icon"></i>
                <div class="hidden">
                    <ul class="bg-secondary text-white rounded p-3">
                        <li class="mb-2">Upload Photos</li>
                        <li class="mb-2">Modify Photos</li>
                    </ul>
                </div>
            </div>
            <div class="hidden-parent">
                <i class="fa-solid fa-message icon"></i>
                
            </div>
            <div class="hidden-parent">
                <i class="fa-solid fa-gear icon"></i>
                <div class="hidden">
                    <a href="logout.php">Logout</a>
                </div>
            </div>
           
        </div>

        <div class="hide">
            <form action="upload.php" method="POST" class="mt-5" enctype="multipart/form-data" id="uploadForm">
                <label for="category">Select Category:</label>
                <select name="category" id="category" class="form-control">
                    <option value="" selected></option>
                    <!-- <//?php
                        $query = "SELECT DISTINCT category FROM image";
                        $result = $db->query($query);

                        if($result->num_rows > 0){
                            while($row = $result->fetch_assoc()){
                                $category = htmlspecialchars($row['category']); // Sanitize output
                                echo "<option value='$category'>$category</option>";
                            }
                        }
                    
                    ?> -->
                    <option value="" selected></option>
                    <option value="wedding">Wedding/Prenuptial </option>
                    <option value="birthday">Birthday</option>
                    <option value="others">Others</option>
                </select>
                <br><br>
                <input type="file" name="uploadImg[]" class="form-control" multiple>
                <input type="submit" id="uploadBtn" value="Upload Files" class="btn btn-primary mt-3" name="upload">
            </form>
        </div>
        <div class="hide mt-4">
           <div class="container">
            <div class="card">
                <div class="card-header">
                    <div class="hidden-parent filter">
                        <span class='icon'>Filter  <i class="fa-solid fa-caret-down"></i></span>
                        <div class="hidden">
                        <ul id="filter-list" class="bg-secondary text-white rounded p-3">
                            <li class="mb-2" data-filter="all">All</li>
                            <li class="mb-2" data-filter="wedding">Wedding/Prenuptial</li>
                            <li class="mb-2" data-filter="birthday">Birthday</li>
                            <li class="mb-2" data-filter="others">Others</li>
                        </ul>

                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="card-text"> 
                        <form>
                            <table class="image_table table table-striped table-border">
                                <tr>
                                    <td>Image</td>
                                    <td>Image Name</td>
                                    <td>Image Category</td>
                                    <td>Action</td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
           </div>
        </div>
    </main>
    <script>
     document.addEventListener('DOMContentLoaded', () => {
            const filterList = document.getElementById('filter-list');
            const tableBody = document.querySelector('.image_table tbody');

            filterList.addEventListener('click', (event) => {
                const filter = event.target.getAttribute('data-filter');
                if (filter) {
                    fetchImages(filter);
                }
            });

            function fetchImages(filter) {
                const xhr = new XMLHttpRequest();
                xhr.open('GET', `adminRequest.php?filter=${filter}`, true);
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        let html = '';
                        let response;
                        try {
                            response = JSON.parse(this.responseText);
                            response.forEach(img => {
                                html += `
                                    <tr>
                                        <td><img src="image/${img.filename}" alt="${img.filename}" width="100"></td>
                                        <td>${img.filename}</td>
                                        <td>${img.category}</td>
                                        <td><button class="btn btn-danger" onclick="deleteImage('${img.filename}')">Delete</button></td>
                                    </tr>
                                `;
                            });
                            tableBody.innerHTML = html;
                        } catch (error) {
                            console.error("Error Parsing JSON: ", error);
                        }
                    } else {
                        tableBody.innerHTML = 'Error loading images.';
                    }
                };
                xhr.send();
            }

            // Initial load
            fetchImages('all');
        });

        function deleteImage(filename) {
            // Function to handle image deletion
            // Implement AJAX request for deleting an image
            console.log(`Delete ${filename}`);
        }




        const add = document.getElementById('category');
        let modal = document.querySelector('.modal');
        const uploadBtn = document.getElementById('uploadBtn');

        uploadBtn.addEventListener("click",validateUpload);
        add.addEventListener("change", function (e){
            if(e.target.value == "add"){
                modal.classList.add('active');
            }else{
                modal.classList.remove('active');
            }
        });
        function validateUpload(e){
            if(add.value == "add" || add.value == ""){
                e.preventDefault();
                alert("Please Select Category!");
            }else{
                return ;
            }
        }

        const icons = document.querySelectorAll('.icon');

        // Function to remove 'active' class from all elements
        function removeActiveFromAll() {
            icons.forEach(icon => {
                const nextElement = icon.nextElementSibling;
                if (nextElement) {
                    nextElement.classList.remove('active');
                }
            });
        }

        // Add event listeners to each icon
        icons.forEach(icon => {
            icon.addEventListener("click", function() {
                const nextElement = icon.nextElementSibling;
                
                if (nextElement) {
                    // Check if the element is already active
                    if (nextElement.classList.contains('active')) {
                        // If active, remove it
                        nextElement.classList.remove('active');
                    } else {
                        // Otherwise, remove 'active' from all and then add it to this one
                        removeActiveFromAll();
                        nextElement.classList.add('active');
                    }
                }
            });
        });
    </script>
</body>
</html>