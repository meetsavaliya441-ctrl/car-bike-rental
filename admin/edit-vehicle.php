<?php
session_start();
require_once(__DIR__ . '/../includes/config.php');
require_once(__DIR__ . '/../includes/db.php');

if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit(); }

$id = intval($_GET['id']);
$res = mysqli_query($conn, "SELECT * FROM vehicles WHERE id='$id'");
$vehicle = mysqli_fetch_assoc($res);

if (isset($_POST['update_vehicle'])) {
    $brand = mysqli_real_escape_string($conn, $_POST['brand']);
    $model = mysqli_real_escape_string($conn, $_POST['model']);
    $price = $_POST['price'];
    $fuel = $_POST['fuel'];
    
    // Image Update Logic
    if(!empty($_FILES['image']['name'])) {
        $image = time() . "_" . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/vehicles/" . $image);
        // Purani image delete karna option hai, par database update zaroori hai
        $img_sql = ", image='$image'";
    } else {
        $img_sql = "";
    }

    $sql = "UPDATE vehicles SET brand='$brand', model='$model', price_per_day='$price', fuel_type='$fuel' $img_sql WHERE id='$id'";
    if(mysqli_query($conn, $sql)) {
        $_SESSION['success'] = "Unit updated successfully!";
        header("Location: manage-vehicles.php");
        exit();
    }
}

include(__DIR__ . '/layout/sidebar.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Vehicle | Elite Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --accent: #00f2fe; }
        body { background: #050505; color: #fff; font-family: 'Plus Jakarta Sans', sans-serif; }
        .main-content { margin-left: 280px; padding: 40px; }
        .edit-card { background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.08); border-radius: 35px; padding: 40px; }
        .form-control, .form-select { background: rgba(255,255,255,0.05) !important; border: 1px solid rgba(255,255,255,0.1) !important; color: #fff !important; border-radius: 15px; padding: 12px; }
    </style>
</head>
<body>
    <div class="main-content">
        <h2 class="fw-800 mb-5">Edit <span class="text-info">Machine Details</span></h2>
        
        <div class="edit-card animate__animated animate__fadeIn">
            <form method="POST" enctype="multipart/form-data">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="text-white-50 small mb-2">Brand Name</label>
                        <input type="text" name="brand" class="form-control" value="<?php echo $vehicle['brand']; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="text-white-50 small mb-2">Model Name</label>
                        <input type="text" name="model" class="form-control" value="<?php echo $vehicle['model']; ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="text-white-50 small mb-2">Daily Price (₹)</label>
                        <input type="number" name="price" class="form-control" value="<?php echo $vehicle['price_per_day']; ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="text-white-50 small mb-2">Fuel Type</label>
                        <select name="fuel" class="form-select">
                            <option value="Petrol" <?php if($vehicle['fuel_type'] == 'Petrol') echo 'selected'; ?>>Petrol</option>
                            <option value="Diesel" <?php if($vehicle['fuel_type'] == 'Diesel') echo 'selected'; ?>>Diesel</option>
                            <option value="EV" <?php if($vehicle['fuel_type'] == 'EV') echo 'selected'; ?>>Electric</option>
                        </select>
                    </div>
                    <div class="col-md-4 text-center">
                        <p class="text-white-50 small mb-2">Current Image</p>
                        <img src="../uploads/vehicles/<?php echo $vehicle['image']; ?>" height="60" class="rounded-3 border border-secondary">
                    </div>
                    <div class="col-md-12">
                        <label class="text-white-50 small mb-2">Change Image (Optional)</label>
                        <input type="file" name="image" class="form-control">
                    </div>
                    <div class="col-md-12 mt-4">
                        <button type="submit" name="update_vehicle" class="btn btn-info w-100 rounded-pill fw-bold py-3">SAVE CHANGES</button>
                        <a href="manage-vehicles.php" class="btn btn-link w-100 text-white-50 mt-2 text-decoration-none">Cancel & Return</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>