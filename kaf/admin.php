<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require '../controllers/auth.php';


if (isset($_POST['toggle_status'])) {
  $username = mysqli_real_escape_string($conn, $_POST['username']);
  $status = mysqli_real_escape_string($conn, $_POST['status']);

  $query = "UPDATE users SET status='$status' WHERE username='$username'";
  mysqli_query($conn, $query);
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Page</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="css/admin.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

</head>
<body>
<nav class="navbar fixed-top navbar-expand-md navbar-light" style="background-color: rgba(255,255,255, 0.85)">
        <div class="container">
            <!-- <a 
                href="./profile.php" 
                class="navbar-brand mb-1 h1">
                <img 
                class="d-inline-block align-top"
                id="proPic" 
                src="img/propic.jpg" alt="profile photo" 
                width="80" height="80">
            </a> -->
            <h2>administration.</h2>
            <button 
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav"
                class="navbar-toggler"
                aria-controls="navbarNav"
                aria-expanded="false"
                aria-label="Toggle navigation">
                
            <i class="bi bi-caret-down arrowDown"></i>
            </button>
            <div 
                class="collapse navbar-collapse "  
                id="navbarNav">
                <form action="results.php" class="d-flex ms-auto" method="POST">
                    <input type="search" name="search" placeholder="Search..." class="form-control me 2">
                    <button class="btn btn-outline-info bi bi-search" type="submit" name="submit-search"></button>
                    </button>
                </form>

                <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item  ">
                        <a href="home.php" class="nav-link  "><h4><i class="bi bi-house"></i></h4></a>
                    </li>    
                <li class="nav-item  ">
                        <a href="login.php?logout=1" class="nav-link  "><h4><i class="bi bi-box-arrow-left"></i></h4></a>
                    </li>
                    <li class="nav-item ">
                        <a href="setting.php" class="nav-link"><h4><i class="bi bi-gear"></h4></i></a>
                    </li>
                </ul>

            </div>
        </div>
    </nav>
  
  <div style="margin-top: 150px" class="container">
    <h1>Admin Dashboard</h1>
    <p>Welcome to the admin dashboard. Here you can manage users and settings.</p>
  </div>

<div class="container">
    <h3>Server Users:</h3>
    <table class="table">
      <thead>
        <tr>
          <th>Username</th>
          <th>Email 1</th>
          <!-- <th>Password</th> -->
          <th>Enabled/Disabled</th>
        </tr>
    </thead>
    <tbody>
      <!-- Load Post Card -->
      <?php 
          if (isset($_SESSION['username'])) {
          $username = mysqli_real_escape_string($conn, $_SESSION['username']);
          }

          $query = "SELECT * FROM users";

          $result = mysqli_query($conn,$query);
          if (mysqli_num_rows($result) > 0 ) {
            while ($row = mysqli_fetch_assoc($result)) {
            $status = $row['status'];
            $status_btn_text = $status == 'enabled' ? 'Disable' : 'Enable';
            $status_btn_class = $status == 'enabled' ? 'btn-danger' : 'btn-success';
            $status_toggle = $status == 'enabled' ? 'disabled' : 'enabled';
            echo "     
            <tr>
              <td>{$row['username']}</td>
              <td>{$row['email']}</td>
              <td>
                <form method='post'>
                  <input type='hidden' name='username' value='{$row['username']}' />
                  <input type='hidden' name='status' value='$status_toggle' />
                  <button class='btn $status_btn_class' type='submit' name='toggle_status'>$status_btn_text</button>
                </form>
              </td>
            </tr>
          ";
        }
      }
    ?>
  </tbody>
</table>
</div>

  
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
