<?php
require_once __DIR__ . '/includes/solutions-data.php';
require_once __DIR__ . '/includes/solutions-seo-content.php';


$slug = trim($_GET['slug'] ?? 'cloud-computing-small-business');
$solution = $solutions[$slug] ?? null;
$seoContent = $solutionSeoContent[$slug] ?? [];

$solutionMedia = [
    'cloud-computing-small-business' => [
        'src' => '/assets/img/home-6/smallbusiness.webp',
        'alt' => 'Small business cloud computing infrastructure dashboard with secure cloud services',
    ],
    'ai-automation-small-business' => [
        'src' => '/assets/img/inner-pages/ArtificialIntelligience2.avif',
        'alt' => 'AI automation workflow for small business lead capture and customer support',
    ],
    'custom-php-mysql-website-development' => [
        'src' => '/assets/img/inner-pages/customblog.webp',
        'alt' => 'Custom PHP and MySQL website development with dynamic blog and database content',
    ],
    'mobile-app-development-business-websites' => [
        'src' => '/assets/img/home-6/mobileappbusiness.avif',
        'alt' => 'Mobile app development dashboard for business website growth',
    ],
    'webview-app-development-ios-android' => [
        'src' => '/assets/img/inner-pages/mobileapp.webp',
        'alt' => 'WebView app development for iOS and Android business websites',
    ],
    'business-website-seo-optimization' => [
        'src' => '/assets/img/solutions/business-website-seo-performance-dashboard.webp',
        'alt' => 'Business website SEO optimization dashboard for search visibility and leads',
    ],
    'small-business-cloud-migration' => [
        'src' => '/assets/img/inner-pages/cloudinfr.webp',
        'alt' => 'Small business cloud migration and secure infrastructure planning',
    ],
    'houston-cloud-consulting-services' => [
        'src' => '/assets/img/solutions/houston-cloud-consulting-growing-businesses.webp',
        'alt' => 'Houston cloud consulting services for small business technology strategy',
    ],
    'houston-web-development-services' => [
        'src' => '/assets/img/inner-pages/webdevelopment.avif',
        'alt' => 'Houston web development services for fast business websites',
    ],
    'ai-chatbot-development-business-websites' => [
        'src' => '/assets/img/inner-pages/AI_Chatbot_Development.webp',
        'alt' => 'AI chatbot development for business websites and lead generation',
    ],
    'managed-it-services-small-businesses' => [
        'src' => '/assets/img/solutions/managed-it-services-small-business-dashboard.webp',
        'alt' => 'Managed IT services for small businesses with cloud monitoring backups security and support',
    ],
    'cloud-technology-services' => [
        'src' => '/assets/img/home-6/cloudtechnology.avif',
        'alt' => 'Cloud technology services for business modernization and digital growth',
    ],
    'cloud-processing-services' => [
        'src' => '/assets/img/home-6/CloudComputing.avif',
        'alt' => 'Cloud processing services using scalable cloud computing resources',
    ],
    'cloud-based-computing-services' => [
        'src' => '/assets/img/home-6/cloudbusiness.avif',
        'alt' => 'Cloud based computing services for growing business operations',
    ],
    'enterprise-hybrid-cloud' => [
        'src' => '/assets/img/home-3/hybridcloud.webp',
        'alt' => 'Enterprise hybrid cloud architecture connecting private and public cloud systems',
    ],
    'aws-cloud-services' => [
        'src' => '/assets/img/home-3/aws-certified-solutions-architect-associate.webp',
        'alt' => 'AWS cloud services and Amazon Web Services cloud computing consulting',
    ],
    'microsoft-azure-cloud-services' => [
        'src' => '/assets/img/inner-pages/AzureHostingDesktop.avif',
        'alt' => 'Microsoft Azure cloud services and Azure hosting for business applications',
    ],
    'cloud-security-services' => [
        'src' => '/assets/img/solutions/cloud-security-controls-small-business.webp',
        'alt' => 'Cloud security services with monitoring compliance and infrastructure protection',
    ],
    'cloud-infrastructure-services' => [
        'src' => '/assets/img/inner-pages/CloudHardware.avif',
        'alt' => 'Cloud infrastructure services for secure servers storage and business applications',
    ],
    'managed-cloud-services-provider' => [
        'src' => '/assets/img/home-6/cloudpartner.webp',
        'alt' => 'Managed cloud services provider supporting monitoring optimization and cloud operations',
    ],
    'cloud-data-security' => [
        'src' => '/assets/img/inner-pages/DataNumbers.avif',
        'alt' => 'Cloud data security for business data privacy backups and secure analytics',
    ],
    'iaas-cloud-infrastructure' => [
        'src' => '/assets/img/inner-pages/CloudSolutions.avif',
        'alt' => 'IaaS cloud infrastructure for scalable servers networks and cloud resources',
    ],
    'saas-paas-iaas-cloud-models' => [
        'src' => '/assets/img/home-6/services/cloud-computing-software-as-a-service-saas.avif',
        'alt' => 'SaaS PaaS and IaaS cloud models for business cloud strategy',
    ],
    'cloud-server-services' => [
        'src' => '/assets/img/inner-pages/CloudComping.avif',
        'alt' => 'Cloud server services for hosting applications websites and databases',
    ],
    'cloud-migration-companies-small-business' => [
        'src' => '/assets/img/home-6/savemoneycloud.webp',
        'alt' => 'Cloud migration company planning cost savings for small business infrastructure',
    ],
    'cloud-computing-technology' => ['src' => '/assets/img/home-6/CloudComputing.avif', 'alt' => 'Cloud computing technology architecture for secure scalable business operations'],
    'cloud-technology-solutions' => ['src' => '/assets/img/home-6/cloudtechnology.avif', 'alt' => 'Cloud technology solutions connecting infrastructure applications data and security'],
    'cloud-technology-partner' => ['src' => '/assets/img/home-6/cloudpartner.webp', 'alt' => 'Cloud technology partner helping a business plan migration security and growth'],
    'cloud-technology-consulting' => ['src' => '/assets/img/solutions/houston-cloud-consulting-growing-businesses.webp', 'alt' => 'Cloud technology consulting session for architecture migration and cost planning'],
    'cloud-computing-company-houston' => ['src' => '/assets/img/home-6/cloudbusiness.avif', 'alt' => 'Houston cloud computing company supporting secure business technology modernization'],
    'cloud-it-consulting-services' => ['src' => '/assets/img/solutions/managed-it-services-small-business-dashboard.webp', 'alt' => 'Cloud IT consulting services dashboard for infrastructure security and support'],
    'cloud-software-development-company' => ['src' => '/assets/img/home-6/software development.png', 'alt' => 'Cloud software development company building secure web applications APIs and databases'],
    'it-consulting-solutions' => ['src' => '/assets/img/home-6/ITConsulting.png', 'alt' => 'IT consulting solutions for cloud security software and small business growth'],
];

