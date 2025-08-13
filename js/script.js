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

mobileBar.addEventListener("click", function (e) {
  menu.classList.toggle("barActive");

  if (mobileBar.classList.contains("fa-bars")) {
    mobileBar.classList.remove("fa-bars");
    mobileBar.classList.add("fa-x");
  } else {
    mobileBar.classList.remove("fa-x");
    mobileBar.classList.add("fa-bars");
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


