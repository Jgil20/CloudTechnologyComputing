<?php
require_once __DIR__ . '/includes/env.php';

/**
 * Shared document header.
 *
 * The Google tag is injected immediately before </head> after the page-specific
 * <title> and meta tags have been rendered. This prevents GA4 page views from
 * being recorded before document.title is available and reduces “(not set)”
 * values in page-title reports.
 */
$gaMeasurementId = getenv('GA_MEASUREMENT_ID') ?: 'GT-NMKVXWDW';
$gaMeasurementIdEscaped = htmlspecialchars($gaMeasurementId, ENT_QUOTES, 'UTF-8');
$analyticsMarkup = '';

if (!empty($gaMeasurementId)) {
    $analyticsMarkup = <<<HTML
<!-- Google tag (gtag.js) — intentionally loaded after title and metadata -->
<script async src="https://www.googletagmanager.com/gtag/js?id={$gaMeasurementIdEscaped}"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '{$gaMeasurementIdEscaped}', {
    'page_title': document.title,
    'page_location': window.location.href
  });
</script>
HTML;
}

$scriptName = basename($_SERVER['SCRIPT_NAME'] ?? '');
$fallbackTitle = ucwords(str_replace(['-', '_', '.php'], [' ', ' ', ''], $scriptName));
$fallbackTitle = trim($fallbackTitle) !== ''
    ? trim($fallbackTitle) . ' | Cloud Technology Computing'
    : 'Cloud Technology Computing';
$fallbackTitleEscaped = htmlspecialchars($fallbackTitle, ENT_QUOTES, 'UTF-8');

ob_start(static function (string $html) use ($analyticsMarkup, $fallbackTitleEscaped): string {
    $headClosePosition = stripos($html, '</head>');
    if ($headClosePosition === false) {
        return $html;
    }

    $headOpenPosition = stripos($html, '<head');
    $headBlock = $headOpenPosition !== false
        ? substr($html, $headOpenPosition, $headClosePosition - $headOpenPosition)
        : substr($html, 0, $headClosePosition);

    // Safety net for any future page that forgets to define a title.
    if (stripos($headBlock, '<title') === false) {
        $headTagEnd = $headOpenPosition !== false ? strpos($html, '>', $headOpenPosition) : false;
        if ($headTagEnd !== false && $headTagEnd < $headClosePosition) {
            $titleMarkup = "\n<title>{$fallbackTitleEscaped}</title>";
            $html = substr_replace($html, $titleMarkup, $headTagEnd + 1, 0);
            $headClosePosition += strlen($titleMarkup);
        }
    }

    // Add page-correct hreflang links using the canonical URL declared by each page.
    $headClosePosition = stripos($html, '</head>');
    $headOpenPosition = stripos($html, '<head');
    $headBlock = ($headOpenPosition !== false && $headClosePosition !== false)
        ? substr($html, $headOpenPosition, $headClosePosition - $headOpenPosition)
        : '';

    if (
        $headBlock !== ''
        && stripos($headBlock, 'hreflang=') === false
        && preg_match('~<link\s+[^>]*rel=["\']canonical["\'][^>]*href=["\']([^"\']+)["\']~i', $headBlock, $canonicalMatch)
    ) {
        $canonicalHref = htmlspecialchars(
            html_entity_decode($canonicalMatch[1], ENT_QUOTES | ENT_HTML5, 'UTF-8'),
            ENT_QUOTES | ENT_SUBSTITUTE,
            'UTF-8'
        );
        $hreflangMarkup = "\n<link rel=\"alternate\" hreflang=\"en-US\" href=\"{$canonicalHref}\">"
            . "\n<link rel=\"alternate\" hreflang=\"x-default\" href=\"{$canonicalHref}\">\n";
        $html = substr_replace($html, $hreflangMarkup, $headClosePosition, 0);
        $headClosePosition += strlen($hreflangMarkup);
    }

    // Inject analytics only once and only after page-specific title/meta markup.
    if ($analyticsMarkup !== '' && stripos($html, 'googletagmanager.com/gtag/js') === false) {
        $html = substr_replace($html, "\n{$analyticsMarkup}\n", $headClosePosition, 0);
    }

    return $html;
});
?>
<!doctype html>
<html lang="en">

