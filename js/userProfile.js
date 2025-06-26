function validateFormUpdateAccount(element){ 
  var regex = {
    username:/^[a-z0-9_-]{3,15}$/,
    fName:/^[a-zA-Z]{3,10}$/,
    lName:/^[a-zA-Z]{3,10}$/,
    eMail: /[^@ \t\r\n]+@[^@ \t\r\n]+\.[^@ \t\r\n]+/,
    passWord: /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{8,}$/,
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