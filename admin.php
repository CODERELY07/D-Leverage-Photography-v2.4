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
    <style>
        [data-tab-content]{
            display:none;
        }
        .active[data-tab-content]{
            display:block;
        }
    </style>
</head>
<body>
    <main>
        <h2>Welcome, Admin</h2>
        <p>What is our business today?</p>
        <div class="setting">
            <div class="hidden-parent">
                <button type="button" class="btn icon btn-primary">
                    <i class="fa-solid fa-caret-down"></i>
                </button>
                <div class="hidden">
                    <ul class="bg-secondary text-white rounded p-3">
                        <li class="mb-2" data-tab-target="#upload-img" class="active tab">Upload Photos</li>
                        <li class="mb-2"  data-tab-target="#modify"  class="tab">Modify Photos</li>
                    </ul>
                </div>
            </div>
            <div class="hidden-parent">
                    <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary icon" data-toggle="modal" data-target="#exampleModal">
                    <i class="fa-solid fa-message "></i>
                </button>
                <div class="hidden">
                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                ...
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Save changes</button>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hidden-parent">
                <button type="button" class="btn icon btn-primary">
                    <i class="fa-solid fa-gear "></i>
                </button>
               
                <div class="hidden">
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        </div>

        <div class="tab-content">
        <div class="active" data-tab-content id="upload-img">
            <form action="upload.php" method="POST" class="mt-5" enctype="multipart/form-data" id="uploadForm">
                <label for="category">Select Category:</label>
                <select name="category" id="category" class="form-control">
                    <option value="" selected></option>
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
        <div data-tab-content id="modify" class="mt-4">
           <div class="container-fluid">
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
                console.log(filter)
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
                        if(this.responseText == "no"){
                          html = '<h1 class="mt-5 text-secondary text-center"> No Image Yet</h1>';
                          tableBody.innerHTML = html;
                        }else{
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


        // Dom menus
        const add = document.getElementById('category');
        let modal = document.querySelector('.modal');
        const uploadBtn = document.getElementById('uploadBtn');
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

        // Function to validate upload
        function validateUpload(e) {
            if (add.value == "add" || add.value == "") {
                e.preventDefault();
                alert("Please Select Category!");
            }
        }

        // Event listeners
        uploadBtn.addEventListener("click", validateUpload);

        add.addEventListener("change", function (e) {
            if (e.target.value == "add") {
                modal.classList.add('active');
            } else {
                modal.classList.remove('active');
            }
        });

        icons.forEach(icon => {
            icon.addEventListener("click", function(e) {
                const nextElement = icon.nextElementSibling;

                if (nextElement) {
                    if (nextElement.classList.contains('active')) {
                        nextElement.classList.remove('active');
                    } else {
                        removeActiveFromAll();
                        nextElement.classList.add('active');
                    }
                }

                // Prevent event from propagating to window click handler
                e.stopPropagation();
            });
        });

        // Window click event listener
        window.addEventListener("click", function() {
            console.log('hi');
            removeActiveFromAll();
        });

        // Dom menu upload and modify tabs
        const tabs = document.querySelectorAll('[data-tab-target]');
        const tabContents = document.querySelectorAll('[data-tab-content]');

        tabs.forEach(tab =>{
            tab.addEventListener("click",()=>{
                const target = document.querySelector(tab.dataset.tabTarget);
                tabContents.forEach(tabContent =>{
                    tabContent.classList.remove('active');
                })
                tabs.forEach(tab =>{
                    tab.classList.remove('active');
                })
                target.classList.add('active');
                tab.classList.add('active');
            })
        })
    </script>
</body>
</html>