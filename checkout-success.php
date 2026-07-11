<?php include 'header.php'; ?>
<meta name="robots" content="noindex, nofollow">
<title>Payment Successful | Cloud Technology Computing</title>
<meta name="description" content="Your Cloud Technology Computing payment was successful. Review your order confirmation and watch for project onboarding details from our team by email.">
<meta property="og:title" content="Payment Successful | Cloud Technology Computing">
<meta property="og:description" content="Your Cloud Technology Computing payment was successful. Review your order confirmation and watch for project onboarding details from our team by email.">
<meta name="twitter:title" content="Payment Successful | Cloud Technology Computing">
<meta name="twitter:description" content="Your Cloud Technology Computing payment was successful. Review your order confirmation and watch for project onboarding details from our team by email.">
<link rel="canonical" href="https://www.cloudtechnologycomputing.com/checkout-success.php" />
<link rel="stylesheet" href="assets/css/ctc-cart.css">
</head>
<body class="home-dark2">
<?php include 'nav.php'; ?>
<main class="ctc-shop-hero" style="min-height:70vh;display:flex;align-items:center;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <span class="ctc-product-tag">Payment received</span>
                <h1>Thank you for your order.</h1>
                <p>Your payment was successful. Cloud Technology Computing will follow up using the billing/contact details from checkout to confirm your project scope and next steps.</p>
                <p class="ctc-note">Stripe Session: <?= htmlspecialchars($_GET['session_id'] ?? 'Pending', ENT_QUOTES, 'UTF-8'); ?></p>
                <a href="/" class="ctc-primary-btn" style="max-width:260px;margin:20px auto 0;">Back to Home</a>
            </div>
        </div>
    </div>
</main>
<script>localStorage.removeItem('ctc_service_cart_v1');</script>
<?php include 'footer.php'; ?>
