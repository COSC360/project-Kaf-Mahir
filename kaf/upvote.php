
<?php 
require "../config/db.php";

if (isset($_POST['post_id'])) {
  echo $_POST['session_username'];
  if (isset($_POST['session_username'])){ //if user logged in

    $post_id = $_POST['post_id'];
    $session_username = $_POST['session_username'];

    $valid_query = "SELECT * FROM posts WHERE post_id = ? AND username = ?";
    $stmt = $conn->prepare($valid_query);
    $stmt->bind_param('is', $post_id, $session_username);
    echo $valid_query;
    $stmt->execute();
    $result = $stmt->get_result();
    $count_votes = $result->num_rows;

    if ($count_votes > 0) {
      echo 'vote already exists';
    } else {
      try {
        $query = "INSERT INTO upvotes (PostID, Username) values(?, ?)";
  
        $statement = $conn ->prepare($query);
        $statement -> bind_param('is', $post_id, $session_username);
        $statement -> execute() or die($conn -> error);
          } catch (PDOException $e) {  // If a user tries to upvote a post twice.
          echo $e -> getCode();
          echo $e->getMessage(); // Output error message to console
  
        } 
    }


    
  }
}




