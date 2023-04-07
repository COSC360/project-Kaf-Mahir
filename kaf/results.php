<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require '../controllers/auth.php';
require 'load-profile.php';

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/setting.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <title>Results</title>
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
                      <a href="login.php?logout" class="nav-link"><h4><i class='bi bi-box-arrow-right'></i></h4></a>
                  </li>
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
                        if (isset($_SESSION['username'])) {
                          echo "<a href='./home.php?logout' class='nav-link logout-link'><h4><i class='bi bi-x-square'></i></h4></a>";
                        } else {
                          //what to show when user is not logged in instead of settings. if anything
                        }                    
                      ?>
                  </li>
                </ul>
            </div>
        </div>
    </nav>
  <div class="container dashboard" style="margin-top: 150px">
  <div class="row ">
    <div class="col-md-8 col-md-offset-2">
      <h1>Search Results: </h1>
      <hr>
      <?php
          if(isset($_POST['submit-search'])) {
            $search = mysqli_real_escape_string($conn, $_POST['search']);
            $sql = "SELECT * FROM posts WHERE CONCAT(AuthorUsername, Body, Title) LIKE '%$search%'";
            $result = mysqli_query($conn, $sql);
            $queryResult = mysqli_num_rows($result);
           
            $UpvotedByCurrentUser = false; //Default is false
            $result_exist = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result_exist) > 0) {
              $UpvotedByCurrentUser = true;
            }

            if ($queryResult > 0) {
              while($row = mysqli_fetch_assoc($result)) {
                echo "
                    <a href='post.php?postID=" . $row['PostID'] . "' style='text-decoration: none; color: black;' class='card'>
                      <div class='card-body'>
                          <h5 class='card-title'>" . $row['Title'] . "</h5>
                          <p class='card-text'>" . $row['Body'] . "</p>
                          <p class='card-text'><small class='text-muted'> Posted by " . $row['AuthorUsername'] . " on " . $row['DateCreated'] . "</small></p>
                      </div>
                    </a>
                      <div>
                          <div class='dropdown m-3 mb-4 ms-auto'>
                            <!-- Button trigger popover -->
                            <button type='button' " . (isset($_SESSION['username']) ? "data-post-username='" . $_SESSION['username'] . "' " : "") . "data-post-id='" . $row['PostID'] . " 'class='btn upvote-btn btn-light' name='upvote-btn' title='Likes'><i class='bi bi-arrow-up-square" . ($UpvotedByCurrentUser ? '-fill liked' : ' unliked') . "'></i> <span id='upvotes-" . $row['PostID'] . "' class='counter-" . $row['PostID'] . "'> ". $row['upvotes'] . "</span></button>
                            <button type='button' class='btn btn-light' data-toggle='popover' data-placement='bottom' title='Comments'><i class='bi bi-chat-left-dots-fill'></i></button>
                            <button type='button' class='btn btn-light' title='Share'><i class='bi bi-share-fill'></i></button>
                          </div>
                        </div>
                     
                ";
              }
            } else {
              echo 'There are no results';
            }
          }
        ?>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>

  <script>
    $(document).ready(function() {
      $('.upvote-btn').click(function() {
        var $icon = $(this).find('i');  //find i element within upvote-btn
        $icon.toggleClass('bi-arrow-up-square bi-arrow-up-square-fill'); //toggle between 2 classes
        var post_id = $(this).data('post-id');
        var session_username = $(this).data('post-username');
        // var $counter = $(this).find('.counter');
        var $upvoteCount = $('#upvotes-' + post_id);
        
        $.ajax({
          url: 'home.php',
          type: 'POST',
          data: {
            post_id: post_id,
            session_username: session_username,
            liked: 1
          },
          success: function(response) {
            
            

            if (response.trim() == 'login'){
              window.location.href = "login.php?msg=" + encodeURIComponent("You must be logged in to upvote.");
            } else {
              console.log(response);
              console.log(session_username);
              var data = JSON.parse(response);
              $("#upvotes-" + post_id).text(data.upvotes);
              console.log('Upvote added');        }
          },
          error: function(xhr, status, error) {
            console.error(error);
          }
        });
      });
    });

  </script>

  <script>
      $(document).ready(function() {
      $('.logout-link').click(function(event) { 
        $.get($(this).attr('href'), function(response) {
          location.reload(); // reload the page after the server responds
        });
      });
    });
  </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  </body>
</html>














