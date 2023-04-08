<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require '../controllers/auth.php';
require 'load-profile.php';



if (isset($_POST['update-password'])) {
  $current_pass = $_POST['inputCurrentPassword'];
  $new_pass = $_POST['inputNewPassword'];
  $confirm_pass = $_POST['inputConfirmPassword'];

  $query = "SELECT * FROM users WHERE email=? OR username=?";
  $statement = $conn -> prepare($query); 
  $statement -> bind_param('ss', $_SESSION['username'], $_SESSION['username']);
  $statement -> execute();
  $result = $statement->get_result();
  $user = $result -> fetch_assoc(); 
  if(password_verify($current_pass, $user['password'])) {
    if ($new_pass !== $confirm_pass) {
      $errors['password'] = 'Password and Confirm Password have to match';
    } else if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/', $new_pass)) {
      $errors['password'] = "Password must contain at least one lowercase letter, one uppercase letter, one number, and be at least 8 characters long.";
    } else if (count($errors)===0){
      $hashed_password = password_hash($new_pass, PASSWORD_DEFAULT);
      $update_query = 'UPDATE users SET password = ? WHERE username = ?';
      $statement -> bind_param('ss', $hashed_password, $_SESSION['username']);
      if ($statement -> execute()) {
        echo "<div class='alert alert-success'> Your password was successfully changed </div>";
      } else {
        echo "<div class='alert alert-danger'> Error changing password. Try again later </div>";
      }
    }
  } else {
      $errors['password'] = 'Password is incorrect';
    }
}



