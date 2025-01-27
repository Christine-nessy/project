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
        padding: 10px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 3px solid #357abd; /* Adds a subtle border for design */
    }
    .navbar .navbar-brand {
        color: white;
        font-size: 1.5rem;
        font-weight: bold;
        text-decoration: none;
    }
    .navbar .nav-links {
        display: flex;
        gap: 15px; /* Adds space between the links */
    }
    .navbar .nav-link {
        color: white;
        font-size: 1.1rem;
        font-weight: 500;
        text-decoration: none;
        padding: 5px 10px;
        transition: color 0.3s ease, background-color 0.3s ease;
        border-radius: 5px;
    }
    .navbar .nav-link:hover {
        color: #4a90e2;
        background-color: white;
    }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <!-- <a class="navbar-brand" href="#">My Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button> -->
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

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>