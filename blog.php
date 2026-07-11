<?php
// This listing is database-driven and must never be served as stale HTML.
if (!headers_sent()) {
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Pragma: no-cache');
    header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');
    header('CDN-Cache-Control: no-store');
    header('Cloudflare-CDN-Cache-Control: no-store');
    header('Surrogate-Control: no-store');
}

require_once __DIR__ . '/includes/db.php';

if (!function_exists('e')) {
    function e(?string $value): string
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('cleanBlogSlug')) {
    function cleanBlogSlug(string $value): string
    {
        $value = strtolower(trim($value));
        return preg_match('/^[a-z0-9-]{1,100}$/', $value) ? $value : '';
    }
}

if (!function_exists('cleanBlogSearch')) {
    function cleanBlogSearch(string $value): string
    {
        $value = trim(preg_replace('/\s+/', ' ', $value) ?? '');
        return substr($value, 0, 100);
    }
}

if (!function_exists('blogPageUrl')) {
    function blogPageUrl(int $page, string $category = '', string $tag = '', string $search = ''): string
    {
        $query = [];

        if ($page > 1) {
            $query['page'] = $page;
        }
        if ($category !== '') {
            $query['category'] = $category;
        }
        if ($tag !== '') {
            $query['tag'] = $tag;
        }
        if ($search !== '') {
            $query['s'] = $search;
        }

        return '/blog.php' . ($query !== [] ? '?' . http_build_query($query, '', '&', PHP_QUERY_RFC3986) : '');
    }
}

if (!function_exists('formatBlogDate')) {
    function formatBlogDate(?string $date): string
    {
        if (empty($date)) {
            return '';
        }

        try {
            return (new DateTimeImmutable($date))->format('M d, Y');
        } catch (Throwable $exception) {
            return '';
        }
    }
}

if (!function_exists('formatBlogDateTimeAttribute')) {
    function formatBlogDateTimeAttribute(?string $date): string
    {
        if (empty($date)) {
            return '';
        }

        try {
            return (new DateTimeImmutable($date))->format(DateTimeInterface::ATOM);
        } catch (Throwable $exception) {
            // Keep rendering the page and footer when a legacy row contains
            // an empty, zero, or otherwise invalid post_date value.
            error_log('Invalid blog post date: ' . $date);
            return '';
        }
    }
}

$siteUrl = 'https://www.cloudtechnologycomputing.com';
$postsPerPage = 6;
$blogCurrentPage = isset($_GET['page']) && ctype_digit((string) $_GET['page'])
    ? max(1, (int) $_GET['page'])
    : 1;
$category = cleanBlogSlug((string) ($_GET['category'] ?? ''));
$tag = cleanBlogSlug((string) ($_GET['tag'] ?? ''));
$search = cleanBlogSearch((string) ($_GET['s'] ?? ''));

$whereSql = "WHERE p.status = 'published' AND p.post_date <= NOW()";
$params = [];

if ($category !== '') {
    $whereSql .= ' AND c.slug = :category';
    $params[':category'] = $category;
}

if ($tag !== '') {
    $whereSql .= " AND EXISTS (
        SELECT 1
        FROM blog_post_tags pt
        INNER JOIN blog_tags t ON t.id = pt.tag_id
        WHERE pt.post_id = p.id AND t.slug = :tag
    )";
    $params[':tag'] = $tag;
}

if ($search !== '') {
    // Use separate placeholders because native PDO prepares do not reliably allow
    // one named parameter to be reused multiple times in the same statement.
    $whereSql .= " AND (
        p.title LIKE :search_title
        OR p.excerpt LIKE :search_excerpt
        OR p.content_html LIKE :search_content
        OR c.name LIKE :search_category
    )";
    $searchValue = '%' . $search . '%';
    $params[':search_title'] = $searchValue;
    $params[':search_excerpt'] = $searchValue;
    $params[':search_content'] = $searchValue;
    $params[':search_category'] = $searchValue;
}

$countSql = "
    SELECT COUNT(DISTINCT p.id)
    FROM blog_posts p
    LEFT JOIN blog_categories c ON p.category_id = c.id
    {$whereSql}
";

