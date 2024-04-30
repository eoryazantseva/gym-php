<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Bootstrap CSS only -->
  
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">

    <!-- FontAwesome -->
    <script
        src="https://kit.fontawesome.com/5c10e22d0a.js"
        crossorigin="anonymous"
    ></script>
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css"
        integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer"
    />
    <link rel="stylesheet" href="css/styles.css" />
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <title>High Street Gym</title>
</head>
<body>
<div class="container-lg">
    <!-- Navigation Bar 1 -->
    <div class="border-bottom" pb-5>
        <nav class="navbar py-lg-2 pt-3 px-0 pb-0">
            <div class="container">
                <!-- Navigation bar container -->
                <div class="row w-100 align-items-center g-3">

                    <div class="col-lg-3 col-xxl-2">
                        <!-- Logo with link for large screens -->
                        <a class="navbar-brand d-none d-lg-block" href="index.php">
                            <img
                                    src="images/logo.png"
                                    alt="logo"
                                    width="150"
                                    id="logo"
                            />
                        </a>
                        <!-- Logo with link for small screens -->
                        <div class="d-flex justify-content-between w-100 d-lg-none">
                            <a class="navbar-brand" href="index.php">
                                <img
                                        src="images/logo.png"
                                        class="ms-3"
                                        alt="logo"
                                        width="150"
                                        id="logo"
                                />
                            </a>

                            <div class="d-flex align-items-center lh-1">
                                <div class="list-inline me-2">
                                    <!-- User login icon -->
                                    <div class="list-inline-item">
                                        <a href="login.php">
                                            <i class="fa fa-solid fa-user me-3 nav-icon"></i>
                                        </a>
                                    </div>
                                   
                                    <!-- <?php
                                    if(!empty($_SESSION["shopping_cart"])) {
                                    $cart_count = count(array_keys($_SESSION["shopping_cart"]));
                                    ?>
                                   
                                    <?php echo $cart_count; ?></span></a>
                                    </div>
                                    <?php
                                    }
                                    ?> -->

                                    <div class="list-inline-item">
                                        <a
                                                class="text-muted"
                                                data-bs-toggle="offcanvas"
                                                data-bs-target="#offCanvasRight"
                                                href="cart.php"
                                                role="button"
                                                aria-controls="offCanvasRight"
                                        >
                                            <i
                                                    class="fa fa-solid fa-cart-shopping me-2 nav-icon"
                                            ></i>
                                        </a>
                                    </div>
                                    <button
                                            class="navbar-toggler collapsed"
                                            type="button"
                                            data-bs-toggle="offcanvas"
                                            data-bs-target="#navbar-default"
                                            aria-controls="navbar-default"
                                            aria-expanded="false"
                                            aria-label="Toggle navigation"
                                    >
                                        <span class="navbar-toggler-icon"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-6 col-lg-5 d-none d-lg-block">
                        <form action="#" class="search-header">
                            <div class="input-group" id="search-bar">
                                <input
                                        class="form-control border-end-0"
                                        type="text"
                                        placeholder="Search for classes..."
                                        aria-label="Search for classes"
                                        aria-describedby="basic-addon2"
                                />
                                <span
                                        class="input-group-text bg-transparent"
                                        id="basic-addon2"
                                >
                      <a href="#">
                        <i class="fa fa-light fa-magnifying-glass nav-icon"></i>
                      </a>
                    </span>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-4 col-xxl-4 text-end d-none d-lg-block">
                        <div class="list-inline">
                            <div class="list-inline-item">
                                <a
                                        href="#"
                                        class="text-muted"
                                        data-bs-toggle="modal"
                                        data-bs-target="#userModal"
                                >
                                    <i class="fa fa-solid fa-cart-shopping me-2 nav-icon"></i>
                                </a>
                            </div>
                            <div class="list-inline-item">
                                <a
                                        class="position-relative"

                                        href="login.php"

                                >
                                    <i class="fa fa-solid fa-user me-3 nav-icon"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <!-- Second navbar -->

        <nav
                class="navbar navbar-expand-lg pt-0 pb-0 navbar-default">
            <div class="container px-0 px-md-3">
                <div class="offcanvas offcanvas-start p-4 p-lg-0" id="navbar-default" aria-modal="true" role="dialog">
                    <div class="d-flex justify-content-between align-items-center mb-2 d-block d-lg-none">
                        <div>
                            <a class="navbar-brand" href="index.php">
                                <img src="images/logo.png" width="100">
                            </a>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="d-none d-md-block">
                        <ul class="navbar-nav">
                            <li class="nav-item ">
                                <a class="nav-link mx-3" href="index.php">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mx-3" href="classes.php">Timetable</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mx-3" href="brands.php">Membership</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mx-3" href="#">About Us</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mx-3" href="blog.php">Blog</a>
                            </li>
                        </ul>
                    </div>
                    <div class="d-block d-md-none">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="index.html">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="classes.php">Timetable</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link" href="brands.php">Membership</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link" href="#">About Us</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link" href="blog.php">Blog</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </div>