<?php


if (isset($_POST['save-changes-btn'])) {
 $name = $_POST['inputName'];
 $username = $_POST['inputUsername'];
 $email = $_POST['inputEmail'];
 $city = $_POST['inputCity'];
 $website = $_POST['inputWebsite'];

 if($_FILES['inputPic']['error'] === 4) {
  echo "<div class='alert alert-warning' role='alert'>Image Does Not Exist.</div>";
 } else {
  $filename = $_FILES['inputPic']['name'];
  $fileerror = $_FILES['inputPic']['error'];
  $tmpname = $_FILES['inputPic']['tmp_name'];
  $filesize = $_FILES['inputPic']['size'];
  $validImageExt = ['jpg', 'jpeg', 'png', 'gif'];
  $imageExtension = explode(".", $filename);
  $imageExtension = strtolower(end($imageExtension));
 
  if (!in_array($imageExtension, $validImageExt)) {
    echo '<div class="alert alert-warning" role="alert">Invalid File Type.</div>';
  } else if ($filesize > 5000000) {
    echo '<div class="alert alert-warning" role="alert">Image is too large.</div>';
  } else { 
    $newImageName = uniqid();
    $newImageName .= ".". $imageExtension;
    echo $fileerror;
    move_uploaded_file($tmpname, 'project-Kaf-Mahir/mahir/img/' . $newImageName);
 }

}
}

?>