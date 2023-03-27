<?php
require('../controllers/auth.php');
error_reporting(E_ALL);
ini_set('display_errors', '1');

$msg = $_GET['msg'] ?? '';
if (!empty($msg)) {
  $alert_class = 'alert-warning'; // Bootstrap class for warning alert
  $message = htmlspecialchars($msg);
  echo "<div class='alert $alert_class'>$message</div>";
}


?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link href="css/style.css" rel="stylesheet" />
    <script
      defer
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
    ></script>
  </head>
  <body>
    <section class="vh-100 gradient-custom">
      <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
          <div class="form-box col-12 col-md-8 col-lg-6 col-xl-5">
            <div class="card text-dark" style="border-radius: 1rem">
              <div id="login" class="card-body p-5 text-center">
                <div class="mb-md-5 mt-md-4 pb-5">
                  <h2 class="fw-bold mb-2 text-uppercase">Login</h2>
                  <p class="text-dark-50 mb-5">
                    Please enter your Username/Email and Password!
                  </p>
                  <form id='loginForm' method="POST" action="login.php">
                    <div class="form-outline mb-4">
                    <div>
                        <!-- Show Input Errors To User -->
                        <?php 
                          if (isset($_POST['login-btn']) && count($errors) > 0): 
                        ?>
                          <div class="alert alert-danger">
                            <?php foreach($errors as $error): ?>
                              <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                          </div>
                        <?php endif; ?>
                      </div>
                      <input
                        type="username"
                        name='username'
                        id="formUsername"
                        class="form-control form-control-lg"
                      />
                      <label class="form-label" for="formUsername">Username or Email</label>
                    </div>
                    <div class="form-outline mb-4">
                      <input
                        type="password"
                        name='password'
                        id="formPassword"
                        class="form-control form-control-lg"
                      />
                      <label class="form-label" for="formPassword">Password</label>
                    </div>
                    <p class="small mb-5 pb-lg-2">
                      <a class="text-dark-50" href="#!">Forgot password?</a>
                    </p>
                    <button class="btn btn-outline-dark btn-lg px-5" type="submit" name='login-btn'>Login</button>
                  </form>
                  <div class="d-flex justify-content-center text-center mt-4 pt-1">
                    <a href="#!" class="text-dark"><i class="fab fa-facebook-f fa-lg"></i></a>
                    <a href="#!" class="text-dark"><i class="fab fa-twitter fa-lg mx-4 px-2"></i></a>
                    <a href="#!" class="text-dark"><i class="fab fa-google fa-lg"></i></a>
                  </div>
                  
                  <div>
                    <p class="mb-0">
                      Don't have an account?
                      <a href="signup.php" class="text-dark-50 fw-bold">Sign Up</a>
                      </p>
                  </div>
                  <div>
                  <a href="home.php" class="text-dark-50 fw-bold">Enter without login</a>
                  </div>
                  
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <script src='./valid2.js'></script>
  </body>
