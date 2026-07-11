<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/project-list-data.php';

$portfolioPage = ctc_portfolio_page_data();
$seo = $portfolioPage['seo'];
$portfolioPageInfo = $portfolioPage['page'];
$portfolioProjects = $portfolioPage['projects'];

$databaseProjects = ctc_portfolio_projects_from_database();
if (!empty($databaseProjects)) {
    $portfolioProjects = $databaseProjects;
}

$perPage = max(1, (int) ($portfolioPageInfo['per_page'] ?? 6));
$totalProjects = count($portfolioProjects);
$totalPages = max(1, (int) ceil($totalProjects / $perPage));
$currentPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
$currentPage = max(1, min($currentPage, $totalPages));
$offset = ($currentPage - 1) * $perPage;
$visibleProjects = array_slice($portfolioProjects, $offset, $perPage);

$metaTitle = (string) ($seo['title'] ?? 'Projects | Cloud Technology Computing');
if ($currentPage > 1) {
    $metaTitle = ctc_truncate_text($metaTitle . ' - Page ' . $currentPage, 65);
}

$metaDescription = ctc_truncate_text(
    (string) ($seo['description'] ?? 'Explore cloud, AI, web development, mobile app, and SEO projects by Cloud Technology Computing.'),
    165
);

$canonicalPath = (string) ($seo['canonical'] ?? '/project.php');
if ($currentPage > 1) {
    $canonicalPath .= '?page=' . $currentPage;
}
$canonicalUrl = ctc_absolute_url($canonicalPath);
$ogImage = ctc_absolute_url((string) ($seo['image'] ?? '/assets/img/home-6/cloudbanner.jpg'));
$robots = (string) ($seo['robots'] ?? 'index, follow');
$siteName = (string) ($seo['site_name'] ?? 'Cloud Technology Computing');
$locale = (string) ($seo['locale'] ?? 'en_US');
$ogType = (string) ($seo['type'] ?? 'website');
$twitterSite = (string) ($seo['twitter_site'] ?? '@JhonArzuGil');
$twitterCreator = (string) ($seo['twitter_creator'] ?? '@JhonArzuGil');
$author = (string) ($seo['author'] ?? 'Jhon Arzu-Gil');
$imageAlt = (string) ($seo['image_alt'] ?? 'Cloud Technology Computing project portfolio');

$breadcrumbItems = [];
foreach (($portfolioPage['breadcrumb'] ?? []) as $index => $item) {
    $breadcrumbItems[] = [
        '@type' => 'ListItem',
        'position' => $index + 1,
        'name' => (string) ($item['name'] ?? ''),
        'item' => ctc_absolute_url((string) ($item['url'] ?? '/')),
    ];
}

$itemListElements = [];
foreach ($portfolioProjects as $index => $projectItem) {
    $itemListElements[] = [
        '@type' => 'ListItem',
        'position' => $index + 1,
        'name' => (string) ($projectItem['title'] ?? 'Project'),
        'url' => ctc_absolute_url((string) ($projectItem['url'] ?? '/project.php')),
        'image' => ctc_absolute_url((string) ($projectItem['image'] ?? '/assets/img/home-6/cloudbanner.jpg')),
    ];
}

$structuredData = [
    '@context' => 'https://schema.org',
    '@graph' => [
        [
            '@type' => 'BreadcrumbList',
            'itemListElement' => $breadcrumbItems,
        ],
        [
            '@type' => 'CollectionPage',
            '@id' => $canonicalUrl . '#webpage',
            'url' => $canonicalUrl,
            'name' => $metaTitle,
            'description' => $metaDescription,
            'isPartOf' => [
                '@id' => 'https://www.cloudtechnologycomputing.com/#website',
            ],
            'mainEntity' => [
                '@id' => $canonicalUrl . '#project-list',
            ],
        ],
        [
            '@type' => 'ItemList',
            '@id' => $canonicalUrl . '#project-list',
            'name' => 'Cloud Technology Computing Project Portfolio',
            'numberOfItems' => $totalProjects,
            'itemListElement' => $itemListElements,
        ],
    ],
];

$buildProjectPageUrl = static function (int $page): string {
    return $page <= 1 ? '/project.php' : '/project.php?page=' . $page;
};

