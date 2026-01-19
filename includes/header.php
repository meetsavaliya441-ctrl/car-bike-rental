<?php
// Absolute path fix: hamesha config file ko sahi se dhundega
require_once(__DIR__ . '/config.php'); 

$current_page = basename($_SERVER['PHP_SELF']);

// Session start agar pehle se nahi hai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo defined('APP_NAME') ? APP_NAME : 'Elite Rental'; ?> | Luxury on Demand</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <style>
        :root {
            --accent: #00f2fe;
            --nav-bg: rgba(10, 10, 10, 0.85);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            padding-top: 90px;
            background: #050505;
            color: #fff;
        }

        /* Navbar Styling */
        .navbar {
            background: var(--nav-bg) !important;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 15px 0;
            position: fixed;
            top: 0; width: 100%; z-index: 1000;
        }

        .navbar-brand {
            font-weight: 800; font-size: 1.6rem; letter-spacing: -1px;
            color: #fff !important; display: flex; align-items: center; gap: 8px;
            text-decoration: none;
        }
        .navbar-brand span { color: var(--accent); }

        .nav-link {
            color: rgba(255, 255, 255, 0.75) !important;
            font-weight: 600; font-size: 0.95rem; margin: 0 10px;
            position: relative; transition: 0.3s;
        }
        .nav-link:hover, .nav-link.active { color: var(--accent) !important; }

        .nav-link::after {
            content: ''; position: absolute; width: 0; height: 2px;
            bottom: -5px; left: 0; background-color: var(--accent);
            transition: width 0.3s ease;
        }
        .nav-link:hover::after, .nav-link.active::after { width: 100%; }

        .nav-auth-btn {
            background: linear-gradient(45deg, var(--accent), #4facfe);
            color: #000 !important; padding: 12px 28px !important;
            border-radius: 16px; font-weight: 700; transition: 0.3s;
            box-shadow: 0 10px 20px rgba(0, 242, 254, 0.2);
            border: none; text-decoration: none; display: inline-flex; align-items: center;
        }
        .nav-auth-btn:hover { transform: translateY(-3px); box-shadow: 0 15px 25px rgba(0, 242, 254, 0.3); color: #000 !important; }

        .logout-link { color: #ff4b5c !important; font-size: 1.2rem; transition: 0.3s; padding: 10px; }
        .logout-link:hover { transform: rotate(90deg); color: #fff !important; }

        /* Notification Alerts */
        .alert-custom {
            position: fixed; top: 100px; right: 20px; z-index: 9999;
            min-width: 300px; border-radius: 20px; border: none;
            backdrop-filter: blur(10px); color: #fff;
            box-shadow: 0 15px 30px rgba(0,0,0,0.5);
        }

        @media (max-width: 991px) {
            .navbar-collapse {
                background: #111; padding: 25px; border-radius: 25px;
                margin-top: 20px; border: 1px solid rgba(255,255,255,0.1);
            }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="<?php echo BASE_URL; ?>index.php">
            <i class="fas fa-bolt text-info"></i> 
            <?php 
                $parts = explode(' ', APP_NAME);
                echo $parts[0]; 
            ?><span><?php echo isset($parts[1]) ? $parts[1] : ''; ?></span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="fas fa-bars"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link <?php echo ($current_page == 'vehicles.php') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>vehicles.php">Vehicles</a></li>
                <li class="nav-item"><a class="nav-link <?php echo ($current_page == 'about.php') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>about.php">About</a></li>
                <li class="nav-item"><a class="nav-link <?php echo ($current_page == 'contact.php') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>contact.php">Contact</a></li>

                <?php if(isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'my_bookings.php') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>user/my_bookings.php">My Bookings</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <a class="nav-link nav-auth-btn" href="<?php echo BASE_URL; ?>user/profile.php">
                            <i class="fas fa-user-circle me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link logout-link" href="<?php echo BASE_URL; ?>logout.php" title="Logout">
                            <i class="fas fa-power-off"></i>
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item ms-lg-3">
                        <a class="nav-link nav-auth-btn" href="<?php echo BASE_URL; ?>user/login.php">Join Now</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-custom animate__animated animate__fadeInRight" style="background: rgba(0, 255, 136, 0.2); border-left: 5px solid #00ff88;">
            <div class="d-flex align-items-center justify-content-between">
                <span><i class="fas fa-check-circle me-2 text-info"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?></span>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        </div>
    <?php endif; ?>

    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-custom animate__animated animate__shakeX" style="background: rgba(255, 75, 92, 0.2); border-left: 5px solid #ff4b5c;">
            <div class="d-flex align-items-center justify-content-between">
                <span><i class="fas fa-exclamation-triangle me-2 text-danger"></i> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?></span>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>