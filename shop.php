<?php
$products = [
    [
        'id' => 'business-consulting',
        'name' => 'Business Cloud Consulting',
        'tag' => 'Starter',
        'price' => 20000,
        'description' => 'A focused strategy session to review your website, cloud setup, security needs, and next technology steps.',
        'features' => ['60-minute consultation', 'Cloud and website recommendations', 'Follow-up action checklist'],
    ],
    [
        'id' => 'seo-monthly',
        'name' => 'Monthly SEO Optimization',
        'tag' => 'Growth',
        'price' => 50000,
        'description' => 'Monthly on-page SEO improvements for service pages, blog posts, titles, descriptions, internal links, and Search Console opportunities.',
        'features' => ['Keyword-focused updates', 'Metadata and internal links', 'Monthly SEO action report'],
    ],
    [
        'id' => 'ai-chatbot',
        'name' => 'AI Chatbot Integration',
        'tag' => 'Lead Capture',
        'price' => 120000,
        'description' => 'Add a custom AI chatbot to your website to answer questions, capture leads, and guide visitors to your services.',
        'features' => ['Custom website chatbot', 'Lead capture flow', 'Basic FAQ training'],
    ],
    [
        'id' => 'custom-website',
        'name' => 'Custom Business Website',
        'tag' => 'Best Seller',
        'price' => 150000,
        'description' => 'A professional business website built for credibility, local SEO, fast loading, and lead generation.',
        'features' => ['Responsive service website', 'Contact/lead form', 'SEO-ready structure'],
    ],
    [
        'id' => 'website-optimization',
        'name' => 'Website Speed & SEO Tune-Up',
        'tag' => 'Performance',
        'price' => 75000,
        'description' => 'Improve page speed, Core Web Vitals, image loading, mobile usability, and technical SEO problems.',
        'features' => ['Performance audit', 'Image and CSS/JS cleanup', 'Core Web Vitals fixes'],
    ],
    [
        'id' => 'managed-cloud',
        'name' => 'Managed Cloud Setup',
        'tag' => 'Cloud',
        'price' => 95000,
        'description' => 'Plan and configure a reliable cloud foundation for hosting, backups, monitoring, and small business scalability.',
        'features' => ['Cloud hosting plan', 'Backup recommendations', 'Monitoring checklist'],
    ],
];
include 'header.php';
?>
<meta name="author" content="Jhon Arzu-Gil">
<meta name="copyright" content="Jhon Arzu-Gil" />
<meta name="description" content="Shop Cloud Technology Computing services online, including websites, AI chatbot setup, SEO, cloud consulting, and managed cloud support. Checkout securely.">
<meta name="robots" content="index, follow">
<meta property="og:title" content="Buy Cloud, AI &amp; Web Services Online | CTC">
<meta property="og:description" content="Shop Cloud Technology Computing services online, including websites, AI chatbot setup, SEO, cloud consulting, and managed cloud support. Checkout securely.">
<meta property="og:url" content="https://www.cloudtechnologycomputing.com/shop.php">
<meta property="og:image" content="https://www.cloudtechnologycomputing.com/assets/img/home-6/cloudbanner.jpg">
<meta property="og:site_name" content="Cloud Technology Computing" />
<meta property="og:locale" content="en_US" />
<meta property="og:type" content="website">
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="Buy Cloud, AI &amp; Web Services Online | CTC">
<meta name="twitter:description" content="Shop Cloud Technology Computing services online, including websites, AI chatbot setup, SEO, cloud consulting, and managed cloud support. Checkout securely.">
<meta name="twitter:image" content="https://www.cloudtechnologycomputing.com/assets/img/home-6/cloudbanner.jpg" />
<link rel="canonical" href="https://www.cloudtechnologycomputing.com/shop.php" />
<link rel="stylesheet" href="assets/css/ctc-cart.css">
<title>Buy Cloud, AI &amp; Web Services Online | CTC</title>
<script type="application/ld+json">
{
  "@context":"https://schema.org",
  "@type":"CollectionPage",
  "name":"Cloud Technology Computing Services Cart",
  "description":"Shop cloud, SEO, AI chatbot, web development, and managed cloud services.",
  "url":"https://www.cloudtechnologycomputing.com/shop.php"
}
</script>
</head>
<body class="home-dark2">
<?php include 'nav.php'; ?>

<main>
    <section class="ctc-shop-hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-9">
                    <span class="ctc-product-tag">Secure services checkout</span>
                    <h1>Cloud, AI, SEO & Web Services for Small Business Growth</h1>
                    <p>Add the services you need to your cart, review your total, and checkout securely. After payment, Cloud Technology Computing will follow up to confirm your project details and next steps.</p>
                    <div class="ctc-shop-badges">
                        <span class="ctc-shop-badge">Cloud consulting</span>
                        <span class="ctc-shop-badge">AI chatbot integration</span>
                        <span class="ctc-shop-badge">SEO optimization</span>
                        <span class="ctc-shop-badge">Custom websites</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="ctc-shop-wrap">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="ctc-alert">Need a custom bundle? Add the closest services to your cart or contact us before checkout.</div>
                    <div class="row g-4">
                        <?php foreach ($products as $product): ?>
                            <div class="col-md-6">
                                <article class="ctc-product-card">
                                    <span class="ctc-product-tag"><?= htmlspecialchars($product['tag']); ?></span>
                                    <h3><?= htmlspecialchars($product['name']); ?></h3>
                                    <p><?= htmlspecialchars($product['description']); ?></p>
                                    <div class="ctc-product-price">$<?= number_format($product['price'] / 100, 0); ?></div>
                                    <ul class="ctc-product-features">
                                        <?php foreach ($product['features'] as $feature): ?>
                                            <li><?= htmlspecialchars($feature); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                    <button class="ctc-primary-btn mt-auto" type="button" data-add-cart="<?= htmlspecialchars($product['id']); ?>">Add to Cart</button>
                                </article>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <aside class="col-lg-4" id="ctc-cart">
                    <div class="ctc-cart-panel">
                        <h2>Your Cart (<span data-cart-count>0</span>)</h2>
                        <div data-cart-items></div>
                        <div class="ctc-cart-total">
                            <span>Total</span>
                            <span data-cart-total>$0</span>
                        </div>
                        <form id="ctc-checkout-form" method="post" action="/checkout.php" autocomplete="off">
                            <input type="hidden" name="cart" id="checkout-cart-json" value="[]">
                            <button id="ctc-checkout-button" class="ctc-primary-btn" type="submit">Checkout Securely</button>
                            <div id="ctc-checkout-status" class="ctc-checkout-status" role="status" aria-live="polite"></div>
                        </form>
                        <button class="ctc-outline-btn mt-3 w-100" type="button" data-clear-cart>Clear Cart</button>
                        <p class="ctc-note">Checkout uses Stripe Checkout For A Secure Shopping Experience.</p>
                    </div>
                </aside>
            </div>
        </div>
    </section>
</main>

<script>
window.CTC_PRODUCTS = <?= json_encode($products, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>;
</script>
<script src="assets/js/ctc-cart.js" defer></script>
<?php include 'footer.php'; ?>
