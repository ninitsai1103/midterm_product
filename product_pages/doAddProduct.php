<?php
require_once("../db_connect.php");

if (!isset($_POST["name"])) {
    echo "請循正常管道進入";
    exit;
}

$name = $_POST["name"];
$primaryCategory = number_format($_POST["primaryCategorySelect"]);
$secondaryCategory = number_format($_POST["secondaryCategorySelect"]);
$price = number_format($_POST["price"]);
$amount = number_format($_POST["amount"]);
// $cover = $_POST["cover"];
// $img = $_POST["img"];
$description = $_POST["description"];
$now = date("Y-m-d H:i:s");

// 必填欄位
if (empty($name) || empty($primaryCategory) || empty($secondaryCategory) || empty($price) || empty($amount)) {
    echo "請輸入必要欄位";
    exit;
}

// cover資料夾接照片檔案
if ($_FILES["cover"]["error"] == 0 && $_FILES["img"]["error"] == 0) {
    // 防止上傳相同檔案覆蓋原有檔案
    // 所以使用當下時間來建立檔案名稱
    $filenameCover = time();
    $filenameImg = time();
    // 獲取上傳檔案的副檔名
    $fileExtCover = pathinfo($_FILES["cover"]["name"], PATHINFO_EXTENSION);
    $fileExtImg = pathinfo($_FILES["img"]["name"], PATHINFO_EXTENSION);
    // 將以上兩者合併成檔名與副檔名重新存於filename中
    $filenameCover = "upload" . $filenameCover . "." . $fileExtCover;
    $filenameImg = "upload" . $filenameImg . "." . $fileExtImg;
    // echo $filename;
    // exit;

    // 將檔案移至指定目錄
    if (move_uploaded_file($_FILES["cover"]["tmp_name"], "../product_cover/" . $filenameCover) && move_uploaded_file($_FILES["img"]["tmp_name"], "../product_img/" . $filenameImg)) {

        echo "upload success!";
        // var_dump($_FILES["cover"]["name"]);
        // var_dump($_FILES["img"]["name"]);
    } else {
        echo "upload failed!";
    }
}

// 將新增資料收入sql變數中
$sql = "INSERT INTO product (name, price, amount, description, `change`, category, secondary_category, cover, img, valid) VALUES ('$name', $price, $amount, '$description', '$now', $primaryCategory, $secondaryCategory, '$filenameCover', '$filenameImg',  1)";
// var_dump($sql);
// 是否成功輸入資料表中
if ($conn->query($sql)) {
    echo "新增資料完成";
} else {
    echo "新增資料錯誤: " . $conn->error;
}

// 新增資料是否成功
// if ($conn->query($sql)) {
//     echo "新增資料完成";
// } else {
//     echo "新增資料錯誤: " . $conn->error;
// }

$conn->close();
header("location: ../product_pages/product-list.php");
