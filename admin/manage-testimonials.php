<?php
session_start();
require_once(__DIR__ . '/../includes/config.php');
require_once(__DIR__ . '/../includes/db.php');

// Security Check
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// --- 1. APPROVE LOGIC ---
if (isset($_GET['approve'])) {
    $id = intval($_GET['approve']);
    $query = "UPDATE testimonials SET status = 'approved' WHERE id = '$id'";
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Review is now live on the website!";
    }
    header("Location: manage-testimonials.php");
    exit();
}

// --- 2. DELETE LOGIC ---
if (isset($_GET['del'])) {
    $id = intval($_GET['del']);
    $query = "DELETE FROM testimonials WHERE id = '$id'";
    if (mysqli_query($conn, $query)) {
        $_SESSION['success'] = "Review deleted successfully.";
    }
    header("Location: manage-testimonials.php");
    exit();
}

// Fetch all reviews with User and Vehicle details
$query = "SELECT t.*, u.name as user_name, v.brand, v.model 
          FROM testimonials t 
          JOIN users u ON t.user_id = u.id 
          JOIN vehicles v ON t.vehicle_id = v.id 
          ORDER BY t.id DESC";
$results = mysqli_query($conn, $query);

include(__DIR__ . '/layout/sidebar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Reviews | Elite Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --accent: #00f2fe; --card-bg: rgba(255, 255, 255, 0.02); }
        body { background: #050505; color: #fff; font-family: 'Plus Jakarta Sans', sans-serif; }
        .main-content { margin-left: 280px; padding: 40px; }
        
        .review-card { background: var(--card-bg); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 30px; padding: 30px; }
        .status-badge { padding: 5px 12px; border-radius: 50px; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; }
        .status-pending { background: rgba(255, 193, 7, 0.1); color: #ffc107; border: 1px solid rgba(255, 193, 7, 0.2); }
        .status-approved { background: rgba(0, 242, 254, 0.1); color: var(--accent); border: 1px solid rgba(0, 242, 254, 0.2); }
        
        .star-active { color: #ffc107; }
        .btn-action { width: 35px; height: 35px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; transition: 0.3s; }
    </style>
</head>
<body>

<div class="main-content">
    <div class="mb-5">
        <h1 class="fw-800 mb-0">Client <span style="color: var(--accent)">Feedback</span></h1>
        <p class="text-white-50">Moderate and approve user testimonials for the homepage.</p>
    </div>

    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success bg-dark text-success border-success mb-4 rounded-4">
            <i class="fas fa-check-circle me-2"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    

    <div class="review-card">
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle">
                <thead>
                    <tr class="text-white-50 small text-uppercase">
                        <th>User</th>
                        <th>Vehicle</th>
                        <th>Rating</th>
                        <th width="30%">Message</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($results) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($results)): ?>
                        <tr>
                            <td>
                                <div class="fw-bold"><?php echo htmlspecialchars($row['user_name']); ?></div>
                                <div class="small opacity-50"><?php echo date('d M, Y', strtotime($row['created_at'])); ?></div>
                            </td>
                            <td>
                                <div class="text-info"><?php echo $row['brand']; ?></div>
                                <div class="small opacity-50"><?php echo $row['model']; ?></div>
                            </td>
                            <td>
                                <div class="star-active small">
                                    <?php for($i=1; $i<=5; $i++) {
                                        echo ($i <= $row['rating']) ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                                    } ?>
                                </div>
                            </td>
                            <td>
                                <small class="text-white-50"><?php echo htmlspecialchars($row['message']); ?></small>
                            </td>
                            <td>
                                <span class="status-badge status-<?php echo $row['status']; ?>">
                                    <?php echo $row['status']; ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <?php if($row['status'] == 'pending'): ?>
                                    <a href="manage-testimonials.php?approve=<?php echo $row['id']; ?>" class="btn-action btn-outline-info me-1" title="Approve">
                                        <i class="fas fa-check"></i>
                                    </a>
                                <?php endif; ?>
                                <a href="manage-testimonials.php?del=<?php echo $row['id']; ?>" class="btn-action btn-outline-danger" onclick="return confirm('Delete this review?')" title="Delete">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center py-5 opacity-50">No reviews submitted yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>