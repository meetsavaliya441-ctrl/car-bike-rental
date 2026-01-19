<?php
session_start();
require_once('includes/config.php');
require_once('includes/db.php');
include('includes/header.php');
?>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="assets/style.css">

<div class="container contact-header animate__animated animate__fadeIn">
    <p class="text-info fw-bold text-uppercase mb-2" style="letter-spacing: 4px; font-size: 0.8rem;">Concierge Support</p>
    <h1>How Can We <span style="color: var(--accent)">Help You?</span></h1>
    <p class="text-white-50 lead mx-auto" style="max-width: 600px;">Our elite support team is online and ready to assist you with your premium rental experience.</p>
</div>

<div class="container pb-5">
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="info-card animate__animated animate__fadeInUp">
                <div class="icon-box"><i class="fas fa-phone-alt"></i></div>
                <h4>Instant Call</h4>
                <p class="text-white-50">Priority booking assistance</p>
                <h5 class="text-info fw-bold">+91 98765 43210</h5>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-card animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
                <div class="icon-box"><i class="fas fa-envelope-open-text"></i></div>
                <h4>Digital Desk</h4>
                <p class="text-white-50">Official queries & feedback</p>
                <h5 class="text-info fw-bold">elite@rentalsys.com</h5>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-card animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                <div class="icon-box"><i class="fas fa-map-marked-alt"></i></div>
                <h4>Global HQ</h4>
                <p class="text-white-50">Corporate Headquarters</p>
                <h5 class="text-info fw-bold">Luxury Drive, BKC, Mumbai</h5>
            </div>
        </div>
    </div>

    <div class="row g-5 align-items-center">
        <div class="col-lg-6">
            <div class="contact-form-wrapper">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="fw-800 m-0">Send a Message</h3>
                    <div class="small"><span class="pulse-indicator"></span>Live Support</div>
                </div>
                
                <form action="process_contact.php" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <input type="text" name="fullname" class="form-control" placeholder="Full Name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="email" name="email" class="form-control" placeholder="Email Address" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="subject" class="form-control" placeholder="Reason for Inquiry">
                    </div>
                    <div class="mb-3">
                        <textarea name="message" class="form-control" rows="5" placeholder="Your message here..." required></textarea>
                    </div>
                    <button type="submit" name="submit_contact" class="btn-send w-100">Dispatch Message <i class="fas fa-paper-plane ms-2"></i></button>
                </form>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="support-grid">
                <div class="feature-box">
                    <i class="fas fa-headset"></i>
                    <h5>24/7 Roadside</h5>
                    <p>Fas-track assistance for any on-road emergencies.</p>
                </div>
                <div class="feature-box">
                    <i class="fas fa-shield-alt"></i>
                    <h5>Elite Insurance</h5>
                    <p>Every booking is covered with full premium insurance.</p>
                </div>
                <div class="feature-box">
                    <i class="fas fa-key"></i>
                    <h5>Doorstep Delivery</h5>
                    <p>We bring your dream ride right to your doorstep.</p>
                </div>
                <div class="feature-box">
                    <i class="fas fa-sync-alt"></i>
                    <h5>Instant Refund</h5>
                    <p>No-questions-asked quick refund policy on cancellations.</p>
                </div>
            </div>
            
            <div class="mt-4 p-4 rounded-5 border border-white border-opacity-10" style="background: rgba(255,255,255,0.01);">
                <div class="d-flex align-items-center">
                    <div class="icon-box m-0 me-3" style="width: 50px; height: 50px; font-size: 1.2rem;">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 fw-bold">Response Time</h6>
                        <p class="small text-white-50 m-0">Typically we respond within 15 minutes during working hours.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>