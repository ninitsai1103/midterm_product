<?php
require_once("../db_connect.php");

if(!isset($_POST["secondaryCategorySelect"])){
    $data=[
        "status"=>0,
        "message"=>"請循正常管道進入"
    ];
    echo json_encode($data);
}

$primaryCategorySelect = $_POST["primaryCategorySelect"];
$secondaryCategorySelect = $_POST["secondaryCategorySelect"];

$sqlPri = "SELECT id FROM primary_category";
$resultPri = $conn->query($sqlPri);


$sqlSec = "SELECT id, primary_id FROM secondary_category";
$resultSec = $conn->query($sqlSec);
?>