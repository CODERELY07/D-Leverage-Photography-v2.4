const header = document.querySelectorAll(".fix");
    window.addEventListener("scroll", () => {
    if (window.scrollY < 0 || window.scrollY == 0) {
        header.forEach(fix => {
        fix.classList.remove("change");
        });
    
    } else {
        header.forEach(fix => {
        fix.classList.add("change");
        });
    }
    });

    // bar
    const mobileBar = document.getElementById('bar');
    const menu = document.querySelector('.mobile-menu');
    
    mobileBar.addEventListener('click',function(e){
    menu.classList.toggle('barActive');
    
    if (mobileBar.classList.contains('fa-bars')) {
        mobileBar.classList.remove('fa-bars');
        mobileBar.classList.add('fa-x');
    } else {
        mobileBar.classList.remove('fa-x');
        mobileBar.classList.add('fa-bars');
    }
})   
const loading = document.querySelectorAll(".img");
loading.forEach((div) => {
  const img = div.querySelector("img");

  function loaded() {
    div.classList.add("loaded");
  }

  if (img.complete) {
    loaded();
  } else {
    img.addEventListener("load", loaded);
  }
});