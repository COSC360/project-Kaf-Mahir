<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require '../controllers/auth.php';
require 'load-profile.php';
    
$isMyProfile = false;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/profile.css">
    <title>Profile</title>
</head>
<body >
<nav class="navbar fixed-top navbar-expand-md navbar-light" style="background-color: rgba(255,255,255, 0.85)">
        <div class="container">
          <?php 
              if ($_SESSION['username'] == $username) {
                $isMyProfile = true;
              } else $isMyProfile == false;
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
                aria-label="Toggle navigation">
                
            <i class="bi bi-caret-down arrowDown"></i>
            </button>
            <div 
                class="collapse navbar-collapse "  
                id="navbarNav">
                <form action="results.php" class="d-flex ms-auto" method="POST">
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

                     
                </ul>

            </div>
        </div>
    </nav>

    <div class="container">
  
              <?php //only show if logged in. For now.
                if (isset($_SESSION['username'])) { //if user logged in
                echo "<button type='button' class='btn btn-dark fixed-bottom p-3' width='100px' height='100px' data-toggle='modal' data-target='#create-post-modal'>Create New Post <i class='bi bi-pencil-square'></i></button>";
                } else {
                  //if replacing
                }
              ?>
    </div>
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
        <form method='POST' action='profile.php' name='postForm' id='postForm'>
          <div class="form-group">
            <label for="post-title">Title</label>
            <input type="text" class="form-control" id="postTitle"name='postTitle'placeholder="Enter post title" required>
          </div>
          <div class="form-group">
            <label for="post-content">Content</label>
            <textarea class="form-control" id="postContent" rows="3" name='postContent' placeholder="Enter post content" required></textarea>
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
    <div class="container myBlogs mb-5">
  <div class="row">
    <div class="col-lg-4">
      <?php 
      if (!$isMyProfile) {
        $user_pic = "img/" . $username . ".jpg";
        $default_pic = "img/default2.jpg";
          if (file_exists($user_pic)) {
              $profile_pic = $user_pic;
          } else {
              $profile_pic = $default_pic;
          }
      }
      ?>
    <div class="profile-banner" style="background-image: url('<?php echo $profile_pic; ?>');">
        <h2><?php echo $username;?></h2>
        <h4><?php echo $full_name;?></h4>
     </div>
    </div>
    <div class="col-lg-6">

    <a class="btn btn-info" href="setting.php" role="button">Profile Settings</a>

        <?php
        $msg = $_GET['msg'] ?? '';
        if (!empty($msg)) {
          $alert_class = 'alert-success'; // Bootstrap class for warning alert
          $message = htmlspecialchars($msg);
          echo "<div class='alert $alert_class'>$message</div>";
        }
        ?>
      <div class="account">
        
        <?php 

        if ($isMyProfile) {
        echo "<h2>My Posts</h2>";
        } else {
          echo "<h2>" .$username. "'s Posts</h2>";
        }?>
        <div class="bio card-body rounded-2">
            <p> <?php echo $bio; ?></p>
        </div>
      </div>
      <div class="card mb-3">
      <?php 
    
    
    //Generate Posts
    $query = "SELECT * FROM posts WHERE AuthorUsername like '%$username%' ORDER BY DateCreated DESC";

    $result = mysqli_query($conn,$query);
    if (mysqli_num_rows($result) > 0 ) {
      while ($row = mysqli_fetch_assoc($result)) {
        // $result_exist = mysqli_query("SELECT * FROM upvotes WHERE postID =$row['PostID'] AND Username =$_SESSION['username']");
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
        <div class='dropdown m-3 mb-4 ms-auto'>
              <!-- Button trigger popover -->
              <button type='button' " . (isset($_SESSION['username']) ? "data-post-username='" . $_SESSION['username'] . "' " : "") . "data-post-id='" . $row['PostID'] . " 'class='btn upvote-btn btn-light' name='upvote-btn' title='Likes'><i class='bi bi-arrow-up-square" . ($UpvotedByCurrentUser ? '-fill liked' : ' unliked') . "'></i> <span id='upvotes-" . $row['PostID'] . "' class='counter-" . $row['PostID'] . "'> ". $row['upvotes'] . "</span></button>
              <button type='button' class='btn btn-light' data-toggle='popover' data-placement='bottom' title='Comments'><i class='bi bi-chat-left-dots-fill'></i></button>
              <button type='button' class='btn btn-light' title='Share'><i class='bi bi-share-fill'></i></button>
        </div>
        ";
      }
    }
    ?>
    </div>
  </div>
</div>

  
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