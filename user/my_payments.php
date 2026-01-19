<?php
session_start();
require_once(__DIR__ . '/../includes/config.php');
require_once(__DIR__ . '/../includes/db.php');

// Auth Check
if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "user/login.php");
    exit();
}

include(__DIR__ . '/../includes/header.php');

$u_id = $_SESSION['user_id'];

// Total spending calculate karne ke liye query
$stats_query = mysqli_query($conn, "SELECT SUM(total_price) as total_spent, COUNT(id) as total_bookings FROM bookings WHERE user_id = '$u_id'");
$stats = mysqli_fetch_assoc($stats_query);

// Sabhi bookings fetch karna (Payment details ke liye)
$query = "SELECT bookings.*, vehicles.brand, vehicles.model 
          FROM bookings 
          JOIN vehicles ON bookings.vehicle_id = vehicles.id 
          WHERE bookings.user_id = '$u_id' 
          ORDER BY bookings.id DESC";
$result = mysqli_query($conn, $query);
?>

<style>
    :root { --accent: #00f2fe; --glass: rgba(255, 255, 255, 0.03); }
    body { background: #050505 !important; color: #fff; }

    .payments-container { padding: 120px 0 80px; }
    
    .stats-card {
        background: linear-gradient(135deg, rgba(0, 242, 254, 0.1), rgba(79, 172, 254, 0.1));
        border: 1px solid rgba(0, 242, 254, 0.2);
        border-radius: 30px; padding: 30px; margin-bottom: 40px;
        backdrop-filter: blur(20px);
    }

    .table-custom { background: var(--glass); border-radius: 30px; overflow: hidden; border: 1px solid rgba(255,255,255,0.08); }
    .table-custom thead { background: rgba(255,255,255,0.05); }
    .table-custom th { border: none; padding: 20px; color: rgba(255,255,255,0.4); font-size: 0.8rem; text-transform: uppercase; }
    .table-custom td { border-top: 1px solid rgba(255,255,255,0.05); padding: 20px; vertical-align: middle; }

    .payment-status {
        padding: 5px 15px; border-radius: 50px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase;
    }
    .status-paid { background: rgba(0, 255, 136, 0.1); color: #00ff88; border: 1px solid rgba(0, 255, 136, 0.2); }

    .btn-invoice {
        color: var(--accent); text-decoration: none; font-weight: 600; font-size: 0.9rem;
        transition: 0.3s; padding: 8px 15px; border-radius: 10px;
    }
    .btn-invoice:hover { background: rgba(0, 242, 254, 0.1); }
</style>

<div class="container payments-container">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="fw-800 mb-1">Payment <span style="color: var(--accent)">History</span></h1>
            <p class="text-white-50">View all your transactions and download invoices.</p>
        </div>
        <div class="stats-card m-0 py-3 px-4">
            <div class="small text-white-50 text-uppercase">Total Invested</div>
            <h3 class="fw-800 mb-0 text-info">₹<?php echo number_format($stats['total_spent'] ?? 0); ?></h3>
        </div>
    </div>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <div class="table-custom animate__animated animate__fadeIn">
            <table class="table table-dark mb-0">
                <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>Vehicle</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th class="text-end">Invoice</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><span class="font-monospace opacity-50 text-uppercase">#TRX-<?php echo $row['id']; ?></span></td>
                            <td>
                                <div class="fw-bold"><?php echo $row['brand'] . " " . $row['model']; ?></div>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($row['booking_date'])); ?></td>
                            <td class="fw-bold text-info">₹<?php echo number_format($row['total_price']); ?></td>
                            <td><span class="payment-status status-paid">Completed</span></td>
                            <td class="text-end">
                                <a href="download_receipt.php?id=<?php echo $row['id']; ?>" class="btn-invoice">
                                    <i class="fas fa-file-download me-2"></i>PDF
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="text-center py-5 rounded-5 border border-white border-opacity-10" style="background: rgba(255,255,255,0.02);">
            <i class="fas fa-wallet fa-3x mb-3 opacity-25"></i>
            <h3>No Transactions Yet</h3>
            <p class="text-white-50">Once you book a vehicle, your payment history will appear here.</p>
            <a href="<?php echo BASE_URL; ?>vehicles.php" class="btn btn-outline-info mt-3 rounded-pill px-5">Explore Fleet</a>
        </div>
    <?php endif; ?>
</div>

<?php include(__DIR__ . '/../includes/footer.php'); ?>