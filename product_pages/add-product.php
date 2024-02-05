<?php
if (!isset($_GET["id"])) {
    $id = 0;
} else {
    $id = $_GET["id"];
}
// 連線資料庫
require_once("../db_connect.php");
// 選取資料
$sqlAll = "SELECT * FROM product WHERE id=$id";
$resultAll = $conn->query($sqlAll);
$rowsAll = $resultAll->fetch_all(MYSQLI_ASSOC);
// 抓主類別資料表
$sqlCategory = "SELECT * FROM primary_category WHERE valid=1";
$resultCategory = $conn->query($sqlCategory);
$rowsCategory = $resultCategory->fetch_all(MYSQLI_ASSOC);

// 抓次類別資料表
$sqlSecondaryCategory = "SELECT * FROM secondary_category WHERE valid=1";
$resultSecondaryCategory = $conn->query($sqlSecondaryCategory);
$rowsSecondaryCategory = $resultSecondaryCategory->fetch_all(MYSQLI_ASSOC);

?>
<!doctype html>
<html lang="en">

<head>
    <title>add-product</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- CSS Files -->
    <link id="pagestyle" href="../assets/css/argon-dashboard.css?v=2.0.4" rel="stylesheet" />
    <!-- Awesome Font -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Bootstrap JavaScript (Popper.js and Bootstrap JS) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="..." crossorigin="anonymous"></script>

</head>

<body>
    <div class="container" style="max-width: 500px;">
        <div class="d-flex justify-content-center">
            <h1>新增商品</h1>
        </div>
        <div class="py-2">
            <a href="product-list.php" class="btn btn-primary" role="button">
                <i class="fa-solid fa-angles-left fa-fw"></i>回商品管理
            </a>
        </div>
        <form action="doAddProduct.php" method="post" enctype="multipart/form-data">
            <div class="mb-2 d-flex">
                <select class="form-select form-select-lg mb-3 me-2" aria-label="Large select example" name="primaryCategorySelect" onchange="location.href='add-product.php?id=<?= $id ?>&primaryCategorySelect=' + this.value;">
                    <option selected>主類別</option>
                    <?php foreach ($rowsCategory as $primaryCategory) : ?>
                        <?php if ($primaryCategory["id"] == $_GET["primaryCategorySelect"]) : ?>
                            <option selected value="<?= $primaryCategory["id"] ?>"><?= $primaryCategory["name"] ?></option>
                        <?php else : ?>
                            <option value="<?= $primaryCategory["id"] ?>"><?= $primaryCategory["name"] ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
                <div>
                    <a name="addPrimaryCategory" id="" class="btn btn-success" href="../product-category_pages/addPriCategory.php" role="button">
                        <i class="fa-solid fa-plus fa-fw"></i>
                    </a>
                </div>
            </div>
            <div class="mb-2 d-flex">
                <select class="form-select form-select-lg mb-3 me-2" aria-label="Large select example" name="secondaryCategorySelect">
                    <option selected>次類別</option>
                    <?php foreach ($rowsSecondaryCategory as $secondaryCategory) : ?>
                        <?php if ($secondaryCategory["primary_id"] == $_GET["primaryCategorySelect"]) : ?>
                            <?php if ($secondaryCategory["id"] == $_GET["secondaryCategorySelect"]) : ?>
                                <option selected value="<?= $secondaryCategory["id"] ?>"><?= $secondaryCategory["name"] ?></option>
                            <?php else : ?>
                                <option value="<?= $secondaryCategory["id"] ?>"><?= $secondaryCategory["name"] ?></option>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
                <div>
                    <a name="addSecondaryCategory" id="" class="btn btn-success" href="../product-category_pages/addSecCategory.php" role="button">
                        <i class="fa-solid fa-plus fa-fw"></i>
                    </a>
                </div>
            </div>
            <div class="mb-2">
                <input type="text" class="form-control" name="name" placeholder="輸入商品名稱">
            </div>
            <div class="mb-2 d-flex">
                <input type="text" class="form-control me-2" name="price" placeholder="輸入價格">
                <input type="text" class="form-control" name="amount" placeholder="輸入庫存量">
            </div>
            <div class="mb-3">
                <label for="cover" class="form-label">商品封面</label>
                <input class="form-control" type="file" id="cover" name="cover">
            </div>
            <div class="mb-3">
                <label for="img" class="form-label">商品細節照</label>
                <input class="form-control" type="file" id="img" name="img">
            </div>
            <div class="mb-2 form-floating">
                <textarea class="form-control" name="description" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px"></textarea>
                <label for="floatingTextarea2">商品描述</label>
            </div>
            <div class="d-grid gap-2 col-2 mx-auto">
                <button type="submit" class="btn btn-primary">
                    送出
                </button>
            </div>
        </form>
    </div>
    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>

</html>