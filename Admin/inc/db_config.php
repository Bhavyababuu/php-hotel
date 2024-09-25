<?php

$hname = 'localhost';
$uname = 'root';
$pass = '';
$db = 'metroempire';

// Create connection
$con = mysqli_connect($hname, $uname, $pass, $db);

// Check connection
if ($con) {
    echo "Connection successful!";
} else {
    echo "Connection failed: " . mysqli_connect_error();
}

function filteration($data) {
    foreach ($data as $key => $value) {
        $data[$key] = trim($value);
        $data[$key] = stripslashes($value); // Corrected function name from "tripslashes" to "stripslashes"
        $data[$key] = htmlspecialchars($value);
        $data[$key] = strip_tags($value); // Removed the misplaced closing parenthesis
    }
    return $data;
}

function select($sql, $values, $datatypes) {
    $con = $GLOBALS['con'];
    if ($stmt = mysqli_prepare($con, $sql)) {
        mysqli_stmt_bind_param($stmt, $datatypes, ...$values);
        if (mysqli_stmt_execute($stmt)) {
            $res = mysqli_stmt_get_result($stmt);
            mysqli_stmt_close($stmt);
            return $res;
        } else {
            mysqli_stmt_close($stmt);
            die("Query cannot be executed - select");
        }
    } else {
        die("Query cannot be prepared - select");
    }
}

?>
