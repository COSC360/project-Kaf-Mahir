<?php
require_once('../controllers/auth.php'); 

if(!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
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
                  <h3>Welcome, <?php echo $_SESSION['username']; ?></h3>
                  <a class="btn btn-primary" href="index.php?logout=1" role="button">Logout</a>
                  <a class="btn btn-info" href="home.php" role="button">Home</a>
                  <div class="alert <?php echo $_SESSION['alert-class']; ?>">
                  <?php if(isset($_SESSION['msg'])): ?>
                  <?php echo $_SESSION['msg'];
                  //Reomove login message after refresh, keep email verification message until verified.
                  unset($_SESSION['msg']);
                  unset($_SESSION['alert-class']);
                  ?>
                </div>
                <?php endif; ?>
                
                </div>
                <div class='alert alert-warning p-lg-4'>
                    Check your email for the verification code we just sent you at <strong><?php echo $_SESSION['email']; ?></strong>
                    <br><strong><em>To Be Implemented</em></strong>
                  </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </body>
</html>
