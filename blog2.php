<?php
require_once __DIR__ . '/includes/db.php';

if (!function_exists('e')) {
    function e(?string $value): string
    {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }
}

$postsPerPage = 6;
$currentPage = isset($_GET['page']) && ctype_digit((string) $_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$category = trim((string) ($_GET['category'] ?? ''));
$tag = trim((string) ($_GET['tag'] ?? ''));
$search = trim((string) ($_GET['s'] ?? ''));
$offset = ($currentPage - 1) * $postsPerPage;

$whereSql = "WHERE p.status = 'published'";
$params = [];

if ($category !== '') {
    $whereSql .= " AND c.slug = :category";
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
    $whereSql .= " AND (
        p.title LIKE :search
        OR p.excerpt LIKE :search
        OR p.content_html LIKE :search
        OR c.name LIKE :search
    )";
    $params[':search'] = '%' . $search . '%';
}

$countSql = "
    SELECT COUNT(*)
    FROM blog_posts p
    LEFT JOIN blog_categories c ON p.category_id = c.id
    {$whereSql}
";

$countStmt = $pdo->prepare($countSql);
foreach ($params as $key => $value) {
    $countStmt->bindValue($key, $value);
}
$countStmt->execute();
$totalPosts = (int) $countStmt->fetchColumn();
$totalPages = max(1, (int) ceil($totalPosts / $postsPerPage));

$sql = "
    SELECT
        p.id,
        p.slug,
        p.title,
        p.excerpt,
        p.featured_image,
        p.featured_image_alt,
        p.post_date,
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
    $stmt->bindValue($key, $value);
}
$stmt->bindValue(':limit', $postsPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$posts = $stmt->fetchAll();

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

    return '/blog.php' . (!empty($query) ? '?' . http_build_query($query) : '');
}

$pageTitle = 'Cloud Computing & AI Blog | Cloud Technology Computing';
$pageDescription = 'Read cloud computing, AI automation, web development, SEO, cybersecurity, and case study articles for small business growth.';

if ($search !== '') {
    $pageTitle = 'Search results for ' . $search . ' | CTC Blog';
    $pageDescription = 'Search Cloud Technology Computing articles for ' . $search . ' across cloud, AI, SEO, web development, and small business technology.';
} elseif ($category !== '') {
    $pageTitle = ucwords(str_replace('-', ' ', $category)) . ' Articles | CTC Blog';
    $pageDescription = 'Browse Cloud Technology Computing articles about ' . str_replace('-', ' ', $category) . ' for small businesses and growing organizations.';
} elseif ($tag !== '') {
    $pageTitle = ucwords(str_replace('-', ' ', $tag)) . ' Resources | CTC Blog';
    $pageDescription = 'Explore Cloud Technology Computing resources tagged ' . str_replace('-', ' ', $tag) . ' with practical technology guidance.';
}