$defaultSolutionMedia = [
    'src' => '/assets/img/home-6/CloudTechnologyComputingDisplay.avif',
    'alt' => 'Cloud Technology Computing cloud AI web development and business technology solutions',
];

function ctc_absolute_asset_url(string $path): string
{
    if (preg_match('/^https?:\/\//i', $path)) {
        return $path;
    }

    return 'https://www.cloudtechnologycomputing.com' . $path;
}

function ctc_solution_media(array $mediaMap, string $slug, array $fallback): array
{
    return $mediaMap[$slug] ?? $fallback;
}

if ($solution === null) {
    http_response_code(404);
    $solution = [
        'title' => 'Solution Not Found | Cloud Technology Computing',
        'meta' => 'The requested Cloud Technology Computing solution page was not found. Explore cloud, AI, web development, SEO, and migration services.',
        'h1' => 'Solution Not Found',
        'keyword' => 'technology solutions',
        'intro' => 'This solution page could not be found. Explore our services or schedule a consultation for help with cloud, AI, web, mobile, and SEO projects.',
        'outcomes' => [],
        'process' => [],
        'faq' => [],
    ];
}

$canonicalUrl = 'https://www.cloudtechnologycomputing.com/solutions/' . rawurlencode($slug);
$currentMedia = ctc_solution_media($solutionMedia, $slug, $defaultSolutionMedia);
$solutionImage = $currentMedia['src'];
$solutionImageAlt = $currentMedia['alt'];
$solutionImageUrl = ctc_absolute_asset_url($solutionImage);
$pagePreloadImage = $solutionImage;
$allLinks = array_filter($solutions, fn($item, $key) => $key !== $slug, ARRAY_FILTER_USE_BOTH);
$relatedLinks = [];
foreach (($seoContent['related'] ?? []) as $relatedSlug) {
    if ($relatedSlug !== $slug && isset($solutions[$relatedSlug])) {
        $relatedLinks[$relatedSlug] = $solutions[$relatedSlug];
    }
}
if (count($relatedLinks) < 6) {
    foreach ($allLinks as $relatedSlug => $relatedItem) {
        if (!isset($relatedLinks[$relatedSlug])) {
            $relatedLinks[$relatedSlug] = $relatedItem;
        }
        if (count($relatedLinks) >= 6) {
            break;
        }
    }
}
$relatedLinks = array_slice($relatedLinks, 0, 6, true);
$detailedOverview = $seoContent['overview'] ?? [];
$capabilities = $seoContent['capabilities'] ?? [];
$planningCopy = $seoContent['planning'] ?? [];
$deliverables = $seoContent['deliverables'] ?? [];
$decisionQuestions = $seoContent['questions'] ?? [];
$whyCopy = $seoContent['why'] ?? [];
$primaryOutcome = $solution['outcomes'][0] ?? 'Improve business technology with a clear, secure, and scalable implementation plan';
$secondOutcome = $solution['outcomes'][1] ?? 'Reduce risk with better planning, documentation, monitoring, and support';
$thirdOutcome = $solution['outcomes'][2] ?? 'Create a stronger digital foundation for marketing, operations, and customer experience';
$bestFitUseCases = $solution['use_cases'] ?? [
    'Small businesses that need a clearer technology roadmap before investing more money',
    'Business owners who want better uptime, faster pages, stronger security, and lower operating friction',
    'Teams that need cloud-connected websites, applications, databases, automations, or customer workflows',
    'Growing companies that want a practical partner for planning, implementation, documentation, and support',
];
$seoSummary = sprintf(
    '%s is built for business owners who need practical help, not confusing technical language. This page explains what the service does, why it matters, what outcomes to expect, and how Cloud Technology Computing can support the work from planning through launch and ongoing improvement.',
    $solution['h1']
);
?>
<?php include __DIR__ . '/header.php'; ?>
<title><?= e($solution['title']); ?></title>
<meta name="description" content="<?= e($solution['meta']); ?>">
<meta name="author" content="Jhon Arzu-Gil">
<meta name="robots" content="index, follow, max-image-preview:large">
<link rel="canonical" href="<?= e($canonicalUrl); ?>">
<meta property="og:title" content="<?= e($solution['title']); ?>">
<meta property="og:description" content="<?= e($solution['meta']); ?>">
<meta property="og:url" content="<?= e($canonicalUrl); ?>">
<meta property="og:image" content="<?= e($solutionImageUrl); ?>">
<meta property="og:image:alt" content="<?= e($solutionImageAlt); ?>">
<meta property="og:site_name" content="Cloud Technology Computing">
<meta property="og:locale" content="en_US">
<meta property="og:type" content="website">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= e($solution['title']); ?>">
<meta name="twitter:description" content="<?= e($solution['meta']); ?>">
<meta name="twitter:image" content="<?= e($solutionImageUrl); ?>">
<script type="application/ld+json">
<?= json_encode([
    '@context' => 'https://schema.org',
    '@graph' => [
        [
            '@type' => 'Service',
            'name' => $solution['h1'],
            'description' => $solution['meta'],
            'provider' => [
                '@type' => 'Organization',
                'name' => 'Cloud Technology Computing',
                'url' => 'https://www.cloudtechnologycomputing.com/',
            ],
            'areaServed' => [
                '@type' => 'AdministrativeArea',
                'name' => 'Houston, Texas and remote clients across the United States',
            ],
            'serviceType' => $solution['keyword'],
            'audience' => [
                '@type' => 'Audience',
                'audienceType' => 'Small businesses and growing organizations',
            ],
            'hasOfferCatalog' => [
                '@type' => 'OfferCatalog',
                'name' => $solution['h1'] . ' capabilities',
                'itemListElement' => array_map(static fn($capability) => [
                    '@type' => 'Offer',
                    'itemOffered' => [
                        '@type' => 'Service',
                        'name' => $capability[0] ?? $solution['h1'],
                        'description' => $capability[1] ?? '',
                    ],
                ], $capabilities),
            ],
            'serviceOutput' => $deliverables,
            'url' => $canonicalUrl,
            'image' => $solutionImageUrl,
        ],
        [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => 'https://www.cloudtechnologycomputing.com/'],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Solutions', 'item' => 'https://www.cloudtechnologycomputing.com/solutions/cloud-computing-small-business'],
                ['@type' => 'ListItem', 'position' => 3, 'name' => $solution['h1'], 'item' => $canonicalUrl],
            ],
        ],
        [
            '@type' => 'FAQPage',
            'mainEntity' => array_map(fn($item) => [
                '@type' => 'Question',
                'name' => $item[0],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $item[1],
                ],
            ], $solution['faq'] ?? []),
        ],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); ?>
