<?php
require '../controllers/auth.php';
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
                src="img/propic.jpg" alt="profile photo" 
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
                    <input type="search" name="search" placeholder="Search..." class="form-control me 2">
                    <button class="btn btn-outline-info bi bi-search" type="submit" name="submit-search"></button></button>
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
    <div class="container dashboard">
      <div class="row center">
        <div class="col-md-8 col-md-offset-2">
          <h1>Trending Thoughts</h1>
          <p>Join the conversation and share your thoughts on a variety of topics.</p>
          <hr>
          <button type="button" class="btn btn-dark mt-4 mb-4  " width="100px" height="100px" data-toggle="modal" data-target="#create-post-modal">Create New Post <i class="bi bi-pencil-square"></i></button>
          
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

          <h3>Featured Topics</h3>
          <div class="row">
          <div class="col-lg-12">
          <!-- Load Post Card -->
          <?php 
            $query = "SELECT * FROM posts ORDER BY DateCreated DESC LIMIT 5 ";
            $result = mysqli_query($conn,$query);
            if (mysqli_num_rows($result) > 0 ) {
              while ($row = mysqli_fetch_assoc($result)) {
                echo "
                        <div class='card mb-3'>
                          <div class='card-body'>
                            <h5 class='card-title'>" . $row['Title'] . "</h5>
                            <p class='card-text'>" . $row['Body'] . "</p>
                            <p class='card-text'><small class='text-muted'> Posted by " . $row['AuthorUsername'] . " on " . $row['DateCreated'] . "</small></p>
                          <div class='dropdown'>
                          <!-- Button trigger popover -->
                          <button type='button' class='btn btn-light' title='Likes'><i class='bi bi-hand-thumbs-up-fill'></i></button>
                          <button type='button' class='btn btn-light' data-toggle='popover' data-placement='bottom' title='Comments'><i class='bi bi-chat-left-dots-fill'></i></button>
                          <button type='button' class='btn btn-light' title='Share'><i class='bi bi-share-fill'></i></button>
                          
                          <!-- Popover content -->
                          <div id='comments-popover-content' class='d-none'>
                            <ul>
                              <li>Comment 1</li>
                              <li>Comment 2</li>
                              <li>Comment 3</li>
                            </ul>
                          </div>

                          <!-- Initialize popover -->
                        </div>
                      </div>
                    </div>
                    ";
              }
            }
          ?>

    <button type="button" class="btn btn-info fixed-bottom" name='morePosts' id='morePosts'>More Posts</button>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  </body>
</html>
