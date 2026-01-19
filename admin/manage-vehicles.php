<?php
session_start();
require_once(__DIR__ . '/../includes/config.php');
require_once(__DIR__ . '/../includes/db.php');

// Security Check
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

// --- 1. DELETE LOGIC ---
if (isset($_GET['del'])) {
    $id = intval($_GET['del']);
    $res = mysqli_query($conn, "SELECT image FROM vehicles WHERE id='$id'");
    if($row = mysqli_fetch_assoc($res)) {
        $file_path = "../uploads/vehicles/" . $row['image'];
        if(file_exists($file_path)) { unlink($file_path); }
        mysqli_query($conn, "DELETE FROM vehicles WHERE id='$id'");
        $_SESSION['success'] = "Unit decommissioned successfully.";
    }
    header("Location: manage-vehicles.php");
    exit();
}

// --- 2. ADD VEHICLE LOGIC ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_vehicle'])) {
    $brand = mysqli_real_escape_string($conn, $_POST['brand'] ?? '');
    $model = mysqli_real_escape_string($conn, $_POST['model'] ?? '');
    $type  = mysqli_real_escape_string($conn, $_POST['type'] ?? 'car'); 
    $price = mysqli_real_escape_string($conn, $_POST['price'] ?? 0);
    $fuel  = mysqli_real_escape_string($conn, $_POST['fuel'] ?? 'Petrol');
    
    $dir = "../uploads/vehicles/";
    if (!is_dir($dir)) { mkdir($dir, 0777, true); }

    if (!empty($_FILES['image']['name'])) {
        $image_name = time() . "_" . preg_replace("/[^a-zA-Z0-9.]/", "_", $_FILES['image']['name']);
        $target = $dir . basename($image_name);

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $sql = "INSERT INTO vehicles (brand, model, type, price_per_day, fuel_type, image) 
                    VALUES ('$brand', '$model', '$type', '$price', '$fuel', '$image_name')";
            
            if(mysqli_query($conn, $sql)) {
                $_SESSION['success'] = "New machine registered: $brand $model";
                header("Location: manage-vehicles.php");
                exit();
            }
        }
    }
}

