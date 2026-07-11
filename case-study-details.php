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
<?php
$pagePreloadImage = assetPath($caseStudy['featured_image'] ?: 'assets/img/home-6/CloudTechnologyComputingDisplay.avif');
include __DIR__ . '/header.php';
?>

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




<!-- Article and BreadcrumbList structured data -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Article",
    "@id": "<?= e($canonicalUrl); ?>#article",
    "headline": "<?= e($metaTitle); ?>",
    "description": "<?= e($metaDescription); ?>",
    "image": "<?= e($ogImageAbsolute); ?>",
    "datePublished": "<?= e($caseStudy['published_at'] ?? ''); ?>",
    "dateModified": "<?= e($caseStudy['updated_at'] ?? $caseStudy['published_at'] ?? ''); ?>",
    "author": {
        "@type": "Person",
        "name": "Jhon Arzu-Gil",
        "url": "https://www.arzugil.com/"
    },
    "publisher": {
        "@type": "Organization",
        "@id": "https://www.cloudtechnologycomputing.com/#organization",
        "name": "Cloud Technology Computing",
        "logo": {
            "@type": "ImageObject",
            "url": "https://www.cloudtechnologycomputing.com/assets/img/sm-logo.svg"
        }
    },
    "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "<?= e($canonicalUrl); ?>"
    },
    "inLanguage": "en-US"
}
</script>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        {"@type": "ListItem", "position": 1, "name": "Home", "item": "https://www.cloudtechnologycomputing.com/"},
        {"@type": "ListItem", "position": 2, "name": "Case Studies", "item": "https://www.cloudtechnologycomputing.com/case-study-standard.php"},
        {"@type": "ListItem", "position": 3, "name": "<?= e($caseStudy['title']); ?>", "item": "<?= e($canonicalUrl); ?>"}
    ]
}
</script>
</head>

<body class="home-dark2">

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
                alt="Cloud technology advertising" width="164" height="210"   >
        </div>

        <div class="inner-banner-2 magnetic-item">
            <img 
                loading="lazy" 
                src="<?= assetPath('assets/img/inner-pages/ibm cloud provider.avif'); ?>" 
                alt="Cloud provider" width="250" height="191"   >
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
                                alt="" width="16" height="9"   >
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
                        alt="<?= e($caseStudy['featured_image_alt'] ?: $caseStudy['title']); ?>" width="800" height="500"   >
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
                                        alt="<?= e($section['section_image_alt'] ?: $section['section_title']); ?>" width="800" height="600"   >
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
                                        alt="<?= e($section['section_image_alt'] ?: $section['section_title']); ?>" width="800" height="600"   >
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
                                                    alt="<?= e($step['title']); ?>" width="64" height="64"   >
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
                                        alt="<?= e($image['image_alt'] ?: $caseStudy['title']); ?>" width="800" height="500"   >
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
