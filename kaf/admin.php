<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require '../controllers/auth.php';


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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Page</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="css/admin.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
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
      <th>Attribute 1</th>
      <th>Attribute 2</th>
      <th>Attribute 3</th>
      <th>Attribute 4</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>john_doe</td>
      <td>Value 1</td>
      <td>Value 2</td>
      <td>Value 3</td>
      <td>Value 4</td>
    </tr>
    <tr>
      <td>jane_smith</td>
      <td>Value 1</td>
      <td>Value 2</td>
      <td>Value 3</td>
      <td>Value 4</td>
    </tr>
    <tr>
      <td>robert_johnson</td>
      <td>Value 1</td>
      <td>Value 2</td>
      <td>Value 3</td>
      <td>Value 4</td>
    </tr>
  </tbody>
</table>

  
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
