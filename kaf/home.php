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
    <link rel="stylesheet" href="css/home.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <title>Home</title>
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
    <div class="container dashboard">
    <div class="row center">
      <div class="col-md-8 col-md-offset-2">
        <?php 
          $msg = $_GET['msg'] ?? '';
          if (!empty($msg)) {
            $alert_class = 'alert-warning'; // Bootstrap class for warning alert
            $message = htmlspecialchars($msg);
            echo "<div class='alert $alert_class'>$message</div>";
          }
        ?>
        <h1>Trending Thoughts</h1>
        <p>Join the conversation and share your thoughts on a variety of topics.</p>
        <hr>

<!-- Button trigger modal -->

  <?php //only show if logged in. For now.
    if (isset($_SESSION['username'])) { //if user logged in
    echo "<button type='button' class='btn btn-dark p-2 mt-2 mb-2' width='100px' height='100px' data-toggle='modal' data-target='#create-post-modal'>Create New Post <i class='bi bi-pencil-square'></i></button>";
    } else {
      //if replacing
    }
  ?>
<!-- Modal -->
<div class="modal fade" id="create-post-modal" tabindex="-1" role="dialog" aria-labelledby="create-post-modal-title" aria-hidden="true">
  <div style="margin-top: 150px" class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="create-post-modal-title">Create New Post</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method='POST' action='home.php' name='postForm' id='postForm'>
          <div class="form-group m-1">
            <label for="post-title">Title</label>
            <input type="text" class="form-control" id="postTitle"name='postTitle'placeholder="Enter post title" required>
          </div>
          <div class="form-group m-1">
            <label for="post-content">Content</label>
            <textarea class="form-control" id="postContent" rows="3" name='postContent' placeholder="Enter post content" required></textarea>
          </div>
          <div class="form-group m-1">
            <label for="post-category">Category</label>
            <select name="category">
              <option value="land_animals">Land Animals</option>
              <option value="bird_watching">Bird Watching</option>
              <option value="marine_animals">Marine Animals</option>
              <option value="misc">Misc</option>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button form='postForm' type="submit" name='create-post-btn' class="btn btn-primary">Create Post</button>
      </div>
    </div>
  </div>
</div>

<h3>Featured Topics</h3>
<div class="row">
  <div class="col-lg-12">
  <!-- Load Post Card -->
    <?php 
    if (isset($_SESSION['username'])) {
    $username = mysqli_real_escape_string($conn, $_SESSION['username']);
    }

    $query = "SELECT * FROM posts ORDER BY DateCreated DESC LIMIT 15";

    $result = mysqli_query($conn,$query);
    if (mysqli_num_rows($result) > 0 ) {
      while ($row = mysqli_fetch_assoc($result)) {
        $postID = mysqli_real_escape_string($conn, $row['PostID']);
        $UpvotedByCurrentUser = false; //Default is false

        $query = "SELECT * FROM upvotes WHERE postID = $postID AND Username = '$username'";
        $result_exist = mysqli_query($conn, $query);
        if (mysqli_num_rows($result_exist) > 0) {
          $UpvotedByCurrentUser = true;
        }
        echo "
        <a href='post.php?postID=" . $row['PostID'] . "' style='text-decoration: none; color: black;' class='card'>
        <div class='card-body'>
          <h5 class='card-title'>" . $row['Title'] . "</h5>
          <p class='card-text'>" . $row['Body'] . "</p>
          <p class='card-text'><small class='text-muted'> Posted by " . $row['AuthorUsername'] . " on " . $row['DateCreated'] . "</small></p>
        </div>
      </a>
      <div class='dropdown m-3 mb-4' style='display: flex; justify-content: flex-end;'>
        <!-- Button trigger popover -->
        <button type='button' " . (isset($_SESSION['username']) ? "data-post-username='" . $_SESSION['username'] . "' " : "") . "data-post-id='" . $row['PostID'] . " 'class='btn upvote-btn btn-light' name='upvote-btn' title='Likes'>
          <i class='bi bi-arrow-up-square" . ($UpvotedByCurrentUser ? '-fill liked' : ' unliked') . "'></i>
          <span id='upvotes-" . $row['PostID'] . "' class='counter-" . $row['PostID'] . "'> ". $row['upvotes'] . "</span>
        </button>
        <a href='post.php?postID=" . $row['PostID'] . "' class='btn btn-light' data-toggle='popover' data-placement='bottom' title='Comments'>
          <i class='bi bi-chat-left-dots-fill'></i>
        </a>

      ";

      if(isset($_SESSION['username'])) {
        if($_SESSION['username']== 'admin')  {

         echo " <button type='button' class='btn btn-light' title='edit' data-toggle='modal' data-target='#editModal'>
          <i class='bi bi-pencil-square'></i>
        </button>
      </div>

      <!-- Modal -->
      <div class='modal fade' id='editModal' tabindex='-1' role='dialog' aria-labelledby='editModalLabel' aria-hidden='true'>
        <div class='modal-dialog' role='document'>
          <div class='modal-content'>
            <div class='modal-header'>
              <h5 class='modal-title' id='editModalLabel'>Edit Post</h5>
              <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                <span aria-hidden='true'>&times;</span>
              </button>
            </div>
            <div class='modal-body'>
              <form action='edit_post.php' method='post'>
                <div class='form-group'>
                  <label for='postBody'>Edit Post:</label>
                  <textarea class='form-control' id='postBody' name='postBody' rows='5'></textarea>
                </div>
              </form>
            </div>
            <div class='modal-footer'>
              <button type='button' class='btn btn-secondary' data-dismiss='modal'>Cancel</button>
              <button type='submit' class='btn btn-primary'>Submit</button>
            </div>
          </div>
        </div>
      </div>";
      } else {
        echo "</div>";
      }
    } else {
      echo "</div>";

    }
  }
    }
    ?>

  <!-- <button type="button" class="btn btn-info fixed-top" name='morePosts' id='morePosts'>More Posts</button> -->


  
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
    <!-- <script>
    //JQuery
    $(document).ready(function() {
      var postCount = 5;
      $("button").click(function() {
          postCount += 3;
          $("#morePosts").load("load-posts.php", {
              newPostCount: postCount
          });
      });
    });
    </script> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  </body>
</html>