<head>
<style>
:root{--bg:#020617;--bg-2:#0b1220;--bg-6:#161519;--fg:#d6deeb;--theme:#06D889;--white:#fff;--accent:#3b82f6;--accent-2:#06d889;--font-saira:"Saira",sans-serif}
*{box-sizing:border-box}
html,body{margin:0;padding:0;background:var(--bg);color:var(--fg);font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif;-webkit-font-smoothing:antialiased;line-height:1.5;overflow-x:hidden;height:auto!important;min-height:100%}
img{max-width:100%;height:auto;display:block}
a{color:inherit;text-decoration:none}
h1,h2,h3,h4,h5,h6{font-family:var(--font-saira);font-weight:700;line-height:1.3;margin:0 0 .5em}
p{margin:0 0 1em}

/* The actual cause of CLS — body.bg-6 background applied inline before async CSS loads */
body{background:#161519;color:#fff}
body.bg-6{background:#161519}

/* Keep the footer at the bottom of short pages and prevent the root
   background from appearing as a dark-blue block below it. */
html{background:#171717}
body{min-height:100vh!important;display:flex;flex-direction:column}
body>main{flex:1 0 auto;width:100%}
body>footer{margin-top:auto;flex-shrink:0;width:100%}

/* Header + preloader to prevent layout shift on first paint */
.preloader{position:fixed;inset:0;background:var(--bg-6);display:flex;align-items:center;justify-content:center;z-index:9999;transition:opacity .3s}
.no-js .preloader{display:none}

/* Reserve space for the hero to prevent layout shift while image loads */
.home6-banner{min-height:80vh;position:relative}

/* Critical font display for Saira — already loaded by async CSS but reserve fallback size */
.section-title-4 h2,.section-title-5 h2,.banner-content h1{font-family:var(--font-saira);font-weight:700;line-height:1.3}

/* Section spacing so blocks don't reflow when async CSS loads */
.sec-pad{padding:120px 0}
.sec-mar{margin:120px 0}
@media (max-width:991px){.sec-pad{padding:80px 0}.sec-mar{margin:80px 0}}
.container{width:100%;max-width:1320px;margin:0 auto;padding:0 15px}
.row{display:flex;flex-wrap:wrap;margin:0 -15px}
.col-12,.col-lg-3,.col-lg-4,.col-lg-6,.col-lg-8,.col-lg-12{flex:0 0 auto;padding:0 15px}
.col-12{flex:0 0 100%;max-width:100%}

/* Chat form layout — fix file upload button alignment (the wrapper img was making it taller than 35px) */
.chat-form{display:flex;align-items:center;position:relative;background:#fff;border-radius:32px;outline:1px solid #CCCCE5;box-shadow:0 0 8px rgba(0,0,0,.06);min-height:47px}
.chat-form .message-input{width:100%;height:47px;outline:none;resize:none;border:none;max-height:180px;font-size:.95rem;padding:14px 0 12px 18px;border-radius:inherit}
.chat-form .chat-controls{display:flex;align-items:center;align-self:flex-end;height:47px;gap:3px;padding-right:6px;flex-shrink:0}
.chat-form .chat-controls button{height:35px;width:35px;border:none;cursor:pointer;border-radius:50%;font-size:1.15rem;background:none;transition:.2s ease;flex-shrink:0;display:flex;align-items:center;justify-content:center}
.chat-form .file-upload-wrapper{position:relative;height:35px;width:35px;flex-shrink:0}
.chat-form .file-upload-wrapper>img{position:absolute!important;top:0;left:0;width:100%;height:100%;object-fit:cover;border-radius:50%;display:none!important}
.chat-form .file-upload-wrapper #file-cancel{display:none!important;position:absolute;top:0;left:0}
.chat-form .file-upload-wrapper #file-upload{position:absolute;top:0;left:0;display:flex!important}
.chat-form .file-upload-wrapper.file-uploaded>img{display:block!important}
.chat-form .file-upload-wrapper.file-uploaded #file-upload{display:none!important}
.chat-form .file-upload-wrapper.file-uploaded:hover #file-cancel{display:flex!important}
.chat-form .chat-controls #send-message{color:#fff;background:#14c690;display:none}
.chat-form .message-input:valid~.chat-controls #send-message{display:flex}

/* Emoji picker - scoped to the chat only, not the toggler */
.chatbot-popup .material-symbols-outlined{font-family:"Material Symbols Outlined";font-weight:400;font-style:normal;font-size:24px;line-height:1;letter-spacing:normal;text-transform:none;display:inline-block;white-space:nowrap;word-wrap:normal;direction:ltr;-webkit-font-feature-settings:"liga";-webkit-font-smoothing:antialiased}

/* Chatbot layout — only style the toggler (popup uses opacity:0/1 toggle via body.show-chatbot) */
#chatbot-toggler{position:fixed;bottom:30px;right:35px;border:none;height:50px;width:50px;border-radius:50%;cursor:pointer;z-index:9999;display:flex;align-items:center;justify-content:center;transition:all .2s ease}
#chatbot-toggler .material-symbols-rounded{font-size:28px;color:#fff}
/* Use opacity (not display) to match style2.min.css's icon swap pattern.
   By default: chat icon (first) shown, close icon (last) hidden.
   When body.show-chatbot: chat icon hidden, close icon shown. */
#chatbot-toggler span{transition:opacity .2s ease}
#chatbot-toggler span:last-child{opacity:0;position:absolute}
#chatbot-toggler span:first-child{opacity:1;position:relative}
body.show-chatbot #chatbot-toggler span:last-child{opacity:1}
body.show-chatbot #chatbot-toggler span:first-child{opacity:0}

/* Reserve space for the LCP banner image column (prevents CLS when high-priority image loads).
   Only reserves dimensions, doesn't override style2.min.css layout rules. */
.banner-area6{min-height:600px}
@media (max-width:991px){.banner-area6{min-height:500px}}
@media (max-width:767px){.banner-area6{min-height:400px}}
.col-lg-5.d-flex.justify-content-center{min-height:600px;align-items:center;position:relative}
@media (max-width:991px){.col-lg-5.d-flex.justify-content-center{min-height:500px}}
@media (max-width:767px){.col-lg-5.d-flex.justify-content-center{min-height:0}}
.banner-big-img,.col-lg-5.d-flex.justify-content-center .banner-img{aspect-ratio:450/650;background:rgba(22,21,25,0.5);overflow:hidden}
.banner-big-img img{width:100%;height:100%;object-fit:cover;display:block}
.banner-sm-img{position:relative;z-index:2}
.banner-sm-img img{width:150px;height:150px;display:block;position:relative;z-index:2}

/* Reserve space for .banner-area4 (h1 + marquee background) — biggest remaining CLS source */
.banner-area4{min-height:100vh;background-size:cover;background-repeat:no-repeat;position:relative;padding:255px 200px 128px;z-index:1}
@media (max-width:1800px){.banner-area4{padding:240px 150px 128px}}
@media (max-width:1700px){.banner-area4{padding:240px 120px 128px}}
@media (max-width:1399px){.banner-area4{padding:240px 80px 70px}}
@media (max-width:1199px){.banner-area4{padding:240px 80px 70px 25px}}
@media (max-width:991px){.banner-area4{padding:220px 20px 70px}}
@media (max-width:576px){.banner-area4{padding:200px 0 70px}}
.banner-area4 .banner-content{max-width:735px;width:100%}

/* Inline the H1 typography to prevent the massive shift when Saira + 70px rules apply late */
.banner-area4 .banner-content h1,.banner-area4 h1{font-family:var(--font-saira);font-weight:600;font-size:70px;line-height:1.3;letter-spacing:.03em;text-transform:capitalize;color:#fff;margin:0 0 15px;width:735px;max-width:100%;min-height:280px}
@media (min-width:1400px) and (max-width:1599px){.banner-area4 h1{font-size:60px;min-height:240px}}
@media (max-width:1399px){.banner-area4 h1{font-size:55px;min-height:220px}}
@media (max-width:1199px){.banner-area4 h1{font-size:50px;min-height:200px}}
@media (max-width:576px){.banner-area4 h1{font-size:38px;min-height:160px}}

.banner-area4 .banner-content p{font-family:var(--font-saira);font-weight:400;font-size:16px;line-height:35px;color:#E4E4E4;margin:0 0 45px;max-width:735px}

/* Marquee background — render at final size from first paint */
.banner-area4 .background-text-slider{overflow:hidden;position:absolute;left:0;bottom:180px;width:100%}
.banner-area4 .background-text-slider h2{font-family:var(--font-saira);font-weight:700;font-size:98px;line-height:154px;letter-spacing:.03em;text-transform:uppercase;color:#fff;opacity:.03;margin:0;white-space:nowrap;overflow:hidden}
@media (max-width:767px){.banner-area4 .background-text-slider h2{font-size:70px}}

/* Swiper sliders need reserved height before JS init (was causing massive CLS).
   Each major slider gets aspect-ratio matching its natural image proportions. */
.swiper{position:relative;overflow:hidden;list-style:none;padding:0;margin:0}
.swiper-wrapper{position:relative;width:100%;height:100%;display:flex;transition-property:transform}
.swiper-slide{flex-shrink:0;width:100%;height:100%;position:relative}

/* banner5-slider — 4 portrait images, ~1448x1086 (first slide) */
.banner5-slider{aspect-ratio:1448/1086;background:rgba(22,21,25,0.5);width:100%;display:block}
.banner5-slider .swiper-wrapper{height:100%!important}
.banner5-slider .swiper-slide{height:100%!important}
.banner5-slider .banner-img{height:100%;position:relative}
.banner5-slider .banner-img img{width:100%;height:100%;object-fit:cover;display:block}

/* Content-driven sliders must grow with their cards.
   Fixed aspect ratios clipped service text and project overlays on mobile. */
.home6-solution-slider,
.home3-success-stories-slider{
  width:100%;
  min-height:1px;
  height:auto;
  aspect-ratio:auto;
  background:rgba(22,21,25,0.5)
}
.home6-solution-slider .swiper-wrapper,
.home3-success-stories-slider .swiper-wrapper{
  height:auto;
  align-items:flex-start
}
.home6-solution-slider .swiper-slide,
.home3-success-stories-slider .swiper-slide{
  height:auto
}
.home6-solution-slider .single-solution,
.home3-success-stories-slider .success-storie-card{
  height:auto
}
.home3-success-stories-slider .success-img img{
  display:block;
  width:100%;
  height:auto
}
@media (max-width:767px){
  .home6-solution-section .single-solution{padding:28px 22px 42px}
  .home6-solution-section .single-solution .solution-content p{line-height:1.75;margin-bottom:24px}
  .home3-success-stories-area .success-storie-card .success-content{left:14px;right:14px;bottom:14px;padding:20px 18px}
}

/* home6-testimonial-slider */
.home6-testimonial-slider{aspect-ratio:16/9;background:rgba(22,21,25,0.5);width:100%}
.home6-testimonial-slider .swiper-wrapper{height:100%!important}
.home6-testimonial-slider .swiper-slide{height:100%!important}

/* Home 5 blog slider — allow mobile cards to grow with their content.
   A fixed 4:3 height clipped the featured image after long mobile titles/excerpts. */
.home5-blog-slider{width:100%;height:auto!important;min-height:0;background:rgba(22,21,25,0.5)}
.home5-blog-slider .swiper-wrapper{height:auto!important;align-items:stretch}
.home5-blog-slider .swiper-slide{height:auto!important}
.home5-blog-slider .single-blog-card{height:100%}
.home5-blog-slider .single-blog-card .blog-img{width:100%;aspect-ratio:16/9;overflow:hidden}
.home5-blog-slider .single-blog-card .blog-img>a{display:block;width:100%;height:100%}
.home5-blog-slider .single-blog-card .blog-img img{display:block;width:100%!important;height:100%!important;object-fit:cover;object-position:center top}
@media (max-width:767px){
  .home5-blog-slider{aspect-ratio:auto!important}
  .home5-blog-slider .single-blog-card{padding-bottom:0!important}
}

/* Reserve space for accordion/collapse content that may expand on init */
.accordion-collapse{min-height:60px}

/* Hide swiper-pagination until JS renders bullets (prevents shift) */
.swiper-pagination{position:absolute;left:50%;transform:translateX(-50%);bottom:8px;z-index:10;min-height:24px}

/* CRITICAL: header + main menu — the menu is the largest CLS source (1.061).
   Lock the dimensions from first paint to prevent font/padding reflows. */
.header-area2{position:absolute;top:0;left:0;width:100%;z-index:2;display:flex;justify-content:space-between;align-items:center;padding:0 5%;transition:all .8s ease-out 0s;border-bottom:1px solid rgba(255,255,255,0.1);min-height:90px}
.header-area2 .header-logo{padding:25px 0;flex-shrink:0}
.main-menu{display:inline-block;min-height:90px}
.main-menu ul{list-style:none;margin:0;padding:0;display:flex;align-items:center;flex-wrap:nowrap;min-height:90px}
.main-menu ul>li{display:inline-block;position:relative;padding:0 8px;white-space:nowrap}
.main-menu ul>li>a{color:#fff;display:block;text-transform:capitalize;padding:32px 14px;position:relative;font-family:var(--font-saira);font-weight:500;font-size:14px;line-height:1.2;transition:all .5s ease-out 0s}
.main-menu ul>li ul.sub-menu{position:absolute;left:0;right:0;top:auto;margin:0;display:none;min-width:215px;background:#fff;box-shadow:0 30px 80px rgba(8,0,42,.08);text-align:left;z-index:999}
.main-menu .dropdown-icon{position:absolute;right:-5px;top:35px;font-size:20px;color:#fff;cursor:pointer;display:none;opacity:0}
.main-menu ul>li.menu-item-has-children::after{content:"\f282";font-family:"bootstrap-icons";font-weight:0;position:absolute;top:35px;right:12px;font-size:11px;color:#fff;transition:all .55s ease-in-out;display:none}

/* Lock header buttons in nav-right */
.nav-right{display:flex;align-items:center;gap:30px;flex-shrink:0;min-height:90px}
.header-btn a{font-family:var(--font-saira);font-weight:600;font-size:16px;letter-spacing:.03em;color:var(--theme-color);border:1px solid var(--theme-color);border-radius:5px;padding:10px 35px;transition:.5s;position:relative;display:inline-block;line-height:1.2
}
.sidebar-btn2{height:36px;width:36px;border-radius:15px;border:1px solid #fff;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:.35s;flex-shrink:0;background:transparent
}

/* Hide mobile menu elements on desktop to prevent accidental layout shift */
.mobile-logo-area{display:none}

/* Webfont display strategy — Saira needs to display immediately to prevent FOUT layout shift */
@font-face{font-family:'Saira Fallback';src:local('Arial');size-adjust:100%;ascent-override:95%;descent-override:30%;line-gap-override:5%}
@font-face{font-family:'Helvetica Fallback';src:local('Helvetica');size-adjust:107%;ascent-override:95%;descent-override:30%;line-gap-override:5%}

/* Mobile navigation close button */
.ctc-menu-close-btn{display:none}
@media (max-width:991px){
  .header-area2 .main-menu{position:fixed !important}
  .header-area2 .main-menu .mobile-logo-area{position:relative;padding-right:54px;min-height:44px}
  .ctc-menu-close-btn{
    display:flex !important;
    align-items:center;
    justify-content:center;
    position:absolute;
    top:8px;
    right:0;
    width:42px;
    height:42px;
    border:1px solid rgba(255,255,255,.35);
    border-radius:50%;
    background:rgba(6,216,137,.12);
    color:#fff;
    cursor:pointer;
    z-index:100002;
    padding:0;
    line-height:1;
  }
  .ctc-menu-close-btn i{
    color:#fff !important;
    font-size:22px !important;
    position:static !important;
    opacity:1 !important;
    display:block !important;
    line-height:1 !important;
  }
  .ctc-menu-close-btn:hover,
  .ctc-menu-close-btn:focus{
    background:var(--theme-color);
    color:#0A1019;
    outline:2px solid rgba(255,255,255,.45);
    outline-offset:2px;
  }
  .ctc-menu-close-btn:hover i,
  .ctc-menu-close-btn:focus i{color:#0A1019 !important}
  body.ctc-mobile-menu-open{overflow:hidden}
}
@media (min-width:992px){.ctc-menu-close-btn{display:none !important}}

</style>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="theme-color" content="#020617">
<meta name="format-detection" content="telephone=no">




<link rel="preconnect" href="https://www.googletagmanager.com" crossorigin>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preconnect" href="https://www.paypalobjects.com" crossorigin>
<link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
<link rel="dns-prefetch" href="https://www.googletagmanager.com">
<link rel="dns-prefetch" href="https://cdn.jsdelivr.net">

<link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
<link rel="icon" type="image/svg+xml" href="/favicon.svg" />
<link rel="shortcut icon" href="/favicon.ico" />
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
<link rel="manifest" href="/site.webmanifest">

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

<?php
$currentScriptName = basename($_SERVER['SCRIPT_NAME'] ?? '');
$isHomepage = $currentScriptName === 'index.php' || $currentScriptName === '';
?>
<!-- Critical CSS inlined above; remaining CSS loaded async to avoid render-blocking -->
<link rel="preload" href="/style.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="/style.min.css"></noscript>
<link rel="preload" href="/css/seo-engagement.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="/css/seo-engagement.min.css"></noscript>
<link rel="preload" href="/assets/css/bootstrap.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="/assets/css/bootstrap.min.css"></noscript>

<?php
// CSS loading strategy:
//   - Small CSS files: async via media="print" swap (saves render-blocking time)
//   - style2.min.css (227KB, contains layout-affecting rules): loaded synchronously
//     to prevent massive layout shift when its rules override our inlined critical CSS.
$asyncCss = $isHomepage
    ? [
        '/assets/css/bootstrap-icons.min.css',
        '/assets/css/boxicons.min.css',
        '/assets/css/swiper-bundle.min.css',
        '/assets/css/jquery.fancybox.min.css',
    ]
    : [
        '/assets/css/bootstrap-icons.min.css',
        '/assets/css/all.min.css',
        '/assets/css/fontawesome.min.css',
        '/assets/css/swiper-bundle.min.css',
        '/assets/css/jquery.fancybox.min.css',
        '/assets/css/boxicons.min.css',
        '/assets/css/preloader.min.css',
        '/assets/css/animate.min.css',
    ];
?>
<!-- style2.min.css: load synchronously (not async) to avoid layout shift when its rules apply. -->
<link rel="stylesheet" href="/assets/css/style2.min.css?v=20260711-2">

<?php
$pageStylesheets = isset($pageStylesheets) && is_array($pageStylesheets)
    ? array_values(array_unique(array_filter($pageStylesheets, 'is_string')))
    : [];
?>
<?php foreach ($pageStylesheets as $pageCss): ?>
<link rel="stylesheet" href="<?= htmlspecialchars($pageCss, ENT_QUOTES, 'UTF-8'); ?>">
<?php endforeach; ?>

<?php foreach ($asyncCss as $css): ?>
<link rel="preload" href="<?= htmlspecialchars($css, ENT_QUOTES, 'UTF-8'); ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="<?= htmlspecialchars($css, ENT_QUOTES, 'UTF-8'); ?>"></noscript>
<?php endforeach; ?>

<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0&family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,1,0&display=swap">

<meta name="p:domain_verify" content="ac5c484e34bef5bf8d2f4b91d7d28dba">
<link rel="alternate" type="application/rss+xml" title="Cloud Technology Computing Blog" href="/rss.xml">

<!-- Local SEO: Houston, TX targeting -->
<meta name="geo.region" content="US-TX">
<meta name="geo.placename" content="Houston">
<meta name="geo.position" content="29.7604;-95.3698">
<meta name="ICBM" content="29.7604, -95.3698">
<meta name="language" content="English">
<meta name="distribution" content="global">
<meta name="rating" content="general">
<meta name="revisit-after" content="7 days">

<?php if (basename($_SERVER['SCRIPT_NAME'] ?? '') === 'shop.php'): ?>
<script src="https://www.paypalobjects.com/ncp/cart/cart.js" data-merchant-id="<?= htmlspecialchars(getenv('PAYPAL_MERCHANT_ID') ?: '3DQHW2ED3QGBL', ENT_QUOTES, 'UTF-8'); ?>" defer></script>
<?php endif; ?>
<script src="/sw-register.min.js" defer></script>
