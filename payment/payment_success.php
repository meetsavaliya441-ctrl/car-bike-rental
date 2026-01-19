<?php
session_start();
require_once(__DIR__ . '/../includes/config.php');

// Auth Check
if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "user/login.php");
    exit();
}

// Sirf tabhi dikhao jab koi success message session mein ho
if (!isset($_SESSION['success'])) {
    header("Location: " . BASE_URL . "user/my_bookings.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Successful | <?php echo APP_NAME; ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        :root { --accent: #00f2fe; }
        body { background: #050505; color: #fff; height: 100vh; display: flex; align-items: center; overflow: hidden; font-family: 'Plus Jakarta Sans', sans-serif; }
        
        .success-card {
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 50px;
            padding: 60px;
            text-align: center;
            max-width: 600px;
            margin: auto;
            position: relative;
            z-index: 10;
        }

        .check-circle {
            width: 120px; height: 120px;
            background: rgba(0, 255, 136, 0.1);
            border: 2px solid #00ff88;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 30px;
            font-size: 3.5rem; color: #00ff88;
            box-shadow: 0 0 50px rgba(0, 255, 136, 0.2);
        }

        .btn-garage {
            background: linear-gradient(45deg, var(--accent), #4facfe);
            color: #000; border: none; padding: 15px 40px; border-radius: 18px;
            font-weight: 800; text-transform: uppercase; margin-top: 30px;
            transition: 0.3s; text-decoration: none; display: inline-block;
        }
        .btn-garage:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0, 242, 254, 0.3); color: #000; }

        /* Confetti Animation Effect */
        .confetti { position: absolute; width: 10px; height: 10px; background: var(--accent); opacity: 0.7; border-radius: 50%; animation: fall 4s infinite linear; }
        @keyframes fall {
            0% { transform: translateY(-100vh) rotate(0deg); opacity: 1; }
            100% { transform: translateY(100vh) rotate(360deg); opacity: 0; }
        }
    </style>
</head>
<body>

<script>
    for(let i=0; i<30; i++) {
        let confetti = document.createElement('div');
        confetti.className = 'confetti';
        confetti.style.left = Math.random() * 100 + 'vw';
        confetti.style.animationDelay = Math.random() * 3 + 's';
        confetti.style.backgroundColor = ['#00f2fe', '#00ff88', '#ffffff'][Math.floor(Math.random()*3)];
        document.body.appendChild(confetti);
    }
</script>

<div class="container">
    <div class="success-card animate__animated animate__zoomIn">
        <div class="check-circle animate__animated animate__bounceIn animate__delay-1s">
            <i class="fas fa-check"></i>
        </div>
        
        <h1 class="fw-800 mb-3">Booking Confirmed!</h1>
        <p class="text-white-50 px-md-5">
            <?php echo $_SESSION['success']; ?>
        </p>
        
        <div class="mt-4 p-4 rounded-4" style="background: rgba(255,255,255,0.03); border: 1px dashed rgba(255,255,255,0.1);">
            <p class="small text-white-50 mb-1">We've sent the booking details and invoice to your registered email address.</p>
        </div>

        <a href="<?php echo BASE_URL; ?>user/my_bookings.php" class="btn-garage">
            Go to My Garage <i class="fas fa-arrow-right ms-2"></i>
        </a>
    </div>
</div>

</body>
</html>