</script>
</head>
<body class="home-dark2 tt-magic-cursor">
<a class="skip-link" href="#main-content">Skip to content</a>
<?php include __DIR__ . '/nav.php'; ?>

<main id="main-content">
    <section class="solution-hero solution-hero-with-media">
        <div class="container">
            <div class="row gy-4 align-items-center">
                <div class="col-lg-7">
                    <span class="solution-eyebrow">Cloud Technology Computing Solution</span>
                    <h1><?= e($solution['h1']); ?></h1>
                    <p class="lead"><?= e($solution['intro']); ?></p>
                    <div class="solution-actions">
                        <a class="primary-btn3" href="/form.php">Book a Free Consultation</a>
                        <a class="primary-btn5" href="/services.php">Explore All Services</a>
                    </div>
                </div>
                <div class="col-lg-5">
                    <figure class="solution-hero-media">
                        <img src="<?= e($solutionImage); ?>" alt="<?= e($solutionImageAlt); ?>" width="900" height="675" loading="eager" decoding="async" fetchpriority="high">
                    </figure>
                </div>
            </div>
        </div>
    </section>

    <?php if (!empty($detailedOverview)): ?>
    <section class="solution-section solution-copy-section">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-4">
                    <span class="solution-kicker">Service overview</span>
                    <h2><?= e($seoContent['overview_title'] ?? ('What to know about ' . $solution['h1'])); ?></h2>
                </div>
                <div class="col-lg-8 solution-long-copy">
                    <?php foreach ($detailedOverview as $paragraph): ?>
                        <p><?= e($paragraph); ?></p>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php if (!empty($solution['outcomes'])): ?>
    <section class="solution-section">
        <div class="container">
            <div class="row gy-4 align-items-start">
                <div class="col-lg-5">
                    <span class="solution-kicker">Business outcomes</span>
                    <h2>What <?= e($solution['h1']); ?> helps you improve</h2>
                    <p><?= e($seoSummary); ?></p>
                </div>
                <div class="col-lg-7">
                    <ul class="solution-list">
                        <?php foreach ($solution['outcomes'] as $outcome): ?>
                            <li><?= e($outcome); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php if (!empty($capabilities)): ?>
    <section class="solution-section">
        <div class="container">
            <span class="solution-kicker">Capabilities</span>
            <h2>What is included in <?= e($solution['h1']); ?></h2>
            <p class="solution-section-intro">The exact scope is tailored to the current environment, business priorities, security requirements, and internal resources. A typical engagement may include the following areas.</p>
            <div class="solution-grid solution-capability-grid mt-4">
                <?php foreach ($capabilities as $capability): ?>
                    <article class="solution-process-card solution-capability-card">
                        <h3><?= e($capability[0] ?? 'Capability'); ?></h3>
                        <p><?= e($capability[1] ?? ''); ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php if (!empty($solution['process'])): ?>
    <section class="solution-section">
        <div class="container">
            <span class="solution-kicker">Process</span>
            <h2>How we deliver it</h2>
            <div class="solution-grid mt-4">
                <?php foreach ($solution['process'] as $index => $step): ?>
                    <article class="solution-process-card">
                        <h3>Step <?= $index + 1; ?></h3>
                        <p><?= e($step); ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php if (!empty($planningCopy) || !empty($decisionQuestions)): ?>
    <section class="solution-section solution-planning-section">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-7 solution-long-copy">
                    <span class="solution-kicker">Planning guide</span>
                    <h2><?= e($seoContent['planning_title'] ?? ('Planning your ' . $solution['keyword'] . ' project')); ?></h2>
                    <?php foreach ($planningCopy as $paragraph): ?>
                        <p><?= e($paragraph); ?></p>
                    <?php endforeach; ?>
                </div>
                <div class="col-lg-5">
                    <div class="solution-proof-card solution-question-card">
                        <h3>Questions to answer before implementation</h3>
                        <ul class="solution-checklist">
                            <?php foreach ($decisionQuestions as $question): ?>
                                <li><?= e($question); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <section class="solution-section">
        <div class="container">
            <div class="row gy-4 align-items-start">
                <div class="col-lg-5">
                    <span class="solution-kicker">Best fit</span>
                    <h2>Who this page is built for</h2>
                    <p>Use this page as a buying guide when you are comparing technology options, planning a project, or deciding which service your business should prioritize first.</p>
                </div>
                <div class="col-lg-7">
                    <ul class="solution-list">
                        <?php foreach ($bestFitUseCases as $useCase): ?>
                            <li><?= e($useCase); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </section>


    <?php if (!empty($deliverables)): ?>
    <section class="solution-section">
        <div class="container">
            <div class="row gy-4 align-items-start">
                <div class="col-lg-5">
                    <span class="solution-kicker">Project deliverables</span>
                    <h2>What your business can receive</h2>
                    <p>Deliverables are selected according to project scope. They are designed to make decisions, implementation, security, and ongoing ownership easier to understand.</p>
                </div>
                <div class="col-lg-7">
                    <ul class="solution-list solution-deliverables">
                        <?php foreach ($deliverables as $deliverable): ?>
                            <li><?= e($deliverable); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <section class="solution-section">
        <div class="container">
            <div class="blog-cta-box">
                <div>
                    <span class="solution-kicker">Get a clear roadmap</span>
                    <h2>Ready to plan your <?= e($solution['keyword']); ?> project?</h2>
                    <p>Start with a focused consultation to review your goals, current systems, risks, budget, and the next practical step for implementation.</p>
                </div>
                <a class="primary-btn3" href="/form.php">Request a Free Consultation</a>
            </div>
        </div>
    </section>

    <?php if (!empty($solution['faq'])): ?>
    <section class="solution-section">
        <div class="container">
            <div class="solution-faq">
                <span class="solution-kicker">FAQ</span>
                <h2>Common questions about <?= e($solution['h1']); ?></h2>
                <?php foreach ($solution['faq'] as $item): ?>
                    <details>
                        <summary><?= e($item[0]); ?></summary>
                        <p><?= e($item[1]); ?></p>
                    </details>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php if (!empty($whyCopy)): ?>
    <section class="solution-section solution-copy-section">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-4">
                    <span class="solution-kicker">Cloud Technology Computing</span>
                    <h2><?= e($seoContent['why_title'] ?? 'Why work with Cloud Technology Computing'); ?></h2>
                </div>
                <div class="col-lg-8 solution-long-copy">
                    <?php foreach ($whyCopy as $paragraph): ?>
                        <p><?= e($paragraph); ?></p>
                    <?php endforeach; ?>
                    <p><a href="/about.php">Learn more about Cloud Technology Computing</a> or <a href="/case-study-standard.php">review our case studies</a> to see how cloud, software, SEO, automation, and managed support work together.</p>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <section class="solution-section">
        <div class="container">
            <span class="solution-kicker">Related Solutions</span>
            <h2>Explore more growth pages</h2>
            <div class="seo-link-grid mt-4">
                <?php foreach ($relatedLinks as $relatedSlug => $related): ?>
                    <?php $relatedMedia = ctc_solution_media($solutionMedia, $relatedSlug, $defaultSolutionMedia); ?>
                    <article class="seo-card solution-related-card">
                        <a class="solution-card-image" href="/solutions/<?= e($relatedSlug); ?>" aria-label="Read more about <?= e($related['h1']); ?>">
                            <img src="<?= e($relatedMedia['src']); ?>" alt="<?= e($relatedMedia['alt']); ?>" width="640" height="420" loading="lazy" decoding="async">
                        </a>
                        <h3><a href="/solutions/<?= e($relatedSlug); ?>"><?= e($related['h1']); ?></a></h3>
                        <p><?= e($related['meta']); ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</main>

<?php include __DIR__ . '/footer.php'; ?>
