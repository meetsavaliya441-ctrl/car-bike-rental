<?php
session_start();
require_once(__DIR__ . '/../includes/config.php');

// Auth Check
if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "user/login.php");
    exit();
}

// Default error message agar session mein na ho
$error_msg = isset($_SESSION['error']) ? $_SESSION['error'] : "Your transaction could not be processed at this time.";
unset($_SESSION['error']); // Message dikhane ke baad delete kar dein
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed | <?php echo APP_NAME; ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        :root { --error-red: #ff4b5c; }
        body { background: #050505; color: #fff; height: 100vh; display: flex; align-items: center; overflow: hidden; font-family: 'Plus Jakarta Sans', sans-serif; }
        
        .error-card {
            background: rgba(255, 75, 92, 0.02);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 75, 92, 0.1);
            border-radius: 50px;
            padding: 60px;
            text-align: center;
            max-width: 600px;
            margin: auto;
        }

        .error-icon {
            width: 120px; height: 120px;
            background: rgba(255, 75, 92, 0.1);
            border: 2px solid var(--error-red);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 30px;
            font-size: 3.5rem; color: var(--error-red);
            box-shadow: 0 0 50px rgba(255, 75, 92, 0.2);
        }

        .btn-retry {
            background: var(--error-red);
            color: #fff; border: none; padding: 15px 40px; border-radius: 18px;
            font-weight: 800; text-transform: uppercase; margin-top: 30px;
            transition: 0.3s; text-decoration: none; display: inline-block;
        }
        .btn-retry:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(255, 75, 92, 0.3); color: #fff; }

        .btn-support {
            background: transparent; border: 1px solid rgba(255,255,255,0.1);
            color: #fff; padding: 15px 40px; border-radius: 18px;
            font-weight: 800; text-transform: uppercase; margin-top: 30px; margin-left: 10px;
            transition: 0.3s; text-decoration: none; display: inline-block;
        }
        .btn-support:hover { background: rgba(255,255,255,0.05); }
    </style>
</head>
<body>

<div class="container">
    <div class="error-card animate__animated animate__headShake">
        <div class="error-icon">
            <i class="fas fa-times"></i>
        </div>
        
        <h1 class="fw-800 mb-3">Transaction Failed</h1>
        <p class="text-white-50 px-md-5 mb-4">
            <?php echo $error_msg; ?>
        </p>
        
        <div class="p-4 rounded-4" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05);">
            <ul class="text-start small text-white-50 mb-0">
                <li>Check your card details and try again.</li>
                <li>Ensure you have sufficient balance.</li>
                <li>Contact your bank if the issue persists.</li>
            </ul>
        </div>

        <div class="d-flex justify-content-center">
            <a href="<?php echo BASE_URL; ?>vehicles.php" class="btn-retry">
                <i class="fas fa-redo me-2"></i> Try Again
            </a>
            <a href="<?php echo BASE_URL; ?>contact.php" class="btn-support">
                Support
            </a>
        </div>
    </div>
</div>

</body>
</html>