include 'header.php';
?>
<meta name="author" content="<?= ctc_h($author); ?>">
<meta name="copyright" content="<?= ctc_h($author); ?>" />
<meta name="description" content="<?= ctc_h($metaDescription); ?>">
<meta name="robots" content="<?= ctc_h($robots); ?>"> 
<!-- Open Graph / Facebook -->
<meta property="og:title" content="<?= ctc_h($metaTitle); ?>">
<meta property="og:description" content="<?= ctc_h($metaDescription); ?>">
<meta property="og:url" content="<?= ctc_h($canonicalUrl); ?>">
<meta property="og:image" content="<?= ctc_h($ogImage); ?>">
<meta property="og:site_name" content="<?= ctc_h($siteName); ?>" />
<meta property="og:locale" content="<?= ctc_h($locale); ?>" />
<meta property="og:type" content="<?= ctc_h($ogType); ?>">
<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image"/>
<meta name="twitter:title" content="<?= ctc_h($metaTitle); ?>">
<meta name="twitter:description" content="<?= ctc_h($metaDescription); ?>">
<meta property="twitter:site" content="<?= ctc_h($twitterSite); ?>">
<meta property="twitter:image" content="<?= ctc_h($ogImage); ?>">
<meta name="twitter:creator" content="<?= ctc_h($twitterCreator); ?>"/>
<meta property="twitter:url" content="<?= ctc_h($canonicalUrl); ?>">
<meta name="twitter:image:alt" content="<?= ctc_h($imageAlt); ?>" />  
<link rel="canonical" href="<?= ctc_h($canonicalUrl); ?>" />
<title><?= ctc_h($metaTitle); ?></title>

<!-- Portfolio structured data -->
<script type="application/ld+json">
<?= json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); ?>
</script>
</head>

