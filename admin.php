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
    <style>
        .modal{
            display: none;
            height: max-content;
        }
        .modal.active{
            display:block;
        }
    </style>
</head>
<body>
    <br><br><br>
    <div class="modal">
        <input type="text" name="addCat" id="addCat">
        <button id="addCatBtn" onclick="addNewCategory()">Add</button>
    </div>
    <form action="upload.php" method="POST" enctype="multipart/form-data" id="uploadForm">
        <label for="category">Select Category:</label>
        <select name="category" id="category">
            <!-- <option value="wedding">Wedding</option>
            <option value="birthday">Birthday</option> -->
            <?php
                $query = "SELECT DISTINCT category FROM image";
                $result = $db->query($query);

                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $category = htmlspecialchars($row['category']); // Sanitize output
                        echo "<option value='$category'>$category</option>";
                    }
                }
              
            ?>
            <option value="add">+</option>
        </select>
        <br><br>
        <input type="file" name="uploadImg[]" multiple>
        <input type="submit" value="Upload Files" name="upload">
    </form>

    <script>
        const add = document.getElementById('category');
        let modal = document.querySelector('.modal');
        add.addEventListener("change", function (e){
            if(e.target.value == "add"){
                modal.classList.add('active');
            }else{
                modal.classList.remove('active');
            }
        });

        function addNewCategory() {
            let addCatInput = document.getElementById('addCat');
            let addCatValue = addCatInput.value.trim().toLowerCase(); // Trim whitespace and convert to lowercase
            
            if (addCatValue === '') {
                alert('Please enter a category name.');
                return;
            }
            
            // Check if category already exists
            let categorySelect = document.getElementById('category');
            for (let option of categorySelect.options) {
                if (option.value === addCatValue) {
                    alert('Category already exists.');
                    return;
                }
            }
            
            // Create a new option element
            let option = document.createElement('option');
            option.value = addCatValue;
            option.textContent = addCatValue.charAt(0).toUpperCase() + addCatValue.slice(1); // Capitalize first letter
            
            // Find the position to insert before the "+"
            let addOption = document.querySelector('#category option[value="add"]');
            categorySelect.insertBefore(option, addOption);
            
            // Clear the input field and hide the modal
            addCatInput.value = '';
            document.querySelector('.modal').classList.remove('active');
        }


    </script>
</body>
</html>