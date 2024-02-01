<?php
// 連線資料庫
require_once("../db_connect.php");
// 選取資料
$sqlAll = "SELECT * FROM product WHERE valid = 1";
$resultAll = $conn->query($sqlAll);
$rowsAll = $resultAll->fetch_all(MYSQLI_ASSOC);
$rowsTotalCount = $resultAll->num_rows;
// 每頁顯示
$perPage = 15;
// 共有幾頁
$pageCount = ceil($rowsTotalCount / $perPage);
// 排序
if (isset($_GET["order"])) {
    $order = $_GET["order"];

    if ($order == 1) {
        $orderString = "ORDER BY id ASC";
    } elseif ($order == 2) {
        $orderString = "ORDER BY id DESC";
    } elseif ($order == 3) {
        $orderString = "ORDER BY name ASC";
    } elseif ($order == 4) {
        $orderString = "ORDER BY name DESC";
    }
}

//搜尋
if (isset($_GET["search"])) { //在搜尋的條件下
    $search = $_GET["search"];
    $sql = "SELECT * FROM product WHERE name LIKE '%$search%' AND valid=1";
    $result = $conn->query($sql);
    //顯示符合搜尋條件且沒有被軟刪除的資料
} elseif (isset($_GET["p"])) {
    $p = $_GET["p"];
    $startIndex = ($p - 1) * $perPage; //該頁從第幾筆資料開始顯示
    $orderString = "";
    $sql = "SELECT * FROM product WHERE valid=1 $orderString LIMIT $startIndex, $perPage";
} else {
    $p = 1; //預設在第一頁
    $order = 1; //預設排序
    $orderString = "ORDER BY id ASC";
    $sql = "SELECT * FROM product WHERE valid=1 LIMIT $perPage"; //顯示所有資料
}
$result = $conn->query($sql);



if (isset($_GET["search"])) { //如果在搜尋的條件下，顯示共有幾筆資料num_rows
    $rowsCount = $result->num_rows;
} else { //否則顯示所有的資料
    $rowsCount = $rowsTotalCount;
}

// 抓主類別資料表
$sqlCategory = "SELECT * FROM primary_category WHERE valid=1";
$resultCategory = $conn->query($sqlCategory);
$rowsCategory = $resultCategory->fetch_all(MYSQLI_ASSOC);

