<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login_form.php");
    exit;
}


?>
 <style>
        body {
            background: url('images/rb_45678.png') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            color: #333;
            height: 100vh;
            margin: 0;
        }
        .sidebar {
            width: 250px;
            position: fixed;
            height: 100%;
            background-color: #4a90e2;
            padding: 15px;
            color: white;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
        }
        .sidebar a:hover {
            background-color: #357abd;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
        }
        .navbar {
            background-color: #4a90e2;
        }
        .navbar .navbar-brand,
        .navbar .nav-link {
            color: white;
        }
        .navbar .nav-link:hover {
            color: #ddd;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">My Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Settings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar">
        <h4>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h4>
        <a href="#">Dashboard</a>
        <a href="#">Messages</a>
        <a href="#">Tasks</a>
        <a href="#">Notifications</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="dashboard-container">
            <h1>Dashboard</h1>
            <p>You are now logged in.</p>
            <!-- Sliding Photos Carousel -->
            <div id="photoCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="images/Nessy.png" class="d-block w-100" alt="Photo 1">
                    </div>
                    <div class="carousel-item">
                        <img src="images/Nessy.png" class="d-block w-100" alt="Photo 2">
                    </div>
                    <div class="carousel-item">
                        <img src="images/Nessy.png" class="d-block w-100" alt="Photo 3">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#photoCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#photoCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
            <a href="logout.php" class="mt-3 d-block">Logout</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>