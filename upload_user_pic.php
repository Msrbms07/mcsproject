<?php
include_once ('base/functions.php');
if($_FILES["file"]["name"] != '')
{
$conn = create_conn();
 $test = explode('.', $_FILES["file"]["name"]);
 $ext = end($test);
 $name = rand(100, 999) . '.' . $ext;
 $location = './images/uploads/' . $name;  
 move_uploaded_file($_FILES["file"]["tmp_name"], $location);
 $sql  = "UPDATE user_m SET user_image = '" . $location . "' WHERE user_id = {$_SESSION['USER_ID_VERIFICATION']}";
 $result = mysqli_query($conn, $sql);
 if($result == true)
 {
    echo "User Image Uploaded";
 }
}
?>