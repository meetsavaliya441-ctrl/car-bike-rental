<?php
/**
 * Payment Gateway Configuration
 * Elite Rental - Luxury on Demand
 */

// 1. Payment Mode (demo ya live)
define('PAYMENT_MODE', 'demo'); // 'demo' for testing, 'live' for real payments

// 2. Currency Settings
define('PAY_CURRENCY', 'INR');
define('PAY_SYMBOL', '₹');

// 3. Tax & Service Charges
define('GST_PERCENTAGE', 18); // Example: 18% GST
define('SERVICE_FEE', 500);   // Fixed service charge per booking

// 4. Demo Credentials (Sirf testing ke liye)
define('DEMO_CARD_NO', '4242 4242 4242 4242');
define('DEMO_CVV', '123');

// 5. Gateway Keys (Future use ke liye khali rakha hai)
define('GATEWAY_MERCHANT_ID', 'ELITE_'.rand(1000, 9999));
define('GATEWAY_SECRET_KEY', 'sk_test_51MzXxxx...');

/**
 * Function: Total Price with Tax calculate karne ke liye
 */
function calculateFinalPrice($base_price) {
    $tax_amount = ($base_price * GST_PERCENTAGE) / 100;
    return $base_price + $tax_amount + SERVICE_FEE;
}
?>