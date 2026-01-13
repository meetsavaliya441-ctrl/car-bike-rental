<?php
session_start();
require_once(__DIR__ . '/../includes/config.php');
require_once(__DIR__ . '/../includes/db.php');

// 1. LOGIN LOGIC
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login_btn'])) {
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = trim($_POST['password']);

    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // HYBRID CHECK: Supports both Hashed and Plain-Text passwords for testing
        $login_granted = false;
        if (password_verify($password, $user['password'])) {
            $login_granted = true; // For Hashed Passwords (from Register page)
        } elseif ($password === $user['password']) {
            $login_granted = true; // For Plain-Text Passwords (manually added in DB)
        }

        if ($login_granted) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['success'] = "Authorize Success. Welcome back!";
            header("Location: " . BASE_URL . "user/profile.php");
            exit();
        } else {
            $_SESSION['error'] = "Authentication Failed: Incorrect Password.";
        }
    } else {
        $_SESSION['error'] = "Access Denied: Email not recognized.";
    }
}

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "user/profile.php");
    exit();
}

include(__DIR__ . '/../includes/header.php');
?>

<style>
    :root { --accent: #00f2fe; --glass-border: rgba(255, 255, 255, 0.1); }
    body { background: #050505 !important; overflow-x: hidden; }

    /* Animated Cyber Background */
    .bg-glow {
        position: fixed; width: 500px; height: 500px;
        background: radial-gradient(circle, rgba(0, 242, 254, 0.08) 0%, transparent 70%);
        top: -100px; right: -100px; z-index: -1; animation: float 15s infinite alternate;
    }
    @keyframes float { 0% { transform: translate(0,0); } 100% { transform: translate(-100px, 200px); } }

    .auth-container { min-height: 85vh; display: flex; align-items: center; justify-content: center; padding: 20px; }

    .login-card {
        background: rgba(255, 255, 255, 0.01);
        backdrop-filter: blur(40px);
        -webkit-backdrop-filter: blur(40px);
        border: 1px solid var(--glass-border);
        padding: 60px 50px;
        border-radius: 50px;
        width: 100%;
        max-width: 480px;
        box-shadow: 0 50px 100px rgba(0,0,0,0.8);
        position: relative;
    }

    .login-card::before {
        content: ''; position: absolute; top: -1px; left: 15%; width: 70%; height: 1px;
        background: linear-gradient(90deg, transparent, var(--accent), transparent);
    }

    .form-group { position: relative; margin-bottom: 25px; }
    .form-group i {
        position: absolute; left: 20px; top: 50%;
        transform: translateY(-50%); color: rgba(255,255,255,0.2);
        transition: 0.3s;
    }

    .form-control {
        background: rgba(255, 255, 255, 0.03) !important;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 18px;
        color: #fff !important;
        padding: 16px 20px 16px 55px;
        font-size: 0.95rem;
    }
    .form-control:focus {
        border-color: var(--accent);
        background: rgba(255, 255, 255, 0.06) !important;
        box-shadow: 0 0 25px rgba(0, 242, 254, 0.15);
    }
    .form-control:focus + i { color: var(--accent); }

    .btn-submit {
        background: linear-gradient(45deg, var(--accent), #4facfe);
        color: #000; border: none; padding: 18px; border-radius: 18px;
        font-weight: 800; text-transform: uppercase; letter-spacing: 1px;
        width: 100%; margin-top: 15px; transition: 0.4s;
    }
    .btn-submit:hover { transform: translateY(-5px); box-shadow: 0 15px 35px rgba(0, 242, 254, 0.3); }

    .brand-icon {
        width: 70px; height: 70px; background: rgba(0, 242, 254, 0.1);
        border-radius: 22px; display: flex; align-items: center; justify-content: center;
        margin: 0 auto 30px; border: 1px solid var(--accent);
        font-size: 1.8rem; color: var(--accent);
    }
    
    .signup-link { color: var(--accent); text-decoration: none; font-weight: 700; transition: 0.3s; }
    .signup-link:hover { text-shadow: 0 0 10px var(--accent); }
</style>

<div class="bg-glow"></div>

<div class="container auth-container">
    <div class="login-card animate__animated animate__zoomIn">
        
        <div class="brand-icon">
            <i class="fas fa-user-shield"></i>
        </div>

        <div class="text-center mb-5">
            <h2 class="fw-800 m-0">Secure <span style="color: var(--accent)">Login</span></h2>
            <p class="text-white-50 small mt-2">Unlock access to your premium garage.</p>
        </div>

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <div class="form-group">
                <input type="email" name="email" class="form-control" placeholder="Identity (Email)" 
                       value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
                <i class="fas fa-fingerprint"></i>
            </div>

            <div class="form-group">
                <input type="password" name="password" class="form-control" placeholder="Secret Key (Password)" required>
                <i class="fas fa-key"></i>
            </div>

            <div class="d-flex justify-content-between mb-4 px-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember">
                    <label class="form-check-label small text-white-50" for="remember">Keep Active</label>
                </div>
                <a href="#" class="small text-info text-decoration-none opacity-50">Forgot Pass?</a>
            </div>

            <button type="submit" name="login_btn" class="btn-submit">
                Access Granted <i class="fas fa-chevron-right ms-2"></i>
            </button>

            <div class="text-center mt-5">
                <p class="text-white-50 small mb-0">New Operator? 
                   <a href="register.php" class="signup-link ms-1">Create Profile</a>
                </p>
            </div>
        </form>
    </div>
</div>

<?php include(__DIR__ . '/../includes/footer.php'); ?>