<?php
session_start();
require_once('includes/config.php');
require_once('includes/db.php');
require_once('includes/auth.php'); 

if (function_exists('checkUser')) { checkUser(); }
include('includes/header.php');

// Filter Logic pakadne ke liye
$filter = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : 'all';

// Query ko filter ke hisaab se adjust karna
$sql = "SELECT * FROM vehicles WHERE status = 'available'";

if ($filter == 'car') {
    $sql .= " AND type = 'car'";
} elseif ($filter == 'bike') {
    $sql .= " AND type = 'bike'";
} elseif ($filter == 'suv') {
    $sql .= " AND type = 'suv'";
}

$result = mysqli_query($conn, $sql);
?>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="assets/style.css">


<div class="container fleet-header">
    <h1>Our <span style="color: var(--accent)">Elite</span> Fleet</h1>
    <p class="text-white-50">Select from the finest range of luxury cars and high-performance bikes.</p>
</div>

<div class="container mb-5">
    <div class="filter-pills">
        <a href="vehicles.php?category=all" class="filter-btn <?php echo ($filter == 'all') ? 'active' : ''; ?>">All Rides</a>
        <a href="vehicles.php?category=car" class="filter-btn <?php echo ($filter == 'car') ? 'active' : ''; ?>">Luxury Cars</a>
        <a href="vehicles.php?category=bike" class="filter-btn <?php echo ($filter == 'bike') ? 'active' : ''; ?>">Sports Bikes</a>
        <!-- <a href="vehicles.php?category=suv" class="filter-btn <?php echo ($filter == 'suv') ? 'active' : ''; ?>">SUVs</a> -->
    </div>
</div>

<div class="container pb-5">
    <div class="row g-4">
        <?php if(mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
            <div class="col-lg-4 col-md-6">
                <div class="vehicle-card">      
                    <div class="img-container">
                        <img src="uploads/vehicles/<?php echo $row['image']; ?>" alt="Vehicle">
                        <div class="price-tag">₹<?php echo number_format($row['price_per_day']); ?>/day</div>
                    </div>
                    
                    <div class="card-body-custom">
                        <h3 class="vehicle-title"><?php echo htmlspecialchars($row['brand'] . " " . $row['model']); ?></h3>
                        
                        <div class="specs-grid">
                            <div class="spec-item"><i class="fas fa-gas-pump"></i> <?php echo $row['fuel_type']; ?></div>
                            <div class="spec-item"><i class="fas fa-cog"></i> <?php echo $row['transmission']; ?></div>
                            <div class="spec-item"><i class="fas fa-users"></i> <?php echo $row['seats']; ?> Seats</div>
                            <div class="spec-item"><i class="fas fa-bolt"></i> <?php echo $row['hp']; ?> HP</div>
                        </div>

                        <a href="booking.php?id=<?php echo $row['id']; ?>" class="btn-book">Book Now</a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <h3 class="opacity-50">No vehicles found in this category.</h3>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include('includes/footer.php'); ?>