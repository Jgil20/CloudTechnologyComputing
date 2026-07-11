<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/project-functions.php';

$project = getProjectBySlugOrId($pdo);

if (!$project) {
    http_response_code(404);
    exit('Project not found.');
}

$projectId = (int) $project['id'];

$heroLargeImages = getProjectImages($pdo, $projectId, 'hero_large');
$heroSmallImages = getProjectImages($pdo, $projectId, 'hero_small');
$contentImages   = getProjectImages($pdo, $projectId, 'content');
$processSteps    = getProjectProcessSteps($pdo, $projectId);

$previousProject = getAdjacentProject($pdo, $projectId, 'previous');
$nextProject     = getAdjacentProject($pdo, $projectId, 'next');

/**
 * Convert content into clean metadata text.
 */
$cleanMetaText = static function (?string $text): string {
    $text = strip_tags((string) $text);
    $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');

    return trim(preg_replace('/\s+/u', ' ', $text) ?? '');
};

/**
 * Keep SEO descriptions within a reasonable length.
 */
$limitMetaText = static function (string $text, int $limit = 160): string {
    if ($text === '') {
        return '';
    }

    $length = function_exists('mb_strlen')
        ? mb_strlen($text, 'UTF-8')
        : strlen($text);

    if ($length <= $limit) {
        return $text;
    }

    $shortened = function_exists('mb_substr')
        ? mb_substr($text, 0, $limit - 3, 'UTF-8')
        : substr($text, 0, $limit - 3);

    $shortened = preg_replace('/\s+\S*$/u', '', $shortened) ?: $shortened;

    return rtrim($shortened, " \t\n\r\0\x0B,.;:-") . '...';
};

/**
 * Convert relative image paths into absolute URLs for social sharing.
 */
$absoluteImageUrl = static function (?string $path): string {
    $path = trim((string) $path);

    if ($path === '') {
        return 'https://www.cloudtechnologycomputing.com/assets/img/home-6/cloudbanner.jpg';
    }

    if (preg_match('#^https?://#i', $path)) {
        return $path;
    }

    return 'https://www.cloudtechnologycomputing.com/' . ltrim($path, '/');
};

$projectTitle = $cleanMetaText($project['title'] ?? 'Technology Project');

$liveProjectUrl = trim((string) ($project['live_url'] ?? ''));
$liveProjectLabel = trim((string) ($project['card_cta'] ?? 'View Live Project'));
if ($liveProjectLabel === '') {
    $liveProjectLabel = 'View Live Project';
}

$metaTitle = $cleanMetaText($project['meta_title'] ?? '');

if ($metaTitle === '') {
    $metaTitle = $projectTitle . ' | Cloud Technology Computing';
}

$descriptionSource =
    $project['meta_description']
    ?: $project['excerpt']
    ?: 'Explore this cloud computing, artificial intelligence, web development, or managed technology project from Cloud Technology Computing.';

$metaDescription = $limitMetaText(
    $cleanMetaText($descriptionSource),
    160
);

$featuredImage = $project['og_image']
    ?: $project['featured_image']
    ?: 'assets/img/home-6/CloudTechnologyComputingDisplay.avif';

$ogImage = $absoluteImageUrl($featuredImage);

$canonicalUrl =
    'https://www.cloudtechnologycomputing.com/project/'
    . rawurlencode((string) $project['slug']);

$structuredData = [
    '@context' => 'https://schema.org',
    '@graph' => [
        [
            '@type' => 'WebPage',
            '@id' => $canonicalUrl . '#webpage',
            'url' => $canonicalUrl,
            'name' => $metaTitle,
            'description' => $metaDescription,
            'isPartOf' => [
                '@id' => 'https://www.cloudtechnologycomputing.com/#website',
            ],
            'primaryImageOfPage' => [
                '@id' => $canonicalUrl . '#primaryimage',
            ],
        ],
        [
            '@type' => 'ImageObject',
            '@id' => $canonicalUrl . '#primaryimage',
            'url' => $ogImage,
            'contentUrl' => $ogImage,
            'caption' => $projectTitle,
        ],
        [
            '@type' => 'CreativeWork',
            '@id' => $canonicalUrl . '#project',
            'mainEntityOfPage' => [
                '@id' => $canonicalUrl . '#webpage',
            ],
            'name' => $projectTitle,
            'headline' => $projectTitle,
            'description' => $metaDescription,
            'url' => $canonicalUrl,
            'image' => [
                '@id' => $canonicalUrl . '#primaryimage',
            ],
            'creator' => [
                '@type' => 'Person',
                'name' => 'Jhon Arzu-Gil',
            ],
            'author' => [
                '@type' => 'Organization',
                '@id' => 'https://www.cloudtechnologycomputing.com/#organization',
                'name' => 'Cloud Technology Computing',
                'url' => 'https://www.cloudtechnologycomputing.com/',
            ],
            'publisher' => [
                '@id' => 'https://www.cloudtechnologycomputing.com/#organization',
            ],
            'provider' => [
                '@id' => 'https://www.cloudtechnologycomputing.com/#organization',
            ],
        ],
        [
            '@type' => 'Organization',
            '@id' => 'https://www.cloudtechnologycomputing.com/#organization',
            'name' => 'Cloud Technology Computing',
            'url' => 'https://www.cloudtechnologycomputing.com/',
            'logo' => [
                '@type' => 'ImageObject',
                'url' => 'https://www.cloudtechnologycomputing.com/assets/img/sm-logo.svg',
            ],
        ],
        [
            '@type' => 'WebSite',
            '@id' => 'https://www.cloudtechnologycomputing.com/#website',
            'url' => 'https://www.cloudtechnologycomputing.com/',
            'name' => 'Cloud Technology Computing',
            'publisher' => [
                '@id' => 'https://www.cloudtechnologycomputing.com/#organization',
            ],
        ],
    ],
];
?>
<?php include __DIR__ . '/header.php'; ?>

