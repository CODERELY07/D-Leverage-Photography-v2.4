const hideElements = document.querySelector('.hide');
// Get the user element
const user = document.getElementById('user');
const filtered = document.getElementById('filtered');
const filterHide = document.getElementById('filter-hide');

// Album Image Scripts 
document.addEventListener("DOMContentLoaded", (event) => {
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function () {
            document.getElementById('album-id').value = this.dataset.id;
            document.getElementById('upload-name').value = this.dataset.name;
            document.getElementById('album-category').value = this.dataset.category;
            document.getElementById('upload-link').value = this.dataset.link;

            // Optional: Scroll to form
            document.querySelector("form").scrollIntoView({ behavior: "smooth" });
        });
    });
});

if(location.pathname === '/D-Leverage-Photography-v2.4/albumImages.php'){
    $('#uploadForm').submit(function(e) {
    e.preventDefault();

    var formData = new FormData(this);

    $.ajax({
        url: 'upload_album_img.php',
        type: 'POST',
        data: formData,
        success: function(response) {
            $('#response').html(response);
        },
        cache: false,
        contentType: false,
        processData: false
    });
});

}

window.addEventListener("click", () => {
    hideElem(filterHide)
    hideElem(hideElements)
});
user.addEventListener("click", (e) => {
    e.stopPropagation();
    toggleElem(hideElements); 
});
if(location.pathname == "/D-Leverage-Photography-v2.4/upload-portfolio.php"){
    filtered.addEventListener("click", (e) => {
        e.stopPropagation();
        toggleElem(filterHide); 
    });
}


function hideElem(element){
    element.classList.remove('active');
}
function toggleElem(element){
    element.classList.toggle('active');
}

