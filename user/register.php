<?php
session_start();
require_once(__DIR__ . '/../includes/config.php');
require_once(__DIR__ . '/../includes/db.php');

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "user/profile.php");
    exit();
}

// 1. REGISTRATION LOGIC
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register_btn'])) {
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Security Breach: Passwords do not match!";
    } else {
        $check_email = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");
        if (mysqli_num_rows($check_email) > 0) {
            $_SESSION['error'] = "Access Denied: Email already in system.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO users (name, email, phone, password) VALUES ('$name', '$email', '$phone', '$hashed_password')";
            
            if (mysqli_query($conn, $query)) {
                $_SESSION['success'] = "Profile Created. You may now authenticate.";
                header("Location: login.php");
                exit();
            } else {
                $_SESSION['error'] = "System Error: Registration failed.";
            }
        }
    }
}

include(__DIR__ . '/../includes/header.php');
?>

<style>
    :root { --accent: #00f2fe; --glass-border: rgba(255, 255, 255, 0.1); }
    body { background: #050505 !important; overflow-x: hidden; font-family: 'Plus Jakarta Sans', sans-serif; }

    /* Animated Cyber Background */
    .bg-glow {
        position: fixed; width: 600px; height: 600px;
        background: radial-gradient(circle, rgba(0, 242, 254, 0.08) 0%, transparent 70%);
        bottom: -100px; left: -100px; z-index: -1; animation: float 15s infinite alternate;
    }
    @keyframes float { 0% { transform: translate(0,0); } 100% { transform: translate(100px, -200px); } }

    .auth-container { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 100px 20px 50px; }

    .register-card {
        background: rgba(255, 255, 255, 0.01);
        backdrop-filter: blur(40px);
        -webkit-backdrop-filter: blur(40px);
        border: 1px solid var(--glass-border);
        padding: 50px;
        border-radius: 50px;
        width: 100%;
        max-width: 550px;
        box-shadow: 0 50px 100px rgba(0,0,0,0.8);
        position: relative;
    }

    .register-card::before {
        content: ''; position: absolute; top: -1px; right: 15%; width: 70%; height: 1px;
        background: linear-gradient(90deg, transparent, var(--accent), transparent);
    }

    .form-group { position: relative; margin-bottom: 20px; }
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
    .btn-submit:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0, 242, 254, 0.3); }

    .brand-icon {
        width: 70px; height: 70px; background: rgba(0, 242, 254, 0.1);
        border-radius: 22px; display: flex; align-items: center; justify-content: center;
        margin: 0 auto 25px; border: 1px solid var(--accent);
        font-size: 1.8rem; color: var(--accent);
    }
    
    .login-link { color: var(--accent); text-decoration: none; font-weight: 700; transition: 0.3s; }
    .login-link:hover { text-shadow: 0 0 10px var(--accent); }

    .alert-custom {
        background: rgba(255, 75, 92, 0.1); border: 1px solid rgba(255, 75, 92, 0.2);
        color: #ff4b5c; border-radius: 15px; padding: 12px; margin-bottom: 25px; font-size: 0.85rem;
    }
</style>

<div class="bg-glow"></div>

<div class="container auth-container">
    <div class="register-card animate__animated animate__fadeInUp">
        
        <div class="brand-icon">
            <i class="fas fa-user-plus"></i>
        </div>

        <div class="text-center mb-4">
            <h2 class="fw-800 m-0">New <span style="color: var(--accent)">Operator</span></h2>
            <p class="text-white-50 small mt-2">Initialize your credentials for the elite fleet.</p>
        </div>

        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert-custom animate__animated animate__shakeX">
                <i class="fas fa-shield-virus me-2"></i> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <div class="form-group">
                <input type="text" name="name" class="form-control" placeholder="Full Name" 
                       value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>" required>
                <i class="fas fa-id-badge"></i>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="email" name="email" class="form-control" placeholder="Email Address" 
                               value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
                        <i class="fas fa-envelope"></i>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="tel" name="phone" class="form-control" placeholder="Phone Number" 
                               value="<?php echo isset($phone) ? htmlspecialchars($phone) : ''; ?>" required>
                        <i class="fas fa-phone"></i>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="Secret Key" required>
                        <i class="fas fa-lock"></i>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <input type="password" name="confirm_password" class="form-control" placeholder="Repeat Key" required>
                        <i class="fas fa-shield-alt"></i>
                    </div>
                </div>
            </div>

            <button type="submit" name="register_btn" class="btn-submit">
                Initialize Profile <i class="fas fa-chevron-right ms-2"></i>
            </button>

            <div class="text-center mt-4">
                <p class="text-white-50 small mb-0">Already registered? 
                   <a href="login.php" class="login-link ms-1">Authorize Access</a>
                </p>
            </div>
        </form>
    </div>
</div>

<?php include(__DIR__ . '/../includes/footer.php'); ?>