if (isset($_POST['post_id'])) { //if ajax request triggered
  if (isset($_SESSION['username'])){ //if user logged in
    try {
      $username = $_SESSION['username'];
      $post_id = $_POST['post_id'];
      $result = mysqli_query($conn, "SELECT * FROM posts WHERE PostID = $post_id");
      $row = mysqli_fetch_assoc($result);
      $n = $row['upvotes'];
       // Check if the user has already liked the post
      $upvoted_query = "SELECT * FROM upvotes WHERE PostID = '$post_id' AND Username = '$username'";
      $upvoted_result = mysqli_query($conn, $upvoted_query);
      if (mysqli_num_rows($upvoted_result) > 0) {
        // The user has already liked the post, so remove the like
        $delete_query = "DELETE FROM upvotes WHERE PostID = '$post_id' AND Username = '$username'";
        mysqli_query($conn, $delete_query);
        mysqli_query($conn, "UPDATE posts SET upvotes=$n-1 WHERE PostID = $post_id");
      } else {
        // The user has not liked the post, so add the like
        $add_query = "INSERT INTO upvotes (PostID, Username) VALUES ('$post_id', '$username')";
        if (mysqli_query($conn, $add_query)) {
        //run second query only if first one succeeded
        mysqli_query($conn, "UPDATE posts SET upvotes=$n+1 WHERE PostID = $post_id");
      }
    }

    $stmt = $conn->prepare("SELECT COUNT(*) FROM upvotes WHERE PostID = ?");
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $row = mysqli_fetch_array($result);
    
    $upvotes = $row[0];
    
    // Return the updated upvote count as a JSON object
    $response = array('upvotes' => $upvotes);
    echo json_encode($response);
  
    } catch (mysqli_sql_exception $e) {  // If a user tries to upvote a post twice.
      echo "WOOP WOOP";
  }
} else {
  echo "login";
}
exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/setting.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <title>Setting</title>
</head>
<body >
  <nav class="navbar fixed-top navbar-expand-md navbar-light" style="background-color: rgba(255,255,255, 0.85)">
        <div class="container">
          <?php 
              if (isset($_SESSION['username'])) { //if user logged in
                  echo "<a 
                  href='./profile.php' 
                  class='navbar-brand mb-1 h1'>
                  <img 
                  class='d-inline-block align-top'
                  id='proPic' 
                  src=". $profile_pic . " alt='profile photo'
                  width='80' height='80'>
              </a>";
                } else {
                  //what to show when user is not logged in instead of profile picture
                  //use echo
                }
            ?>
            <button 
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav"
                class="navbar-toggler"
                aria-controls="navbarNav"
                aria-expanded="false"
                aria-label="Toggle navigation"><i class="bi bi-caret-down arrowDown"></i></button>
            <div 
                class="collapse navbar-collapse "  
                id="navbarNav">
                <form action="results.php" class=" form-control-lg d-flex ms-auto" method="POST">
                    <input type="search" name="search" placeholder="Search..." class="form-control me 2">
                    <button class="btn btn-outline-info bi bi-search" type="submit" name="submit-search"></button></button>
                </form>
                <ul class="navbar-nav ms-auto align-items-center">
                  <li class="nav-item  ">
                      <a href="home.php" class="nav-link  "><h4><i class="bi bi-house"></i></h4></a>
                  </li>    
                  <li class="nav-item  ">
                      <a href="logout.php" onclick='logout()' class="nav-link"><h4><i class='bi bi-box-arrow-right'></i></h4></a>
                  </li>
                  <script>
                    function logout() {
                      // Wait for 1 second before redirecting
                      setTimeout(function() {
                        window.location.href = "http://localhost/project-Kaf-Mahir/kaf/login.php";
                      }, 1000);
                    }
                  </script>
                  <li class="nav-item ">
                    <?php 
                        if (isset($_SESSION['username'])) {
                          echo "<a href='./setting.php' class='nav-link'><h4><i class='bi bi-gear'></i></h4></a>";
                        } else {
                          //what to show when user is not logged in instead of settings. if anything
                        }                    
                      ?>
                  </li>
                  <li class="nav-item ">
                    <?php 
                        if (isset($_SESSION['username']) && $_SESSION['username'] == 'admin') {
                          echo "<a href='./admin.php' class='nav-link'><h4><i class='bi bi-x-square'></i></h4></a>";
                        } else {
                          //what to show when user is not logged in instead of settings. if anything
                        }                    
                      ?>
                  </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container  mySetting" style="margin-top: 150px">
      <h1 class="mb-4">Account Settings</h1>
      <div class="row">
        <div class="col-md-3">
          <!-- Sidebar -->
          <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
            <a class="nav-link active" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab"
              aria-controls="v-pills-profile" aria-selected="true">Profile</a>
            <a class="nav-link" id="v-pills-security-tab" data-toggle="pill" href="#v-pills-security" role="tab"
              aria-controls="v-pills-security" aria-selected="false">Security</a>
          </div>
        </div>
        <div class="col-md-9">
          <!-- Tab Content -->
          <div class="tab-content" id="v-pills-tabContent">
            <div class="tab-pane fade show active" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
              <h2 class="mb-4">Profile Settings</h2>
              <form action='setting.php' method='POST' enctype="multipart/form-data">
                <!-- Show Input Errors To User -->
                  <?php if (isset($_POST['save-profile']) && !validateFile()) { 
                    echo "<div class='alert alert-warning'>Image formant not supported</div>"; 
                  } ?>   
                <div class="form-group">
                      <label class="mt-2" for="inputName">Name</label>
                      <input type="text" name="inputName"class="form-control" id="inputName" placeholder="Enter name">
                    </div>
                <div class="form-group">
                  <label class="mt-2" for="inputBio">Bio</label>
                  <textarea type="text" name="inputBio" class="form-control" id="inputBio" placeholder="Enter Bio"> </textarea>
                </div>
                <div class="form-group">
                  <label class="mt-2" for="inputPic">Profile Image</label>
                  <input type="file" class="form-control" id="inputPic" name='inputPic' accept= "image/*">
                </div>
                <button type="submit" name="save-profile" class="btn btn-primary mt-3">Save Changes</button>
              </form>
            </div>
            <div>
                        <!-- Show Input Errors To User -->
                        <?php 
                          if (isset($_POST['update-password']) && count($errors) > 0): 
                        ?>
                          <div class="alert alert-danger">
                            <?php foreach($errors as $error): ?>
                              <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                          </div>
                        <?php endif; ?>
                      </div>

            <div class="tab-pane fade" id="v-pills-security" role="tabpanel" aria-labelledby="v-pills-security-tab">
              <h2 class="mb-4">Security Settings</h2>
              <form action='setting.php' method='post'>
                <div class="form-group">
                    <label class="mt-2" for="inputCurrentPassword">Current Password</label>
                    <input type="password" class="form-control" id="inputCurrentPassword" placeholder="Enter current password" required>
                </div>
                <div class="form-group">
                    <label class="mt-2" for="inputNewPassword">New Password</label>
                    <input type="password" class="form-control" id="inputNewPassword" placeholder="Enter new password" required>
                </div>
                <div class="form-group">
                    <label class="mt-2" for="inputConfirmPassword">Confirm New Password</label>
                    <input type="password" class="form-control" id="inputConfirmPassword" placeholder="Confirm new password" required>
                </div>
                <button type="submit" class="btn btn-primary mt-2" name='update-password'>Update Password</button>
              </form>
            </div>
          </div>
      </div>
    </div>
 </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  </body>
</html>

