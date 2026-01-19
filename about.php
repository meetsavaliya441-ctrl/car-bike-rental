<?php
session_start();
require_once('includes/config.php');
require_once('includes/db.php');
include('includes/header.php');
?>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<link rel="stylesheet" href="assets/style.css">


<section class="about-hero">
    <div class="container" data-aos="zoom-out">
        <h5 class="text-info fw-bold text-uppercase mb-3" style="letter-spacing: 5px;">Our Identity</h5>
        <h1>Beyond <span style="color: var(--accent);">Transportation.</span></h1>
        <p class="lead opacity-50 mt-3">Redefining the luxury rental landscape since 2018.</p>
    </div>
</section>

<section class="container py-5 my-5">
    <div class="row align-items-center g-5">
        <div class="col-lg-6" data-aos="fade-right">
            <h2 class="display-4 fw-800 mb-4">A Journey Built on <br> <span class="text-info">Trust & Excellence</span></h2>
            <p class="text-white-50 fs-5">Elite Rental was founded with a single mission: to provide an uncompromising driving experience. We believe that a rental shouldn't just be a car or a bike; it should be a gateway to memories.</p>
            <p class="text-white-50">Our curated fleet of high-performance vehicles undergoes a 50-point safety check before every hand-over, ensuring that your safety is never a second thought.</p>
        </div>
        <div class="col-lg-6" data-aos="fade-left">
            <div class="row g-3">
                <div class="col-6">
                    <img src="https://images.unsplash.com/photo-1502877338535-766e1452684a?q=80&w=2072" class="img-fluid rounded-5 mb-3" alt="Car">
                    <div class="bg-info p-4 rounded-5 text-dark">
                        <h4 class="fw-bold mb-0">100%</h4>
                        <p class="mb-0 small fw-bold">Satisfaction Rate</p>
                    </div>
                </div>
                <div class="col-6">
                    <div class="bg-white p-4 rounded-5 text-dark mb-3">
                        <h4 class="fw-bold mb-0">24/7</h4>
                        <p class="mb-0 small fw-bold">Roadside Assist</p>
                    </div>
                    <img src="https://images.unsplash.com/photo-1558981806-ec527fa84c39?q=80&w=2070" class="img-fluid rounded-5" alt="Bike">
                </div>
            </div>
        </div>
    </div>
</section>

<section class="container py-5">
    <div class="text-center mb-5" data-aos="fade-up">
        <h2 class="display-5 fw-800">Our Core <span class="text-info">Values</span></h2>
    </div>
    <div class="row g-4">
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
            <div class="value-card">
                <i class="fas fa-shield-alt val-icon"></i>
                <h4>Safety First</h4>
                <p class="text-white-50">Military-grade sanitation and regular technical audits for every vehicle in our fleet.</p>
            </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
            <div class="value-card">
                <i class="fas fa-gem val-icon"></i>
                <h4>Luxury Only</h4>
                <p class="text-white-50">We don't do 'standard'. Only premium, high-performance cars and bikes make the cut.</p>
            </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
            <div class="value-card">
                <i class="fas fa-handshake val-icon"></i>
                <h4>Transparency</h4>
                <p class="text-white-50">No hidden costs, no surprise taxes. What you see is exactly what you pay.</p>
            </div>
        </div>
    </div>
</section>

<section class="container-fluid py-5 my-5" style="background: rgba(255,255,255,0.02);">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-md-3">
                <p class="stat-number">5k+</p>
                <p class="stat-label">Happy Clients</p>
            </div>
            <div class="col-md-3">
                <p class="stat-number">200+</p>
                <p class="stat-label">Luxury Vehicles</p>
            </div>
            <div class="col-md-3">
                <p class="stat-number">15+</p>
                <p class="stat-label">City Hubs</p>
            </div>
            <div class="col-md-3">
                <p class="stat-number">6yrs</p>
                <p class="stat-label">Experience</p>
            </div>
        </div>
    </div>
</section>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>AOS.init({ duration: 1000, once: true });</script>

<?php include('includes/footer.php'); ?>