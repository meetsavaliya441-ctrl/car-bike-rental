<?php
session_start();
// Config aur DB load karna
require_once(__DIR__ . '/../includes/config.php');
require_once(__DIR__ . '/../includes/db.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "user/login.php");
    exit();
}

include(__DIR__ . '/../includes/header.php');

// User ki latest details fetch karna
$u_id = $_SESSION['user_id'];
$user_query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$u_id'");
$user_data = mysqli_fetch_assoc($user_query);
?>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/style.css">
<style>
    :root { --accent: #00f2fe; --card-bg: rgba(255, 255, 255, 0.03); }
    body { background: #050505 !important; color: #fff; font-family: 'Plus Jakarta Sans', sans-serif; }

    .dashboard-wrapper { padding: 120px 0 80px; }
    
    /* Profile Sidebar */
    .profile-sidebar {
        background: var(--card-bg);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 40px;
        padding: 40px;
        text-align: center;
        backdrop-filter: blur(20px);
    }
    .avatar-box {
        width: 120px; height: 120px;
        background: linear-gradient(45deg, var(--accent), #4facfe);
        border-radius: 35px;
        margin: 0 auto 25px;
        display: flex; align-items: center; justify-content: center;
        font-size: 3rem; color: #000; font-weight: 800;
        box-shadow: 0 20px 40px rgba(0, 242, 254, 0.3);
    }

    /* Info Display */
    .info-group {
        background: rgba(255,255,255,0.02);
        border: 1px solid rgba(255,255,255,0.05);
        border-radius: 20px;
        padding: 20px 25px;
        margin-bottom: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .info-label { color: rgba(255,255,255,0.4); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; }
    .info-value { font-weight: 600; font-size: 1rem; }

    /* Quick Actions */
    .action-card {
        background: var(--card-bg);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 30px;
        padding: 30px;
        transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        text-decoration: none;
        color: #fff;
        display: block;
        height: 100%;
    }
    .action-card:hover { 
        border-color: var(--accent); 
        background: rgba(255, 242, 254, 0.05); 
        transform: translateY(-10px); 
        color: #fff;
        box-shadow: 0 15px 30px rgba(0,0,0,0.3);
    }
    .action-icon { font-size: 1.5rem; color: var(--accent); margin-bottom: 15px; }

    .btn-edit {
        background: transparent; border: 1px solid var(--accent);
        color: var(--accent); padding: 12px 30px; border-radius: 15px;
        font-weight: 700; width: 100%; transition: 0.3s;
        text-decoration: none; display: block;
    }
    .btn-edit:hover { background: var(--accent); color: #000; }
</style>

<div class="container dashboard-wrapper">
    <div class="row g-5">
        <div class="col-lg-4">
            <div class="profile-sidebar animate__animated animate__fadeInLeft">
                <div class="avatar-box">
                    <?php echo strtoupper(substr($user_data['name'], 0, 1)); ?>
                </div>
                <h3 class="fw-800 mb-1"><?php echo htmlspecialchars($user_data['name']); ?></h3>
                <p class="text-white-50 mb-4">Elite Member since <?php echo date('Y'); ?></p>
                
                <div class="text-start mb-4">
                    <div class="info-group">
                        <div>
                            <span class="info-label">Email Address</span>
                            <div class="info-value"><?php echo htmlspecialchars($user_data['email']); ?></div>
                        </div>
                        <i class="fas fa-envelope opacity-25"></i>
                    </div>
                    <div class="info-group">
                        <div>
                            <span class="info-label">Phone Number</span>
                            <div class="info-value"><?php echo htmlspecialchars($user_data['phone'] ?? 'Not set'); ?></div>
                        </div>
                        <i class="fas fa-phone opacity-25"></i>
                    </div>
                </div>
                
                <a href="<?php echo BASE_URL; ?>user/edit_profile.php" class="btn-edit">Edit Profile</a>
            </div>
        </div>

        <div class="col-lg-8">
            <h2 class="fw-800 mb-4 animate__animated animate__fadeInDown">Account Overview</h2>
            
            <div class="row g-4">
                <div class="col-md-6">
                    <a href="<?php echo BASE_URL; ?>user/my_bookings.php" class="action-card">
                        <div class="action-icon"><i class="fas fa-calendar-alt"></i></div>
                        <h4>My Bookings</h4>
                        <p class="text-white-50 small mb-0">View and manage your active reservations and rental history.</p>
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="<?php echo BASE_URL; ?>user/my_payments.php" class="action-card">
                        <div class="action-icon"><i class="fas fa-wallet"></i></div>
                        <h4>Payment History</h4>
                        <p class="text-white-50 small mb-0">Track your transactions, invoices, and download receipts.</p>
                    </a>
                </div>
                <div class="col-md-12">
                    <a href="<?php echo BASE_URL; ?>user/edit_profile.php" class="action-card" style="border-style: dashed;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="mb-1">Security Settings</h4>
                                <p class="text-white-50 small mb-0">Update your password and manage account security.</p>
                            </div>
                            <i class="fas fa-shield-alt fa-2x opacity-25"></i>
                        </div>
                    </a>
                </div>
            </div>

            <div class="mt-5 p-5 border border-white border-opacity-10 rounded-5 animate__animated animate__fadeInUp" style="background: rgba(255,255,255,0.01);">
                <h5 class="fw-bold mb-4"><i class="fas fa-lightbulb text-warning me-2"></i>Security Tip</h5>
                <p class="text-white-50 mb-0">Always ensure your contact details are up to date to receive important booking notifications and emergency support updates.</p>
            </div>
        </div>
    </div>
</div>

<?php include(__DIR__ . '/../includes/footer.php'); ?>