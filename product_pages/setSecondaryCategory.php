<?php
require_once("../db_connect.php");

// if(!isset($_POST["primaryValue"])){
//     $data=[
//         "status"=>0,
//         "message"=>"請循正常管道進入"
//     ];
//     echo json_encode($data);
//     exit;
// }

$primaryValue = $_POST["primaryValue"];

$sql = "SELECT primary_category.name AS primary_name, secondary_category.id, secondary_category.name AS secondary_name FROM primary_category JOIN secondary_category ON primary_category.id = secondary_category.primary_id";
$result = $conn->query($sql);
$rows = $result->fetch_all(MYSQLI_ASSOC);
// var_dump($rows);


        
?>