<style>
    :root {
        --footer-bg: #0a0a0a;
        --accent-glow: #00f2fe;
    }

    .main-footer {
        background-color: var(--footer-bg);
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        padding: 80px 0 30px;
        margin-top: 100px;
        position: relative;
    }

    /* Top Accent Line */
    .main-footer::before {
        content: "";
        position: absolute;
        top: 0; left: 0; width: 100%; height: 2px;
        background: linear-gradient(to right, transparent, var(--accent-glow), transparent);
    }

    .footer-logo {
        font-family: 'Bebas Neue', cursive;
        font-size: 2rem;
        letter-spacing: 2px;
        color: #fff;
        text-decoration: none;
    }

    .footer-logo span { color: var(--accent-glow); }

    .footer-heading {
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 25px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #fff;
    }

    .footer-links {
        list-style: none;
        padding: 0;
    }

    .footer-links li {
        margin-bottom: 12px;
    }

    .footer-links a {
        color: rgba(255, 255, 255, 0.5);
        text-decoration: none;
        transition: 0.3s;
        font-size: 0.95rem;
    }

    .footer-links a:hover {
        color: var(--accent-glow);
        padding-left: 5px;
    }

    .social-icons a {
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.05);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        color: #fff;
        margin-right: 10px;
        transition: 0.3s;
        text-decoration: none;
    }

    .social-icons a:hover {
        background: var(--accent-glow);
        color: #000;
        transform: translateY(-5px);
    }

    .copyright-bar {
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        padding-top: 30px;
        margin-top: 50px;
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.3);
    }
</style>

<footer class="main-footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-5 mb-lg-0">
                <a href="index.php" class="footer-logo">RENTAL<span>SYSTEM</span></a>
                <p class="mt-3 text-white-50 w-75">
                    Premium vehicles for extraordinary journeys. Experience the pinnacle of luxury and performance.
                </p>
                <div class="social-icons mt-4">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 mb-4">
                <h5 class="footer-heading">Navigate</h5>
                <ul class="footer-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="vehicles.php">All Vehicles</a></li>
                    <li><a href="about.php">Our Story</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-4 mb-4">
                <h5 class="footer-heading">Support</h5>
                <ul class="footer-links">
                    <li><a href="#">Help Center</a></li>
                    <li><a href="#">Rental Policy</a></li>
                    <li><a href="#">Insurance Coverage</a></li>
                    <li><a href="#">Safety Guidelines</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-4 mb-4">
                <h5 class="footer-heading">Contact</h5>
                <ul class="footer-links">
                    <li><i class="fas fa-map-marker-alt me-2 text-info"></i> 123 Luxury Drive, India</li>
                    <li><i class="fas fa-phone-alt me-2 text-info"></i> +91 98765 43210</li>
                    <li><i class="fas fa-envelope me-2 text-info"></i> support@rentalsys.com</li>
                </ul>
            </div>
        </div>

        <div class="copyright-bar d-flex flex-wrap justify-content-between align-items-center">
            <p>&copy; <?php echo date('Y'); ?> Elite Rental System. Built for Excellence.</p>
            <div class="footer-bottom-links">
                <a href="#" class="text-decoration-none text-reset me-3">Privacy Policy</a>
                <a href="#" class="text-decoration-none text-reset">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>