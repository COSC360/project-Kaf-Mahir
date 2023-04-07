<?php 
//return profile info from database
if (isset($_GET['username'])) {
  $username = mysqli_real_escape_string($conn, $_GET['username']);
}
else if(isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
}

if (!empty($username)) {
    $query = "SELECT * FROM profiles WHERE username = '$username'";
    $result = mysqli_query($conn,$query);
    $row = mysqli_fetch_array($result);
    
    if (!empty($row)) {
        $full_name = $row['full_name'];
        $bio = $row['bio'];
    } else {
        // $msg = "User does not have a profile or doesn't exist";
        // header("Location: home.php?msg=" . urlencode($msg));
        // exit;
        $full_name = "Full Name";
        $bio = "Bio";
    }

  }
//pic
$profile_pic;
$user_pic = "img/" .$_SESSION['username'] . ".jpg";
$default_pic = "img/default2.jpg";

if (file_exists($user_pic)) {
    $profile_pic = $user_pic;
} else {
    $profile_pic = $default_pic;
}

if (isset($_FILES['inputPic']['tmp_name'])) {
  $temp_file = $_FILES['inputPic']['tmp_name'];
  $file_name = $_SESSION['username']. ".jpg";
  $file_path = "img/" .$file_name;
  if (move_uploaded_file($temp_file, $file_path)) {
    echo "image moved successfully";
    $msg = "Profile successfully updated";
    header("Location: profile.php?msg=" . urlencode($msg));
    exit;
  } else {
    echo "image not moved";
  }

}

//validate extension
function validateFile() {
isset($_FILES['inputPic']['name']) ? $inputPic = $_FILES['inputPic']['name'] : $inputPic = null;
if ($inputPic != null && isValidFile($inputPic)) {
  return true;
} else if ($inputPic == null) {
  return true; 
} else return false;
}


function isValidFile($file) {
  $part = explode('.', $file);
  $file_ext = end($part); //set equal to .jpg for exmaple

  switch (strtolower($file_ext)) {
    case 'jpg':
    case 'jpeg':
    case 'png':
    case 'gif':
     
    return true;
  }
    return false;
}
    