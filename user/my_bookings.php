<?php
// Session check sabse pehle
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Database aur Config files ke absolute paths
require_once(__DIR__ . '/../includes/config.php'); 
require_once(__DIR__ . '/../includes/db.php');

// 2. Auth check using BASE_URL
if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "login.php");
    exit();
}

// Header include
include(__DIR__ . '/../includes/header.php');

$u_id = $_SESSION['user_id'];

// 3. SQL Query with JOIN
$query = "SELECT b.*, v.brand, v.model, v.image, v.id as v_id 
          FROM bookings b 
          JOIN vehicles v ON b.vehicle_id = v.id 
          WHERE b.user_id = '$u_id' 
          ORDER BY b.id DESC";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}
?>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<style>
    :root { --accent: #00f2fe; --glass: rgba(255, 255, 255, 0.03); }
    body { background: #050505 !important; color: #fff; font-family: 'Plus Jakarta Sans', sans-serif; }

    .bookings-container { padding: 120px 0 80px; min-height: 80vh; }
    
    .booking-card-modern {
        background: var(--glass);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 30px;
        padding: 25px;
        margin-bottom: 25px;
        transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        backdrop-filter: blur(20px);
    }

    .booking-card-modern:hover {
        border-color: var(--accent);
        background: rgba(255, 255, 255, 0.07);
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 242, 254, 0.1);
    }

    .vehicle-thumb {
        width: 140px; height: 90px;
        object-fit: cover; border-radius: 18px;
        border: 1px solid rgba(255,255,255,0.1);
    }

    .status-pill {
        padding: 6px 16px; border-radius: 50px;
        font-size: 0.7rem; font-weight: 800;
        text-transform: uppercase; letter-spacing: 1px;
        display: inline-block;
        text-align: center;
    }

    .status-Confirmed { background: rgba(0, 255, 136, 0.1); color: #00ff88; border: 1px solid rgba(0, 255, 136, 0.3); }
    .status-Pending { background: rgba(255, 193, 7, 0.1); color: #ffc107; border: 1px solid rgba(255, 193, 7, 0.3); }
    .status-Cancelled { background: rgba(255, 75, 92, 0.1); color: #ff4b5c; border: 1px solid rgba(255, 75, 92, 0.3); }

    .booking-id { font-family: 'Monospace'; color: var(--accent); font-size: 0.85rem; opacity: 0.8; display: block; margin-bottom: 5px; }
    .date-label { color: rgba(255,255,255,0.4); font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px; }
    .date-value { font-weight: 600; font-size: 0.95rem; }

    .btn-action-group { display: flex; flex-direction: column; gap: 10px; min-width: 150px; }

    .btn-custom {
        padding: 10px 20px; border-radius: 12px;
        font-size: 0.8rem; text-decoration: none; transition: 0.3s;
        display: inline-flex; align-items: center; justify-content: center; gap: 8px;
        font-weight: 600;
        border: 1px solid transparent;
    }

    .btn-receipt {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        color: #fff;
    }
    .btn-receipt:hover:not(.disabled) { background: #fff; color: #000; }
    .btn-receipt.disabled { opacity: 0.3; cursor: not-allowed; pointer-events: none; }
</style>

<div class="container bookings-container">
    <div class="d-flex justify-content-between align-items-end mb-5 animate__animated animate__fadeIn">
        <div>
            <span class="text-info text-uppercase fw-bold small" style="letter-spacing: 3px;">Member Access</span>
            <h1 class="fw-800 mb-0 display-4">My <span style="color: var(--accent)">Garage</span></h1>
            <p class="text-white-50">Manage your luxury reservations and history.</p>
        </div>
        <a href="<?php echo BASE_URL; ?>vehicles.php" class="btn btn-outline-info rounded-pill px-4 py-2 fw-bold">
            <i class="fas fa-plus me-2"></i>New Booking
        </a>
    </div>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <div class="row">
            <div class="col-12">
                <?php while ($row = mysqli_fetch_assoc($result)): 
                    $status_raw = strtolower($row['status']); 
                    $status_display = ucfirst($status_raw); 
                ?>
                    <div class="booking-card-modern d-flex align-items-center flex-wrap animate__animated animate__fadeInUp">
                        <div class="me-4 mb-3 mb-md-0">
                            <img src="<?php echo BASE_URL; ?>uploads/vehicles/<?php echo $row['image']; ?>" class="vehicle-thumb" alt="Vehicle">
                        </div>

                        <div class="flex-grow-1 me-4 mb-3 mb-md-0">
                            <span class="booking-id">REF: #RE-<?php echo str_pad($row['id'], 5, "0", STR_PAD_LEFT); ?></span>
                            <h3 class="mb-0 fw-800 text-uppercase" style="letter-spacing: -1px;"><?php echo htmlspecialchars($row['brand'] . " " . $row['model']); ?></h3>
                        </div>

                        <div class="me-5 d-none d-lg-block text-center">
                            <div class="date-label">Rental Duration</div>
                            <div class="date-value">
                                <?php echo date('d M', strtotime($row['from_date'])); ?> - 
                                <?php echo date('d M, Y', strtotime($row['to_date'])); ?>
                            </div>
                        </div>

                        <div class="me-5 text-center">
                            <div class="date-label">Total Amount</div>
                            <div class="date-value text-info fs-5">₹<?php echo number_format($row['total_price']); ?></div>
                        </div>

                        <div class="btn-action-group">
                            <div class="text-center">
                                <span class="status-pill status-<?php echo $status_display; ?> mb-2 w-100">
                                    <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i> <?php echo $status_display; ?>
                                </span>
                            </div>
                            
                            <a href="<?php echo BASE_URL; ?>user/download_receipt.php?id=<?php echo $row['id']; ?>" 
                               class="btn-custom btn-receipt <?php echo ($status_raw != 'confirmed') ? 'disabled' : ''; ?>">
                                <i class="fas fa-file-invoice"></i> Receipt
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="text-center py-5 rounded-5 border border-white border-opacity-10 animate__animated animate__zoomIn" style="background: rgba(255,255,255,0.02); margin-top: 50px;">
            <i class="fas fa-car-side fa-4x mb-4 opacity-20 text-info"></i>
            <h2 class="fw-bold">Your garage is empty</h2>
            <p class="text-white-50">Luxury is waiting for you. Make your first reservation today.</p>
            <a href="<?php echo BASE_URL; ?>vehicles.php" class="btn btn-info mt-3 rounded-pill px-5 py-3 fw-800">Browse Elite Fleet</a>
        </div>
    <?php endif; ?>
</div>

<?php 
include(__DIR__ . '/../includes/footer.php'); 
?>