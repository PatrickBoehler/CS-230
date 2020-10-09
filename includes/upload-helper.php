<?php
session_start();

define('KB', 1024);
define('MB', 1048576);

if (isset($_POST['prof-submit'])) {
    require 'dbhandler.php';

    $uname = $_SESSION['username'];

    $file = $_FILES['prof-image'];
    $filename = $file['name'];
    $filename = $file['tmp_name'];
    $filename = $file['error'];
    $filename = $file['size'];

    $bio = $_POST['bio'];

    $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    $allowed = array('jpg', 'jpeg', 'png', 'svg');

    if ($file_error !==0 ){
        header("Location: ../profile.php?error=UploadError");
        exit();
    }if (!in_array($ext, $allowed)) {
        header("Location: ../profile.php?errorInvalidType");
        exit();
    }
    if ($file_size > 4*MB){
        header("Location: ../profile.php?errorFileSizeExceeded");
    }
    else{
        $new_name = uniqid('', true).".".$ext;
        $destination = '../uploads/'.$new_name;

        $sql = "UPDATE profile SET picpath=?, bio=? WHERE uname =?";
        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sql)){
                header("Location: ../profile.php?error=SQLInjection");
                exit();
        }else{
            mysqli_stmt_bind_param($stmt, "sss", $destination, $fname, $email, $username, $hashedPass);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            move_uploaded_file($file_tmp_name, $destination);
            header("Location: ../profile.php?success=UploadSuccess");
        }
    }


   

}