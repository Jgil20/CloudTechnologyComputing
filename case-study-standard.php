<?php
require_once __DIR__ . '/includes/db.php';

if (!function_exists('e')) {
    function e(?string $value): string
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('assetPath')) {
    function assetPath(?string $path): string
    {
        if (!$path) {
            return '/assets/img/inner-pages/Website Optimization3.avif';
        }

        if (
            str_starts_with($path, 'http://') ||
            str_starts_with($path, 'https://')
        ) {
            return $path;
        }

        return '/' . ltrim($path, '/');
    }
}

if (!function_exists('caseStudyUrl')) {
    function caseStudyUrl(string $slug): string
    {
        return '/case-study/' . rawurlencode($slug);
        
        // Use this instead if your clean URL rule is not working yet:
        // return '/case-study-details.php?slug=' . rawurlencode($slug);
    }
}

$page = isset($_GET['page']) && ctype_digit((string) $_GET['page'])
    ? max(1, (int) $_GET['page'])
    : 1;

$perPage = 6;
$offset = ($page - 1) * $perPage;

$countStmt = $pdo->prepare("
    SELECT COUNT(*)
    FROM case_studies
    WHERE status = 'published'
");
$countStmt->execute();
$totalCaseStudies = (int) $countStmt->fetchColumn();
$totalPages = max(1, (int) ceil($totalCaseStudies / $perPage));

$stmt = $pdo->prepare("
    SELECT 
        id,
        title,
        slug,
        subtitle,
        excerpt,
        featured_image,
        featured_image_alt,
        industry,
        service_type,
        published_at
    FROM case_studies
    WHERE status = 'published'
    ORDER BY published_at DESC, id DESC
    LIMIT :limit OFFSET :offset
");

$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$caseStudies = $stmt->fetchAll();
?>
<?php include 'header.php'; ?>
<meta name="author" content="Jhon Arzu-Gil">
<meta name="copyright" content="Jhon Arzu-Gil" />
<meta name="description" content="Read case studies on cloud migration, website speed, AI chatbot lead capture, SEO, and managed IT results from Cloud Technology Computing client projects.">
<meta name="robots" content="index, follow"> 
<!-- Open Graph / Facebook -->
<meta property="og:title" content="Cloud, AI &amp; Website Case Studies | CTC">
<meta property="og:description" content="Read case studies on cloud migration, website speed, AI chatbot lead capture, SEO, and managed IT results from Cloud Technology Computing client projects.">
<meta property="og:url" content="https://www.cloudtechnologycomputing.com/case-study-standard.php">
<meta property="og:image" content="https://www.cloudtechnologycomputing.com/assets/img/home-6/cloudbanner.jpg">
<meta property="og:site_name" content="Cloud Technology Computing" />
<meta property="og:locale" content="en_US" />
<meta property="og:type" content="website">
<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image"/>
<meta name="twitter:title" content="Cloud, AI &amp; Website Case Studies | CTC">
<meta name="twitter:description" content="Read case studies on cloud migration, website speed, AI chatbot lead capture, SEO, and managed IT results from Cloud Technology Computing client projects.">
<meta property="twitter:site" content="@JhonArzuGil">
<meta property="twitter:image" content="https://www.cloudtechnologycomputing.com/assets/img/home-6/cloudbanner.jpg">
<meta name="twitter:creator" content="@JhonArzuGil"/>
<meta property="twitter:url" content="https://www.cloudtechnologycomputing.com/case-study-standard.php">
<meta name="twitter:image:alt" content="Case Studies: Cloud Performance Optimization | CTC" />  
    <!-- Favicon -->
   
     <!-- Title -->
<link rel="canonical" href="https://www.cloudtechnologycomputing.com/case-study-standard.php" />
    <title>Cloud, AI &amp; Website Case Studies | CTC</title>
<script type="application/ld+json">
<?php
$hasParts = [];
foreach ($caseStudies as $cs) {
    $slug = $cs['slug'] ?? '';
    if ($slug === '') { continue; }
    $hasParts[] = [
        "@type"       => "WebPage",
        "name"        => $cs['title'] ?? 'Cloud Technology Computing Case Study',
        "url"         => "https://www.cloudtechnologycomputing.com/case-study/" . rawurlencode($slug),
        "description" => $cs['excerpt'] ?? null,
    ];
}
$ld = [
    "@context"        => "https://schema.org",
    "@type"           => "CollectionPage",
    "name"            => "Cloud Technology Computing Case Studies",
    "description"     => "Browse Cloud Technology Computing case studies on cloud performance optimization, AI chatbot integrations, and cloud migration for small businesses.",
    "url"             => "https://www.cloudtechnologycomputing.com/case-study-standard.php",
    "isPartOf"        => ["@type" => "WebSite", "name" => "Cloud Technology Computing", "url" => "https://www.cloudtechnologycomputing.com/"],
    "hasPart"         => $hasParts,
    "provider"        => ["@type" => "Organization", "name" => "Cloud Technology Computing", "url" => "https://www.cloudtechnologycomputing.com/"],
];
echo json_encode($ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
?>
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
            <!-- <img loading="lazy" src="assets/images/bg/office1.png" alt="image"   > -->
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
    <!-- End breadcrumbs section -->
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1>Cloud Technology Computing Case Studies</h1>
            </div>
        </div>
    </div>
    <div class="case-study-pages sec-mar">
        <div class="container">
            <div class="row gy-5 mb-60 justify-content-center">
                <h2 class="visually-hidden">Real-World Cloud and Web Development Case Studies</h2>

    <?php if (!empty($caseStudies)): ?>
        <?php foreach ($caseStudies as $caseStudy): ?>
            <div class="col-lg-8">
                <div class="case-study-wrap">
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="case-img magnetic-item">
                                <a href="<?= e(caseStudyUrl($caseStudy['slug'])); ?>">
                                    <img 
                                        loading="lazy" 
                                        class="img-fluid" 
                                        src="<?= e(assetPath($caseStudy['featured_image'])); ?>" 
                                        alt="<?= e($caseStudy['featured_image_alt'] ?: $caseStudy['title']); ?>" width="800" height="500"   >
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-7 d-flex align-items-center">
                            <div class="case-content">
                                <span>
                                    <?= e($caseStudy['service_type'] ?: $caseStudy['industry'] ?: 'CASE STUDY'); ?>
                                </span>

                                <h3>
                                    <a href="<?= e(caseStudyUrl($caseStudy['slug'])); ?>">
                                        <?= e($caseStudy['subtitle'] ?: $caseStudy['title']); ?>
                                    </a>
                                </h3>

                                <p>
                                    <?= e($caseStudy['excerpt']); ?>
                                </p>

                                <div class="learn-btn">
                                    <a class="primary-btn9" href="<?= e(caseStudyUrl($caseStudy['slug'])); ?>">
                                        <span>Read Cloud Technology Computing case study</span>
                                        <svg width="16" height="14" viewBox="0 0 16 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M8 0.5L15 7.5M15 7.5L8 13.5M15 7.5L1.30274e-07 7.5"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        <?php endforeach; ?>

    <?php else: ?>
        <div class="col-lg-8">
            <div class="case-study-wrap">
                <div class="case-content">
                    <span>CASE STUDIES</span>
                    <h3>No case studies found</h3>
                    <p>New case studies will appear here once they are published in the database.</p>
                </div>
            </div>
        </div>
    <?php endif; ?>

</div>
           <?php if ($totalPages > 1): ?>
    <div class="row">
        <div class="col-lg-12 d-flex justify-content-center">
            <div class="load-more-btn d-flex gap-3 align-items-center">

                <?php if ($page > 1): ?>
                    <a class="primary-btn3" href="/case-study-standard.php?page=<?= $page - 1; ?>">
                        Previous
                    </a>
                <?php endif; ?>

                <span style="color: #ffffff;">
                    Page <?= (int) $page; ?> of <?= (int) $totalPages; ?>
                </span>

                <?php if ($page < $totalPages): ?>
                    <a class="primary-btn3" href="/case-study-standard.php?page=<?= $page + 1; ?>">
                        Load More
                    </a>
                <?php endif; ?>

            </div>
        </div>
    </div>
<?php endif; ?>
        </div>
    </div>
    <!-- Start Footer section -->
    <?php include 'footer.php'; ?>