// 抓次類別資料表
$sqlSecondaryCategory = "SELECT * FROM secondary_category WHERE valid=1";
$resultSecondaryCategory = $conn->query($sqlSecondaryCategory);
$rowsSecondaryCategory = $resultSecondaryCategory->fetch_all(MYSQLI_ASSOC);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <title>
        Argon Dashboard 2 by Creative Tim
    </title>
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
    <!-- 檢視修改刪除Modal -->
    <?php foreach ($rowsAll as $product) : ?>
    <div class="modal modal-dialog-scrollable fade" id="read" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">
                        <?= $product["name"] ?>
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container">

                        <div class="row">
                            <div class="col-4 ">
                                <img src="../cover/<?= $product["cover"] ?>" alt="<?= $product["name"] ?>" width="300px" height="300px" class="mt-3">
                            </div>
                            <div class="col-8">
                                <table class="table table-bordered">

                                    <tr>
                                        <th>ID</th>
                                        <td>
                                            <?= $product["id"] ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>商品名稱</th>
                                        <td>
                                            <?= $product["name"] ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>價格</th>
                                        <td>
                                            <?= $product["price"] ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>庫存</th>
                                        <td>
                                            <?= $product["amount"] ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>主類別</th>
                                        <td>
                                            <?= $product["category"] ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>次類別</th>
                                        <td>
                                            <?= $product["secondary_category"] ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>商品描述</th>
                                        <td class="text-wrap">
                                            <?= $product["description"] ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>上次更新時間</th>
                                        <td>
                                            <?= $product["update"] ?>
                                        </td>
                                    </tr>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer d-flex justify-content-lg-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">關閉</button>
                    <div>
                        <!-- 修改 -->
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal<?= $product["id"] ?>">
                            修改
                        </button>
                        <!-- 刪除 -->
                        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmModal<?= $product["id"] ?>" role="button"><i class="fa-solid fa-trash fa-fw"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- 按修改會跳出來的東西 (完成)-->
    <div class="modal fade" id="editModal<?= $product["id"] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">修改商品資料</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="row">

                    <div class="col-4 d-flex justify-content-center mt-3">
                        <div class="previewimage border">
                            <img src="../cover/<?= $product["cover"] ?>" alt="<?= $product["name"] ?>" width="300px" height="300px" class="mt-3">
                        </div>
                    </div>

                    <div class="col-8">
                        <!-- Form for editing user details -->
                        <form action="doUpdateProduct.php" method="post">
                            <!-- <input type="hidden" name="user_id" value=""> -->
                            <div class="mb-2">
                                <input type="text" class="form-control" name="name" placeholder="輸入商品名稱">
                            </div>
                            <div class="mb-2 d-flex">
                                <select class="form-select" aria-label="選擇主類別" name="primaryCategory">
                                    <option selected>選擇主類別</option>
                                    <option value="1">One</option>
                                    <option value="2">Two</option>
                                    <option value="3">Three</option>
                                </select>
                                <div>
                                    <a name="addPrimaryCategory" id="" class="btn btn-success" href="#" role="button">
                                        <i class="fa-solid fa-plus fa-fw"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="mb-2 d-flex">
                                <select class="form-select" aria-label="選擇次類別" name="secondaryCategory">
                                    <option selected>選擇次類別</option>
                                    <option value="1">One</option>
                                    <option value="2">Two</option>
                                    <option value="3">Three</option>
                                </select>
                                <div>
                                    <a name="addSecondaryCategory" id="" class="btn btn-success" href="#" role="button">
                                        <i class="fa-solid fa-plus fa-fw"></i>
                                    </a>
                                </div>
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
                                    確認
                                </button>
                            </div>
                            <!-- <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                                        <button type="submit" class="btn btn-danger">確認</button>
                                    </div> -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <!-- End of modal -->
</head>

