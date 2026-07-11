<?php
require_once __DIR__ . '/includes/env.php';

$products = [
    'business-consulting' => ['name' => 'Business Cloud Consulting', 'price' => 20000, 'description' => '60-minute strategy session and cloud/website action checklist'],
    'seo-monthly' => ['name' => 'Monthly SEO Optimization', 'price' => 50000, 'description' => 'Monthly on-page SEO, internal links, metadata, and reporting'],
    'ai-chatbot' => ['name' => 'AI Chatbot Integration', 'price' => 120000, 'description' => 'Custom AI chatbot setup for lead capture and website support'],
    'custom-website' => ['name' => 'Custom Business Website', 'price' => 150000, 'description' => 'Responsive SEO-ready business website with lead form'],
    'website-optimization' => ['name' => 'Website Speed & SEO Tune-Up', 'price' => 75000, 'description' => 'Performance, mobile usability, and Core Web Vitals improvements'],
    'managed-cloud' => ['name' => 'Managed Cloud Setup', 'price' => 95000, 'description' => 'Cloud hosting, backup, and monitoring foundation'],
];


function ctc_is_ajax_request(): bool
{
    $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
    return (isset($_POST['ajax']) && $_POST['ajax'] === '1') || stripos($accept, 'application/json') !== false;
}

function ctc_json_response(bool $ok, array $payload = [], int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array_merge(['ok' => $ok], $payload), JSON_UNESCAPED_SLASHES);
    exit;
}

function ctc_error_page(string $title, string $message): void
{
    if (ctc_is_ajax_request()) {
        ctc_json_response(false, ['title' => $title, 'error' => $message], 400);
    }

    http_response_code(400);
    $safeTitle = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
    $safeMessage = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
    echo "<!doctype html><html lang='en'><head><meta charset='utf-8'><meta name='viewport' content='width=device-width, initial-scale=1'><title>{$safeTitle}</title><style>body{font-family:Arial,sans-serif;background:#05080d;color:#fff;padding:40px}.box{max-width:760px;margin:auto;background:#0b1220;border:1px solid rgba(6,216,137,.3);border-radius:18px;padding:28px}a{color:#06d889}</style></head><body><div class='box'><h1>{$safeTitle}</h1><p>{$safeMessage}</p><p><a href='shop.php'>Return to services cart</a></p></div></body></html>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: shop.php');
    exit;
}

$cartJson = $_POST['cart'] ?? '';
$cart = json_decode($cartJson, true);

if (!is_array($cart) || count($cart) === 0) {
    ctc_error_page('Cart is empty', 'Please add at least one service before checkout.');
}

$lineItems = [];
$orderSummary = [];
$index = 0;

foreach ($cart as $item) {
    $id = isset($item['id']) ? (string) $item['id'] : '';
    $qty = isset($item['qty']) ? (int) $item['qty'] : 0;

    if (!isset($products[$id]) || $qty < 1 || $qty > 10) {
        continue;
    }

    $product = $products[$id];
    $lineItems["line_items[{$index}][price_data][currency]"] = 'usd';
    $lineItems["line_items[{$index}][price_data][unit_amount]"] = $product['price'];
    $lineItems["line_items[{$index}][price_data][product_data][name]"] = $product['name'];
    $lineItems["line_items[{$index}][price_data][product_data][description]"] = $product['description'];
    $lineItems["line_items[{$index}][quantity]"] = $qty;
    $orderSummary[] = $product['name'] . ' x ' . $qty;
    $index++;
}

if (count($lineItems) === 0) {
    ctc_error_page('Invalid cart', 'One or more services in your cart could not be verified. Please rebuild your cart and try again.');
}

if (!function_exists('curl_init')) {
    ctc_error_page('Checkout server error', 'PHP cURL is not enabled on this hosting account. Enable cURL in cPanel/PHP Extensions, then try checkout again.');
}

$stripeSecretKey = ctc_env('STRIPE_SECRET_KEY', '');
if ($stripeSecretKey === '') {
    ctc_error_page('Stripe is not configured', 'Add STRIPE_SECRET_KEY to your hosting environment or .env file, then try checkout again. Example: STRIPE_SECRET_KEY=sk_live_your_private_key');
}

$siteUrl = rtrim(ctc_env('APP_URL', 'https://www.cloudtechnologycomputing.com'), '/');
$payload = array_merge([
    'mode' => 'payment',
    'success_url' => $siteUrl . '/checkout-success.php?session_id={CHECKOUT_SESSION_ID}',
    'cancel_url' => $siteUrl . '/checkout-cancel.php',
    'billing_address_collection' => 'auto',
    'customer_creation' => 'if_required',
    'metadata[site]' => 'Cloud Technology Computing',
    'metadata[order_summary]' => substr(implode(', ', $orderSummary), 0, 450),
], $lineItems);

$ch = curl_init('https://api.stripe.com/v1/checkout/sessions');
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query($payload),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer ' . $stripeSecretKey,
        'Content-Type: application/x-www-form-urlencoded',
    ],
    CURLOPT_TIMEOUT => 30,
]);

$response = curl_exec($ch);
$curlError = curl_error($ch);
$statusCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($response === false) {
    ctc_error_page('Checkout connection failed', 'Stripe checkout could not be reached. Error: ' . $curlError);
}

$data = json_decode($response, true);
if ($statusCode < 200 || $statusCode >= 300 || empty($data['url'])) {
    $stripeMessage = $data['error']['message'] ?? 'Stripe did not return a checkout URL.';
    ctc_error_page('Checkout failed', $stripeMessage);
}

if (ctc_is_ajax_request()) {
    ctc_json_response(true, ['url' => $data['url']]);
}

header('Location: ' . $data['url'], true, 303);
exit;
