<?php
session_start();
require_once(__DIR__ . '/../includes/config.php');
require_once(__DIR__ . '/../includes/db.php');

// Security Check
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// --- DELETE USER LOGIC ---
if (isset($_GET['del'])) {
    $id = intval($_GET['del']);
    $del_query = "DELETE FROM users WHERE id = '$id'";
    if (mysqli_query($conn, $del_query)) {
        $_SESSION['success'] = "User account terminated successfully.";
    } else {
        $_SESSION['error'] = "Action failed: " . mysqli_error($conn);
    }
    header("Location: manage-users.php");
    exit();
}

// Stats for User Directory
$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id) as count FROM users"))['count'];
$new_users_today = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id) as count FROM users WHERE DATE(created_at) = CURDATE()"))['count'];

$users = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
include(__DIR__ . '/layout/sidebar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Elite Admin | Customer Directory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <style>
        :root { 
            --accent: #00f2fe; 
            --accent-glow: rgba(0, 242, 254, 0.15); 
            --card-bg: rgba(255, 255, 255, 0.02); 
            --border: rgba(255, 255, 255, 0.08);
        }
        
        body { background: #050505; color: #fff; font-family: 'Plus Jakarta Sans', sans-serif; overflow-x: hidden; }
        
        .main-content { margin-left: 280px; padding: 40px; transition: 0.3s; }

        /* Header Gradient */
        .text-gradient { background: linear-gradient(45deg, #fff, var(--accent)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }

        /* Stat Cards */
        .stat-pill {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 25px;
            padding: 20px 30px;
            backdrop-filter: blur(10px);
            transition: 0.4s;
        }
        .stat-pill:hover { border-color: var(--accent); transform: translateY(-5px); }

        /* User Card/Table Styling */
        .user-directory-card { 
            background: var(--card-bg); 
            backdrop-filter: blur(15px); 
            border: 1px solid var(--border); 
            border-radius: 35px; 
            padding: 35px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
        }

        .user-avatar { 
            width: 45px; height: 45px; 
            background: linear-gradient(135deg, var(--accent), #4facfe); 
            color: #000; border-radius: 14px; 
            display: flex; align-items: center; justify-content: center; 
            font-weight: 800; font-size: 1.1rem;
            box-shadow: 0 5px 15px rgba(0, 242, 254, 0.2);
        }

        .table { color: #fff; vertical-align: middle; }
        .table thead th { 
            background: transparent; 
            color: rgba(255,255,255,0.4); 
            border-bottom: 1px solid var(--border);
            padding: 15px; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1.5px; 
        }
        .table td { padding: 20px 15px; border-bottom: 1px solid rgba(255,255,255,0.02); }

        /* Action Buttons */
        .btn-delete { 
            width: 40px; height: 40px; border-radius: 12px; 
            background: rgba(255, 75, 92, 0.1); color: #ff4b5c; 
            border: 1px solid rgba(255, 75, 92, 0.2); 
            transition: 0.3s; 
        }
        .btn-delete:hover { background: #ff4b5c; color: #fff; transform: scale(1.1); box-shadow: 0 0 15px rgba(255, 75, 92, 0.4); }

        .status-indicator { width: 8px; height: 8px; border-radius: 50%; background: var(--accent); display: inline-block; margin-right: 8px; box-shadow: 0 0 10px var(--accent); }
    </style>
</head>
<body>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-5 animate__animated animate__fadeInDown">
        <div>
            <h1 class="fw-800 mb-1" style="font-size: 2.5rem;">Client <span class="text-gradient">Registry</span></h1>
            <p class="text-white-50 small text-uppercase" style="letter-spacing: 2px;">Authorized access to encrypted user databases.</p>
        </div>
        <div class="d-flex gap-3">
            <div class="stat-pill">
                <span class="text-white-50 small d-block">Total Members</span>
                <span class="fs-4 fw-800"><?php echo $total_users; ?></span>
            </div>
            <div class="stat-pill border-info" style="background: rgba(0, 242, 254, 0.05);">
                <span class="text-info small d-block fw-bold">New Today</span>
                <span class="fs-4 fw-800 text-info">+<?php echo $new_users_today; ?></span>
            </div>
        </div>
    </div>

    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success bg-dark text-success border-success mb-4 rounded-4 animate__animated animate__fadeIn">
            <i class="fas fa-user-check me-2"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <div class="user-directory-card animate__animated animate__fadeInUp">
        <div class="table-responsive">
            <table class="table table-dark">
                <thead>
                    <tr>
                        <th class="ps-3">Identity</th>
                        <th>Encrypted Email</th>
                        <th>Comms Link (Phone)</th>
                        <th>Joined Date</th>
                        <th class="text-end pe-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($users) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($users)): ?>
                        <tr>
                            <td class="ps-3">
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar me-3">
                                        <?php echo strtoupper(substr($row['name'], 0, 1)); ?>
                                    </div>
                                    <div>
                                        <div class="fw-bold fs-6"><?php echo htmlspecialchars($row['name']); ?></div>
                                        <div class="small text-white-50"><span class="status-indicator"></span>Verified User</div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-info fw-500"><?php echo $row['email']; ?></td>
                            <td><span class="opacity-75"><?php echo $row['phone']; ?></span></td>
                            <td>
                                <div class="small fw-bold">
                                    <?php echo (isset($row['created_at'])) ? date('d M, Y', strtotime($row['created_at'])) : "Legacy"; ?>
                                </div>
                                <div class="text-white-50" style="font-size: 0.7rem;">UTC Global Time</div>
                            </td>
                            <td class="text-end pe-3">
                                <a href="manage-users.php?del=<?php echo $row['id']; ?>" class="btn-delete d-inline-flex align-items-center justify-content-center" title="Terminate Account" onclick="return confirm('WARNING: This action cannot be undone. Terminate user account?')">
                                    <i class="fas fa-user-slash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center py-5 opacity-50">Zero active client records detected.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>