<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/blog-functions.php';

$post = getPostBySlugOrId($pdo);

if (!$post) {
    http_response_code(404);
    exit('Blog post not found.');
}

saveComment($pdo, (int) $post['id']);

$views = incrementPostViews($pdo, (int) $post['id']);
$tags = getPostTags($pdo, (int) $post['id']);
$comments = getApprovedComments($pdo, (int) $post['id']);
$recentPosts = getRecentPosts($pdo, 3);
$categories = getCategoriesWithCounts($pdo);
$allTags = getAllTags($pdo);
$affiliateLinks = getPostAffiliateLinks($pdo, (int) $post['id']);
$relatedPosts = getRelatedPosts($pdo, (int) $post['id'], isset($post['category_id']) ? (int) $post['category_id'] : null, 3);

$canonicalUrl = 'https://www.cloudtechnologycomputing.com/blog/' . rawurlencode($post['slug']);
$metaTitle = $post['meta_title'] ?: $post['title'];
$metaDescription = $post['meta_description'] ?: $post['excerpt'];
$ogImage = $post['og_image'] ?: $post['featured_image'];
if ($ogImage && !str_starts_with($ogImage, 'http://') && !str_starts_with($ogImage, 'https://')) {
    $ogImage = 'https://www.cloudtechnologycomputing.com/' . ltrim($ogImage, '/');
}
if (!$ogImage) {
    $ogImage = 'https://www.cloudtechnologycomputing.com/assets/img/home-6/CloudTechnologyComputingDisplay.avif';
}

$featuredImage = $post['featured_image'] ?: '/assets/img/home-6/CloudTechnologyComputingDisplay.avif';
if ($featuredImage && !str_starts_with($featuredImage, 'http://') && !str_starts_with($featuredImage, 'https://')) {
    $featuredImage = '/' . ltrim($featuredImage, '/');
}

$authorImage = $post['author_image'] ?: '/assets/img/home-3/ProfilePictJhonArzuGil.webp';
if ($authorImage && !str_starts_with($authorImage, 'http://') && !str_starts_with($authorImage, 'https://')) {
    $authorImage = '/' . ltrim($authorImage, '/');
}
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title><?= e($metaTitle) ?></title>
  <meta name="description" content="<?= e($metaDescription) ?>">
  <meta name="author" content="<?= e($post['author_name']) ?>">
  <meta name="robots" content="index, follow">
  <link rel="canonical" href="<?= e($canonicalUrl) ?>">

  <meta property="og:title" content="<?= e($metaTitle) ?>">
  <meta property="og:description" content="<?= e($metaDescription) ?>">
  <meta property="og:url" content="<?= e($canonicalUrl) ?>">
  <meta property="og:image" content="<?= e($ogImage) ?>">
  <meta property="og:site_name" content="Cloud Technology Computing">
  <meta property="og:type" content="article">
  <meta property="article:published_time" content="<?= e(date('c', strtotime($post['post_date']))) ?>">
  <meta property="article:modified_time" content="<?= e(date('c', strtotime($post['updated_at'] ?? $post['post_date']))) ?>">

  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?= e($metaTitle) ?>">
  <meta name="twitter:description" content="<?= e($metaDescription) ?>">
  <meta name="twitter:image" content="<?= e($ogImage) ?>">

  <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="/assets/css/bootstrap-icons.css" rel="stylesheet">
  <link href="/assets/css/all.min.css" rel="stylesheet">
  <link href="/assets/css/fontawesome.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/assets/css/swiper-bundle.min.css">
  <link rel="stylesheet" href="/assets/css/animate.min.css">
  <link rel="stylesheet" href="/assets/css/jquery.fancybox.min.css">
  <link href="/assets/css/boxicons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/assets/css/preloader.css">
  <link rel="stylesheet" href="/assets/css/style2.css">
  <link rel="stylesheet" href="/style.css">
  <link rel="stylesheet" href="/css/blog-refactor.css">
  <link rel="stylesheet" href="/css/seo-engagement.css">
  <link rel="icon" href="/assets/img/sm-logo.svg" type="image/svg+xml">
