<?php
session_start();
require_once(__DIR__ . '/../includes/config.php');
require_once(__DIR__ . '/../includes/db.php');

// Security Check
if (!isset($_SESSION['admin_id'])) { 
    header("Location: login.php"); 
    exit(); 
}

// --- 0. AUTO-CREATE TABLE IF MISSING ---
// Yeh block error ko prevent karega agar table nahi bani hui hai to
$table_check = "CREATE TABLE IF NOT EXISTS brands (
    id INT AUTO_INCREMENT PRIMARY KEY,
    brand_name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
mysqli_query($conn, $table_check);

// --- 1. ADD BRAND LOGIC ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_brand'])) {
    $brand_name = mysqli_real_escape_string($conn, trim($_POST['brand_name']));
    
    if(!empty($brand_name)) {
        $query = "INSERT INTO brands (brand_name) VALUES ('$brand_name')";
        if(mysqli_query($conn, $query)) {
            $_SESSION['success'] = "Brand '$brand_name' added!";
        }
    }
    header("Location: manage-brands.php");
    exit();
}

// --- 2. DELETE BRAND LOGIC ---
if (isset($_GET['del'])) {
    $id = intval($_GET['del']);
    mysqli_query($conn, "DELETE FROM brands WHERE id = '$id'");
    $_SESSION['success'] = "Brand deleted!";
    header("Location: manage-brands.php");
    exit();
}

// --- 3. FETCH BRANDS (Safety Check added) ---
$brands = mysqli_query($conn, "SELECT * FROM brands ORDER BY id DESC");

// Agar query abhi bhi fail ho rahi hai (e.g. Database connection issue)
if (!$brands) {
    die("Database Error: " . mysqli_error($conn));
}

include(__DIR__ . '/layout/sidebar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Brands | Elite Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --accent: #00f2fe; --card-bg: rgba(255, 255, 255, 0.02); }
        body { background: #050505; color: #fff; font-family: 'Plus Jakarta Sans', sans-serif; }
        .main-content { margin-left: 280px; padding: 40px; }
        .glass-card { background: var(--card-bg); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 25px; padding: 25px; }
        .form-control { background: rgba(255,255,255,0.05) !important; border: 1px solid rgba(255,255,255,0.1) !important; color: #fff !important; }
        .btn-brand { background: var(--accent); color: #000; font-weight: 700; border: none; border-radius: 10px; padding: 10px; }
    </style>
</head>
<body>

<div class="main-content">
    <h1 class="fw-bold mb-4">Manage <span style="color: var(--accent)">Brands</span></h1>

    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success bg-dark text-success border-success mb-4 rounded-3">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-4">
            <div class="glass-card">
                <form action="" method="POST">
                    <label class="mb-2 small opacity-50">New Brand Name</label>
                    <input type="text" name="brand_name" class="form-control mb-3" required>
                    <button type="submit" name="add_brand" class="btn-brand w-100">Add Brand</button>
                </form>
            </div>
        </div>

        <div class="col-md-8">
            <div class="glass-card">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th>#ID</th>
                            <th>Brand Name</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        // Line 131 fix: Check agar query result sahi hai
                        if ($brands && mysqli_num_rows($brands) > 0): 
                            while($row = mysqli_fetch_assoc($brands)): 
                        ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td class="fw-bold"><?php echo htmlspecialchars($row['brand_name']); ?></td>
                            <td class="text-end">
                                <a href="manage-brands.php?del=<?php echo $row['id']; ?>" class="text-danger" onclick="return confirm('Delete this brand?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; else: ?>
                            <tr><td colspan="3" class="text-center opacity-50">No brands found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>