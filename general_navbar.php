<!-- generalnavbar.php -->
<?php
// Start the session to track user authentication if needed
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navigation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Navbar background with gradient */
        .navbar {
            position: fixed;
            background: linear-gradient(
                to right, 
                #FFB200,  /* Yellow */
                #EB5B00,  /* Orange */
                #D91656,  /* Pink */
                #640D5F   /* Dark Purple */
            );
            position: fixed;
        }

        /* Navbar text styles */
        .navbar .navbar-brand {
            color: #FFF !important;
            font-weight: bold;
        }

        .navbar .nav-link {
            color: #FFF !important;
            font-weight: 500;
            transition: color 0.3s ease-in-out;
        }

        /* Hover effect */
        .navbar .nav-link:hover {
            color: #FFB200 !important; /* Yellow on hover */
        }

        /* Navbar toggler */
        .navbar-toggler {
            border: none;
        }

        .navbar-toggler-icon {
            filter: invert(1);
        }
    </style>
</head>
<body>

    <!-- Navigation Bar --> 
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">HookedByNessy</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about_us.php">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="products.php">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
