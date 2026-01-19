<?php
session_start();
require_once(__DIR__ . '/../includes/config.php');
require_once(__DIR__ . '/../includes/db.php');

// Security Check
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// --- 1. Status Update Logic ---
if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = intval($_GET['id']);
    $status = mysqli_real_escape_string($conn, $_GET['status']);
    
    $update_query = "UPDATE bookings SET status = '$status' WHERE id = '$id'";
    if (mysqli_query($conn, $update_query)) {
        $_SESSION['success'] = "Booking #$id status updated to $status.";
    } else {
        $_SESSION['error'] = "Update failed: " . mysqli_error($conn);
    }
    header("Location: manage-bookings.php");
    exit();
}

// --- 2. Fixed SQL Query (Removed v.type to avoid error) ---
$query = "SELECT b.*, u.name as user_name, u.phone as user_phone, 
          v.brand, v.model 
          FROM bookings b 
          JOIN users u ON b.user_id = u.id 
          JOIN vehicles v ON b.vehicle_id = v.id 
          ORDER BY b.id DESC";

$bookings = mysqli_query($conn, $query);

// Error Handling agar query fail ho
if (!$bookings) {
    die("<div style='background:#111; color:#ff4b5c; padding:30px; border-radius:20px; font-family:sans-serif;'>
            <h3>Database Connection Error!</h3>
            <p>Reason: " . mysqli_error($conn) . "</p>
            <p>Please check if columns like 'total_price' or 'status' exist in your 'bookings' table.</p>
         </div>");
}

include(__DIR__ . '/layout/sidebar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Bookings | Elite Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <style>
        :root { --accent: #00f2fe; --card-bg: rgba(255, 255, 255, 0.02); }
        body { background: #050505; color: #fff; font-family: 'Plus Jakarta Sans', sans-serif; }
        .main-content { margin-left: 280px; padding: 40px; min-height: 100vh; }
        
        .booking-card { 
            background: var(--card-bg); 
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.08); 
            border-radius: 35px; 
            padding: 35px; 
        }
        
        .status-badge { padding: 6px 15px; border-radius: 50px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; }
        .status-confirmed { background: rgba(0, 242, 254, 0.1); color: var(--accent); border: 1px solid rgba(0, 242, 254, 0.2); }
        .status-pending { background: rgba(255, 193, 7, 0.1); color: #ffc107; border: 1px solid rgba(255, 193, 7, 0.2); }
        .status-cancelled { background: rgba(255, 75, 92, 0.1); color: #ff4b5c; border: 1px solid rgba(255, 75, 92, 0.2); }
        
        .table { color: #fff; vertical-align: middle; border-color: rgba(255,255,255,0.05); }
        .table thead th { border: none; color: rgba(255,255,255,0.4); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; padding-bottom: 20px; }
        
        .btn-action { width: 35px; height: 35px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; transition: 0.3s; }
        .btn-approve { background: rgba(0, 255, 136, 0.1); color: #00ff88; }
        .btn-reject { background: rgba(255, 75, 92, 0.1); color: #ff4b5c; }
        .btn-approve:hover { background: #00ff88; color: #000; }
        .btn-reject:hover { background: #ff4b5c; color: #fff; }
    </style>
</head>
<body>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-5 animate__animated animate__fadeInDown">
        <div>
            <h1 class="fw-800 mb-0">Booking <span style="color: var(--accent)">Ledger</span></h1>
            <p class="text-white-50">Review, authorize, or cancel client reservations.</p>
        </div>
    </div>

    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success bg-dark text-success border-success mb-4 rounded-4 animate__animated animate__fadeIn">
            <i class="fas fa-check-circle me-2"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    

    <div class="booking-card animate__animated animate__fadeInUp">
        <div class="table-responsive">
            <table class="table table-dark">
                <thead>
                    <tr>
                        <th class="ps-3">ID</th>
                        <th>Customer</th>
                        <th>Machine</th>
                        <th>Rental Window</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th class="text-end pe-3">Protocol</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($bookings) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($bookings)): ?>
                        <tr>
                            <td class="ps-3 text-white-50 small">#<?php echo $row['id']; ?></td>
                            <td>
                                <div class="fw-bold"><?php echo htmlspecialchars($row['user_name']); ?></div>
                                <div class="small text-white-50" style="font-size: 0.7rem;"><?php echo $row['user_phone']; ?></div>
                            </td>
                            <td>
                                <span class="text-info fw-bold"><?php echo $row['brand']; ?></span>
                                <div class="small opacity-50"><?php echo $row['model']; ?></div>
                            </td>
                            <td>
                                <div class="small fw-bold text-white-50">
                                    <i class="far fa-calendar-alt me-1 text-accent"></i>
                                    <?php echo date('d M', strtotime($row['from_date'])); ?> - <?php echo date('d M', strtotime($row['to_date'])); ?>
                                </div>
                            </td>
                            <td class="fw-800">₹<?php echo number_format($row['total_price'] ?? 0); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo strtolower($row['status']); ?>">
                                    <?php echo $row['status']; ?>
                                </span>
                            </td>
                            <td class="text-end pe-3">
                                <?php if($row['status'] == 'pending'): ?>
                                    <a href="manage-bookings.php?id=<?php echo $row['id']; ?>&status=confirmed" class="btn-action btn-approve me-1" title="Approve">
                                        <i class="fas fa-check"></i>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if($row['status'] != 'cancelled'): ?>
                                    <a href="manage-bookings.php?id=<?php echo $row['id']; ?>&status=cancelled" class="btn-action btn-reject" onclick="return confirm('Archive this transaction as cancelled?')" title="Reject">
                                        <i class="fas fa-times"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center py-5 text-white-50">Zero active bookings found in database.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>