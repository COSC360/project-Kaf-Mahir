<?php 
require '../config/db.php';
  $newPostCount = $_POST['newPostCount']; //from the jquery
    $query = "SELECT * FROM posts ORDER BY DateCreated DESC LIMIT $newPostCoun ";
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