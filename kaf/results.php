<?php
require '../config/db.php';
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/home.css">
    <title>Results</title>
</head>
<body>
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
                    <button class="btn btn-outline-info bi bi-search" type="submit" name="submit-search"></button>
                </form>


                <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item  ">
                        <a href="./home.php" class="nav-link active "><h4><i class="bi bi-house"></i></h4></a>
                    </li>    
                <li class="nav-item  ">
                        <a href="login.php?logout=1" class="nav-link  "><h4><i class="bi bi-box-arrow-left"></i></h4></a>


                    </li>
                    <li class="nav-item ">
                        <a href="./setting.php" class="nav-link"><h4><i class="bi bi-gear"></i></h4></a>
                    </li>
                </ul>


            </div>
        </div>
    </nav>


    <div class="container dashboard">
    <div class="row center">
      <div class="col-md-8 col-md-offset-2">
        <h1>Search Results: </h1>
        <hr>
        <?php
            if(isset($_POST['submit-search'])) {
              $search = mysqli_real_escape_string($conn, $_POST['search']);
              $sql = "SELECT * FROM posts WHERE CONCAT(AuthorUsername, Body, Title) LIKE '%$search%'";
              $result = mysqli_query($conn, $sql);
              $queryResult = mysqli_num_rows($result);


              if ($queryResult > 0) {
                while($row = mysqli_fetch_assoc($result)) {
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
              } else {
                echo 'There are no results';
              }
            }
          ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  </body>
</html>
