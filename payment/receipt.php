<?php
session_start();
require_once(__DIR__ . '/../includes/config.php');
require_once(__DIR__ . '/../includes/db.php');

// Auth Check
if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "user/login.php");
    exit();
}

// Booking ID get karna
if (!isset($_GET['id'])) {
    header("Location: my_bookings.php");
    exit();
}

$booking_id = mysqli_real_escape_string($conn, $_GET['id']);
$user_id = $_SESSION['user_id'];

// Data fetch karna with User and Vehicle details
$query = "SELECT bookings.*, vehicles.brand, vehicles.model, vehicles.price_per_day, users.name, users.email, users.phone 
          FROM bookings 
          JOIN vehicles ON bookings.vehicle_id = vehicles.id 
          JOIN users ON bookings.user_id = users.id 
          WHERE bookings.id = '$booking_id' AND bookings.user_id = '$user_id'";

$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("Unauthorized Access or Invoice Not Found.");
}

// Days calculate karna
$d1 = new DateTime($data['from_date']);
$d2 = new DateTime($data['to_date']);
$days = $d1->diff($d2)->days ?: 1;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice_<?php echo $booking_id; ?>_EliteRental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700;800&display=swap');
        
        body { background: #f0f0f0; font-family: 'Inter', sans-serif; color: #333; }
        .invoice-card { 
            background: #fff; max-width: 850px; margin: 40px auto; 
            padding: 60px; border-radius: 0; box-shadow: 0 0 30px rgba(0,0,0,0.1);
            position: relative; border-top: 10px solid #000;
        }
        .brand-logo { font-weight: 800; font-size: 1.8rem; letter-spacing: -1px; color: #000; }
        .brand-logo span { color: #00f2fe; }
        .invoice-label { color: #888; text-transform: uppercase; font-size: 0.75rem; font-weight: 700; letter-spacing: 1px; }
        .table-invoice thead { background: #f8f9fa; }
        .table-invoice th { border: none; padding: 15px; font-size: 0.85rem; }
        .table-invoice td { padding: 20px 15px; vertical-align: middle; border-bottom: 1px solid #eee; }
        .total-section { background: #000; color: #fff; padding: 30px; border-radius: 10px; margin-top: 30px; }
        
        @media print {
            body { background: #fff; }
            .invoice-card { margin: 0; box-shadow: none; width: 100%; max-width: 100%; }
            .btn-print { display: none; }
        }
    </style>
</head>
<body>

<div class="container text-center mt-4 btn-print">
    <button onclick="window.print()" class="btn btn-dark px-4 py-2 rounded-pill">
        <i class="fas fa-print me-2"></i> Print Invoice
    </button>
    <a href="my_bookings.php" class="btn btn-outline-secondary px-4 py-2 rounded-pill ms-2">Back to Garage</a>
</div>

<div class="invoice-card">
    <div class="row align-items-center mb-5">
        <div class="col-sm-6">
            <div class="brand-logo">ELITE<span>RENTAL</span></div>
            <p class="text-muted small mb-0"><?php echo CONTACT_ADDR; ?></p>
            <p class="text-muted small"><?php echo CONTACT_EMAIL; ?></p>
        </div>
        <div class="col-sm-6 text-sm-end">
            <h1 class="fw-800 mb-1">INVOICE</h1>
            <p class="mb-0 invoice-label">Transaction ID</p>
            <p class="fw-bold">#TRX-<?php echo str_pad($data['id'], 6, '0', STR_PAD_LEFT); ?></p>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-6">
            <p class="invoice-label mb-1">Billed To</p>
            <h5 class="fw-bold mb-0"><?php echo $data['name']; ?></h5>
            <p class="text-muted small mb-0"><?php echo $data['email']; ?></p>
            <p class="text-muted small"><?php echo $data['phone']; ?></p>
        </div>
        <div class="col-6 text-end">
            <p class="invoice-label mb-1">Date Issued</p>
            <p class="fw-bold"><?php echo date('M d, Y', strtotime($data['booking_date'])); ?></p>
        </div>
    </div>

    <table class="table table-invoice mb-4">
        <thead>
            <tr class="invoice-label">
                <th>Description</th>
                <th class="text-center">Rate</th>
                <th class="text-center">Duration</th>
                <th class="text-end">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <h6 class="fw-bold mb-1"><?php echo $data['brand'] . " " . $data['model']; ?></h6>
                    <p class="text-muted small mb-0">Luxury Rental - <?php echo $data['from_date']; ?> to <?php echo $data['to_date']; ?></p>
                </td>
                <td class="text-center">₹<?php echo number_format($data['price_per_day']); ?>/day</td>
                <td class="text-center"><?php echo $days; ?> Days</td>
                <td class="text-end fw-bold">₹<?php echo number_format($data['total_price']); ?></td>
            </tr>
        </tbody>
    </table>

    <div class="row justify-content-end">
        <div class="col-md-5">
            <div class="total-section">
                <div class="d-flex justify-content-between mb-2">
                    <span class="opacity-75">Subtotal</span>
                    <span>₹<?php echo number_format($data['total_price']); ?></span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="opacity-75">Taxes (GST 0%)</span>
                    <span>₹0</span>
                </div>
                <div class="d-flex justify-content-between border-top pt-3">
                    <h4 class="fw-bold mb-0">Grand Total</h4>
                    <h4 class="fw-bold mb-0">₹<?php echo number_format($data['total_price']); ?></h4>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5 pt-5 text-center border-top">
        <p class="small text-muted mb-0">Thank you for riding with Elite Rental.</p>
        <p class="small text-muted">This is a computer-generated document, no signature required.</p>
    </div>
</div>

<script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>
</html>