<title><?= e($metaTitle); ?></title>

<meta name="author" content="Jhon Arzu-Gil">
<meta name="description" content="<?= e($metaDescription); ?>">
<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">

<link rel="canonical" href="<?= e($canonicalUrl); ?>">

<!-- Open Graph -->
<meta property="og:type" content="article">
<meta property="og:site_name" content="Cloud Technology Computing">
<meta property="og:locale" content="en_US">
<meta property="og:title" content="<?= e($metaTitle); ?>">
<meta property="og:description" content="<?= e($metaDescription); ?>">
<meta property="og:url" content="<?= e($canonicalUrl); ?>">
<meta property="og:image" content="<?= e($ogImage); ?>">
<meta property="og:image:alt" content="<?= e($projectTitle); ?>">

<!-- Twitter / X -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@CTCCorporation">
<meta name="twitter:creator" content="@JhonArzuGil">
<meta name="twitter:title" content="<?= e($metaTitle); ?>">
<meta name="twitter:description" content="<?= e($metaDescription); ?>">
<meta name="twitter:image" content="<?= e($ogImage); ?>">
<meta name="twitter:image:alt" content="<?= e($projectTitle); ?>">


<script type="application/ld+json">
<?= json_encode(
    $structuredData,
    JSON_UNESCAPED_SLASHES
    | JSON_UNESCAPED_UNICODE
    | JSON_HEX_TAG
    | JSON_HEX_AMP
    | JSON_HEX_APOS
    | JSON_HEX_QUOT
    | JSON_PRETTY_PRINT
); ?>
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
            <img loading="lazy" src="<?= assetPath('assets/img/inner-pages/ArtificialIntelligience.avif'); ?>" alt="Project details banner" width="300" height="300"   >
        </div>

        <div class="inner-banner-2 magnetic-item">
            <img loading="lazy" src="<?= assetPath('assets/img/inner-pages/Wordpress2.avif'); ?>" alt="Project details banner" width="300" height="300"   >
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb-wrapper">
                    <div class="breadcrumb-cnt">
                        <span>Project Details</span>
                        <h1><?= e($project['title']); ?></h1>

                        <div class="breadcrumb-list">
                            <a href="/">Home</a>
                            <img loading="lazy" src="<?= assetPath('assets/img/inner-pages/breadcrumb-arrow.svg'); ?>" alt="" width="16" height="9"   >
                            Project Details
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="portfolio-details sec-mar">
    <div class="container">

        <div class="row g-4 mb-80">
            <div class="col-lg-7">
                <div class="portfolio-img magnetic-item">
                    <img 
                        loading="lazy" 
                        class="img-fluid" 
                        src="<?= e(assetPath($heroLargeImages[0]['image'] ?? $project['featured_image'])); ?>" 
                        alt="<?= e($heroLargeImages[0]['image_alt'] ?? $project['featured_image_alt'] ?? $project['title']); ?>" width="800" height="500"   >
                </div>
            </div>

            <div class="col-lg-5">
                <div class="row g-4">
                    <?php if (!empty($heroSmallImages)): ?>
                        <?php foreach (array_slice($heroSmallImages, 0, 2) as $image): ?>
                            <div class="col-lg-12">
                                <div class="portfolio-img magnetic-item">
                                    <img 
                                        loading="lazy" 
                                        class="img-fluid" 
                                        src="<?= e(assetPath($image['image'])); ?>" 
                                        alt="<?= e($image['image_alt'] ?: $project['title']); ?>" width="800" height="500"   >
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-lg-12">
                            <div class="portfolio-img magnetic-item">
                                <img loading="lazy" class="img-fluid" src="<?= assetPath('assets/img/inner-pages/portfolio-dt-02.png'); ?>" alt="<?= e($project['title']); ?>" width="800" height="500"   >
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="portfolio-img magnetic-item">
                                <img loading="lazy" class="img-fluid" src="<?= assetPath('assets/img/inner-pages/portfolio-dt-03.png'); ?>" alt="<?= e($project['title']); ?>" width="800" height="500"   >
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="row gy-5">
            <div class="col-lg-8">
                <div class="portfolio-content">
                    <h3>Project Overview</h3>

                    <?php if (!empty($project['overview_html'])): ?>
                        <?= $project['overview_html']; ?>
                    <?php else: ?>
                        <p><?= e($project['excerpt']); ?></p>
                    <?php endif; ?>

                    <?php if (!empty($processSteps)): ?>
                        <div class="working-process">
                            <h3>Our Process</h3>

                            <div class="row g-4 justify-content-center">
                                <?php foreach ($processSteps as $step): ?>
                                    <div class="col-xl-4 col-sm-6">
                                        <div class="single-process magnetic-item">
                                            <?php if (!empty($step['icon'])): ?>
                                                <div class="icon">
                                                    <img loading="lazy" src="<?= e(assetPath($step['icon'])); ?>" alt="<?= e($step['title']); ?>" width="64" height="64"   >
                                                </div>
                                            <?php endif; ?>

                                            <span><?= e($step['step_number']); ?></span>
                                            <h3><?= e($step['title']); ?></h3>
                                            <p><?= e($step['description']); ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($contentImages)): ?>
                        <div class="row g-4 mb-55">
                            <?php foreach (array_slice($contentImages, 0, 2) as $index => $image): ?>
                                <div class="<?= $index === 0 ? 'col-lg-7' : 'col-lg-5'; ?> col-sm-6">
                                    <div class="portfolio-img magnetic-item">
                                        <img 
                                            loading="lazy" 
                                            class="img-fluid" 
                                            src="<?= e(assetPath($image['image'])); ?>" 
                                            alt="<?= e($image['image_alt'] ?: $project['title']); ?>" width="800" height="500"   >
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <h3>Result</h3>

                    <?php if (!empty($project['result_html'])): ?>
                        <?= $project['result_html']; ?>
                    <?php else: ?>
                        <p>This project helped create a stronger, more scalable, and more professional digital solution.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="portfolio-info">
                    <ul>
                        <li>
                            <span>Client:</span>
                            <h5><?= e($project['client_name'] ?: 'Cloud Technology Computing'); ?></h5>
                        </li>

                        <li>
                            <span>Company:</span>
                            <h5><?= e($project['company_name'] ?: 'Cloud Technology Computing'); ?></h5>
                        </li>

                        <li>
                            <span>Location:</span>
                            <h5><?= e($project['location'] ?: 'Houston, Texas'); ?></h5>
                        </li>

                        <li>
                            <span>Project Type:</span>
                            <h5><?= e($project['project_type'] ?: 'Technology Project'); ?></h5>
                        </li>

                        <li>
                            <span>Duration:</span>
                            <h5><?= e($project['duration'] ?: 'Ongoing'); ?></h5>
                        </li>
                    </ul>
                </div>

                <?php if ($liveProjectUrl !== ''): ?>
                    <div class="portfolio-details-sm-banner" style="margin-bottom: 30px;">
                        <div class="section-title-5">
                            <h2>View the <br><span>live project</span></h2>

                            <div class="get-btn">
                                <a class="primary-btn3" href="<?= e(urlPath($liveProjectUrl)); ?>" target="_blank" rel="noopener noreferrer"><?= e($liveProjectLabel); ?></a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="portfolio-details-sm-banner">
                    <div class="section-title-5">
                        <h2>Ready to <br><span>work with us?</span></h2>

                        <div class="get-btn">
                            <a class="primary-btn3" href="<?= urlPath('form.php'); ?>">Free Consultation!</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($previousProject || $nextProject): ?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="details-navigation">

                        <?php if ($previousProject): ?>
                            <div class="single-navigation">
                                <div class="content">
                                    <a href="<?= e(projectUrl($previousProject['slug'])); ?>">Previous</a>

                                    <h4>
                                        <a href="<?= e(projectUrl($previousProject['slug'])); ?>">
                                            <?= e($previousProject['title']); ?>
                                        </a>
                                    </h4>
                                </div>

                                <a href="<?= e(projectUrl($previousProject['slug'])); ?>" class="img">
                                    <img 
                                        loading="lazy" 
                                        src="<?= e(assetPath($previousProject['featured_image'])); ?>" 
                                        alt="<?= e($previousProject['featured_image_alt'] ?: $previousProject['title']); ?>" width="800" height="500"   >

                                    <div class="arrow">
                                        <svg width="12" height="12" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M0 1H12M12 1V13M12 1L0.5 12"></path>
                                        </svg>
                                    </div>
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if ($nextProject): ?>
                            <div class="single-navigation two">
                                <a href="<?= e(projectUrl($nextProject['slug'])); ?>" class="img">
                                    <img 
                                        loading="lazy" 
                                        src="<?= e(assetPath($nextProject['featured_image'])); ?>" 
                                        alt="<?= e($nextProject['featured_image_alt'] ?: $nextProject['title']); ?>" width="800" height="500"   >

                                    <div class="arrow">
                                        <svg width="12" height="12" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M0 1H12M12 1V13M12 1L0.5 12"></path>
                                        </svg>
                                    </div>
                                </a>

                                <div class="content">
                                    <a href="<?= e(projectUrl($nextProject['slug'])); ?>">Next</a>

                                    <h4>
                                        <a href="<?= e(projectUrl($nextProject['slug'])); ?>">
                                            <?= e($nextProject['title']); ?>
                                        </a>
                                    </h4>
                                </div>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>
