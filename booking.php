<?php
session_start();
require_once(__DIR__ . '/includes/config.php');
require_once(__DIR__ . '/includes/db.php');

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please login to continue with the booking.";
    header("Location: " . BASE_URL . "user/login.php");
    exit();
}

include(__DIR__ . '/includes/header.php');

$v_id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : 0;

$query = "SELECT * FROM vehicles WHERE id = '$v_id'";
$result = mysqli_query($conn, $query);
$vehicle = mysqli_fetch_assoc($result);

if (!$vehicle) {
    echo "<div class='container mt-5 pt-5 text-center'><h1 class='text-white'>Vehicle not found!</h1><a href='".BASE_URL."vehicles.php' class='btn btn-info mt-3'>Go Back to Fleet</a></div>";
    include(__DIR__ . '/includes/footer.php');
    exit();
}
?>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/style.css">
    
<div class="container booking-wrapper">
    <div class="row g-5">
        <div class="col-lg-6">
            <div class="vehicle-preview animate__animated animate__fadeInLeft">
                <img src="uploads/vehicles/<?php echo $vehicle['image']; ?>" class="preview-img" alt="Vehicle">
                <div class="price-badge">₹<?php echo number_format($vehicle['price_per_day']); ?> / DAY</div>
                <div class="mt-5">
                    <h1 class="display-4 fw-800 mb-3"><?php echo $vehicle['brand'] . " " . $vehicle['model']; ?></h1>
                    <div class="d-flex flex-wrap gap-4 opacity-75 mb-4">
                        <span><i class="fas fa-gas-pump text-info me-2"></i><?php echo $vehicle['fuel_type']; ?></span>
                        <span><i class="fas fa-cog text-info me-2"></i><?php echo $vehicle['transmission']; ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="booking-card animate__animated animate__fadeInRight">
                <h2 class="fw-800 mb-4">Reservation Details</h2>
                
                <form action="<?php echo BASE_URL; ?>payment/payment.php" method="POST" id="bookingForm">
                    <input type="hidden" name="vehicle_id" value="<?php echo $v_id; ?>">
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label text-white-50 small">Pickup Date</label>
                            <input type="date" name="from_date" id="from_date" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label text-white-50 small">Return Date</label>
                            <input type="date" name="to_date" id="to_date" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-white-50 small">Pickup Location</label>
                        <select name="location" class="form-control" required>
                            <option value="">Select Pickup Point</option>
                            <option value="Airport Terminal 1">Airport Terminal 1</option>
                            <option value="City Center Hub">City Center Hub</option>
                        </select>
                    </div>

                    <div class="booking-summary mt-5 p-4 rounded-4" style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.05);">
                        <div class="d-flex justify-content-between mb-3">
                            <span>Base Rate</span>
                            <span>₹<?php echo number_format($vehicle['price_per_day']); ?>/day</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Rental Period</span>
                            <span id="day_count">0 Days</span>
                        </div>
                        <div class="d-flex justify-content-between border-top pt-3">
                            <span class="h5 fw-bold">Grand Total</span>
                            <span class="total-price-display" id="total_price_display">₹0</span>
                        </div>
                    </div>

                    <button type="submit" class="btn-confirm mt-4">Confirm Reservation <i class="fas fa-arrow-right ms-2"></i></button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const fromDateInput = document.getElementById('from_date');
    const toDateInput = document.getElementById('to_date');
    const dayCountLabel = document.getElementById('day_count');
    const totalPriceLabel = document.getElementById('total_price_display');
    const pricePerDay = <?php echo $vehicle['price_per_day']; ?>;

    fromDateInput.addEventListener('change', function() {
        toDateInput.min = this.value;
        calculateTotal();
    });

    function calculateTotal() {
        if (fromDateInput.value && toDateInput.value) {
            const start = new Date(fromDateInput.value);
            const end = new Date(toDateInput.value);
            const diffTime = end - start;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; 

            if (diffDays > 0) {
                dayCountLabel.innerText = diffDays + " Days";
                totalPriceLabel.innerText = "₹" + (diffDays * pricePerDay).toLocaleString('en-IN');
            } else {
                dayCountLabel.innerText = "0 Days";
                totalPriceLabel.innerText = "₹0";
            }
        }
    }

    toDateInput.addEventListener('change', calculateTotal);
</script>

<?php include(__DIR__ . '/includes/footer.php'); ?>