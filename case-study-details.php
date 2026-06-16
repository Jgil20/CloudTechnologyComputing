<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/case-study-functions.php';

$caseStudy = getCaseStudyBySlugOrId($pdo);

if (!$caseStudy) {
    http_response_code(404);
    exit('Case study not found.');
}

$sections = getCaseStudySections($pdo, (int) $caseStudy['id']);
$processSteps = getCaseStudyProcessSteps($pdo, (int) $caseStudy['id']);
$galleryImages = getCaseStudyGallery($pdo, (int) $caseStudy['id']);

$canonicalUrl = 'https://www.cloudtechnologycomputing.com/case-study/' . urlencode($caseStudy['slug']);
$metaTitle = $caseStudy['meta_title'] ?: $caseStudy['title'];
$metaDescription = $caseStudy['meta_description'] ?: $caseStudy['excerpt'];
$ogImage = $caseStudy['og_image'] ?: assetPath($caseStudy['featured_image']);
$ogImageAbsolute = (str_starts_with($ogImage, 'http://') || str_starts_with($ogImage, 'https://'))
    ? $ogImage
    : 'https://www.cloudtechnologycomputing.com' . $ogImage;
$publishedAt = !empty($caseStudy['published_at']) ? strtotime($caseStudy['published_at']) : null;
$updatedAt = !empty($caseStudy['updated_at']) ? strtotime($caseStudy['updated_at']) : null;
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title><?= e($metaTitle) ?></title>
  <meta name="description" content="<?= e($metaDescription) ?>">
  <meta name="author" content="Jhon Arzu-Gil">
  <meta name="robots" content="index, follow">
  <link rel="canonical" href="<?= e($canonicalUrl) ?>">

  <meta property="og:title" content="<?= e($metaTitle) ?>">
  <meta property="og:description" content="<?= e($metaDescription) ?>">
  <meta property="og:url" content="<?= e($canonicalUrl) ?>">
  <meta property="og:image" content="<?= e($ogImage) ?>">
  <meta property="og:site_name" content="Cloud Technology Computing">
  <meta property="og:type" content="article">

  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?= e($metaTitle) ?>">
  <meta name="twitter:description" content="<?= e($metaDescription) ?>">
  <meta name="twitter:image" content="<?= e($ogImage) ?>">

  <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/css/bootstrap-icons.css" rel="stylesheet">
  <link href="../assets/css/all.min.css" rel="stylesheet">
  <link href="../assets/css/fontawesome.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/swiper-bundle.min.css">
  <link rel="stylesheet" href="../assets/css/animate.min.css">
  <link rel="stylesheet" href="../assets/css/jquery.fancybox.min.css">
  <link href="../assets/css/boxicons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/preloader.css">
  <link rel="stylesheet" href="../assets/css/style2.css">
    <link rel="stylesheet" href="../style.css" as="style">
<link rel="stylesheet" href="../assets/css/blog-refactor.css">

  <link rel="preload" href="../assets/css/styles.min.css" as="style">
<link rel="stylesheet" href="../assets/css/styles.min.css" media="print" onload="this.media='all'">
<noscript>
    <link rel="stylesheet" href="../assets/css/styles.min.css">
