<?php
session_start();
require_once(__DIR__ . '/../includes/config.php');
require_once(__DIR__ . '/../includes/db.php');

// Auth Check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$u_id = $_SESSION['user_id'];

// 1. Fetch Current Data
$user_query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$u_id'");
$user = mysqli_fetch_assoc($user_query);

// 2. Processing Logic
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_profile'])) {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);

        $update = mysqli_query($conn, "UPDATE users SET name='$name', phone='$phone' WHERE id='$u_id'");
        if ($update) {
            $_SESSION['user_name'] = $name; // Session update
            $_SESSION['success'] = "Profile updated successfully!";
            header("Location: edit_profile.php");
            exit();
        }
    }

    if (isset($_POST['change_password'])) {
        $old_pass = $_POST['old_password'];
        $new_pass = $_POST['new_password'];
        $conf_pass = $_POST['confirm_password'];

        if (password_verify($old_pass, $user['password'])) {
            if ($new_pass === $conf_pass) {
                $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);
                mysqli_query($conn, "UPDATE users SET password='$hashed_pass' WHERE id='$u_id'");
                $_SESSION['success'] = "Password changed successfully!";
            } else {
                $_SESSION['error'] = "New passwords do not match.";
            }
        } else {
            $_SESSION['error'] = "Current password is incorrect.";
        }
        header("Location: edit_profile.php");
        exit();
    }
}

include(__DIR__ . '/../includes/header.php');
?>

<style>
    :root { --accent: #00f2fe; --card-bg: rgba(255, 255, 255, 0.03); }
    body { background: #050505 !important; }

    .settings-container { padding: 120px 0 80px; }
    
    .settings-card {
        background: var(--card-bg);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 35px;
        padding: 40px;
        backdrop-filter: blur(20px);
        height: 100%;
    }

    .form-control {
        background: rgba(255, 255, 255, 0.05) !important;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        color: #fff !important;
        padding: 12px 20px;
        margin-bottom: 20px;
    }

    .btn-update {
        background: var(--accent); color: #000; border: none;
        padding: 12px 30px; border-radius: 12px; font-weight: 700;
        transition: 0.3s; width: 100%;
    }
    .btn-update:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(0, 242, 254, 0.2); }
    
    .section-title { font-weight: 800; margin-bottom: 25px; display: flex; align-items: center; gap: 10px; }
    .section-title i { color: var(--accent); font-size: 1.2rem; }
</style>

<div class="container settings-container">
    <div class="row mb-5">
        <div class="col-12">
            <h1 class="fw-800">Account <span style="color: var(--accent)">Settings</span></h1>
            <p class="text-white-50">Manage your profile information and security preferences.</p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="settings-card animate__animated animate__fadeInLeft">
                <h4 class="section-title"><i class="fas fa-user-edit"></i> Personal Information</h4>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="small text-white-50 ms-2 mb-1">Full Name</label>
                        <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="small text-white-50 ms-2 mb-1">Email (Cannot be changed)</label>
                        <input type="email" class="form-control opacity-50" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                    </div>
                    <div class="mb-4">
                        <label class="small text-white-50 ms-2 mb-1">Phone Number</label>
                        <input type="tel" name="phone" class="form-control" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                    </div>
                    <button type="submit" name="update_profile" class="btn-update">Save Changes</button>
                </form>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="settings-card animate__animated animate__fadeInRight">
                <h4 class="section-title"><i class="fas fa-shield-alt"></i> Security & Password</h4>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="small text-white-50 ms-2 mb-1">Current Password</label>
                        <input type="password" name="old_password" class="form-control" placeholder="••••••••" required>
                    </div>
                    <div class="mb-3">
                        <label class="small text-white-50 ms-2 mb-1">New Password</label>
                        <input type="password" name="new_password" class="form-control" placeholder="••••••••" required>
                    </div>
                    <div class="mb-4">
                        <label class="small text-white-50 ms-2 mb-1">Confirm New Password</label>
                        <input type="password" name="confirm_password" class="form-control" placeholder="••••••••" required>
                    </div>
                    <button type="submit" name="change_password" class="btn-update" style="background: transparent; border: 1px solid var(--accent); color: var(--accent);">Update Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include(__DIR__ . '/../includes/footer.php'); ?>