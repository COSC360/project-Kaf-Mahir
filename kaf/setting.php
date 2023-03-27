<?php
require "../controllers/auth.php";
require 'load-profile.php';
  
 //update/create profile
 
 if (isset($_POST['save-profile']) && validateFile()) {
  if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $full_name = $_POST['inputName'];
    $bio = $_POST['inputBio'];

    if (!empty($full_name && !empty($bio))) {
    //Check if username already has profile information
    $check_query = "SELECT * FROM `profiles` WHERE `username` = '$username'";
    $result = mysqli_query($conn, $check_query);
    if (mysqli_num_rows($result) > 0) {
      $update_query = "UPDATE `profiles` SET `full_name` = '$full_name', `bio` = '$bio' WHERE `username` = '$username'";
      if (mysqli_query($conn, $update_query)) {
        $msg = "Profile successfully updated";
        header("Location: profile.php?msg=" . urlencode($msg));
        exit;
    }
    } else {
    $insert_query = "INSERT INTO profiles (username, full_name, bio) values (?, ?, ?)";
    $statement = $conn -> prepare($insert_query);
    $statement -> bind_param('sss', $username, $full_name, $bio);
    // If user is successfully created, log them in and redirect to homepage
    if ($statement -> execute()) {
      $msg = "Profile successfully created";
      header("Location: profile.php?msg=" . urlencode($msg));
      exit;
  } else {
    die("Error: ". $statement -> error);
  }   
}
}
}
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
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100&display=swap" rel="stylesheet">
    <title>Profile</title>
</head>
<body >
    <nav class="navbar fixed-top navbar-expand-md navbar-light" style="background-color: rgba(255,255,255, 0.85)">
        <div class="container">
            <a 
                href="./profile.php" 
                class="navbar-brand mb-1 h1">
                <img 
                class="d-inline-block align-top"
                id="proPic" 
                src="<?php echo $profile_pic; ?>" alt="profile photo" 
                width="80" height="80">
            </a>
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
                    <input type="search" placeholder="Search..." name="search" class="form-control me 2">
                    <button class="btn btn-outline-info bi bi-search" type="submit" name="submit-search"></button>
                    </button>
                </form>

                <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item  ">
                        <a href="./home.php" class="nav-link  "><h4><i class="bi bi-house"></i></h4></a>
                    </li>    
                <li class="nav-item  ">
                        <a href="" class="nav-link  "><h4><i class="bi bi-box-arrow-left"></i></h4></a>
                    </li>
                    <li class="nav-item ">
                        <a href="./setting.php" class="nav-link active"><h4><i class="bi bi-gear"></h4></i></a>
                    </li>
                </ul>

            </div>
        </div>
    </nav>
    <div class="container  mySetting">
      <h1 class="mb-4">Account Settings</h1>
      <div class="row">
        <div class="col-md-3">
          <!-- Sidebar -->
          <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
            <a class="nav-link active" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab"
              aria-controls="v-pills-profile" aria-selected="true">Profile</a>
            <a class="nav-link" id="v-pills-notifications-tab" data-toggle="pill" href="#v-pills-notifications" role="tab"
              aria-controls="v-pills-notifications" aria-selected="false">Notifications</a>
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
                  <!-- <div class="form-group">
                    <label class="mt-2" for="inputEmail">Email</label>
                    <input type="email" class="form-control" id="inputEmail" placeholder="Enter email">
                  </div>
                  <div class="form-group">
                    <label class="mt-2" for="inputCity">City</label>
                    <input type="text" class="form-control" id="inputCity" placeholder="Enter city">
                  </div> -->
                <div class="form-group">
                  <label class="mt-2" for="inputPic">Profile Image</label>
                  <input type="file" class="form-control" id="inputPic" name='inputPic' accept= "image/*">
                </div>
                  <button type="submit" name="save-profile" class="btn btn-primary mt-3">Save Changes</button>
                </form>
            </div>
            <div class="tab-pane fade" id="v-pills-notifications" role="tabpanel" aria-labelledby="v-pills-notifications-tab">
              <h2 class="mb-4">Notification Settings</h2>
              <form>
                <div class="form-group">
                  <label for="inputNotification1">Notification 1</label>
                  <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="inputNotification1">
                    <label class="custom-control-label" for="inputNotification1">Receive notification 1</label>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputNotification2">Notification 2</label>
                  <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="inputNotification2">
                    <label class="custom-control-label" for="inputNotification2">Receive notification 2</label>
                  </div>
                </div>
                <div class="form-group">
                  <label for="inputNotification3">Notification 3</label>
                  <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="inputNotification3">
                    <label class="custom-control-label" for="inputNotification3">Receive notification 3</label>
                  </div>
                </div>
                <button type="submit" class="btn btn-primary">Save Changes</button>
              </form>
            </div>
            <div class="tab-pane fade" id="v-pills-security" role="tabpanel" aria-labelledby="v-pills-security-tab">
              <h2 class="mb-4">Security Settings</h2>
              <form>
                <div class="form-group">
                    <label class="mt-2" for="inputCurrentPassword">Current Password</label>
                    <input type="password" class="form-control" id="inputCurrentPassword" placeholder="Enter current password">
                </div>
                <div class="form-group">
                    <label class="mt-2" for="inputNewPassword">New Password</label>
                    <input type="password" class="form-control" id="inputNewPassword" placeholder="Enter new password">
                </div>
                <div class="form-group">
                    <label class="mt-2" for="inputConfirmPassword">Confirm New Password</label>
                    <input type="password" class="form-control" id="inputConfirmPassword" placeholder="Confirm new password">
                </div>
                <button type="submit" class="btn btn-primary mt-2">Update Password</button>
              </form>
            </div>
          </div>
      </div>
    </div>
 

</div>
    <footer>
        
    </footer>

<!-- Bootstrap JavaScript -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>
</html>

