<?php
session_start();
require_once('includes/config.php');
require_once('includes/db.php');
require_once('includes/auth.php'); 

if (function_exists('checkUser')) {
    checkUser();
}

include('includes/header.php');
$display_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Member';
?>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&family=Bebas+Neue&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="assets/style.css">

<main>
    <div class="hero-wrapper">
        <div class="container d-flex justify-content-center">
            <div class="welcome-card animate__animated animate__zoomIn">
                <span class="status-badge" style="display: inline-block; background: rgba(0, 242, 254, 0.1); color: var(--accent); padding: 8px 25px; border-radius: 50px; font-size: 0.75rem; font-weight: 800; letter-spacing: 3px; border: 1px solid rgba(0, 242, 254, 0.3); margin-bottom: 15px; text-transform: uppercase;">Authorized Member</span>
                <h1 class="display-name">WELCOME, <?php echo htmlspecialchars($display_name); ?>!</h1>
                <p class="lead mb-5 text-white-50">Precision engineered for those who demand excellence. Experience the pinnacle of luxury rentals.</p>
                
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="<?php echo BASE_URL; ?>vehicles.php" class="btn-explore">Browse Fleet</a>
                    
                    <a href="<?php echo BASE_URL; ?>user/my_bookings.php" class="btn btn-outline-light py-3 px-5 rounded-4" style="border-radius: 20px; font-weight: 600; letter-spacing: 1px; transition: 0.3s; text-decoration: none;">
                        My Garage
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="stats-bar animate__animated animate__fadeInUp">
            <div class="row text-center g-4">
                <div class="col-md-4 border-md-end border-white border-opacity-10">
                    <h3 style="color: var(--accent); font-weight: 800; font-size: 2.5rem;" class="mb-0">500+</h3>
                    <p style="font-size: 0.75rem; opacity: 0.6; text-transform: uppercase; letter-spacing: 1px;">Premium Fleet</p>
                </div>
                <div class="col-md-4 border-md-end border-white border-opacity-10">
                    <h3 style="color: var(--accent); font-weight: 800; font-size: 2.5rem;" class="mb-0">24/7</h3>
                    <p style="font-size: 0.75rem; opacity: 0.6; text-transform: uppercase; letter-spacing: 1px;">Concierge Support</p>
                </div>
                <div class="col-md-4">
                    <h3 style="color: var(--accent); font-weight: 800; font-size: 2.5rem;" class="mb-0">100%</h3>
                    <p style="font-size: 0.75rem; opacity: 0.6; text-transform: uppercase; letter-spacing: 1px;">Secured Journey</p>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include('includes/footer.php'); ?>