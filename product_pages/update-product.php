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
// var_dump($rowsCategory);
// 抓次類別資料表
$sqlSecondaryCategory = "SELECT * FROM secondary_category WHERE valid=1";
$resultSecondaryCategory = $conn->query($sqlSecondaryCategory);
$rowsSecondaryCategory = $resultSecondaryCategory->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html lang="en">

<head>
    <title>update-product</title>
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
    <div class="container">
        <div class="">
            <h1>修改商品資料</h1>
        </div>
        <div class="row">
            <div class="col-12">
                <!-- Form for editing product details -->
                <form action="doUpdateProduct.php" method="post" enctype="multipart/form-data">
                    <?php foreach ($rowsAll as $product) : ?>
                        <table class="table">
                            <tr class="border-end">
                                <th>類別</th>
                                <td>
                                    <select class="form-select form-select-lg mb-3 me-2" aria-label="Large select example" name="primaryCategorySelect" onchange="location.href='update-product.php?id=<?= $id ?>&primaryCategorySelect=' + this.value;">
                                        <option selected>主類別</option>
                                        <?php foreach ($rowsCategory as $primaryCategory) : ?>
                                            <?php if ($primaryCategory["id"] == $_GET["primaryCategorySelect"]) : ?>
                                                <option selected value="<?= $primaryCategory["id"] ?>"><?= $primaryCategory["name"] ?></option>
                                            <?php else : ?>
                                                <option value="<?= $primaryCategory["id"] ?>"><?= $primaryCategory["name"] ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
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
                                </td>
                            </tr>
                            <tr class="border-end">
                                <th>商品名稱</th>
                                <td>
                                    <input type="text" class="form-control" name="name" value="<?= $product["name"] ?>">
                                </td>
                            </tr>
                            <tr class="border-end">
                                <th>價格</th>
                                <td>
                                    <input type="number" class="form-control" name="price" value="<?= $product["price"] ?>">
                                </td>
                            </tr>
                            <tr class="border-end">
                                <th>庫存</th>
                                <td>
                                    <input type="number" class="form-control" name="amount" value="<?= $product["amount"] ?>">
                                </td>
                            </tr>
                            <tr class="border-end">
                                <th>商品封面</th>
                                <td>
                                    <input type="hidden" name="old_cover" value="<?= $product["cover"] ?>">
                                    <img src="../product_cover/<?= $product["cover"] ?>" style="width: 300px; height: 300px;" alt="">
                                    <input type="file" name="cover">
                                </td>
                            </tr>
                            <tr class="border-end">
                                <th>商品細節照</th>
                                <td>
                                    <input type="hidden" name="old_img" value="<?= $product["img"] ?>">
                                    <img src="../product_img/<?= $product["img"] ?>" style="width: 300px; height: 300px;" alt="">
                                    <input type="file" name="img">
                                </td>
                            </tr>
                            <tr class="border-end">
                                <th>商品描述</th>
                                <td>
                                    <textarea type="text" class="form-control" name="description" value="<?= $user["address"] ?>"></textarea>
                                </td>
                            </tr>

                            <!-- <tr class="border-end">
                                                <th>更換大頭貼</th>
                                                <td>
                                                  <input type="file" class="form-control" name="editImg">
                                                </td>
                                              </tr> -->

                        </table>
                        <div class="d-grid gap-2 col-2 mx-auto">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <button type="submit" class="btn btn-primary">
                                確認
                            </button>
                        </div>
                    <?php endforeach; ?>
                </form>
            </div>
        </div>
    </div>
    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>

</html>