<body class="home-dark2">
    <!-- Preloader Start -->
    <div class="preloader">
        <div id="particles-background" class="vertical-centered-box"></div>
        <div id="particles-foreground" class="vertical-centered-box"></div>
    
        <div class="vertical-centered-box">
        <div class="content">
            <div class="loader-circle"></div>
            <div class="loader-line-mask">
            <div class="loader-line"></div>
            </div>
            <svg width="50" height="50" viewBox="0 0 40 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M31.5875 7.80132C26.1756 2.71548 18.9772 3.33531 13.0177 7.36702C12.9433 7.45181 12.4808 7.69025 12.9963 6.94836C24.4371 -5.54919 45.4795 11.5151 33.7252 25.7347C36.3568 20.0872 37.0161 12.9032 31.5879 7.80144L31.5875 7.80132Z"
                    fill="#06D889" />
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M26.7504 1.91075C8.15888 -3.63601 -7.81139 25.1051 12.8958 38C-10.3418 27.992 1.07241 -2.40195 21.5296 0.151704C23.1991 0.358215 25.7562 1.14769 26.7503 1.91051L26.7504 1.91075Z"
                    fill="#06D889" />
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M31.656 20.3691C31.656 26.5676 26.6425 31.6058 20.4701 31.6058C14.2923 31.6058 9.2793 26.5675 9.2793 20.3691C9.2793 14.1705 14.2928 9.13232 20.4701 9.13232C26.6425 9.13232 31.656 14.1706 31.656 20.3691ZM12.2671 21.8578C11.4325 23.1348 12.4106 26.377 15.3081 28.2948C18.1789 30.2125 21.8579 30.0695 22.7139 28.7876C23.5485 27.5373 21.7676 28.3426 18.514 27.1345C13.1444 25.1426 13.0966 20.5759 12.2671 21.8578Z"
                    fill="#06D889" />
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M38.395 13.1796C46.0027 27.7854 24.886 46.5405 10.1649 33.2636C8.28281 31.579 7.45359 29.9525 6.08203 27.8385C17.5284 43.6315 42.7177 31.1549 38.1986 13.4121C38.0338 12.7603 38.1402 12.7021 38.3952 13.179L38.395 13.1796Z"
                    fill="#06D889" />
            </svg>
        </div>
        </div>
    
    </div>
    <!-- Preloader End -->
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
            <p>"🌐 Cloud Technology Computing | Est. Oct 6, 2023 | Texas-Based | Cloud Computing & Web Development Wizards 🚀 #TechInnovators"</p>
        </div>
        <div class="address-card">
            <div class="content">
                <div class="informations">
                    <div class="single-info">
                        <div class="icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="info">
                            <p>4409 Caplin St, Houston, TX 77026, United States</p>
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
                            <a href="mailto: Jgil20@me.com">Jgil20@me.com</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="follow-area">
            <h5 class="blog-widget-title">Follow Us</h5>
            <p class="para">Follow us on Social Network</p>
            <div class="blog-widget-body">
                <ul class="follow-list d-flex flex-row align-items-start gap-4">
                    
                    <li><a href="https://www.facebook.com/CloudTechnologyComputingCorporation" aria-label="Visit Cloud Technology Computing on Facebook" target="_blank" rel="noopener noreferrer"><i class="bx bxl-facebook"></i></a></li>
                    <li><a href="https://twitter.com/CTCCorporation" aria-label="Visit Cloud Technology Computing on X" target="_blank" rel="noopener noreferrer"><i class="bx bxl-twitter"></i></a></li>
                    <li><a href="https://www.instagram.com/cloudtechnologycomputing" target="_blank" rel="noopener noreferrer" aria-label="Visit Cloud Technology Computing on Instagram"><i class="bx bxl-instagram"></i></a></li>
                    <li><a href="https://www.pinterest.com/CloudTechnologyComputing" target="_blank" rel="noopener noreferrer" aria-label="Visit Cloud Technology Computing on Pinterest"><i class="bx bxl-pinterest"></i></a></li>
                </ul>
            </div>
        </div>
    </div>
 <!-- Start header section -->
      <?php include"nav.php" ?> 
    <!-- End header section -->
    <!-- Start breadcrumbs section -->
    <section class="breadcrumbs">
        <div class="breadcrumb-sm-images">
            <div class="inner-banner-1 magnetic-item">
                <img loading="lazy" src="assets/img/inner-pages/OnlineAdvertisingCloudTechnologyComputing.avif" alt="computer clouds" width="164" height="210"   >
            </div>
            <div class="inner-banner-2 magnetic-item">
                <img loading="lazy" src="assets/img/inner-pages/ibm cloud provider.avif" alt="cloud what" width="250" height="191"   >
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-wrapper">
                        <div class="breadcrumb-cnt">
                            <span><?= ctc_h($portfolioPageInfo['eyebrow'] ?? 'Projects'); ?></span>
                            <h1><?= ctc_h($portfolioPageInfo['h1'] ?? 'Our Completed Projects'); ?></h1>
                            <div class="breadcrumb-list">
                                <a href="/">Home</a><img loading="lazy" src="assets/img/inner-pages/breadcrumb-arrow.svg" alt="" width="16" height="9"   > Projects
                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End breadcrumbs section -->
    <div class="home3-success-stories-area two sec-mar">
        <div class="container-fluid">
            <div class="row g-4 justify-content-center">
                <h2 class="visually-hidden"><?= ctc_h($portfolioPageInfo['hidden_h2'] ?? 'Cloud, Web, and Mobile Project Portfolio'); ?></h2>
                <?php if (empty($visibleProjects)): ?>
                    <div class="col-lg-8 col-md-10 col-sm-12">
                        <div class="success-storie-card">
                            <div class="success-content">
                                <span>Projects</span>
                                <h3>No published projects yet.</h3>
                                <p>Add published rows to the <code>projects</code> table to display project cards here.</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <?php foreach ($visibleProjects as $projectItem): ?>
                    <?php
                        $projectTitle = (string) ($projectItem['title'] ?? 'Project');
                        $projectCategory = (string) ($projectItem['category'] ?? 'Project');
                        $projectUrl = ctc_url((string) ($projectItem['url'] ?? '#'));
                        $detailUrl = ctc_url((string) ($projectItem['detail_url'] ?? $projectUrl));
                        $projectImage = ctc_asset((string) ($projectItem['image'] ?? 'assets/img/home-6/cloudbanner.jpg'));
                        $projectAlt = (string) ($projectItem['alt'] ?? $projectTitle . ' project by Cloud Technology Computing');
                        $titleTarget = preg_match('#^https?://#i', $projectUrl) ? ' target="_blank" rel="noopener noreferrer"' : '';
                        $detailTarget = preg_match('#^https?://#i', $detailUrl) ? ' target="_blank" rel="noopener noreferrer"' : '';
                    ?>
                    <div class="col-lg-4 col-md-6 col-sm-10">
                        <div class="success-storie-card">
                            <div class="success-img">
                                <img loading="lazy" class="img-fluid magnetic-item" src="<?= ctc_h($projectImage); ?>" alt="<?= ctc_h($projectAlt); ?>" width="500" height="480">
                            </div>
                            <div class="success-content">
                                <span><?= ctc_h($projectCategory); ?></span>
                                <h3><a href="<?= ctc_h($projectUrl); ?>"<?= $titleTarget; ?>><?= ctc_h($projectTitle); ?></a></h3>
                                <div class="view-btn">
                                    <a href="<?= ctc_h($detailUrl); ?>" aria-label="View <?= ctc_h($projectTitle); ?> project details"<?= $detailTarget; ?>>
                                        <svg width="12" height="12" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M0 1H12M12 1V13M12 1L0.5 12"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php if ($totalPages > 1): ?>
                <div class="row">
                    <nav aria-label="Project portfolio pagination">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?= $currentPage <= 1 ? 'disabled' : ''; ?>">
                                <a class="page-link" href="<?= ctc_h($buildProjectPageUrl($currentPage - 1)); ?>" aria-label="Previous project page"><i class="bi bi-arrow-left"></i></a>
                            </li>
                            <?php for ($pageNumber = 1; $pageNumber <= $totalPages; $pageNumber++): ?>
                                <li class="page-item">
                                    <a class="page-link <?= $pageNumber === $currentPage ? 'active' : ''; ?>" href="<?= ctc_h($buildProjectPageUrl($pageNumber)); ?>" aria-label="Open project page <?= (int) $pageNumber; ?>"><?= (int) $pageNumber; ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : ''; ?>">
                                <a class="page-link" href="<?= ctc_h($buildProjectPageUrl($currentPage + 1)); ?>" aria-label="Next project page"><i class="bi bi-arrow-right"></i></a>
                            </li>
                        </ul>
                    </nav>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <!-- Start Footer section -->
  <?php include 'footer.php'; ?>