$countStmt = $pdo->prepare($countSql);
foreach ($params as $key => $value) {
    $countStmt->bindValue($key, $value, PDO::PARAM_STR);
}
$countStmt->execute();
$totalPosts = (int) $countStmt->fetchColumn();
$totalPages = max(1, (int) ceil($totalPosts / $postsPerPage));

$pageOutOfRange = $totalPosts > 0 && $blogCurrentPage > $totalPages;
if ($pageOutOfRange) {
    http_response_code(404);
}

$offset = ($blogCurrentPage - 1) * $postsPerPage;

$sql = "
    SELECT
        p.id,
        p.slug,
        p.title,
        p.excerpt,
        p.featured_image,
        p.featured_image_alt,
        p.post_date,
        p.updated_at,
        c.name AS category_name,
        c.slug AS category_slug
    FROM blog_posts p
    LEFT JOIN blog_categories c ON p.category_id = c.id
    {$whereSql}
    ORDER BY p.post_date DESC, p.id DESC
    LIMIT :limit OFFSET :offset
";

$stmt = $pdo->prepare($sql);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value, PDO::PARAM_STR);
}
$stmt->bindValue(':limit', $postsPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

$categoryStmt = $pdo->query("
    SELECT
        c.name,
        c.slug,
        COUNT(p.id) AS post_count
    FROM blog_categories c
    INNER JOIN blog_posts p
        ON p.category_id = c.id
        AND p.status = 'published'
        AND p.post_date <= NOW()
    GROUP BY c.id, c.name, c.slug
    HAVING COUNT(p.id) > 0
    ORDER BY c.name ASC
");
$categories = $categoryStmt->fetchAll(PDO::FETCH_ASSOC);

$activeCategoryName = '';
foreach ($categories as $categoryRow) {
    if (($categoryRow['slug'] ?? '') === $category) {
        $activeCategoryName = (string) $categoryRow['name'];
        break;
    }
}

$pageTitle = 'Cloud, AI & Small Business Tech Blog | CTC';
$pageDescription = 'Read practical guides on cloud computing, AI chatbots, websites, SEO, cybersecurity, managed IT, and automation for small business growth. Start learning today.';
$heroTitle = 'Cloud, AI, Web Development, and SEO Resources';
$heroText = 'Practical technology guides designed to help small businesses improve security, performance, automation, visibility, and growth.';

if ($search !== '') {
    $pageTitle = 'Search Results for ' . $search . ' | CTC Blog';
    $pageDescription = 'Search Cloud Technology Computing articles about cloud services, AI chatbots, SEO, web development, cybersecurity, managed IT, and automation for growth.';
    $heroTitle = 'Search Results for “' . $search . '”';
    $heroText = $totalPosts === 1
        ? '1 article matched your search.'
        : $totalPosts . ' articles matched your search.';
} elseif ($category !== '') {
    $displayCategory = $activeCategoryName !== ''
        ? $activeCategoryName
        : ucwords(str_replace('-', ' ', $category));
    $pageTitle = $displayCategory . ' Articles | CTC Blog';
    $pageDescription = 'Browse practical ' . strtolower($displayCategory) . ' articles for small businesses, entrepreneurs, and growing organizations.';
    $heroTitle = $displayCategory . ' Articles';
    $heroText = 'Explore practical guidance, comparisons, checklists, and strategies related to ' . strtolower($displayCategory) . '.';
} elseif ($tag !== '') {
    $displayTag = ucwords(str_replace('-', ' ', $tag));
    $pageTitle = $displayTag . ' Resources | CTC Blog';
    $pageDescription = 'Explore Cloud Technology Computing resources tagged ' . strtolower($displayTag) . ' with practical guidance for modern businesses.';
    $heroTitle = $displayTag . ' Resources';
    $heroText = 'Browse articles and practical guidance tagged ' . strtolower($displayTag) . '.';
} elseif ($blogCurrentPage > 1) {
    $pageTitle = 'Cloud Computing & AI Blog – Page ' . $blogCurrentPage . ' | CTC';
    $pageDescription = 'Browse more Cloud Technology Computing guides covering cloud, AI, cybersecurity, SEO, web development, managed IT, and small business technology growth.';
}

$isSearchPage = $search !== '';
$isFilteredPage = $category !== '' || $tag !== '';
$robotsContent = ($isSearchPage || $pageOutOfRange)
    ? 'noindex, follow, max-image-preview:large'
    : 'index, follow, max-image-preview:large';

// Internal search results are noindex and canonicalize to the main blog page.
$canonicalPath = $isSearchPage
    ? '/blog.php'
    : blogPageUrl($blogCurrentPage, $category, $tag, '');
$canonicalUrl = $siteUrl . $canonicalPath;

$itemListElements = [];
foreach ($posts as $index => $post) {
    $itemListElements[] = [
        '@type' => 'ListItem',
        'position' => $offset + $index + 1,
        'url' => $siteUrl . '/blog/' . rawurlencode((string) $post['slug']),
        'name' => (string) $post['title'],
    ];
}

$blogSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'Blog',
    'name' => 'Cloud Technology Computing Blog',
    'description' => $pageDescription,
    'url' => $canonicalUrl,
    'publisher' => [
        '@type' => 'Organization',
        'name' => 'Cloud Technology Computing',
        'url' => $siteUrl . '/',
        'logo' => [
            '@type' => 'ImageObject',
            'url' => $siteUrl . '/assets/img/cloud.svg',
        ],
    ],
    'blogPost' => array_map(
        static fn(array $post): array => [
            '@type' => 'BlogPosting',
            'headline' => (string) $post['title'],
            'url' => $siteUrl . '/blog/' . rawurlencode((string) $post['slug']),
            'datePublished' => (string) $post['post_date'],
            'dateModified' => (string) ($post['updated_at'] ?: $post['post_date']),
        ],
        $posts
    ),
];

$itemListSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'ItemList',
    'name' => $heroTitle,
    'numberOfItems' => count($itemListElements),
    'itemListElement' => $itemListElements,
];

