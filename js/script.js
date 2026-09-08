const headers = document.querySelectorAll(".fix");

window.addEventListener("scroll", () => {
  if (window.scrollY < 0 || window.scrollY == 0) {
    headers.forEach((fix) => {
      fix.classList.remove("change");
    });
  } else {
    headers.forEach((fix) => {
      fix.classList.add("change");
    });
  }
});

// bar
const mobileBar = document.getElementById("bar");
const menu = document.querySelector(".mobile-menu");
const menuBackdrop = document.querySelector(".mobile-menu-backdrop");

function toggleMobileMenu(forceClose) {
  const opening = forceClose ? false : !menu.classList.contains("barActive");

  menu.classList.toggle("barActive", opening);
  if (menuBackdrop) menuBackdrop.classList.toggle("barActive", opening);
  mobileBar.setAttribute("aria-expanded", String(opening));
  mobileBar.setAttribute("aria-label", opening ? "Close menu" : "Open menu");
  document.body.style.overflow = opening ? "hidden" : "";

  mobileBar.classList.toggle("fa-bars", !opening);
  mobileBar.classList.toggle("fa-x", opening);
}

mobileBar.addEventListener("click", () => toggleMobileMenu());
mobileBar.addEventListener("keydown", (e) => {
  if (e.key === "Enter" || e.key === " ") {
    e.preventDefault();
    toggleMobileMenu();
  }
});

if (menuBackdrop) {
  menuBackdrop.addEventListener("click", () => toggleMobileMenu(true));
}

menu.querySelectorAll("a").forEach((link) => {
  link.addEventListener("click", () => toggleMobileMenu(true));
});

document.addEventListener("keydown", (e) => {
  if (e.key === "Escape" && menu.classList.contains("barActive")) {
    toggleMobileMenu(true);
  }
});

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

// Contact XMLhttpRequest

const contactForm = document.getElementById("contactForm");

if(location.pathname == "/D-Leverage-Photography-v2.4/contact.php"){
    contactForm.addEventListener("submit", function (e) {
    e.preventDefault();
    let formData = new FormData(this);
    /*Testing purpose */
    // let formObject = {};
    // formData.forEach((key,value)=>{
    //    formObject[key] = value;
    // })

    // console.log(formObject);
    formData.append("send", document.getElementById("send").name);
    let xhr = new XMLHttpRequest();
    xhr.open("Post", "contactSubmit.php");

    xhr.onload = function () {
      if (xhr.status >= 200 && xhr.status < 300) {
        if (this.responseText == 0) {
          Swal.fire({
            icon: "error",
            title: "All Data is required",
            text: "Please Input your data!",
          });
        } else if (this.responseText == -1) {
          Swal.fire({
            icon: "Error",
            title: "Input Error",
            text: "Please provide correct email!",
          });
        } else if (this.responseText == -2) {
          Swal.fire({
            icon: "Error",
            title: "Already Book!",
            text: "You're Already Send a messgae, Please wait a minute for the response",
          });
          contactForm.reset();
        } else {
          Swal.fire(
            "Your Form is Submitted Succesfully!Check your email after a minutes"
          );
          contactForm.reset();
        }
      } else {
        console.log("Error:", xhr.statusText);
      }
    };
    xhr.onerror = function () {
      console.error("Request failed");
    };

    xhr.send(formData);
  });
 
}


