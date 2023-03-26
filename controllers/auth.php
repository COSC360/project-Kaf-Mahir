<?php
session_start();
require '../config/db.php';

// Initialize variables
$errors = array();
$username = '';
$email = '';
$password_conf = '';


// Check if the sign-up form has been submitted
if (isset($_POST["signup-btn"])) {
  
  // Get user inputs from form
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $password_conf = $_POST['password_conf'];

  // Validate user inputs
  if (empty($username)) {
    $errors['username'] = 'Username Required';
  }
  if (empty($email)) {
    $errors['email'] = 'Email Required';
  }
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Invalid Email Format';
  }
  if (empty($password)) {
    $errors['password'] = 'Password Required';
  }
  if ($password !== $password_conf) {
    $errors['password_conf'] = 'Passwords do not match';
  }

  // Check if email already exists in database
  $query_email = "SELECT * FROM users WHERE email=? LIMIT 1";
  $statement = $conn -> prepare($query_email);
  $statement -> bind_param('s', $email); //s = string
  $statement -> execute();
  $result = $statement->get_result();
  $count_emails = $result -> num_rows;
  if ($count_emails > 0) {
     $errors['email'] = 'Email Already Exists. Please Log In';
  }
  $statement -> close();

  // Check if username already exists in database
  $query_username = "SELECT * FROM users WHERE username=? LIMIT 1";
  $statement = $conn -> prepare($query_username);
  $statement -> bind_param('s', $username); //s = string
  $statement -> execute();
  $result = $statement->get_result();
  $count_usernames = $result -> num_rows;
  if ($count_usernames > 0) {
     $errors['username'] = 'Username Already Exists. Please Choose Another One';
  }
  $statement -> close();

  // If there are no errors, hash password and create new user in database
  if(count($errors) === 0) {
    $password = password_hash($password, PASSWORD_DEFAULT);
    $token = bin2hex(random_bytes(50));
    $verified = false;

    $query = "INSERT INTO users (email, username, verified, token, password) values (?, ?, ?, ?, ?)";
    $statement = $conn -> prepare($query);
    $statement -> bind_param('ssiss', $email, $username, $verified, $token, $password); //b = boolean

    // If user is successfully created, log them in and redirect to homepage
    if ($statement -> execute()) {
      $_SESSION['email'] = $email;
      $_SESSION['username'] = $username;
      $_SESSION['verified'] = $verified;
      $_SESSION['msg'] = 'You are now logged in';
      $_SESSION['alert-class'] = 'p-lg-5 text-success-emphasis bg-success-subtle border border-success-subtle rounded-3';
      header("Location: index.php");
      exit();
    } else {
      $errors['db'] = 'Registration Failed'; 
    }
    $statement -> close();
  }
}

// If there are errors, display them to user
if(count($errors) > 0) {
  foreach($errors as $error) {
    echo $error . '<br>';
  }
}




// login validation
if (isset($_POST["login-btn"])) {
  // Get user inputs from form
  $username = $_POST['username'];
  $password = $_POST['password'];
  
  // Validate user inputs
  if (empty($username)) {
    $errors['username'] = 'Username/Email Required';
  }
  
  if (empty($password)) {
    $errors['password'] = 'Password Required';
  }

  if(count($errors) > 0) {
    foreach($errors as $error) {
      echo $error. '<br>';
    }
  } 
  
  if (count($errors) === 0) {
  //Check if username or email already exist in database
  $query = "SELECT * FROM users WHERE email=? OR username=? LIMIT 1";
  $statement = $conn -> prepare($query); 
  $statement -> bind_param('ss', $username, $username);
  $statement -> execute();
  $result = $statement->get_result();
  $user = $result -> fetch_assoc(); 
  
  if (empty($user)) {
    $errors['username'] = 'Username/Email Not Found';
  }
   else if(password_verify($password, $user['password'])) {
      //login user
      $_SESSION['email'] = $user['email'];
      $_SESSION['username'] = $user['username'];
      // $_SESSION['verified'] = $user['verified'];
      $_SESSION['msg'] = 'You are now logged in';
      $_SESSION['alert-class'] = 'p-lg-5 text-success-emphasis bg-success-subtle border border-success-subtle rounded-3';
      header("Location: home.php");
      exit();
} else {
  $errors['wrong'] = 'Wrong Email/Username or Password';
}
  $statement -> close();
}
}


// print errors and number of errors to user
if(count($errors) > 0) {
  foreach($errors as $error) {
    echo $error. '<br>';
  }
} 


//logout
  if (isset($_GET["logout"])) {
    if (isset($_SESSION['username'])) {
    session_unset();  //unset all session variables
    session_destroy();
    exit();
    // unset($_SESSION['verified']);
    }
  }



  

//create post
if (isset($_POST["create-post-btn"])) {
  
  // Get user inputs from form
  $username = $_SESSION['username'];
  $postTitle = $_POST['postTitle'];
  $postContent = $_POST['postContent'];

  // Validate user inputs
  if (empty($postTitle)) {
    $errors['postTitle'] = 'Post Title Required';
  }
  if (empty($postContent)) {
    $errors['postContent'] = 'Post Content Required';
  }
  if (count($errors) == 0) {
    $query = "INSERT INTO posts (AuthorUsername, Title, Body) VALUES (?,?,?)";
    $statement = $conn -> prepare($query);
    $statement -> bind_param('sss', $username, $postTitle, $postContent);
    $statement -> execute();
    $statement -> close();
  }



//login function
function loginUser($conn, $username, $password) {
  $userExists = userExists($conn, $username, $username);

  if ($userExists === false) { 
    $errors['username'] = 'Username/Email Not Found';
    header("Location: login.php");
    exit();
}

$hashed_password = $userExists['password'];
if (password_verify($password, $hashed_password) === false) {
  $errors['wrong'] = 'Wrong Email/Username or Password';
    header("Location: login.php");
    exit();
  } else if (count($errors) === 0) {
    session_start();
    $_SESSION['email'] = $userExists['email'];
    $_SESSION['username'] = $username;
    $_SESSION['msg'] = 'You are now logged in';
    $_SESSION['alert-class'] = 'p-lg-5 text-success-emphasis bg-success-subtle border border-success-subtle rounded-3';
    header("Location: home.php");
    exit();
  }

}
function userExists($conn, $username, $email) {
  $query = "SELECT * FROM users WHERE username=? AND email=? LIMIT 1";
  $statement = $conn -> prepare($query);
  $statement -> bind_param('ss', $username, $email);
  if ($statement -> execute()) {
    $result = $statement->get_result();
    if ($user = $result -> fetch_assoc()) {
      return $user;
    } else {
      return false;
    }
    $statement -> close();
  }
  
 
}

  
}


?>