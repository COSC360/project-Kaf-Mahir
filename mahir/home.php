<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/home.css">
    <title>Home</title>
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
                <form action="" class="d-flex ms-auto">
                    <input type="search" placeholder="Search..." class="form-control me 2">
                    <button class="btn btn-outline-info" type="send">
                    <i class="bi bi-search"></i>
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
                        <a href="./setting.php" class="nav-link"><h4><i class="bi bi-gear"></h4></i></a>
                    </li>
                </ul>

            </div>
        </div>
    </nav>

    <div class="container dashboard">
    <div class="row center">
      <div class="col-md-8 col-md-offset-2">
        <h1>Welcome to the Discussion Forum</h1>
        <p>Join the conversation and share your thoughts on a variety of topics.</p>
        <hr>
        <h3>Featured Topics</h3>
        <div class="row">
        <div class="col-lg-12">
          <div class="card mb-3">
            <div class="card-body">
              <h5 class="card-title">Discussion Title</h5>
              <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed euismod justo vel lacus suscipit, in bibendum lectus posuere. Proin feugiat, libero auctor pharetra tristique, enim justo hendrerit mauris, vel maximus sapien purus vel justo.</p>
              <p class="card-text"><small class="text-muted">Posted by User1 on March 22, 2023</small></p>
              <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  View Comments
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                  <a class="dropdown-item" href="#">Comment 1</a>
                  <a class="dropdown-item" href="#">Comment 2</a>
                  <a class="dropdown-item" href="#">Comment 3</a>
                </div>
              </div>
            </div>
          </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Discussion Title</h5>
                            <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed euismod justo vel lacus suscipit, in bibendum lectus posuere. Proin feugiat, libero auctor pharetra tristique, enim justo hendrerit mauris, vel maximus sapien purus vel justo.</p>
                            <p class="card-text"><small class="text-muted">Posted by User2 on March 21, 2023</small></p>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Discussion Title</h5>
                            <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed euismod justo vel lacus suscipit, in bibendum lectus posuere. Proin feugiat, libero auctor pharetra tristique, enim justo hendrerit mauris, vel maximus sapien purus vel justo.</p>
                            <p class="card-text"><small class="text-muted">Posted by User3 on March 20, 2023</small></p>
                        </div>
                    </div>
                </div>
            </div>
      </div>
    </div>
  </div>
</body>
</html>