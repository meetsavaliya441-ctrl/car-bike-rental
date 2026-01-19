<?php
session_start();
require_once(__DIR__ . '/../includes/config.php');
require_once(__DIR__ . '/../includes/db.php');

// 1. Redirect agar admin pehle se logged in hai
if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit();
}

// 2. Login Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['admin_login'])) {
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = trim($_POST['password']);

    // Database se admin fetch karna (LIMIT 1 for speed)
    $query = "SELECT * FROM admins WHERE username = '$username' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $admin = mysqli_fetch_assoc($result);
        
        // Security check: Priority to Hashed password, then plain-text for legacy
        $auth_success = false;
        if (password_verify($password, $admin['password'])) {
            $auth_success = true;
        } elseif ($password === $admin['password']) {
            $auth_success = true;
        }

        if ($auth_success) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['username'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Security Breach: Identity verification failed.";
        }
    } else {
        $error = "Access Denied: Credential not recognized.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elite Admin | Secure Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <style>
        :root { --accent: #00f2fe; }
        body { 
            background: #050505 !important; 
            height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center;
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow: hidden;
            margin: 0;
        }

        .bg-glow {
            position: fixed; width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(0, 242, 254, 0.1) 0%, transparent 70%);
            z-index: -1; animation: pulse 8s infinite alternate;
        }
        @keyframes pulse { 0% { transform: scale(1); opacity: 0.4; } 100% { transform: scale(1.3); opacity: 0.7; } }

        .login-card {
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            padding: 50px 40px;
            border-radius: 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 50px 100px rgba(0,0,0,0.7);
            text-align: center;
        }

        .brand-icon {
            width: 75px; height: 75px; 
            background: rgba(0, 242, 254, 0.05);
            border: 1px solid var(--accent);
            border-radius: 24px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 30px;
            font-size: 2rem; color: var(--accent);
            text-shadow: 0 0 15px var(--accent);
        }

        .form-control {
            background: rgba(255, 255, 255, 0.04) !important;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            color: #fff !important;
            padding: 15px 20px;
            margin-bottom: 20px;
            transition: 0.3s;
        }
        .form-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 20px rgba(0, 242, 254, 0.15);
            outline: none;
        }

        .btn-login {
            background: linear-gradient(45deg, var(--accent), #4facfe);
            color: #000; border: none; padding: 16px; border-radius: 16px;
            font-weight: 800; width: 100%; text-transform: uppercase;
            letter-spacing: 1.5px; transition: 0.4s;
            margin-top: 10px;
        }
        .btn-login:hover { transform: translateY(-3px); box-shadow: 0 15px 35px rgba(0, 242, 254, 0.3); }

        .error-msg {
            background: rgba(255, 75, 92, 0.1);
            color: #ff4b5c; border: 1px solid rgba(255, 75, 92, 0.2);
            border-radius: 14px; padding: 12px; margin-bottom: 25px; font-size: 0.85rem;
        }
    </style>
</head>
<body>

<div class="bg-glow"></div>

<div class="login-card animate__animated animate__fadeIn">
    <div class="brand-icon">
        <i class="fas fa-shield-halved"></i>
    </div>
    
    <h2 class="fw-800 mb-1 text-white">Admin <span style="color: var(--accent)">Terminal</span></h2>
    <p class="text-white-50 small mb-4">Command center authorization required.</p>

    <?php if(isset($error)): ?>
        <div class="error-msg animate__animated animate__shakeX">
            <i class="fas fa-exclamation-triangle me-2"></i> <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" autocomplete="off">
        <div class="text-start">
            <label class="small text-white-50 ms-2 mb-1">Access Identity</label>
            <input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
        </div>
        
        <div class="text-start">
            <label class="small text-white-50 ms-2 mb-1">Secure Passkey</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>

        <button type="submit" name="admin_login" class="btn-login">
            Authenticate <i class="fas fa-key ms-2"></i>
        </button>
    </form>

    <div class="mt-4 pt-2">
        <a href="../index.php" class="text-white-50 small text-decoration-none">
            <i class="fas fa-arrow-left me-1"></i> Return to Main Fleet
        </a>
    </div>
</div>

</body>
</html>