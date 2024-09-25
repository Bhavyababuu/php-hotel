<?php

function AdminLogin(){
    session_start();
    if (!(isset($_SESSION['adminLogin']) && $_SESSION['adminLogin'] == true)) {
        echo "<script>window.location.href='index.php';</script>";
        exit; // Stop further script execution
    }
    session_regenerate_id(true); // Start the session here

}

function redirect($url){
    echo "<script>window.location.href='$url';</script>";
    exit; // Stop further script execution
}

function alert($type, $msg){
    $bs_class = ($type == "success") ? "alert-success" : "alert-danger";
    echo <<<alert
    <div class="alert $bs_class alert-dismissible fade show custom-alert" role="alert">
        <strong class="me-3">$msg</strong> 
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
alert;
}
?>