<body class="g-sidenav-show   bg-gray-100">
    <div class="min-height-300 bg-primary position-absolute w-100"></div>
    <aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 " id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0" href=" https://demos.creative-tim.com/argon-dashboard/pages/dashboard.html " target="_blank">
                <img src="../assets/img/logo-ct-dark.png" class="navbar-brand-img h-100" alt="main_logo">
                <span class="ms-1 font-weight-bold">MIDTERM PROJECT</span>
            </a>
        </div>
        <hr class="horizontal dark mt-0">
        <div class=" w-auto " id="sidenav-collapse-main">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="./pages/dashboard.html">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-tv-2 text-primary text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">主頁面
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="./pages/tables.html">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-calendar-grid-58 text-warning text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">會員管理/註冊</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="./pages/billing.html">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-credit-card text-success text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">商品管理</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="./pages/virtual-reality.html">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-app text-info text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">商品類別管理</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="./pages/rtl.html">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-world-2 text-danger text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">文章管理</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="./pages/rtl.html">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-world-2 text-danger text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">訂單管理</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="./pages/rtl.html">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-world-2 text-danger text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">講師管理</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="./pages/rtl.html">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-world-2 text-danger text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">課程管理</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="./pages/rtl.html">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-world-2 text-danger text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">優惠券管理</span>
                    </a>
                </li>

                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">個人主頁</h6>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="../pages/profile.html">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">個人檔案</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="../pages/sign-in.html">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-single-copy-04 text-warning text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">登入</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="../pages/sign-up.html">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-collection text-info text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">登出</span>
                    </a>
                </li>
            </ul>
        </div>

    </aside>
    <main class="main-content position-relative border-radius-lg ">
        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur" data-scroll="false">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">主頁面</a></li>
                        <li class="breadcrumb-item text-sm text-white active" aria-current="page">商品管理</li>
                    </ol>
                    <!-- <h6 class="font-weight-bolder text-white mb-0">主頁面</h6> -->
                </nav>
                <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                    <div class="ms-md-auto pe-md-3 d-flex align-items-center">

                    </div>
                    <div class="text-white px-4">
                        HI, USER
                    </div>
                    <ul class="navbar-nav  justify-content-end">
                        <li class="nav-item d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-white font-weight-bold px-0">
                                <i class="fa fa-user me-sm-1"></i>
                                <span class="d-sm-inline d-none">登入</span>
                            </a>
                        </li>
                        <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-white p-0" id="iconNavbarSidenav">
                                <div class="sidenav-toggler-inner">
                                    <i class="sidenav-toggler-line bg-white"></i>
                                    <i class="sidenav-toggler-line bg-white"></i>
                                    <i class="sidenav-toggler-line bg-white"></i>
                                </div>
                            </a>
                        </li>

                    </ul>
                </div>
            </div>
        </nav>
        <!-- End Navbar -->
        <!-- 商品列表 -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <div class="d-flex justify-content-between">
                                <h4>商品列表</h4>
                                <div>
                                    新增
                                    <a name="" id="" class="btn btn-primary" href="add-product.php" role="button">
                                        <i class="fa-solid fa-plus fa-fw"></i>
                                    </a>
                                </div>
                            </div>
                            <!-- 搜尋欄 -->
                            <div class="col">
                                <form action="">
                                    <div class="input-group">
                                        <?php if (isset($_GET["search"])) : ?>
                                            <a name="" id="" class="btn btn-primary" href="product-list.php" role="button">返回</a>
                                        <?php endif; ?>

                                        <input style="height: 41px;" type="search" class="form-control box-sizing inline-block" placeholder="商品名稱" aria-label="Recipient's username" aria-describedby="button-addon2" name="search" <?php if (isset($_GET["search"])) : $searchValue = $_GET["search"]; ?> value="<?= $searchValue ?>" <?php endif; ?>>
                                        <button class="btn btn-primary" type="search" id="button-addon2"><i class="fa-solid fa-magnifying-glass fa-fw"></i></button>
                                    </div>
                                </form>
                            </div>
                            <!-- 類別 -->
                            <div class="d-flex">
                                <select class="form-select form-select-lg mb-3 me-2" aria-label="Large select example">
                                    <option selected>主類別</option>
                                    <?php foreach ($rowsCategory as $primaryCategory) : ?>
                                        <option value="<?= $primaryCategory["id"] ?>"><?= $primaryCategory["name"] ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <select class="form-select form-select-lg mb-3" aria-label="Large select example">
                                    <option selected>次類別</option>
                                    <?php foreach ($rowsSecondaryCategory as $secondaryCategory) : ?>
                                        <option value="<?= $secondaryCategory["id"] ?>"><?= $secondaryCategory["name"] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="d-flex justify-content-between align-item-center">
                                <div>
                                    共 <?= $rowsCount ?> 筆
                                </div>
                                <div class="d-flex">
                                    <div class="me-2">排序</div>
                                    <div class="btn-group">
                                        <a class="btn btn-primary <?php if ($order == 1) echo "active" ?>" href="product-list.php?order=1&p=<?= $p ?>"><i class="fa-solid fa-arrow-down-1-9 fa-fw"></i></a>
                                        <a class="btn btn-primary <?php if ($order == 2) echo "active" ?>" href="product-lst.php?order=2&p=<?= $p ?>"><i class="fa-solid fa-arrow-down-9-1 fa-fw"></i></a>
                                        <a class="btn btn-primary <?php if ($order == 3) echo "active" ?>" href="product-list.php?order=3&p=<?= $p ?>"><i class="fa-solid fa-arrow-down-a-z fa-fw"></i></a>
                                        <a class="btn btn-primary <?php if ($order == 4) echo "active" ?>" href="product-list.php?order=4&p=<?= $p ?>"><i class="fa-solid fa-arrow-down-z-a fa-fw"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <?php if ($rowsCount > 0) : ?>
                                    <table class="table align-items-center mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-secondary text-s font-weight-bolder opacity-7">商品名稱</th>
                                                <th class="text-secondary text-s font-weight-bolder opacity-7 ps-2">價錢</th>
                                                <th class="text-secondary text-s font-weight-bolder opacity-7 ps-2">庫存</th>
                                                <th class="text-center text-uppercase text-secondary text-s font-weight-bolder opacity-7">更新時間</th>
                                                <th class="text-secondary text-center opacity-7">檢視</th>
                                                <th class="text-secondary text-center opacity-7">修改</th>
                                                <th class="text-secondary text-center opacity-7">刪除</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $rows = $result->fetch_all(MYSQLI_ASSOC);
                                            foreach ($rows as $product) : ?>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex px-2 py-1">
                                                            <div class="d-flex flex-column justify-content-center">
                                                                <h6 class="mb-0 text-m"><?= $product["name"] ?></h6>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <h6 class="mb-0 text-m"><?= $product["price"] ?></h6>
                                                    </td>
                                                    <td>
                                                        <h6 class="mb-0 text-m"><?= $product["amount"] ?></h6>
                                                    </td>
                                                    <td class="align-middle text-center">
                                                        <span class="text-secondary text-m font-weight-bold"><?= $product["update"] ?></span>
                                                    </td>
                                                    <td class="align-middle text-center">
                                                        <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#read">
                                                            <i class="fa-solid fa-eye fa-fw"></i>
                                                        </button>
                                                    </td>
                                                    <td class="align-middle text-center">
                                                        <button class="btn btn-primary"type="button" role="button" data-bs-toggle="modal" data-bs-target="#edit<?= $product["id"] ?>">
                                                            <i class="fa-solid fa-pen-to-square fa-fw"></i>
                                                        </button>
                                                    </td>
                                                    <td class="align-middle text-center">
                                                        <button class="btn btn-danger" type="button" href="doDeleteProduct.php" method="post" role="button">
                                                            <i class="fa-solid fa-trash-can fa-fw"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- 商品列表結束 -->

            <!-- 在搜尋的情況下不顯示分頁 -->
            <?php if (!isset($_GET["search"])) : ?>
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <?php for ($i = 1; $i <= $pageCount; $i++) : ?>
                            <li class="page-item <?php if ($i == $p) echo "active" ?>">
                                <a class="page-link" href="product-list.php?order=<?= $order ?>&p=<?= $i ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
        <footer class="footer pt-3  ">
            <div class="container-fluid">
                <div class="row align-items-center justify-content-lg-between">
                    <div class="col-lg-6 mb-lg-0 mb-4">
                        <div class="copyright text-center text-sm text-muted text-lg-start">
                            © <script>
                                document.write(new Date().getFullYear())
                            </script>,
                            made with <i class="fa fa-heart"></i> by
                            <a href="https://www.creative-tim.com" class="font-weight-bold" target="_blank">Creative Tim</a>
                            for a better web.
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                            <li class="nav-item">
                                <a href="https://www.creative-tim.com" class="nav-link text-muted" target="_blank">Creative Tim</a>
                            </li>
                            <li class="nav-item">
                                <a href="https://www.creative-tim.com/presentation" class="nav-link text-muted" target="_blank">About Us</a>
                            </li>
                            <li class="nav-item">
                                <a href="https://www.creative-tim.com/blog" class="nav-link text-muted" target="_blank">Blog</a>
                            </li>
                            <li class="nav-item">
                                <a href="https://www.creative-tim.com/license" class="nav-link pe-0 text-muted" target="_blank">License</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
        </div>
    </main>
    <div class="fixed-plugin">
        <a class="fixed-plugin-button text-dark position-fixed px-3 py-2">
            <i class="fa fa-cog py-2"> </i>
        </a>
        <div class="card shadow-lg">
            <div class="card-header pb-0 pt-3 ">
                <div class="float-start">
                    <h5 class="mt-3 mb-0">Argon Configurator</h5>
                    <p>See our dashboard options.</p>
                </div>
                <div class="float-end mt-4">
                    <button class="btn btn-link text-dark p-0 fixed-plugin-close-button">
                        <i class="fa fa-close"></i>
                    </button>
                </div>
                <!-- End Toggle Button -->
            </div>
            <hr class="horizontal dark my-1">
            <div class="card-body pt-sm-3 pt-0 overflow-auto">
                <!-- Sidebar Backgrounds -->
                <div>
                    <h6 class="mb-0">Sidebar Colors</h6>
                </div>
                <a href="javascript:void(0)" class="switch-trigger background-color">
                    <div class="badge-colors my-2 text-start">
                        <span class="badge filter bg-gradient-primary active" data-color="primary" onclick="sidebarColor(this)"></span>
                        <span class="badge filter bg-gradient-dark" data-color="dark" onclick="sidebarColor(this)"></span>
                        <span class="badge filter bg-gradient-info" data-color="info" onclick="sidebarColor(this)"></span>
                        <span class="badge filter bg-gradient-success" data-color="success" onclick="sidebarColor(this)"></span>
                        <span class="badge filter bg-gradient-warning" data-color="warning" onclick="sidebarColor(this)"></span>
                        <span class="badge filter bg-gradient-danger" data-color="danger" onclick="sidebarColor(this)"></span>
                    </div>
                </a>
                <!-- Sidenav Type -->
                <div class="mt-3">
                    <h6 class="mb-0">Sidenav Type</h6>
                    <p class="text-sm">Choose between 2 different sidenav types.</p>
                </div>
                <div class="d-flex">
                    <button class="btn bg-gradient-primary w-100 px-3 mb-2 active me-2" data-class="bg-white" onclick="sidebarType(this)">White</button>
                    <button class="btn bg-gradient-primary w-100 px-3 mb-2" data-class="bg-default" onclick="sidebarType(this)">Dark</button>
                </div>
                <p class="text-sm d-xl-none d-block mt-2">You can change the sidenav type just on desktop view.</p>
                <!-- Navbar Fixed -->
                <div class="d-flex my-3">
                    <h6 class="mb-0">Navbar Fixed</h6>
                    <div class="form-check form-switch ps-0 ms-auto my-auto">
                        <input class="form-check-input mt-1 ms-auto" type="checkbox" id="navbarFixed" onclick="navbarFixed(this)">
                    </div>
                </div>
                <hr class="horizontal dark my-sm-4">
                <div class="mt-2 mb-5 d-flex">
                    <h6 class="mb-0">Light / Dark</h6>
                    <div class="form-check form-switch ps-0 ms-auto my-auto">
                        <input class="form-check-input mt-1 ms-auto" type="checkbox" id="dark-version" onclick="darkMode(this)">
                    </div>
                </div>
                <a class="btn bg-gradient-dark w-100" href="https://www.creative-tim.com/product/argon-dashboard">Free Download</a>
                <a class="btn btn-outline-dark w-100" href="https://www.creative-tim.com/learning-lab/bootstrap/license/argon-dashboard">View documentation</a>
                <div class="w-100 text-center">
                    <a class="github-button" href="https://github.com/creativetimofficial/argon-dashboard" data-icon="octicon-star" data-size="large" data-show-count="true" aria-label="Star creativetimofficial/argon-dashboard on GitHub">Star</a>
                    <h6 class="mt-3">Thank you for sharing!</h6>
                    <a href="https://twitter.com/intent/tweet?text=Check%20Argon%20Dashboard%20made%20by%20%40CreativeTim%20%23webdesign%20%23dashboard%20%23bootstrap5&amp;url=https%3A%2F%2Fwww.creative-tim.com%2Fproduct%2Fargon-dashboard" class="btn btn-dark mb-0 me-2" target="_blank">
                        <i class="fab fa-twitter me-1" aria-hidden="true"></i> Tweet
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=https://www.creative-tim.com/product/argon-dashboard" class="btn btn-dark mb-0 me-2" target="_blank">
                        <i class="fab fa-facebook-square me-1" aria-hidden="true"></i> Share
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!--   Core JS Files   -->
    <!-- <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>
    <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script> -->
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="../assets/js/argon-dashboard.min.js?v=2.0.4"></script>
</body>

</html>