<?php
/**
 * RSS 2.0 feed for the Cloud Technology Computing blog.
 *
 * Supports optional ?s=<term> filter that narrows to posts whose title, excerpt,
 * content, or category contains the term (same pattern as blog.php).
 *
 * @see https://validator.w3.org/feed/  (validate after deploy)
 */

require_once __DIR__ . '/includes/db.php';

$siteUrl = 'https://www.cloudtechnologycomputing.com';
$feedUrl = $siteUrl . '/rss.php';
$search  = trim((string) ($_GET['s'] ?? ''));

header('Content-Type: application/rss+xml; charset=utf-8');
header('Cache-Control: public, max-age=900'); // 15 minutes

$whereSql = "WHERE p.status = 'published'";
$params = [];

if ($search !== '') {
    $whereSql .= " AND (
        p.title LIKE :search
        OR p.excerpt LIKE :search
        OR p.content_html LIKE :search
        OR c.name LIKE :search
    )";
    $params[':search'] = '%' . $search . '%';
}

$sql = "
    SELECT
        p.id, p.slug, p.title, p.excerpt, p.content_html, p.author_name,
        p.post_date, p.updated_at, p.featured_image, p.featured_image_alt,
        p.og_image,
        c.name AS category_name, c.slug AS category_slug
    FROM blog_posts p
    LEFT JOIN blog_categories c ON p.category_id = c.id
    {$whereSql}
    ORDER BY p.post_date DESC, p.id DESC
    LIMIT 20
";

$posts = [];
try {
    $stmt = $pdo->prepare($sql);
    foreach ($params as $k => $v) {
        $stmt->bindValue($k, $v);
    }
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $e) {
    // Database unavailable: emit a valid empty feed rather than 500.
    error_log('rss.php DB error: ' . $e->getMessage());
    $posts = [];
}

$feedTitle = $search !== ''
    ? "Cloud Technology Computing Blog — search: {$search}"
    : 'Cloud Technology Computing Blog';
$feedDescription = $search !== ''
    ? "Blog posts matching \"{$search}\" from Cloud Technology Computing."
    : 'Cloud computing, AI automation, web development, SEO, and small business IT insights from Cloud Technology Computing.';

// Build site logo absolute URL
$siteLogo = $siteUrl . '/assets/img/sm-logo.svg';

// Last build date from latest post, or now
$lastBuild = !empty($posts) ? strtotime($posts[0]['post_date']) : time();
$pubDate   = gmdate('D, d M Y H:i:s', $lastBuild) . ' GMT';

echo '<?xml version="1.0" encoding="UTF-8"?>', "\n";
?>
<rss version="2.0"
     xmlns:atom="http://www.w3.org/2005/Atom"
     xmlns:content="http://purl.org/rss/1.0/modules/content/"
     xmlns:dc="http://purl.org/dc/elements/1.1/"
     xmlns:media="http://search.yahoo.com/mrss/"
     xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd">
  <channel>
    <title><?= htmlspecialchars($feedTitle, ENT_XML1, 'UTF-8') ?></title>
    <link><?= htmlspecialchars($siteUrl, ENT_XML1, 'UTF-8') ?></link>
    <description><?= htmlspecialchars($feedDescription, ENT_XML1, 'UTF-8') ?></description>
    <language>en-us</language>
    <copyright>© <?= date('Y') ?> Cloud Technology Computing. All rights reserved.</copyright>
    <managingEditor>Jgil20@me.com (Jhon Arzu-Gil)</managingEditor>
    <webMaster>Jgil20@me.com (Jhon Arzu-Gil)</webMaster>
    <lastBuildDate><?= $pubDate ?></lastBuildDate>
    <pubDate><?= $pubDate ?></pubDate>
    <ttl>60</ttl>
    <image>
      <url><?= htmlspecialchars($siteLogo, ENT_XML1, 'UTF-8') ?></url>
      <title><?= htmlspecialchars($feedTitle, ENT_XML1, 'UTF-8') ?></title>
      <link><?= htmlspecialchars($siteUrl, ENT_XML1, 'UTF-8') ?></link>
      <width>144</width>
      <height>144</height>
    </image>
    <atom:link href="<?= htmlspecialchars($feedUrl . ($search !== '' ? '?s=' . rawurlencode($search) : ''), ENT_XML1, 'UTF-8') ?>" rel="self" type="application/rss+xml" />
    <?php foreach ($posts as $post):
        $postUrl  = $siteUrl . '/blog/' . rawurlencode($post['slug']);
        $postImg  = $post['og_image'] ?: $post['featured_image'];
        $postImgAbs = '';
        if ($postImg) {
            if (str_starts_with($postImg, 'http://') || str_starts_with($postImg, 'https://')) {
                $postImgAbs = $postImg;
            } else {
                $postImgAbs = $siteUrl . '/' . ltrim($postImg, '/');
            }
        }
        $pub  = strtotime($post['post_date']);
        $upd  = strtotime($post['updated_at'] ?? '');
        $pubR = $pub ? gmdate('D, d M Y H:i:s', $pub) . ' GMT' : '';
        $updR = $upd ? gmdate('D, d M Y H:i:s', $upd) . ' GMT' : '';
    ?>
    <item>
      <title><?= htmlspecialchars($post['title'], ENT_XML1, 'UTF-8') ?></title>
      <link><?= htmlspecialchars($postUrl, ENT_XML1, 'UTF-8') ?></link>
      <guid isPermaLink="true"><![CDATA[<?= htmlspecialchars($postUrl, ENT_XML1, 'UTF-8') ?>]]></guid>
      <description><![CDATA[<?= $post['excerpt'] ?? '' ?>]]></description>
      <content:encoded><![CDATA[<?= $post['content_html'] ?? '' ?>]]></content:encoded>
      <dc:creator><![CDATA[<?= htmlspecialchars($post['author_name'] ?? 'Jhon Arzu-Gil', ENT_XML1, 'UTF-8') ?>]]></dc:creator>
      <?php if (!empty($post['category_name'])): ?>
      <category><![CDATA[<?= htmlspecialchars($post['category_name'], ENT_XML1, 'UTF-8') ?>]]></category>
      <?php endif; ?>
      <?php if ($pubR): ?><pubDate><?= $pubR ?></pubDate><?php endif; ?>
      <?php if ($updR): ?><atom:updated><?= gmdate('Y-m-d\TH:i:s\Z', $upd) ?></atom:updated><?php endif; ?>
      <?php if ($postImgAbs): ?>
      <media:content url="<?= htmlspecialchars($postImgAbs, ENT_XML1, 'UTF-8') ?>" medium="image" <?= !empty($post['featured_image_alt']) ? '' : '' ?>type="<?= preg_match('/\.(avif|webp)$/i', $postImgAbs) ? (preg_match('/\.avif$/i', $postImgAbs) ? 'image/avif' : 'image/webp') : 'image/jpeg' ?>">
        <?php if (!empty($post['featured_image_alt'])): ?>
        <media:title type="plain"><![CDATA[<?= htmlspecialchars($post['featured_image_alt'], ENT_XML1, 'UTF-8') ?>]]></media:title>
        <?php endif; ?>
      </media:content>
      <?php endif; ?>
    </item>
    <?php endforeach; ?>
  </channel>
</rss>
