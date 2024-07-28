<?php
function checkAuthentication($location){
    if(!isset( $_SESSION['user_id'])){
        $_SESSION['errorMessage'] = "Please login to access this page";
        header('Location: '.$location);
    }
}
function isAdmin($location){
    if(!isset($_SESSION['role']) && $_SESSION['role'] == "admin"){
        $_SESSION['errorMessage'] = "Please login to access this page";
        header('Location: '.$location);
    }
}
?>