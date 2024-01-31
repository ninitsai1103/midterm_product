<?php

require_once("../db_connect.php");

if (!isset($_POST["name"])) {
    die("請循正常管道進去");
} 

$name = $_POST["name"];
$primaryCategory = number_format($_POST["primaryCategory"]);
$secondaryCategory = number_format($_POST["secondaryCategory"]);
$price = number_format($_POST["price"]);
$amount = number_format($_POST["amount"]);
// $cover = $_POST["cover"];
// $img = $_POST["img"];
$description = $_POST["description"];
$now = date("Y-m-d H:i:s");

$sql="UPDATE product SET name='$name', category=$primaryCategory, secondary_category=$secondaryCategory, price=$price, amount=$amount, desciption='$description', update=$now WHERE id=$id";

if($conn->query($sql) === TRUE){
    echo "更新成功";
}else{
    echo "更新資料錯誤" .$conn->error;
}

$conn->close();

//修改完成,自動回到 user-edit.php?id=$id" 頁面 (閃一下)
header("location: user-edit.php?id=$id");