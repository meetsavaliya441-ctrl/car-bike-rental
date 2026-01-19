<?php
session_start();
require_once(__DIR__ . '/../includes/config.php');
require_once(__DIR__ . '/../includes/db.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

// Data Fetching
$total_vehicles = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id) as count FROM vehicles"))['count'];
$total_bookings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id) as count FROM bookings"))['count'];
$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id) as count FROM users"))['count'];
$revenue_res = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_price) as total FROM bookings WHERE status='confirmed'"));
$total_revenue = $revenue_res['total'] ?? 0;

$recent_bookings = mysqli_query($conn, "
    SELECT b.*, u.name as user_name, v.brand, v.model 
    FROM bookings b 
    JOIN users u ON b.user_id = u.id 
    JOIN vehicles v ON b.vehicle_id = v.id 
    ORDER BY b.id DESC LIMIT 6
");

include(__DIR__ . '/layout/sidebar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Elite Admin | Analytics Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <style>
        :root { --accent: #00f2fe; --accent-glow: rgba(0, 242, 254, 0.15); --card-bg: rgba(255, 255, 255, 0.02); }
        body { background: #050505; color: #fff; font-family: 'Plus Jakarta Sans', sans-serif; }
        
        .main-content { margin-left: 280px; padding: 40px; transition: 0.3s; }

        .stat-card {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 35px;
            padding: 30px;
            position: relative;
            overflow: hidden;
            transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        
        .stat-card:hover { 
            border-color: var(--accent); 
            transform: translateY(-10px); 
            box-shadow: 0 25px 50px rgba(0,0,0,0.5), 0 0 20px var(--accent-glow);
        }

        .icon-box {
            width: 55px; height: 55px;
            background: rgba(255, 255, 255, 0.05);
            color: var(--accent);
            border-radius: 18px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem; margin-bottom: 25px;
            transition: 0.4s;
        }

        .stat-card:hover .icon-box { background: var(--accent); color: #000; transform: rotate(-10deg); }

        .table-card { 
            background: var(--card-bg); 
            border-radius: 35px; 
            border: 1px solid rgba(255, 255, 255, 0.08); 
            padding: 35px;
        }

        .table thead th { 
            background: transparent; 
            color: rgba(255,255,255,0.4); 
            border-bottom: 1px solid rgba(255,255,255,0.05);
            padding: 15px; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1.5px; 
        }

        .table td { padding: 20px 15px; border-bottom: 1px solid rgba(255,255,255,0.02); vertical-align: middle; }
        
        .status-badge { padding: 6px 16px; border-radius: 50px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; }
        .status-confirmed { background: rgba(0, 242, 254, 0.1); color: var(--accent); border: 1px solid rgba(0, 242, 254, 0.2); }
        .status-pending { background: rgba(255, 193, 7, 0.1); color: #ffc107; border: 1px solid rgba(255, 193, 7, 0.2); }

        .user-avatar { width: 35px; height: 35px; border-radius: 10px; margin-right: 12px; }
    </style>
</head>
<body>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div class="animate__animated animate__fadeInLeft">
            <h1 class="fw-800 mb-1" style="font-size: 2.5rem;">Command <span class="text-gradient" style="background: linear-gradient(45deg, #fff, var(--accent)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Center</span></h1>
            <p class="text-white-50">Operational intelligence & real-time analytics.</p>
        </div>
        <div class="d-flex align-items-center gap-4 animate__animated animate__fadeInRight">
            <div class="text-end">
                <h6 class="mb-0 fw-800 text-uppercase" style="letter-spacing: 1px;"><?php echo $_SESSION['admin_name']; ?></h6>
                <span class="badge bg-dark border border-secondary text-info mt-1" style="font-size: 0.6rem;">SUPER ADMIN</span>
            </div>
            <img src="https://ui-avatars.com/api/?name=Admin&background=00f2fe&color=000&bold=true" class="rounded-4 border border-secondary p-1" width="55">
        </div>
    </div>

    <div class="row g-4 mb-5 animate__animated animate__fadeInUp">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon-box"><i class="fas fa-rocket"></i></div>
                <p class="text-white-50 small mb-1">Total Fleet</p>
                <h2 class="fw-800"><?php echo $total_vehicles; ?> <small class="fs-6 opacity-50">Units</small></h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon-box"><i class="fas fa-bolt"></i></div>
                <p class="text-white-50 small mb-1">Bookings</p>
                <h2 class="fw-800"><?php echo $total_bookings; ?> <small class="fs-6 opacity-50">Total</small></h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon-box"><i class="fas fa-fingerprint"></i></div>
                <p class="text-white-50 small mb-1">Active Users</p>
                <h2 class="fw-800"><?php echo $total_users; ?> <small class="fs-6 opacity-50">Verified</small></h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="icon-box"><i class="fas fa-chart-pie"></i></div>
                <p class="text-white-50 small mb-1">Gross Revenue</p>
                <h2 class="fw-800 text-info">
                    <?php 
                        if($total_revenue >= 100000) {
                            echo '₹' . number_format($total_revenue / 100000, 2) . 'L';
                        } else {
                            echo '₹' . number_format($total_revenue / 1000, 1) . 'K';
                        }
                    ?>
                </h2>
            </div>
        </div>
    </div>

    <div class="table-card animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h4 class="fw-800 mb-0">Recent Reservations</h4>
                <p class="text-white-50 small mb-0">Latest 6 transactions from the portal.</p>
            </div>
            <a href="manage-bookings.php" class="btn btn-dark rounded-pill border-secondary px-4 py-2 small fw-bold" style="font-size: 0.8rem;">
                Explore All <i class="fas fa-external-link-alt ms-2" style="font-size: 0.7rem;"></i>
            </a>
        </div>
        
        <div class="table-responsive">
            <table class="table table-dark">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Vehicle Details</th>
                        <th>Schedule</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($recent_bookings)): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="https://ui-avatars.com/api/?name=<?php echo $row['user_name']; ?>&background=random" class="user-avatar">
                                <span class="fw-bold"><?php echo $row['user_name']; ?></span>
                            </div>
                        </td>
                        <td>
                            <span class="text-info fw-bold"><?php echo $row['brand']; ?></span> 
                            <span class="opacity-50"><?php echo $row['model']; ?></span>
                        </td>
                        <td>
                            <div class="small fw-bold"><?php echo date('M d', strtotime($row['from_date'])); ?></div>
                            <div class="text-white-50" style="font-size: 0.7rem;"><?php echo date('Y', strtotime($row['from_date'])); ?></div>
                        </td>
                        <td><span class="fw-800">₹<?php echo number_format($row['total_price']); ?></span></td>
                        <td>
                            <span class="status-badge <?php echo ($row['status'] == 'confirmed') ? 'status-confirmed' : 'status-pending'; ?>">
                                <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>
                                <?php echo $row['status']; ?>
                            </span>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>