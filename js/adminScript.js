const hideElements = document.querySelector('.hide');
// Get the user element
const user = document.getElementById('user');
const filtered = document.getElementById('filtered');
const filterHide = document.getElementById('filter-hide');

window.addEventListener("click", () => {
    hideElem(filterHide)
    hideElem(hideElements)
});
user.addEventListener("click", (e) => {
    e.stopPropagation();
    toggleElem(hideElements); 
});
filtered.addEventListener("click", (e) => {
    e.stopPropagation();
    toggleElem(filterHide); 
});

function hideElem(element){
    element.classList.remove('active');
}
function toggleElem(element){
    element.classList.toggle('active');
}