<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0&family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,1,0">
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "BlogPosting",
    "headline": <?= json_encode($post['title']) ?>,
    "description": <?= json_encode($metaDescription) ?>,
    "image": <?= json_encode($ogImage) ?>,
    "author": {
      "@type": "Person",
      "name": <?= json_encode($post['author_name']) ?>
    },
    "publisher": {
      "@type": "Organization",
      "name": "Cloud Technology Computing",
      "logo": {
        "@type": "ImageObject",
        "url": "https://www.cloudtechnologycomputing.com/assets/img/sm-logo.svg"
      }
    },
    "datePublished": <?= json_encode(date('c', strtotime($post['post_date']))) ?>,
    "dateModified": <?= json_encode(date('c', strtotime($post['updated_at'] ?? $post['post_date']))) ?>,
    "mainEntityOfPage": <?= json_encode($canonicalUrl) ?>
  }
  </script>
</head>

<body class="home-dark2 tt-magic-cursor">

<a class="skip-link" href="#main-content">Skip to content</a>
<?php include __DIR__ . '/nav.php'; ?>
<div class="reading-progress" id="reading-progress"></div>

<main id="main-content">

<section class="breadcrumbs">
    <div class="breadcrumb-sm-images">
        <div class="inner-banner-1 magnetic-item">
            <img loading="lazy" src="/assets/img/inner-pages/OnlineAdvertisingCloudTechnologyComputing.avif" alt="Cloud technology advertising">
        </div>
        <div class="inner-banner-2 magnetic-item">
            <img loading="lazy" src="/assets/img/inner-pages/ibm cloud provider.avif" alt="Cloud provider">
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="breadcrumb-wrapper">
                    <div class="breadcrumb-cnt">
                        <span>Blog Details</span>
                        <h1><?= e($post['title']) ?></h1>
                        <div class="breadcrumb-list">
                            <a href="/">Home</a>
                            <img loading="lazy" src="/assets/img/inner-pages/breadcrumb-arrow.svg" alt="">
                            Blog Details
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="bolog-details-area sec-mar">
    <div class="container">

        <div class="row">
            <div class="col-lg-12">
                <div class="post-thumb magnetic-item">
                    <img 
                        loading="lazy" 
                        class="img-fluid" 
                        src="<?= e($featuredImage) ?>" 
                        alt="<?= e($post['featured_image_alt'] ?: $post['title']) ?>">
                </div>
            </div>
        </div>

        <div class="row g-lg-4 gy-5">
            <div class="col-lg-8">
                <div class="blog-details-content">

                    <span><?= e($post['category_name'] ?? 'Blog') ?></span>
                    <h2><?= e($post['title']) ?></h2>

                    <div class="author-and-meta">
                        <div class="author-area">
                            <div class="author-img">
                                <img 
                                    loading="lazy" 
                                    src="<?= e($authorImage) ?>" 
                                    alt="<?= e($post['author_name']) ?>">
                            </div>
                            <div class="author-content">
                                <h6>By, <span><?= e($post['author_name']) ?></span></h6>
                            </div>
                        </div>

                        <ul class="blog-meta">
                            <li>
                                <?= date('d M, Y', strtotime($post['post_date'])) ?>
                            </li>
                            <li>
                                <?= number_format($views) ?> Views
                            </li>
                            <li>
                                <?= count($comments) ?> Comment<?= count($comments) === 1 ? '' : 's' ?>
                            </li>
                        </ul>
                    </div>

                    <div class="dynamic-post-content">
                        <?= $post['content_html'] ?>
                    </div>

                    <div class="blog-cta-box">
                        <div>
                            <span class="solution-kicker">Need this implemented?</span>
                            <h2>Turn this article into a real business upgrade.</h2>
                            <p>Cloud Technology Computing can help with cloud migration, AI chatbot integration, PHP/MySQL development, mobile app publishing, and SEO improvements.</p>
                        </div>
                        <a class="primary-btn3" href="/form.php">Book a Free Consultation</a>
                    </div>

                    <?php if (!empty($affiliateLinks)): ?>
                        <div class="blog-affiliate-box">
                            <h3>Recommended Tools</h3>
                            <p class="affiliate-disclosure">
                                Disclosure: Some links below are referral or affiliate links. 
                                I may receive a reward if you sign up through them. Terms apply.
                            </p>

                            <div class="affiliate-grid">
                                <?php foreach ($affiliateLinks as $link): ?>
                                    <article class="affiliate-card">
                                        <span class="affiliate-category"><?= e($link['category']) ?></span>
                                        <h4><?= e($link['name']) ?></h4>
                                        <p><?= e($link['description']) ?></p>
                                        <small><?= e($link['disclosure']) ?></small>
                                        <br>
                                        <a 
                                            href="<?= e($link['url']) ?>"
                                            target="_blank"
                                            rel="sponsored nofollow noopener noreferrer"
                                            class="primary-btn3">
                                            <?= e($link['cta']) ?>
                                        </a>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="blog-tag-and-social">
                        <div class="tag">
                            <h6>Tag:</h6>
                            <ul>
                                <?php foreach ($tags as $tag): ?>
                                    <li>
                                        <a href="/blog.php?tag=<?= rawurlencode($tag['slug']) ?>">
                                            <?= e($tag['name']) ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <div class="social">
                            <h6>Share On:</h6>
                            <ul>
                                <li>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($canonicalUrl) ?>" target="_blank" rel="noopener" aria-label="Share on Facebook">
                                        <i class="bx bxl-facebook"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="https://twitter.com/intent/tweet?url=<?= urlencode($canonicalUrl) ?>&text=<?= urlencode($post['title']) ?>" target="_blank" rel="noopener" aria-label="Share on X">
                                        <i class="bx bxl-twitter"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= urlencode($canonicalUrl) ?>" target="_blank" rel="noopener" aria-label="Share on LinkedIn">
                                        <i class="bi bi-linkedin"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-lg-4">
                <div class="widget-area">

                    <div class="single-widgets widget_search">
                        <form method="GET" action="/blog.php">
                            <div class="wp-block-search__inside-wrapper">
                                <input 
                                    type="search" 
                                    class="wp-block-search__input" 
                                    name="s" 
                                    placeholder="Search Here" 
                                    required>
                                <button type="submit" class="wp-block-search__button primary-btn3">
                                    Search
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="single-widgets widget_egns_categoris">
                        <div class="widget-title">
                            <h4>Category</h4>
                        </div>

                        <ul class="wp-block-categoris-cloud">
                            <?php foreach ($categories as $category): ?>
                                <li>
                                    <a href="/blog.php?category=<?= rawurlencode($category['slug']) ?>">
                                        <span><?= e($category['name']) ?></span>
                                        <span class="number-of-categoris">
                                            (<?= (int) $category['post_count'] ?>)
                                        </span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <div class="single-widgets widget_egns_recent_post">
                        <div class="widget-title">
                            <h4>Newest Posts</h4>
                        </div>

                        <div class="recent-post-wraper">
                            <?php foreach ($recentPosts as $recent): ?>
                                <div class="widget-cnt">
                                    <div class="wi">
                                        <a href="/blog/<?= rawurlencode($recent['slug']) ?>">
                                            <img 
                                                loading="lazy" 
                                                src="<?= e(!empty($recent['featured_image']) ? '/' . ltrim($recent['featured_image'], '/') : '/assets/img/home-3/Cloud Solutions Techology.webp') ?>" 
                                                alt="<?= e($recent['title']) ?>">
                                        </a>
                                    </div>
                                    <div class="wc">
                                        <h6>
                                            <a href="/blog/<?= rawurlencode($recent['slug']) ?>">
                                                <?= e($recent['title']) ?>
                                            </a>
                                        </h6>
                                        <a href="/blog.php">
                                            <?= date('d M, Y', strtotime($recent['post_date'])) ?>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="single-widgets widget_egns_tag">
                        <div class="widget-title">
                            <h4>All Tag</h4>
                        </div>

                        <p class="wp-block-tag-cloud">
                            <?php foreach ($allTags as $tag): ?>
                                <a href="/blog.php?tag=<?= rawurlencode($tag['slug']) ?>">
                                    <?= e($tag['name']) ?>
                                </a>
                            <?php endforeach; ?>
                        </p>
                    </div>

                </div>
            </div>
        </div>

        <?php if (!empty($relatedPosts)): ?>
        <section class="solution-section">
            <span class="solution-kicker">Keep Learning</span>
            <h2>Related articles</h2>
            <div class="related-post-grid mt-4">
                <?php foreach ($relatedPosts as $related): ?>
                    <article class="related-post-card">
                        <h3><a href="/blog/<?= rawurlencode($related['slug']) ?>"><?= e($related['title']) ?></a></h3>
                        <?php if (!empty($related['excerpt'])): ?>
                            <p><?= e($related['excerpt']) ?></p>
                        <?php endif; ?>
                        <a class="primary-btn5" href="/blog/<?= rawurlencode($related['slug']) ?>">Read Article</a>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-9">
                <div class="comments-area sec-mar">
                    <h3><?= count($comments) ?> Comment<?= count($comments) === 1 ? '' : 's' ?></h3>

                    <?php foreach ($comments as $comment): ?>
                        <div class="single-comment">
                            <div class="author-thumb">
                                <img 
                                    loading="lazy" 
                                    src="/assets/img/inner-pages/hackerJhonBG.avif" 
                                    alt="Comment author">
                            </div>
                            <div class="comment-content">
                                <div class="author-post">
                                    <div class="author-info">
                                        <h4><?= e($comment['name']) ?></h4>
                                        <span><?= date("d M, Y h:i a", strtotime($comment['created_at'])) ?></span>
                                    </div>
                                </div>
                                <p><?= nl2br(e($comment['message'])) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="comment-form">
                    <h3>Leave a comment</h3>

                    <form action="/blog/<?= rawurlencode($post['slug']) ?>" method="POST">
                        <div class="row">
                            <div class="col-12" aria-hidden="true" style="position:absolute;left:-9999px;">
                                <label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                            </div>
                            <div class="col-md-12 mb-40">
                                <div class="form-inner">
                                    <input type="text" name="name" placeholder="Enter your name" required>
                                </div>
                            </div>

                            <div class="col-md-6 mb-40">
                                <div class="form-inner">
                                    <input type="email" name="email" placeholder="Enter your email" required>
                                </div>
                            </div>

                            <div class="col-md-6 mb-40">
                                <div class="form-inner">
                                    <input type="text" name="subject" placeholder="Subject">
                                </div>
                            </div>

                            <div class="col-12 mb-40">
                                <div class="form-inner">
                                    <textarea name="message" placeholder="Your message" required></textarea>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-inner">
                                    <button class="primary-btn3" type="submit">
                                        Post a Comment
                                    </button>
                                    <p class="mt-3">
                                        Comments are held for approval before appearing publicly.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </div>
</div>

</main>
<script>
  window.addEventListener('scroll', function () {
    const progress = document.getElementById('reading-progress');
    if (!progress) return;
    const scrollTop = window.scrollY || document.documentElement.scrollTop;
    const docHeight = document.documentElement.scrollHeight - window.innerHeight;
    const width = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
    progress.style.width = width + '%';
  }, { passive: true });
</script>
<?php include __DIR__ . '/footer.php'; ?>