$pagePreloadImage = '/assets/img/inner-pages/OnlineAdvertisingCloudTechnologyComputing.avif';
?>
<?php include __DIR__ . '/header.php'; ?>
<title><?= e($pageTitle); ?></title>
<meta name="author" content="Jhon Arzu-Gil">
<meta name="description" content="<?= e($pageDescription); ?>">
<meta name="robots" content="<?= e($robotsContent); ?>">
<link rel="canonical" href="<?= e($canonicalUrl); ?>">
<?php if (!$isSearchPage && $blogCurrentPage > 1 && !$pageOutOfRange): ?>
<link rel="prev" href="<?= e($siteUrl . blogPageUrl($blogCurrentPage - 1, $category, $tag)); ?>">
<?php endif; ?>
<?php if (!$isSearchPage && $blogCurrentPage < $totalPages): ?>
<link rel="next" href="<?= e($siteUrl . blogPageUrl($blogCurrentPage + 1, $category, $tag)); ?>">
<?php endif; ?>
<meta property="og:title" content="<?= e($pageTitle); ?>">
<meta property="og:description" content="<?= e($pageDescription); ?>">
<meta property="og:url" content="<?= e($canonicalUrl); ?>">
<meta property="og:image" content="<?= e($siteUrl . '/assets/img/home-6/CloudTechnologyComputingDisplay.avif'); ?>">
<meta property="og:image:alt" content="Cloud Technology Computing cloud, AI, web development, and SEO resources">
<meta property="og:site_name" content="Cloud Technology Computing">
<meta property="og:locale" content="en_US">
<meta property="og:type" content="website">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= e($pageTitle); ?>">
<meta name="twitter:description" content="<?= e($pageDescription); ?>">
<meta name="twitter:image" content="<?= e($siteUrl . '/assets/img/home-6/CloudTechnologyComputingDisplay.avif'); ?>">
<script type="application/ld+json">
<?= json_encode($blogSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); ?>
</script>
<?php if ($itemListElements !== []): ?>
<script type="application/ld+json">
<?= json_encode($itemListSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); ?>
</script>
<?php endif; ?>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "ItemList",
    "@id": "https://www.cloudtechnologycomputing.com/blog#blog",
    "name": "Cloud Technology Computing Blog",
    "description": "Articles about cloud computing, web development, AI, SEO, mobile apps, and small business technology strategy from Cloud Technology Computing.",
    "url": "https://www.cloudtechnologycomputing.com/blog.php",
    "isPartOf": {"@type": "WebSite", "name": "Cloud Technology Computing", "url": "https://www.cloudtechnologycomputing.com/"},
    "inLanguage": "en-US"
}
</script>
</head>
<body class="home-dark2">
<a class="skip-link" href="#main-content">Skip to content</a>
<?php include __DIR__ . '/nav.php'; ?>

