<?php
session_start();
// absolute paths fix for subfolder
require_once(__DIR__ . '/../includes/config.php');
require_once(__DIR__ . '/../includes/db.php');

// 1. Auth Check
if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "user/login.php");
    exit();
}

// 2. Form Submission Check
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Data sanitize karna
    $user_id = $_SESSION['user_id'];
    $vehicle_id = mysqli_real_escape_string($conn, $_POST['vehicle_id']);
    $from_date = mysqli_real_escape_string($conn, $_POST['from_date']);
    $to_date = mysqli_real_escape_string($conn, $_POST['to_date']);
    $pay_method = mysqli_real_escape_string($conn, $_POST['payment_method'] ?? 'Online');
    
    // 3. Price Validation (Security: DB se price lena hamesha behtar hai)
    $v_res = mysqli_query($conn, "SELECT price_per_day FROM vehicles WHERE id = '$vehicle_id'");
    $v_data = mysqli_fetch_assoc($v_res);
    
    if (!$v_data) {
        $_SESSION['error'] = "Vehicle not found.";
        header("Location: " . BASE_URL . "vehicles.php");
        exit();
    }

    $price_per_day = $v_data['price_per_day'];
    $date1 = new DateTime($from_date);
    $date2 = new DateTime($to_date);
    $days = $date1->diff($date2)->days ?: 1;
    $total_price = ($days * $price_per_day) + 250 + (($days * $price_per_day) * 0.05); // Base + Fee + Tax

    // 4. Database Entry (Background mein save kar rahe hain)
    $query = "INSERT INTO bookings (user_id, vehicle_id, from_date, to_date, total_price, status) 
              VALUES ('$user_id', '$vehicle_id', '$from_date', '$to_date', '$total_price', 'confirmed')";
    
    $db_save = mysqli_query($conn, $query);

} else {
    header("Location: " . BASE_URL . "index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Processing Transaction...</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        body { background: #050505; color: #fff; height: 100vh; display: flex; align-items: center; justify-content: center; font-family: 'Plus Jakarta Sans', sans-serif; overflow: hidden; }
        .loader-content { text-align: center; max-width: 400px; }
        .spin-container { position: relative; width: 100px; height: 100px; margin: 0 auto 30px; }
        .outer-ring { position: absolute; width: 100%; height: 100%; border: 4px solid rgba(0, 242, 254, 0.1); border-top: 4px solid #00f2fe; border-radius: 50%; animation: spin 1s linear infinite; }
        .inner-icon { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 2rem; color: #00f2fe; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        .badge-secure { background: rgba(0, 255, 136, 0.1); color: #00ff88; padding: 8px 20px; border-radius: 50px; font-size: 0.75rem; font-weight: 700; border: 1px solid rgba(0, 255, 136, 0.2); margin-top: 20px; display: inline-block; }
    </style>
</head>
<body>

<div class="loader-content animate__animated animate__fadeIn">
    <div class="spin-container">
        <div class="outer-ring"></div>
        <div class="inner-icon"><i class="fas fa-shield-alt"></i></div>
    </div>
    
    <h3 class="fw-800 mb-2">Processing via <?php echo strtoupper($pay_method); ?></h3>
    <p class="text-white-50 small px-4">Verifying your transaction with the bank. Please do not close this window.</p>
    
    <div class="badge-secure">
        <i class="fas fa-lock me-2"></i> 256-BIT ENCRYPTION SECURED
    </div>
</div>

<script>
    // 3.5 Seconds ka demo delay taaki payment real lage
    setTimeout(function() {
        <?php if($db_save): ?>
            window.location.href = 'payment_success.php';
        <?php else: ?>
            window.location.href = 'payment_failed.php';
        <?php endif; ?>
    }, 3500);
</script>

</body>
</html>