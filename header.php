<!doctype html>
<html lang="en">

<head>
<?php
$gaMeasurementId = getenv('GA_MEASUREMENT_ID') ?: 'GT-NMKVXWDW';
?>
<?php if (!empty($gaMeasurementId)): ?>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?= htmlspecialchars($gaMeasurementId, ENT_QUOTES, 'UTF-8'); ?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '<?= htmlspecialchars($gaMeasurementId, ENT_QUOTES, 'UTF-8'); ?>');
</script>
<?php endif; ?>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="theme-color" content="#020617">
<meta name="format-detection" content="telephone=no">




<link rel="preconnect" href="https://www.googletagmanager.com" crossorigin>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preconnect" href="https://www.paypalobjects.com" crossorigin>

<link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
<link rel="icon" type="image/svg+xml" href="/favicon.svg" />
<link rel="shortcut icon" href="/favicon.ico" />
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
<link rel="manifest" href="/site.webmanifest" />

<?php
// Only preload the hero on the homepage. Other pages set $pagePreloadImage explicitly
// if they want a specific above-the-fold image.
if (!isset($pagePreloadImage)) {
    $pagePreloadImage = (basename($_SERVER['SCRIPT_NAME'] ?? '') === 'index.php')
        ? '/assets/img/home-6/CloudTechnologyComputingDisplay.avif'
        : '';
}
?>
<?php if (!empty($pagePreloadImage)): ?>
<link rel="preload" as="image" href="<?= htmlspecialchars($pagePreloadImage, ENT_QUOTES, 'UTF-8'); ?>" fetchpriority="high">
<?php endif; ?>

<link rel="stylesheet" href="/style.css">
<link rel="stylesheet" href="/css/seo-engagement.css">
<link rel="stylesheet" href="/assets/css/bootstrap.min.css">

<?php
// Helper: non-critical CSS — load via media="print" swap pattern to avoid render-blocking.
$nonCriticalCss = [
    '/assets/css/bootstrap-icons.css',
    '/assets/css/all.min.css',
    '/assets/css/fontawesome.min.css',
    '/assets/css/swiper-bundle.min.css',
    '/assets/css/animate.min.css',
    '/assets/css/jquery.fancybox.min.css',
    '/assets/css/boxicons.min.css',
    '/assets/css/preloader.css',
    '/assets/css/style2.css',
];
?>
<?php foreach ($nonCriticalCss as $css): ?>
<link rel="stylesheet" href="<?= htmlspecialchars($css, ENT_QUOTES, 'UTF-8'); ?>" media="print" onload="this.media='all'">
<noscript><link rel="stylesheet" href="<?= htmlspecialchars($css, ENT_QUOTES, 'UTF-8'); ?>"></noscript>
<?php endforeach; ?>

<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0&family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,1,0&display=swap">

<meta name="p:domain_verify" content="ac5c484e34bef5bf8d2f4b91d7d28dba">
<link rel="alternate" type="application/rss+xml" title="Cloud Technology Computing Blog" href="/rss.php">
<link rel="manifest" href="/manifest.json">

<?php if (basename($_SERVER['SCRIPT_NAME'] ?? '') === 'shop.php'): ?>
<script src="https://www.paypalobjects.com/ncp/cart/cart.js" data-merchant-id="<?= htmlspecialchars(getenv('PAYPAL_MERCHANT_ID') ?: '3DQHW2ED3QGBL', ENT_QUOTES, 'UTF-8'); ?>" defer></script>
<?php endif; ?>
<script src="/sw-register.js" defer></script>
