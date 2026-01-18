<?php
// 1. Static Base URL
// Localhost par slash (/) ka dhyaan rakhein
define('BASE_URL', 'http://localhost/car-bike-rental/');

// 2. Physical Path (ROOT PATH) - Yeh Not Found error ko fix karega
// Yeh PHP ko batata hai ki files computer mein kahan padi hain
define('ROOT_PATH', dirname(__DIR__) . '/');

// 3. Application Identity
define('APP_NAME', 'ELITE RENTAL');
define('APP_TAGLINE', 'Luxury on Demand');

// 4. Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'car_rental_db');

// 5. Localization & Currency
define('CURRENCY', '₹');
define('TIMEZONE', 'Asia/Kolkata');
date_default_timezone_set(TIMEZONE);

// 6. Security & Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 7. Error Reporting (Ise testing ke baad 0 kar dena)
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>