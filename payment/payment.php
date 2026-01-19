<?php
session_start();
require_once(__DIR__ . '/../includes/config.php');
require_once(__DIR__ . '/../includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "user/login.php");
    exit();
}

if (!isset($_POST['vehicle_id']) || !isset($_POST['from_date'])) {
    header("Location: " . BASE_URL . "vehicles.php");
    exit();
}

include(__DIR__ . '/../includes/header.php');

// Data Fetching
$vehicle_id = mysqli_real_escape_string($conn, $_POST['vehicle_id']);
$from_date = $_POST['from_date'];
$to_date = $_POST['to_date'];

$v_query = mysqli_query($conn, "SELECT * FROM vehicles WHERE id = '$vehicle_id'");
$vehicle = mysqli_fetch_assoc($v_query);

$d1 = new DateTime($from_date);
$d2 = new DateTime($to_date);
$days = $d1->diff($d2)->days ?: 1;

$base_price = $days * $vehicle['price_per_day'];
$service_fee = 250; 
$tax = $base_price * 0.05; 
$total_amount = $base_price + $service_fee + $tax;
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<style>
    :root { --accent: #00f2fe; --glass: rgba(255, 255, 255, 0.03); }
    body { background: #050505 !important; color: #fff; font-family: 'Plus Jakarta Sans', sans-serif; }
    .payment-wrapper { padding: 120px 0 80px; }
    .checkout-card { background: var(--glass); backdrop-filter: blur(40px); border: 1px solid rgba(255,255,255,0.1); border-radius: 40px; padding: 40px; }
    .nav-pills .nav-link { color: #fff; border: 1px solid rgba(255,255,255,0.1); margin-bottom: 10px; transition: 0.3s; }
    .nav-pills .nav-link.active { background: var(--accent) !important; color: #000 !important; font-weight: 700; border-color: var(--accent); }
    .credit-card { background: linear-gradient(135deg, #1d1d1d, #111); border: 1px solid rgba(255,255,255,0.15); border-radius: 25px; padding: 30px; margin-bottom: 30px; position: relative; }
    .card-chip { width: 50px; height: 38px; background: linear-gradient(45deg, #ffd700, #b8860b); border-radius: 6px; margin-bottom: 20px; }
    .form-control-custom { background: rgba(255,255,255,0.05) !important; border: 1px solid rgba(255,255,255,0.1) !important; border-radius: 15px; color: #fff !important; padding: 15px; margin-bottom: 20px; width: 100%; }
    .summary-item { display: flex; justify-content: space-between; margin-bottom: 12px; color: rgba(255,255,255,0.5); }
    .total-price { font-size: 1.7rem; font-weight: 800; color: var(--accent); border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px; }
    .btn-pay { background: var(--accent); color: #000; border: none; width: 100%; padding: 18px; border-radius: 18px; font-weight: 800; text-transform: uppercase; transition: 0.3s; }
    .btn-pay:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0, 242, 254, 0.3); }

    /* Demo Loader Styling */
    .upi-demo-box { padding: 40px 20px; border-radius: 30px; background: rgba(0,0,0,0.2); border: 1px dashed var(--accent); }
    .demo-pulse { width: 80px; height: 80px; background: rgba(0, 242, 254, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; border: 2px solid var(--accent); animation: pulse 2s infinite; }
    @keyframes pulse { 0% { box-shadow: 0 0 0 0 rgba(0, 242, 254, 0.4); } 70% { box-shadow: 0 0 0 20px rgba(0, 242, 254, 0); } 100% { box-shadow: 0 0 0 0 rgba(0, 242, 254, 0); } }
</style>

<div class="container payment-wrapper">
    <div class="row g-5">
        <div class="col-lg-5">
            <h2 class="fw-800 mb-4 animate__animated animate__fadeInLeft">Invoice <span style="color: var(--accent)">Details</span></h2>
            <div class="checkout-card mb-4">
                <div class="d-flex align-items-center mb-4 text-start">
                    <img src="../uploads/vehicles/<?php echo $vehicle['image']; ?>" class="rounded-4 me-3" style="width: 100px; height: 70px; object-fit: cover;">
                    <div>
                        <h5 class="mb-0 fw-bold"><?php echo $vehicle['brand'] . " " . $vehicle['model']; ?></h5>
                        <small class="text-white-50"><?php echo $days; ?> Days</small>
                    </div>
                </div>
                <div class="summary-item"><span>Rental Charges</span> <span>₹<?php echo number_format($base_price); ?></span></div>
                <div class="summary-item"><span>Luxury Service Fee</span> <span>₹<?php echo $service_fee; ?></span></div>
                <div class="summary-item"><span>Govt. Tax (GST)</span> <span>₹<?php echo number_format($tax); ?></span></div>
                <div class="total-price d-flex justify-content-between">
                    <span>Final Amount</span>
                    <span>₹<?php echo number_format($total_amount); ?></span>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <h2 class="fw-800 mb-4 animate__animated animate__fadeInRight">Gateway <span style="color: var(--accent)">Select</span></h2>
            <div class="checkout-card">
                <ul class="nav nav-pills mb-4 gap-2 justify-content-center" id="pills-tab" role="tablist">
                    <li class="nav-item"><button class="nav-link active rounded-pill px-4" data-bs-toggle="pill" data-bs-target="#card-pay" type="button"><i class="fas fa-credit-card me-2"></i>Secure Card</button></li>
                    <li class="nav-item"><button class="nav-link rounded-pill px-4" data-bs-toggle="pill" data-bs-target="#upi-pay" type="button"><i class="fas fa-bolt me-2"></i>Instant UPI</button></li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="card-pay">
                        <div class="credit-card text-start">
                            <div class="card-chip"></div>
                            <div class="h4 mb-4 tracking-widest" id="card_no_display">#### #### #### ####</div>
                            <div class="d-flex justify-content-between">
                                <div><small class="text-white-50 d-block" style="font-size:0.6rem">HOLDER</small><span id="card_name_display"><?php echo strtoupper($_SESSION['user_name']); ?></span></div>
                                <div class="text-end"><small class="text-white-50 d-block" style="font-size:0.6rem">EXPIRES</small><span id="card_expiry_display">MM/YY</span></div>
                            </div>
                        </div>
                        <form action="payment_process.php" method="POST">
                            <input type="hidden" name="vehicle_id" value="<?php echo $vehicle_id; ?>">
                            <input type="hidden" name="from_date" value="<?php echo $from_date; ?>">
                            <input type="hidden" name="to_date" value="<?php echo $to_date; ?>">
                            <input type="hidden" name="total_amount" value="<?php echo $total_amount; ?>">
                            <input type="hidden" name="payment_method" value="Card">
                            
                            <input type="text" id="card_no_input" class="form-control-custom" placeholder="Card Number" maxlength="19" required>
                            <div class="row">
                                <div class="col-7"><input type="text" id="card_expiry_input" class="form-control-custom" placeholder="MM/YY" maxlength="5" required></div>
                                <div class="col-5"><input type="password" class="form-control-custom" placeholder="CVV" maxlength="3" required></div>
                            </div>
                            <button type="submit" class="btn-pay mt-2">Pay Securely ₹<?php echo number_format($total_amount); ?></button>
                        </form>
                    </div>

                    <div class="tab-pane fade" id="upi-pay">
                        <div class="upi-demo-box text-center">
                            <div class="demo-pulse"><i class="fas fa-mobile-alt fa-2x text-info"></i></div>
                            <h4 class="mb-3">Simulated UPI Gateway</h4>
                            <p class="text-white-50 small mb-4 px-4">Enter your virtual ID to simulate an instant transfer.</p>
                            
                            <form action="payment_process.php" method="POST">
                                <input type="hidden" name="vehicle_id" value="<?php echo $vehicle_id; ?>">
                                <input type="hidden" name="from_date" value="<?php echo $from_date; ?>">
                                <input type="hidden" name="to_date" value="<?php echo $to_date; ?>">
                                <input type="hidden" name="total_amount" value="<?php echo $total_amount; ?>">
                                <input type="hidden" name="payment_method" value="UPI Demo">
                                
                                <div class="mb-3">
                                    <input type="text" class="form-control-custom text-center" placeholder="yourname@bank" required>
                                </div>
                                <button type="submit" class="btn-pay">Request Payment ₹<?php echo number_format($total_amount); ?></button>
                            </form>
                            <div class="mt-4 opacity-50"><i class="fab fa-google-pay fa-2x mx-2"></i><i class="fab fa-apple-pay fa-2x mx-2"></i><i class="fab fa-amazon-pay fa-2x mx-2"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Live Visual Updates
    document.getElementById('card_no_input').addEventListener('input', (e) => {
        let val = e.target.value.replace(/\D/g, '').replace(/(.{4})/g, '$1 ').trim();
        e.target.value = val;
        document.getElementById('card_no_display').innerText = val || "#### #### #### ####";
    });

    document.getElementById('card_expiry_input').addEventListener('input', (e) => {
        let val = e.target.value;
        document.getElementById('card_expiry_display').innerText = val || "MM/YY";
    });
</script>

<?php include(__DIR__ . '/../includes/footer.php'); ?>