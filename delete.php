<?php
require "./configuration.php";

$deleteId = $_GET["deleteId"];
$deleteSql = "DELETE FROM users WHERE ID = $deleteId";
$deleteResult = mysqli_query($connection, $deleteSql);

if ($deleteResult) {
    echo "<script>alert('Deleted Successfully!');
    window.location.href='./display.php';
    </script>";
} else {
    echo "400 Error, Bad Request!";
}
