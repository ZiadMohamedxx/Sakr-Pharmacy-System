const nav = document.getElementById("mynav");
const wrapper = document.querySelector(".wrapper");
const loginLink = document.querySelector(".login-link");
const RegisterLink = document.querySelector(".Register-link");
const btnPopup = document.querySelector(".pop");
const inconclose = document.querySelector(".icon-close");
var navBtn = document.getElementById("btn-nav");
var navBtn2 = document.getElementById("btn-nav2");
var BodyBuild = document.getElementById("BodyBuildingLink");
window.onscroll = function () {
  if (
    document.body.scrollTop >= 200 ||
    document.documentElement.scrollTop >= 1000
  ) {
    nav.classList.add("nav-colored");
    nav.classList.remove("nav-transparent");
    navBtn.classList.remove("d-none");
    navBtn2.classList.remove("d-none");
  } else {
    nav.classList.add("nav-transparent");
    nav.classList.remove("nav-colored");
    navBtn.classList.add("d-none");
    navBtn2.classList.add("d-none");
  }
};

function validateFormLogin(element) {
  var regex = {
    username:/^[a-z0-9_-]{3,15}$/,
    password: /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{8,}$/,
  };

  if (regex[element.id].test(element.value)) {
    element.classList.add("is-valid");
    element.nextElementSibling.classList.add("d-none");
    element.classList.remove("is-invalid");
  } else {
    element.classList.add("is-invalid");
    element.classList.remove("is-valid");
    element.nextElementSibling.classList.remove("d-none");
  }
}
function validateFormRegister(element) {
  var regex = {
    username:/^[a-z0-9_-]{3,15}$/,
    fName: /^[a-zA-Z]{3,10}$/,
    sName: /^[a-zA-Z]{3,10}$/,
    eMailRegister: /[^@ \t\r\n]+@[^@ \t\r\n]+\.[^@ \t\r\n]+/,
    repasswordRegister:
      /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{8,}$/,
    passwordRegister:
      /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{8,}$/,
    Phone: /^01[0125]\d{8}$/,
  };
  console.log(element);

  if (regex[element.id].test(element.value)) {
    element.classList.add("is-valid");
    element.nextElementSibling.classList.add("d-none");
    element.classList.remove("is-invalid");
  } else {
    element.classList.add("is-invalid");
    element.classList.remove("is-valid");
    element.nextElementSibling.classList.remove("d-none");
  }
}

function getSectionId(id) {
  localStorage.setItem("selectedSection", id);
  window.location.href = "courses.html";
}