</noscript>
<script src="../assets/js/scripts.min.js" defer></script>
  <link rel="icon" href="../assets/img/sm-logo.svg" type="image/gif" sizes="20x20">





  <script type="application/ld+json">
  <?= json_encode([
      '@context' => 'https://schema.org',
      '@type' => 'Article',
      'headline' => $metaTitle,
      'description' => $metaDescription,
      'image' => $ogImageAbsolute,
      'author' => [
          '@type' => 'Organization',
          'name' => 'Cloud Technology Computing',
          'url' => 'https://www.cloudtechnologycomputing.com/',
      ],
      'publisher' => [
          '@type' => 'Organization',
          'name' => 'Cloud Technology Computing',
          'logo' => [
              '@type' => 'ImageObject',
              'url' => 'https://www.cloudtechnologycomputing.com/assets/img/sm-logo.svg',
          ],
      ],
      'datePublished' => $publishedAt ? date('c', $publishedAt) : null,
      'dateModified' => $updatedAt ? date('c', $updatedAt) : ($publishedAt ? date('c', $publishedAt) : null),
      'mainEntityOfPage' => $canonicalUrl,
  ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>
  </script>

</head>

<body class="home-dark2 tt-magic-cursor">

<div class="preloader">
    <div id="particles-background" class="vertical-centered-box"></div>
    <div id="particles-foreground" class="vertical-centered-box"></div>
    <div class="vertical-centered-box">
        <div class="content">
            <div class="loader-circle"></div>
            <div class="loader-line-mask">
                <div class="loader-line"></div>
            </div>
        </div>
    </div>
</div>

<div class="header-sidebar">
    <div class="siderbar-top">
        <div class="sidebar-log">
            <a href="/">Cloud Technology Computing</a>
        </div>
        <div class="close-btn">
            <i class="bi bi-x-lg"></i>
        </div>
    </div>

    <div class="sidebar-content">
        <p>🌐 Cloud Technology Computing | Texas-Based | Cloud Computing & Web Development Wizards 🚀</p>
    </div>

    <div class="address-card">
        <div class="content">
            <div class="informations">
                <div class="single-info">
                    <div class="icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="info">
                        <p>Houston, Texas, United States</p>
                    </div>
                </div>

                <div class="single-info">
                    <div class="icon">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <div class="info">
                        <a href="tel:17138709966">+1 713 870 9966</a>
                    </div>
                </div>

                <div class="single-info">
                    <div class="icon">
                        <i class="far fa-envelope"></i>
                    </div>
                    <div class="info">
                        <a href="mailto:Jgil20@me.com">Jgil20@me.com</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "nav.php"; ?>

<section class="breadcrumbs">
    <div class="breadcrumb-sm-images">
        <div class="inner-banner-1 magnetic-item">
            <img 
                loading="lazy" 
                src="<?= assetPath('assets/img/inner-pages/OnlineAdvertisingCloudTechnologyComputing.avif'); ?>" 
                alt="Cloud technology advertising">
        </div>

        <div class="inner-banner-2 magnetic-item">
            <img 
                loading="lazy" 
                src="<?= assetPath('assets/img/inner-pages/ibm cloud provider.avif'); ?>" 
                alt="Cloud provider">
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb-wrapper">
                    <div class="breadcrumb-cnt">
                        <span>Case Study Details</span>

                        <h1><?= e($caseStudy['subtitle'] ?: $caseStudy['title']); ?></h1>

                        <div class="breadcrumb-list">
                            <a href="/">Home</a>
                            <img 
                                loading="lazy" 
                                src="<?= assetPath('assets/img/inner-pages/breadcrumb-arrow.svg'); ?>" 
                                alt="">
                            Case Study Details
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="case-study-details sec-mar">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">

                <div class="case-study-title">
                    <h2><?= e($caseStudy['title']); ?></h2>
                </div>

                <div class="case-big-img magnetic-item">
                    <img 
                        loading="lazy" 
                        class="img-fluid" 
                        src="<?= assetPath($caseStudy['featured_image']); ?>" 
                        alt="<?= e($caseStudy['featured_image_alt'] ?: $caseStudy['title']); ?>">
                </div>

                <?php if (!empty($caseStudy['content_intro'])): ?>
                    <div class="case-content mb-60">
                        <?= $caseStudy['content_intro']; ?>
                    </div>
                <?php endif; ?>

                <?php foreach ($sections as $section): ?>
                    <?php if ($section['image_position'] === 'full' || empty($section['section_image'])): ?>

                        <div class="row mb-120">
                            <div class="col-lg-12">
                                <div class="case-content mb-60">
                                    <h2><?= e($section['section_title']); ?></h2>
                                    <?= $section['section_content']; ?>
                                </div>
                            </div>
                        </div>

                    <?php elseif ($section['image_position'] === 'left'): ?>

                        <div class="row g-lg-4 gy-5 mb-120">
                            <div class="col-lg-6">
                                <div class="case-img magnetic-item">
                                    <img 
                                        loading="lazy" 
                                        class="img-fluid" 
                                        src="<?= assetPath($section['section_image']); ?>" 
                                        alt="<?= e($section['section_image_alt'] ?: $section['section_title']); ?>">
                                </div>
                            </div>

                            <div class="col-lg-6 d-flex align-items-center">
                                <div class="case-content">
                                    <h2><?= e($section['section_title']); ?></h2>
                                    <?= $section['section_content']; ?>
                                </div>
                            </div>
                        </div>

                    <?php else: ?>

                        <div class="row g-lg-4 gy-5 mb-120">
                            <div class="col-lg-6 d-flex align-items-center">
                                <div class="case-content">
                                    <h2><?= e($section['section_title']); ?></h2>
                                    <?= $section['section_content']; ?>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="case-img magnetic-item">
                                    <img 
                                        loading="lazy" 
                                        class="img-fluid" 
                                        src="<?= assetPath($section['section_image']); ?>" 
                                        alt="<?= e($section['section_image_alt'] ?: $section['section_title']); ?>">
                                </div>
                            </div>
                        </div>

                    <?php endif; ?>

                    <?php if ($section['section_title'] === 'Process Load Times' && !empty($processSteps)): ?>
                        <div class="row g-4 justify-content-center mb-120">
                            <?php foreach ($processSteps as $step): ?>
                                <div class="col-xl-3 col-sm-6">
                                    <div class="single-process magnetic-item">
                                        <?php if (!empty($step['icon'])): ?>
                                            <div class="icon">
                                                <img 
                                                    loading="lazy" 
                                                    src="<?= assetPath($step['icon']); ?>" 
                                                    alt="<?= e($step['title']); ?>">
                                            </div>
                                        <?php endif; ?>

                                        <span><?= e($step['step_number']); ?></span>
                                        <h3><?= e($step['title']); ?></h3>
                                        <p><?= e($step['description']); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                <?php endforeach; ?>

                <?php if (!empty($galleryImages)): ?>
                    <div class="row g-4 justify-content-center">
                        <?php foreach ($galleryImages as $image): ?>
                            <div class="col-md-6">
                                <div class="case-img magnetic-item">
                                    <img 
                                        loading="lazy" 
                                        class="img-fluid" 
                                        src="<?= assetPath($image['image']); ?>" 
                                        alt="<?= e($image['image_alt'] ?: $caseStudy['title']); ?>">
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/emoji-mart@latest/dist/browser.js"></script>

    <!-- Linking custom script -->
    <script src="../script.js"></script>
  <script>
       $(".marquee_text").marquee({
       direction: "left",
       duration: 20000,
       gap: 50,
       delayBeforeStart: 0,
       duplicated: true,
       startVisible: true,
       });

       $(".marquee_text3").marquee({
       direction: "left",
       duration: 30000,
       gap: 50,
       delayBeforeStart: 0,
       duplicated: true,
       startVisible: true,
       });
   </script>

<script src="../assets/js/jquery-3.6.0.min.js"></script>
<script src="../assets/js/popper.min.js"></script>
<script src="../assets/js/bootstrap.min.js"></script>
<script src="../assets/js/swiper-bundle.min.js"></script>
<script src="../assets/js/waypoints.min.js"></script>
<script src="../assets/js/jquery.counterup.min.js"></script>
<script src="../assets/js/isotope.pkgd.min.js"></script>
<script src="../assets/js/jquery.fancybox.min.js"></script>
<script src="../assets/js/gsap.min.js"></script>
<script src="../assets/js/simpleParallax.min.js"></script>
<script src="../assets/js/TweenMax.min.js"></script>
<script src="../assets/js/jquery.marquee.min.js"></script>
<script src="../assets/js/wow.min.js"></script>
<script src="../assets/js/preloader.js"></script>
<script src="../assets/js/custom.js"></script>

</body>
</html>