<?php
session_start();
require_once('includes/config.php');
require_once('includes/db.php');
require_once('includes/auth.php'); 

if (function_exists('checkUser')) { checkUser(); }
include('includes/header.php');

// URL se ID lena
$v_id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : 0;

// Vehicle details fetch karna
$query = "SELECT * FROM vehicles WHERE id = '$v_id'";
$result = mysqli_query($conn, $query);
$v = mysqli_fetch_assoc($result);

if (!$v) {
    header("Location: vehicles.php");
    exit();
}
?>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/style.css">


<div class="container details-container">
    <div class="mb-4 opacity-50">
        <a href="index.php" class="text-white text-decoration-none">Home</a> / 
        <a href="vehicles.php" class="text-white text-decoration-none">Fleet</a> / 
        <span><?php echo $v['model']; ?></span>
    </div>

    <div class="showcase-box animate__animated animate__fadeIn">
        <img src="uploads/vehicles/<?php echo $v['image']; ?>" alt="Vehicle Detail">
        <div class="floating-price">
            <span class="opacity-50 small d-block">Daily Rental Rate</span>
            <h2 class="fw-800 mb-0">₹<?php echo $v['price_per_day']; ?> <span class="fs-6 fw-normal">/ DAY</span></h2>
        </div>
    </div>

    <div class="row g-5">
        <div class="col-lg-8">
            <h1 class="display-3 fw-800 mb-2"><?php echo $v['brand'] . " " . $v['model']; ?></h1>
            <p class="badge bg-primary px-3 py-2 rounded-pill mb-4"><?php echo strtoupper($v['status']); ?></p>
            
            <h4 class="fw-700 mt-5 mb-4">Mastering the Road</h4>
            <p class="lead text-white-50">
                Experience the perfect blend of performance, technology, and luxury. This <?php echo $v['brand']; ?> is meticulously maintained and features high-end finishes, ensuring a premium driving experience. From the roar of its <?php echo $v['hp']; ?> HP engine to the intuitive dashboard, every detail is crafted for excellence.
            </p>

            <div class="row g-3 mt-5">
                <div class="col-md-3 col-6">
                    <div class="spec-card">
                        <i class="fas fa-tachometer-alt"></i>
                        <h5>Power</h5>
                        <p><?php echo $v['hp']; ?> HP</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="spec-card">
                        <i class="fas fa-cog"></i>
                        <h5>Type</h5>
                        <p><?php echo $v['transmission']; ?></p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="spec-card">
                        <i class="fas fa-gas-pump"></i>
                        <h5>Fuel</h5>
                        <p><?php echo $v['fuel_type']; ?></p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="spec-card">
                        <i class="fas fa-users"></i>
                        <h5>Capacity</h5>
                        <p><?php echo $v['seats']; ?> Seats</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="p-5 rounded-5 border border-white border-opacity-10 h-100" style="background: rgba(255,255,255,0.02);">
                <h4 class="fw-700 mb-4">Rental Includes</h4>
                <ul class="list-unstyled opacity-75">
                    <li class="mb-3"><i class="fas fa-check-circle text-info me-2"></i> Comprehensive Insurance</li>
                    <li class="mb-3"><i class="fas fa-check-circle text-info me-2"></i> 24/7 Roadside Assistance</li>
                    <li class="mb-3"><i class="fas fa-check-circle text-info me-2"></i> Free 100km per day</li>
                    <li class="mb-3"><i class="fas fa-check-circle text-info me-2"></i> Sanitized Interior</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="booking-bar animate__animated animate__slideInUp">
    <div>
        <h5 class="mb-0 fw-800"><?php echo $v['model']; ?></h5>
        <span class="text-white-50 small">Premium Subscription Available</span>
    </div>
    <div class="d-flex align-items: center; gap-4">
        <div class="text-end d-none d-md-block">
            <span class="d-block small opacity-50">Total per day</span>
            <span class="fw-bold fs-5">₹<?php echo $v['price_per_day']; ?></span>
        </div>
        <a href="booking.php?id=<?php echo $v_id; ?>" class="btn-reserve">Reserve Now</a>
    </div>
</div>

<?php include('includes/footer.php'); ?>