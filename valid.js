var form = document.getElementById("mainForm");

form.addEventListener("submit", function (event) {
  var formUser = document.getElementById("formUsername");
  var formEmail = document.getElementById("formEmail");
  var formPassword = document.getElementById("formPassword");
  var formPasswordConfirm = document.getElementById("formPasswordConfirm");

  if (formUser.value.trim() == "") {
    formUser.classList.add("is-invalid");
    event.preventDefault();
  } else {
    formUser.classList.remove("is-invalid");
  }

  if (formEmail.value == "") {
    formEmail.classList.add("is-invalid");
    event.preventDefault();
  } else {
    formEmail.classList.remove("is-invalid");
  }

  if (formPassword.value.trim() == "") {
    formPassword.classList.add("is-invalid");
    event.preventDefault();
  } else {
    formPassword.classList.remove("is-invalid");
  }

  if (formPasswordConfirm.value == "") {
    formPasswordConfirm.classList.add("is-invalid");
    event.preventDefault();
  } else {
    formPasswordConfirm.classList.remove("is-invalid");
  }
});

// add event listeners to each input element
formUser.addEventListener("input", function () {
  if (formUser.value.trim() != "") {
    formUser.classList.remove("is-invalid");
  }
});

formEmail.addEventListener("input", function () {
  if (formEmail.value != "") {
    formEmail.classList.remove("is-invalid");
  }
});

formPassword.addEventListener("input", function () {
  if (formPassword.value.trim() != "") {
    formPassword.classList.remove("is-invalid");
  }
});

formPasswordConfirm.addEventListener("input", function () {
  if (formPasswordConfirm.value != "") {
    formPasswordConfirm.classList.remove("is-invalid");
  }
});