$canonicalUrl = 'https://www.cloudtechnologycomputing.com' . blogPageUrl($currentPage, $category, $tag, $search);
?>
<?php include __DIR__ . '/header.php'; ?>
<title><?= e($pageTitle); ?></title>
<meta name="author" content="Jhon Arzu-Gil">
<meta name="description" content="<?= e($pageDescription); ?>">
<meta name="robots" content="index, follow, max-image-preview:large">
<link rel="canonical" href="<?= e($canonicalUrl); ?>">
<?php if ($currentPage > 1): ?>
<link rel="prev" href="<?= e('https://www.cloudtechnologycomputing.com' . blogPageUrl($currentPage - 1, $category, $tag, $search)); ?>">
<?php endif; ?>
<?php if ($currentPage < $totalPages): ?>
<link rel="next" href="<?= e('https://www.cloudtechnologycomputing.com' . blogPageUrl($currentPage + 1, $category, $tag, $search)); ?>">
<?php endif; ?>
<meta property="og:title" content="<?= e($pageTitle); ?>">
<meta property="og:description" content="<?= e($pageDescription); ?>">
<meta property="og:url" content="<?= e($canonicalUrl); ?>">
<meta property="og:image" content="https://www.cloudtechnologycomputing.com/assets/img/home-6/CloudTechnologyComputingDisplay.avif">
<meta property="og:site_name" content="Cloud Technology Computing">
<meta property="og:locale" content="en_US">
<meta property="og:type" content="website">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= e($pageTitle); ?>">
<meta name="twitter:description" content="<?= e($pageDescription); ?>">
<meta name="twitter:image" content="https://www.cloudtechnologycomputing.com/assets/img/home-6/CloudTechnologyComputingDisplay.avif">
<script type="application/ld+json">
<?= json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Blog',
    'name' => 'Cloud Technology Computing Blog',
    'description' => $pageDescription,
    'url' => $canonicalUrl,
    'publisher' => [
        '@type' => 'Organization',
        'name' => 'Cloud Technology Computing',
        'url' => 'https://www.cloudtechnologycomputing.com/',
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); ?>
</script>
</head>
<body class="home-dark2 tt-magic-cursor">
<a class="skip-link" href="#main-content">Skip to content</a>
<?php include __DIR__ . '/nav.php'; ?>

