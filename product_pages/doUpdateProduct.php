<?php

require_once("../db_connect.php");

if (!isset($_POST["name"])) {
    die("請循正常管道進去");
}

$name = $_POST["name"];
$primaryCategory = $_POST["primaryCategorySelect"];
$secondaryCategory = $_POST["secondaryCategorySelect"];
$price = $_POST["price"];
$amount = $_POST["amount"];
// $cover = $_POST["cover"];
// $img = $_POST["img"];
$description = $_POST["description"];
$now = date("Y-m-d H:i:s");

// 更新圖片
$product_id = $_POST["product_id"];
$sql = "SELECT * FROM product WHERE id=" . $product_id;
$result= $conn->query($sql);
$row = $result->fetch_assoc();

$oldCover = $_POST["old_cover"];
$oldImg = $_POST["old_img"];

// 封面更新
if ($_FILES['cover']['error'] == 0) {
    #如果有選擇圖片就使用新上傳的圖片
    $filenameCover = time(); // 取得當前的 Unix 時間戳（秒級別）
    // pathinfo 取得上傳檔案的擴展名(路徑/PATHINFO_EXTENSION(.jpg))
    $fileExtCover = pathinfo($_FILES["cover"]["name"], PATHINFO_EXTENSION);
    $filenameCover = "update" . $filenameCover . "." . $fileExtCover;

    #上傳圖片
    if (move_uploaded_file($_FILES['cover']['tmp_name'], '../product_cover/' . $filenameCover)) {
        echo "封面更新成功";
    } else {
        echo "封面更新失敗";
    }
} else {
    echo $_FILES['cover']['error'];
    #如果沒有選擇圖片就使用原本資料庫的圖片
    $filenameCover = $oldCover;
}

$sql = "UPDATE product SET name='" . $name . "', cover='" . $filenameCover . "' WHERE id=" . $product_id;
$conn->query($sql);

// 細節照片更新
if ($_FILES['img']['error'] == 0) {
    #如果有選擇圖片就使用新上傳的圖片
    $filenameImg = time(); // 取得當前的 Unix 時間戳（秒級別）
    // pathinfo 取得上傳檔案的擴展名(路徑/PATHINFO_EXTENSION(.jpg))
    $fileExtImg = pathinfo($_FILES["img"]["name"], PATHINFO_EXTENSION);
    $filenameImg = "update" . $filenameImg . "." . $fileExtImg;

    #上傳圖片
    if (move_uploaded_file($_FILES['img']['tmp_name'], '../product_img/' . $filenameImg)) {
        echo "細節照更新成功";
    } else {
        echo "細節照更新失敗";
    }
} else {
    echo $_FILES['img']['error'];
    #如果沒有選擇圖片就使用原本資料庫的圖片
    $filenameImg = $oldImg;
}
$sql = "UPDATE product SET name='" . $name . "', img='" . $filenameImg . "' WHERE id=" . $product_id;
$conn->query($sql);
// $sql = "UPDATE product SET name=?, img=? WHERE id=?";
// $stmt = $conn->prepare($sql);
// $stmt->execute([$name, $filenameImg, $product_id]);

$sql = "UPDATE product SET name='$name', category=$primaryCategory, secondary_category=$secondaryCategory, price=$price, amount=$amount, description='$description', `change`='$now' WHERE id=$product_id";

// $sql = "UPDATE product SET name='$name', category=$primaryCategory, secondary_category=$secondaryCategory, price=$price, amount=$amount, description='$description', `update`=$now WHERE id=$product_id";

echo "<br>";
echo $sql;
if ($conn->query($sql) === TRUE) {
    echo "更新成功";
} else {
    echo "更新資料錯誤" . $conn->error;
}


$conn->close();

//修改完成
header("location: product-list.php");