<main id="main-content">
    <section class="breadcrumbs">
        <div class="breadcrumb-sm-images" aria-hidden="true">
            <div class="inner-banner-1 magnetic-item">
                <img src="/assets/img/inner-pages/OnlineAdvertisingCloudTechnologyComputing.avif" alt="" fetchpriority="high" width="164" height="210"   >
            </div>
            <div class="inner-banner-2 magnetic-item">
                <img loading="lazy" src="/assets/img/inner-pages/ibm cloud provider.avif" alt="" width="250" height="191"   >
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-wrapper">
                        <div class="breadcrumb-cnt">
                            <span>Technology Insights</span>
                            <h1><?= e($heroTitle); ?></h1>
                            <p><?= e($heroText); ?></p>
                            <div class="breadcrumb-list">
                                <a href="/">Home</a>
                                <img loading="lazy" src="/assets/img/inner-pages/breadcrumb-arrow.svg" alt="" width="16" height="9"   >
                                <a href="/blog.php">Blog</a>
                                <?php if ($isFilteredPage || $isSearchPage): ?>
                                    <img loading="lazy" src="/assets/img/inner-pages/breadcrumb-arrow.svg" alt="" width="16" height="9"   >
                                    <span><?= e($search !== '' ? 'Search' : ($activeCategoryName !== '' ? $activeCategoryName : ucwords(str_replace('-', ' ', $category !== '' ? $category : $tag)))); ?></span>
                                <?php endif; ?>
                            </div>
                            <form class="mt-4" method="get" action="/blog.php" role="search" aria-label="Search blog articles">
                                <div class="row g-2 justify-content-center">
                                    <div class="col-md-7">
                                        <label class="visually-hidden" for="blog-search">Search blog articles</label>
                                        <input id="blog-search" class="form-control" type="search" name="s" value="<?= e($search); ?>" maxlength="100" placeholder="Search cloud, AI, SEO, cybersecurity...">
                                    </div>
                                    <div class="col-md-auto">
                                        <button class="primary-btn3" type="submit">Search Articles</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="home3-blog-area sec-mar">
        <div class="container">
            <?php if ($categories !== []): ?>
                <nav class="mb-4" aria-label="Blog categories">
                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                        <a class="btn btn-outline-light<?= $category === '' && $tag === '' && $search === '' ? ' active' : ''; ?>" href="/blog.php">All Articles</a>
                        <?php foreach ($categories as $categoryRow): ?>
                            <?php $categoryUrl = blogPageUrl(1, (string) $categoryRow['slug']); ?>
                            <a class="btn btn-outline-light<?= $category === $categoryRow['slug'] ? ' active' : ''; ?>" href="<?= e($categoryUrl); ?>">
                                <?= e((string) $categoryRow['name']); ?>
                                <span aria-label="<?= (int) $categoryRow['post_count']; ?> articles">(<?= (int) $categoryRow['post_count']; ?>)</span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </nav>
            <?php endif; ?>

            <div class="blog-cta-box">
                <div>
                    <span class="solution-kicker">Turn traffic into customers</span>
                    <h2>Need a stronger search presence?</h2>
                    <p>Combine useful content, focused service pages, technical SEO, analytics, and clear calls to action to attract qualified visitors.</p>
                </div>
                <a class="primary-btn3" href="/solutions/business-website-seo-optimization">Explore SEO Optimization</a>
            </div>

            <?php if ($search !== '' || $category !== '' || $tag !== ''): ?>
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                    <p class="mb-0">
                        Showing <?= count($posts); ?> of <?= $totalPosts; ?> result<?= $totalPosts === 1 ? '' : 's'; ?>.
                    </p>
                    <a href="/blog.php">Clear filters and view all articles</a>
                </div>
            <?php endif; ?>

            <div class="row g-4">
                <?php if ($pageOutOfRange): ?>
                    <div class="col-12">
                        <div class="blog-cta-box">
                            <div>
                                <h2>This blog page does not exist.</h2>
                                <p>The requested page is outside the available article archive.</p>
                            </div>
                            <a class="primary-btn3" href="/blog.php">Return to the Blog</a>
                        </div>
                    </div>
                <?php elseif ($posts !== []): ?>
                    <?php foreach ($posts as $index => $post): ?>
                        <?php
                            $postUrl = '/blog/' . rawurlencode((string) $post['slug']);
                            $image = !empty($post['featured_image'])
                                ? '/' . ltrim((string) $post['featured_image'], '/')
                                : '/assets/img/home-3/Cloud Solutions Techology.webp';
                            $imageAlt = !empty($post['featured_image_alt'])
                                ? (string) $post['featured_image_alt']
                                : (string) $post['title'];
                            $categorySlug = (string) ($post['category_slug'] ?? '');
                            $publishedDate = formatBlogDate((string) ($post['post_date'] ?? ''));
                            $dateTime = formatBlogDateTimeAttribute((string) ($post['post_date'] ?? ''));
                            $absolutePostUrl = $siteUrl . $postUrl;
                        ?>
                        <div class="col-lg-4 col-md-6">
                            <article class="single-blog magnetic-item h-100">
                                <div class="blog-img">
                                    <a href="<?= e($postUrl); ?>">
                                        <img
                                            class="img-fluid"
                                            src="<?= e($image); ?>"
                                            alt="<?= e($imageAlt); ?>"
                                            width="420"
                                            height="280"
                                            <?= $index === 0 ? 'fetchpriority="high"' : 'loading="lazy"'; ?>   >
                                    </a>
                                    <?php if ($categorySlug !== ''): ?>
                                        <div class="blog-tag">
                                            <a href="<?= e(blogPageUrl(1, $categorySlug)); ?>"><?= e((string) ($post['category_name'] ?? 'Blog')); ?></a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="blog-content">
                                    <ul class="blog-meta">
                                        <li>
                                            <?php if ($publishedDate !== ''): ?>
                                                <time datetime="<?= e($dateTime); ?>"><?= e($publishedDate); ?></time>
                                            <?php endif; ?>
                                        </li>
                                    </ul>
                                    <h2 class="h4"><a href="<?= e($postUrl); ?>"><?= e((string) $post['title']); ?></a></h2>
                                    <p><?= e((string) $post['excerpt']); ?></p>
                                    <div class="blog-footer">
                                        <div class="read-btn">
                                            <a href="<?= e($postUrl); ?>" aria-label="Read <?= e((string) $post['title']); ?>">Read More
                                                <svg width="12" height="12" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M0 1H12M12 1V13M12 1L0.5 12"></path></svg>
                                            </a>
                                        </div>
                                        <div class="social-area">
                                            <ul>
                                                <li><a href="https://www.facebook.com/sharer/sharer.php?u=<?= rawurlencode($absolutePostUrl); ?>" aria-label="Share <?= e((string) $post['title']); ?> on Facebook" target="_blank" rel="noopener noreferrer"><i class="bx bxl-facebook"></i></a></li>
                                                <li><a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= rawurlencode($absolutePostUrl); ?>" aria-label="Share <?= e((string) $post['title']); ?> on LinkedIn" target="_blank" rel="noopener noreferrer"><i class="bi bi-linkedin"></i></a></li>
                                            </ul>
                                            <span><img loading="lazy" src="/assets/img/home-3/plain-icon.svg" alt="" width="15" height="15"   ></span>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="blog-cta-box">
                            <div>
                                <h2>No articles found.</h2>
                                <p>Try a broader search or browse all Cloud Technology Computing articles.</p>
                            </div>
                            <a class="primary-btn3" href="/blog.php">View All Articles</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (!$pageOutOfRange && $totalPages > 1): ?>
                <?php
                    $startPage = max(1, $blogCurrentPage - 2);
                    $endPage = min($totalPages, $blogCurrentPage + 2);
                ?>
                <div class="row mt-5">
                    <nav aria-label="Blog page navigation">
                        <ul class="pagination justify-content-center flex-wrap">
                            <li class="page-item <?= $blogCurrentPage <= 1 ? 'disabled' : ''; ?>">
                                <?php if ($blogCurrentPage > 1): ?>
                                    <a class="page-link" href="<?= e(blogPageUrl($blogCurrentPage - 1, $category, $tag, $search)); ?>" rel="prev" aria-label="Previous blog page"><i class="bi bi-arrow-left"></i></a>
                                <?php else: ?>
                                    <span class="page-link" aria-hidden="true"><i class="bi bi-arrow-left"></i></span>
                                <?php endif; ?>
                            </li>

                            <?php if ($startPage > 1): ?>
                                <li class="page-item"><a class="page-link" href="<?= e(blogPageUrl(1, $category, $tag, $search)); ?>">1</a></li>
                                <?php if ($startPage > 2): ?><li class="page-item disabled"><span class="page-link">…</span></li><?php endif; ?>
                            <?php endif; ?>

                            <?php for ($page = $startPage; $page <= $endPage; $page++): ?>
                                <li class="page-item <?= $page === $blogCurrentPage ? 'active' : ''; ?>">
                                    <a class="page-link" href="<?= e(blogPageUrl($page, $category, $tag, $search)); ?>" <?= $page === $blogCurrentPage ? 'aria-current="page"' : ''; ?>><?= $page; ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($endPage < $totalPages): ?>
                                <?php if ($endPage < $totalPages - 1): ?><li class="page-item disabled"><span class="page-link">…</span></li><?php endif; ?>
                                <li class="page-item"><a class="page-link" href="<?= e(blogPageUrl($totalPages, $category, $tag, $search)); ?>"><?= $totalPages; ?></a></li>
                            <?php endif; ?>

                            <li class="page-item <?= $blogCurrentPage >= $totalPages ? 'disabled' : ''; ?>">
                                <?php if ($blogCurrentPage < $totalPages): ?>
                                    <a class="page-link" href="<?= e(blogPageUrl($blogCurrentPage + 1, $category, $tag, $search)); ?>" rel="next" aria-label="Next blog page"><i class="bi bi-arrow-right"></i></a>
                                <?php else: ?>
                                    <span class="page-link" aria-hidden="true"><i class="bi bi-arrow-right"></i></span>
                                <?php endif; ?>
                            </li>
                        </ul>
                    </nav>
                </div>
            <?php endif; ?>

            <section class="solution-section">
                <span class="solution-kicker">Popular Service Pages</span>
                <h2>Explore solutions built around real business needs</h2>
                <div class="seo-link-grid mt-4">
                    <article class="seo-card"><h3><a href="/solutions/cloud-computing-small-business">Cloud Computing for Small Businesses</a></h3><p>Cloud hosting, secure storage, backups, migration planning, and managed cloud support.</p></article>
                    <article class="seo-card"><h3><a href="/solutions/ai-automation-small-business">AI Automation for Small Businesses</a></h3><p>AI chatbots, lead capture, workflow automation, and practical productivity improvements.</p></article>
                    <article class="seo-card"><h3><a href="/solutions/custom-php-mysql-website-development">Custom PHP & MySQL Development</a></h3><p>Dynamic blogs, customer portals, forms, service pages, and database-driven applications.</p></article>
                    <article class="seo-card"><h3><a href="/solutions/managed-it-services-small-businesses">Managed IT Services</a></h3><p>Technical support, cloud management, security monitoring, backups, and IT planning.</p></article>
                    <article class="seo-card"><h3><a href="/solutions/houston-cloud-consulting-services">Houston Cloud Consulting</a></h3><p>Cloud assessments, migration roadmaps, cost optimization, security, and modernization.</p></article>
                    <article class="seo-card"><h3><a href="/contact.php">Request a Technology Consultation</a></h3><p>Discuss your website, cloud, AI, cybersecurity, mobile app, SEO, or managed IT goals.</p></article>
                </div>
            </section>
        </div>
    </section>
</main>

<!-- CTC_BLOG_MAIN_COMPLETE -->
<?php
$footerPath = __DIR__ . '/footer.php';
if (!is_file($footerPath) || !is_readable($footerPath)) {
    error_log('CTC footer missing or unreadable: ' . $footerPath);
    echo '<footer style="padding:40px;background:#171717;color:#fff;text-align:center"><p style="color:#fff">Cloud Technology Computing</p></footer>';
    echo '</body></html>';
} else {
    require $footerPath;
}
?>