<main id="main-content">
    <section class="breadcrumbs">
        <div class="breadcrumb-sm-images">
            <div class="inner-banner-1 magnetic-item">
                <img loading="lazy" src="/assets/img/inner-pages/OnlineAdvertisingCloudTechnologyComputing.avif" alt="Cloud Technology Computing digital marketing and cloud services" width="260" height="180">
            </div>
            <div class="inner-banner-2 magnetic-item">
                <img loading="lazy" src="/assets/img/inner-pages/ibm cloud provider.avif" alt="Cloud consulting and cloud provider support" width="260" height="180">
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-wrapper">
                        <div class="breadcrumb-cnt">
                            <span>Blog</span>
                            <h1>Cloud, AI, Web Development, and SEO Resources</h1>
                            <div class="breadcrumb-list">
                                <a href="/">Home</a><img loading="lazy" src="/assets/img/inner-pages/breadcrumb-arrow.svg" alt="" width="16" height="16"> Blog
                            </div>
                            <form class="mt-4" method="get" action="/blog.php" role="search" aria-label="Search blog articles">
                                <div class="row g-2 justify-content-center">
                                    <div class="col-md-7">
                                        <input class="form-control" type="search" name="s" value="<?= e($search); ?>" placeholder="Search cloud, AI, SEO, web development..." aria-label="Search blog">
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
            <div class="blog-cta-box">
                <div>
                    <span class="solution-kicker">Start with the right page</span>
                    <h2>Want more visitors from Google?</h2>
                    <p>Create focused service pages, publish useful blog posts, and connect each article to a consultation CTA.</p>
                </div>
                <a class="primary-btn3" href="/solutions/business-website-seo-optimization">See SEO Optimization</a>
            </div>

            <div class="row g-4">
                <?php if (!empty($posts)): ?>
                    <?php foreach ($posts as $post): ?>
                        <?php
                            $postUrl = '/blog/' . rawurlencode($post['slug']);
                            $image = !empty($post['featured_image']) ? '/' . ltrim($post['featured_image'], '/') : '/assets/img/home-3/Cloud Solutions Techology.webp';
                            $imageAlt = $post['featured_image_alt'] ?: $post['title'];
                            $categorySlug = $post['category_slug'] ?: '';
                        ?>
                        <div class="col-lg-4 col-md-6">
                            <article class="single-blog magnetic-item h-100">
                                <div class="blog-img">
                                    <a href="<?= e($postUrl); ?>">
                                        <img loading="lazy" class="img-fluid" src="<?= e($image); ?>" alt="<?= e($imageAlt); ?>" width="420" height="280">
                                    </a>
                                    <?php if ($categorySlug !== ''): ?>
                                        <div class="blog-tag">
                                            <a href="/blog.php?category=<?= rawurlencode($categorySlug); ?>"><?= e($post['category_name'] ?? 'Blog'); ?></a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="blog-content">
                                    <ul class="blog-meta">
                                        <li><a href="<?= e($postUrl); ?>"><?= date('M d, Y', strtotime($post['post_date'])); ?></a></li>
                                    </ul>
                                    <h2 class="h4"><a href="<?= e($postUrl); ?>"><?= e($post['title']); ?></a></h2>
                                    <p><?= e($post['excerpt']); ?></p>
                                    <div class="blog-footer">
                                        <div class="read-btn">
                                            <a href="<?= e($postUrl); ?>" aria-label="Read <?= e($post['title']); ?>">Read More
                                                <svg width="12" height="12" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M0 1H12M12 1V13M12 1L0.5 12"></path></svg>
                                            </a>
                                        </div>
                                        <div class="social-area">
                                            <ul>
                                                <li><a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode('https://www.cloudtechnologycomputing.com' . $postUrl); ?>" aria-label="Share on Facebook" target="_blank" rel="noopener"><i class="bx bxl-facebook"></i></a></li>
                                                <li><a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= urlencode('https://www.cloudtechnologycomputing.com' . $postUrl); ?>" aria-label="Share on LinkedIn" target="_blank" rel="noopener"><i class="bi bi-linkedin"></i></a></li>
                                            </ul>
                                            <span><img loading="lazy" src="/assets/img/home-3/plain-icon.svg" alt="Share article" width="26" height="26"></span>
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
                                <p>Try a different search or browse our service solution pages.</p>
                            </div>
                            <a class="primary-btn3" href="/blog.php">View All Articles</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($totalPages > 1): ?>
                <div class="row mt-5">
                    <nav aria-label="Blog page navigation">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?= $currentPage <= 1 ? 'disabled' : ''; ?>">
                                <a class="page-link" href="<?= $currentPage > 1 ? e(blogPageUrl($currentPage - 1, $category, $tag, $search)) : '#'; ?>" aria-label="Previous page"><i class="bi bi-arrow-left"></i></a>
                            </li>
                            <?php for ($page = 1; $page <= $totalPages; $page++): ?>
                                <li class="page-item">
                                    <a class="page-link <?= $page === $currentPage ? 'active' : ''; ?>" href="<?= e(blogPageUrl($page, $category, $tag, $search)); ?>"><?= $page; ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : ''; ?>">
                                <a class="page-link" href="<?= $currentPage < $totalPages ? e(blogPageUrl($currentPage + 1, $category, $tag, $search)) : '#'; ?>" aria-label="Next page"><i class="bi bi-arrow-right"></i></a>
                            </li>
                        </ul>
                    </nav>
                </div>
            <?php endif; ?>

            <section class="solution-section">
                <span class="solution-kicker">Popular Service Pages</span>
                <h2>Explore solutions built for buyer search intent</h2>
                <div class="seo-link-grid mt-4">
                    <article class="seo-card"><h3><a href="/solutions/cloud-computing-small-business">Cloud Computing for Small Businesses</a></h3><p>Cloud hosting, storage, backups, migration, and managed cloud support.</p></article>
                    <article class="seo-card"><h3><a href="/solutions/ai-automation-small-business">AI Automation for Small Businesses</a></h3><p>AI chatbots, lead capture, workflow automation, and business productivity.</p></article>
                    <article class="seo-card"><h3><a href="/solutions/custom-php-mysql-website-development">Custom PHP & MySQL Website Development</a></h3><p>Dynamic blogs, case studies, service pages, forms, and database-driven content.</p></article>
                </div>
            </section>
        </div>
    </section>
</main>

<?php include __DIR__ . '/footer.php'; ?>