$vehicles = mysqli_query($conn, "SELECT * FROM vehicles ORDER BY id DESC");
include(__DIR__ . '/layout/sidebar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Elite Admin | Fleet Control</title>
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

        /* Header Style */
        .text-gradient { background: linear-gradient(45deg, #fff, var(--accent)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }

        /* Fleet Card Styling */
        .fleet-card { 
            background: var(--card-bg); 
            backdrop-filter: blur(15px); 
            border: 1px solid var(--border); 
            border-radius: 35px; 
            padding: 35px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
        }

        /* Form Controls */
        .custom-input, .custom-select {
            background: rgba(255,255,255,0.05) !important;
            color: #fff !important;
            border: 1px solid var(--border) !important;
            border-radius: 15px !important;
            padding: 12px 18px !important;
        }
        
        .custom-select option { background: #111; color: #fff; }

        /* Table Styling */
        .table { color: #fff; vertical-align: middle; }
        .table thead th { 
            background: transparent; 
            color: rgba(255,255,255,0.4); 
            border-bottom: 1px solid var(--border);
            padding: 15px; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1.5px; 
        }
        .table td { padding: 20px 15px; border-bottom: 1px solid rgba(255,255,255,0.02); }

        /* Vehicle Image Hover */
        .vehicle-img-preview { 
            width: 100px; height: 60px; object-fit: cover; 
            border-radius: 15px; border: 1px solid var(--border);
            transition: 0.4s;
        }
        tr:hover .vehicle-img-preview { transform: scale(1.1); border-color: var(--accent); }

        /* Buttons */
        .btn-add { 
            background: var(--accent); color: #000; font-weight: 800; 
            border-radius: 20px; padding: 12px 25px; border: none; 
            transition: 0.4s; 
        }
        .btn-add:hover { transform: translateY(-3px); box-shadow: 0 10px 20px var(--accent-glow); }

        .btn-action {
            width: 38px; height: 38px; border-radius: 12px;
            display: inline-flex; align-items: center; justify-content: center;
            background: rgba(255, 255, 255, 0.05); color: #fff;
            transition: 0.3s; border: 1px solid var(--border);
        }
        .btn-edit:hover { background: var(--accent); color: #000; border-color: var(--accent); }
        .btn-delete:hover { background: #ff4b5c; color: #fff; border-color: #ff4b5c; }

        .status-badge { 
            background: rgba(0, 242, 254, 0.1); color: var(--accent); 
            padding: 5px 15px; border-radius: 50px; font-size: 0.7rem; 
            font-weight: 800; text-transform: uppercase; border: 1px solid rgba(0, 242, 254, 0.2);
        }

        /* Modal glassmorphism */
        .modal-content {
            background: rgba(15, 17, 21, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid var(--accent);
            border-radius: 40px;
        }
    </style>
</head>
<body>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-5 animate__animated animate__fadeInDown">
        <div>
            <h1 class="fw-800 mb-1" style="font-size: 2.5rem;">Fleet <span class="text-gradient">Control</span></h1>
            <p class="text-white-50 small text-uppercase" style="letter-spacing: 2px;">Operational dashboard for vehicle inventory.</p>
        </div>
        <button class="btn-add" data-bs-toggle="modal" data-bs-target="#addVehicleModal">
            <i class="fas fa-plus-circle me-2"></i> Register New Unit
        </button>
    </div>

    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success bg-dark text-success border-success mb-4 rounded-4 animate__animated animate__fadeIn">
            <i class="fas fa-check-circle me-2"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <div class="fleet-card animate__animated animate__fadeInUp">
        <div class="table-responsive">
            <table class="table table-dark">
                <thead>
                    <tr>
                        <th>Machine Preview</th>
                        <th>Identity</th>
                        <th>Classification</th>
                        <th>Daily Rate</th>
                        <th class="text-end">Command</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($vehicles && mysqli_num_rows($vehicles) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($vehicles)): ?>
                        <tr>
                            <td>
                                <img src="../uploads/vehicles/<?php echo $row['image']; ?>" class="vehicle-img-preview" onerror="this.src='https://via.placeholder.com/100x60?text=No+Img'">
                            </td>
                            <td>
                                <div class="fw-bold fs-5"><?php echo htmlspecialchars($row['brand']); ?></div>
                                <div class="small text-white-50 text-uppercase" style="letter-spacing: 1px;"><?php echo htmlspecialchars($row['model']); ?></div>
                            </td>
                            <td>
                                <span class="status-badge">
                                    <i class="fas <?php echo ($row['type'] == 'bike') ? 'fa-motorcycle' : 'fa-car'; ?> me-1"></i>
                                    <?php echo htmlspecialchars($row['type'] ?? 'N/A'); ?>
                                </span>
                            </td>
                            <td>
                                <div class="fw-800 text-info" style="font-size: 1.1rem;">₹<?php echo number_format($row['price_per_day'] ?? 0); ?></div>
                                <div class="text-white-50 small">per 24 hours</div>
                            </td>
                            <td class="text-end">
                                <a href="edit-vehicle.php?id=<?php echo $row['id']; ?>" class="btn-action btn-edit me-2" title="Edit Unit">
                                    <i class="fas fa-pen-nib"></i>
                                </a>
                                <a href="manage-vehicles.php?del=<?php echo $row['id']; ?>" class="btn-action btn-delete" onclick="return confirm('Erase this unit from active service?')" title="Decommission">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center py-5 opacity-50">No active machines detected in fleet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addVehicleModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content text-white">
            <form action="manage-vehicles.php" method="POST" enctype="multipart/form-data">
                <div class="modal-header border-0 p-4 pb-0">
                    <h3 class="fw-800 mb-0">Initialize <span style="color: var(--accent)">Machine</span></h3>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="small text-white-50 mb-2 text-uppercase fw-bold">Brand</label>
                            <input type="text" name="brand" class="form-control custom-input" placeholder="e.g. Lamborghini" required>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-white-50 mb-2 text-uppercase fw-bold">Model</label>
                            <input type="text" name="model" class="form-control custom-input" placeholder="e.g. Aventador" required>
                        </div>
                        <div class="col-md-4">
                            <label class="small text-white-50 mb-2 text-uppercase fw-bold">Classification</label>
                            <select name="type" class="form-select custom-select" required>
                                <option value="car">Luxury Car</option>
                                <option value="bike">Superbike</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="small text-white-50 mb-2 text-uppercase fw-bold">Daily Tariff (₹)</label>
                            <input type="number" name="price" class="form-control custom-input" placeholder="50000" required>
                        </div>
                        <div class="col-md-4">
                            <label class="small text-white-50 mb-2 text-uppercase fw-bold">Propulsion</label>
                            <select name="fuel" class="form-select custom-select">
                                <option value="Petrol">Petrol</option>
                                <option value="Diesel">Diesel</option>
                                <option value="EV">Electric</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="small text-white-50 mb-2 text-uppercase fw-bold">Visual Unit (Image)</label>
                            <input type="file" name="image" class="form-control custom-input" accept="image/*" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" name="add_vehicle" class="btn-add w-100 py-3">CONFIRM REGISTRATION</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>