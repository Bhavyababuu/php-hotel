<?php
require('../inc/db_config.php');
require('../inc/essentials.php');

adminLogin();

if (isset($_POST['get_general'])) { // Corrected the method case
    $q = "SELECT * FROM `settings` WHERE `s_no`=?";
    $values = [1];
    $res = select($q, $values, "i");
    $data = mysqli_fetch_assoc($res);
    $json_data = json_encode($data);
    echo $json_data;
}
?>
