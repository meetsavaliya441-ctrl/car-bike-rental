<?php
session_start();
require_once(__DIR__ . '/../includes/config.php');
require_once(__DIR__ . '/../includes/db.php');

if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$b_id = mysqli_real_escape_string($conn, $_GET['id']);
$query = "SELECT bookings.*, vehicles.brand, vehicles.model, users.name as user_name, users.email 
          FROM bookings 
          JOIN vehicles ON bookings.vehicle_id = vehicles.id 
          JOIN users ON bookings.user_id = users.id
          WHERE bookings.id = '$b_id' AND bookings.user_id = '{$_SESSION['user_id']}'";

$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if(!$data) { die("Unauthorized access."); }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Invoice #<?php echo $data['id']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; font-family: 'Inter', sans-serif; }
        .invoice-box { max-width: 800px; margin: 50px auto; background: #fff; padding: 50px; border-radius: 20px; box-shadow: 0 0 20px rgba(0,0,0,0.1); }
        .brand-color { color: #00d2ff; font-weight: 800; }
    </style>
</head>
<body onload="window.print()">
    <div class="invoice-box">
        <div class="d-flex justify-content-between mb-5">
            <div>
                <h2 class="brand-color">ELITE RENTAL</h2>
                <p class="text-muted">Invoice #INV-<?php echo $data['id']; ?></p>
            </div>
            <div class="text-end">
                <h5><?php echo $data['user_name']; ?></h5>
                <p class="text-muted"><?php echo $data['email']; ?></p>
            </div>
        </div>
        <hr>
        <table class="table table-borderless my-4">
            <thead>
                <tr class="text-muted">
                    <th>Vehicle Details</th>
                    <th>Duration</th>
                    <th class="text-end">Total Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong><?php echo $data['brand'] . " " . $data['model']; ?></strong></td>
                    <td><?php echo $data['from_date']; ?> to <?php echo $data['to_date']; ?></td>
                    <td class="text-end"><strong>₹<?php echo number_format($data['total_price'], 2); ?></strong></td>
                </tr>
            </tbody>
        </table>
        <hr>
        <div class="text-center mt-5">
            <p class="mb-0">Thank you for choosing <strong>Elite Rental</strong>!</p>
            <p class="small text-muted">This is a computer-generated invoice.</p>
        </div>
    </div>
</body>
</html>