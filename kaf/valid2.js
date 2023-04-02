var form = document.getElementById("loginForm");
var formUser = document.getElementById("formUsername");
form.addEventListener("submit", function (event) {
  console.log(formUser.value);
  var formPassword = document.getElementById("formPassword");
  console.log(formPassword.value);

  if (formUser.value.trim() == "") {
    formUser.classList.add("is-invalid");
    event.preventDefault();
  } else {
    formUser.classList.remove("is-invalid");
  }

  if (formPassword.value.trim() == "") {
    formPassword.classList.add("is-invalid");
    event.preventDefault();
  } else {
    formPassword.classList.remove("is-invalid");
  }
});

// add event listeners to each input element
formUser.addEventListener("input", function () {
  if (formUser.value.trim() != "") {
    formUser.classList.remove("is-invalid");
  }
});

formPassword.addEventListener("input", function () {
  if (formPassword.value.trim() != "") {
    formPassword.classList.remove("is-invalid");
  }
});
